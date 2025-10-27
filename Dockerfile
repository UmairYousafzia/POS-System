# Stage 1 - Build dependencies
FROM composer:2.6 AS vendor

WORKDIR /app

# Copy composer files and install dependencies (no dev)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Stage 2 - PHP with Apache
FROM php:8.2-apache

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy existing app
COPY . .

# Copy vendor folder from build stage
COPY --from=vendor /app/vendor /var/www/html/vendor

# Give permissions to Laravel folders
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 8000
EXPOSE 8000

# Run Apache
CMD ["apache2-foreground"]
