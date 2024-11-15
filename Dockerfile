
# Stage 1: Composer Dependencies
FROM composer:2 AS composer

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Stage 2: Node.js Dependencies and Build
FROM node:16-alpine AS node

WORKDIR /var/www/html
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 3: Production Image
FROM php:8.1-fpm-alpine

# Install system dependencies
RUN apk --no-cache add \
    nginx \
    supervisor \
    curl \
    bash \
    shadow \
    && rm -rf /var/cache/apk/*

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Copy Composer dependencies
COPY --from=composer /var/www/html/vendor /var/www/html/vendor

# Copy built assets
COPY --from=node /var/www/html/public/js /var/www/html/public/js
COPY --from=node /var/www/html/public/css /var/www/html/public/css

# Copy configuration files
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf

# Set permissions
RUN adduser -D -u 1000 www
RUN chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Set environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false

# Start supervisord to manage PHP-FPM and Nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]