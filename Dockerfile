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

# Configure Apache to use Laravel public directory and allow .htaccess
RUN sed -ri 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#g' /etc/apache2/sites-available/000-default.conf \
    && printf "\n<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n" >> /etc/apache2/apache2.conf \
    && printf "ServerName localhost\n" >> /etc/apache2/apache2.conf

# Expose port 80 (default Apache)
EXPOSE 80

# Run Apache
CMD ["apache2-foreground"]
