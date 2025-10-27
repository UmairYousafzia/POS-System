# Stage 1: Build dependencies
FROM composer:2 AS vendor

WORKDIR /app

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copy the rest of the application
COPY . .

# Stage 2: PHP + Apache
FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory inside container
WORKDIR /var/www/html

# Copy project files from previous stage
COPY --from=vendor /app ./

# Give permissions to Laravel folders
RUN chmod -R 777 storage bootstrap/cache

# Expose port 8000
EXPOSE 8000

# Start Apache in foreground
CMD ["apache2-foreground"]
