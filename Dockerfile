# Stage 1: Build PHP dependencies with Composer
FROM composer:2.6 AS vendor

WORKDIR /app

# Copy and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader --prefer-dist

# Stage 2: Build frontend with Node + Vite
FROM node:18-alpine AS frontend

WORKDIR /app

# Environment variables
ARG VITE_PUSHER_KEY
ARG VITE_PUSHER_CLUSTER
ARG VITE_APP_NAME

ENV VITE_PUSHER_KEY=$VITE_PUSHER_KEY
ENV VITE_PUSHER_CLUSTER=$VITE_PUSHER_CLUSTER
ENV VITE_APP_NAME=$VITE_APP_NAME

COPY package*.json vite.config.js phpunit.xml postcss.config.js tailwind.config.js components.json tsconfig.json ./
RUN npm ci

# Copy frontend source
COPY resources resources
COPY public public

# Build frontend
RUN npm run build

# Stage 3: Production image
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    curl &&\
    docker-php-ext-install pdo_pgsql zip && \
    a2enmod rewrite && \
    rm -rf /var/lib/apt/lists/*

# Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copy Laravel application code
COPY . .

# Copy vendor folder from the vendor stage
COPY --from=vendor /app/vendor ./vendor

# Copy built frontend from the frontend stage
COPY --from=frontend /app/public/build public/build

# Permissions for storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache


# Copy composer binary from vendor stage
COPY --from=vendor /usr/bin/composer /usr/local/bin/composer

RUN composer dump-autoload --optimize && \
    php artisan package:discover --ansi