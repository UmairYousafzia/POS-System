# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory to Laravel root
WORKDIR /var/www/html

# Copy composer from the official composer image
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy all project files into container
COPY . .

# Install PHP dependencies (ignore errors from post-scripts during build)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress || true

# Ensure necessary folders exist
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ✅ Fix: Set the Apache document root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# ✅ Ensure the public directory exists before Apache starts
RUN test -d /var/www/html/public || (echo "Error: public directory not found" && exit 1)

# Expose port 80 (not 8000 — Apache listens on 80)
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
