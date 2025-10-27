# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer from the official image
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy all project files into container
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress || true

# Ensure necessary folders exist
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache

# Give permissions to storage and cache folders
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set Apache document root to public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Expose port 8000
EXPOSE 8000

# Start Apache
CMD ["apache2-foreground"]
