<<<<<<< HEAD
# Stage 1: Build Vite assets
FROM node:18 as node_builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Laravel app with Apache
FROM php:8.2-apache

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Laravel project
COPY . .

# Copy Vite build from node_builder
COPY --from=node_builder /app/public /var/www/html/public

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Use custom Apache config
COPY apache.conf /etc/apache2/sites-available/000-default.conf

=======
FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    bash \
    libzip-dev \
    oniguruma-dev \
    zip \
    unzip \
    mysql-client \
    curl \
    libpng-dev \
    && docker-php-ext-install pdo_mysql zip bcmath

WORKDIR /var/www/html

# Copy the whole Laravel app from your build context
COPY . /var/www/html

# Install Composer (copy binary from official composer image)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Run composer install (production mode, optimized)
RUN composer install --no-dev --optimize-autoloader

# Fix permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
>>>>>>> d0c6342 (main)
