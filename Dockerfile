# Stage 1: Build PHP dependencies with Composer
FROM composer:2.6 AS vendor

WORKDIR /app

# Copy and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader --prefer-dist

# Stage 2: Build frontend assets with Node + Vite
FROM node:18-alpine AS frontend

WORKDIR /app

# Accept Vite-related build args
ARG VITE_PUSHER_KEY
ARG VITE_PUSHER_CLUSTER
ARG VITE_APP_NAME

# Set them as environment variables so Vite can access them at build time
ENV VITE_PUSHER_KEY=$VITE_PUSHER_KEY
ENV VITE_PUSHER_CLUSTER=$VITE_PUSHER_CLUSTER
ENV VITE_APP_NAME=$VITE_APP_NAME

# Copy only the files needed to install npm dependencies and build
COPY package*.json vite.config.js phpunit.xml postcss.config.js tailwind.config.js components.json tsconfig.json ./
RUN npm ci

# Copy frontend source
COPY resources resources
COPY public public

# Build frontend assets
RUN npm run build

# Stage 3: Production image
FROM php:8.2-apache

ARG VITE_PUSHER_KEY
ARG VITE_PUSHER_CLUSTER
ARG VITE_APP_NAME

# Optionally set these as runtime environment variables if needed by Laravel
ENV VITE_PUSHER_KEY=$VITE_PUSHER_KEY
ENV VITE_PUSHER_CLUSTER=$VITE_PUSHER_CLUSTER
ENV VITE_APP_NAME=$VITE_APP_NAME

# Install required system dependencies and extensions
RUN apt-get update && \
    apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    curl \
    git && \
    docker-php-ext-install pdo_pgsql zip && \
    a2enmod rewrite && \
    rm -rf /var/lib/apt/lists/*

# Adjust Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copy the Laravel application code
COPY . .

# Copy the vendor folder from the vendor stage
COPY --from=vendor /app/vendor ./vendor

# Copy the built frontend assets from the frontend stage
COPY --from=frontend /app/public/build public/build

# Ensure proper permissions for storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache


# Copy the composer binary from vendor stage (it's installed in /usr/bin/composer in composer images)
COPY --from=vendor /usr/bin/composer /usr/local/bin/composer

RUN composer dump-autoload --optimize && \
    php artisan package:discover --ansi