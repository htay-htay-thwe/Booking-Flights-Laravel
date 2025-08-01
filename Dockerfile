# FROM php:8.2-fpm-alpine

# # Install system dependencies and PHP extensions
# RUN apk add --no-cache \
#     bash \
#     libzip-dev \
#     oniguruma-dev \
#     zip \
#     unzip \
#     mysql-client \
#     curl \
#     libpng-dev \
#     && docker-php-ext-install pdo_mysql zip bcmath

# WORKDIR /var/www/html

# # Copy Laravel app files
# COPY . /var/www/html

# # Install Composer binary from official image
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Run composer install without dev dependencies, optimize autoloader
# RUN composer install --no-dev --optimize-autoloader

# # Set permissions for storage and cache directories
# RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
#     && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# EXPOSE 9000

# CMD ["php-fpm"]

# FROM php:8.2-fpm

# # Install system dependencies
# RUN apt-get update && apt-get install -y \
#     nginx \
#     curl \
#     zip unzip \
#     git \
#     libzip-dev \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     && docker-php-ext-install pdo_mysql zip

# # Install Composer globally
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# # Set working directory
# WORKDIR /var/www/html

# # Copy application files
# COPY . .

# # Copy nginx config

# COPY ./nginx/backend-nginx.conf /etc/nginx/nginx.conf


# # Fix permissions for Laravel storage and cache
# RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
#     && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# # Install Laravel dependencies
# RUN composer install --no-dev --optimize-autoloader

# EXPOSE 80

# # Start PHP-FPM and NGINX together
# CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]


FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    zip unzip \
    git \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy all app files including vite.config.js and resources before building assets
COPY . .

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs

# Install npm dependencies and build assets
RUN npm install
RUN npm run build

# Fix permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
