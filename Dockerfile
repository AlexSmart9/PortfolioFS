# 1. Use official PHP Apache image
FROM php:8.2-apache

# 2. Install dependencies
RUN apt-get update && apt-get install -y libpq-dev unzip git && docker-php-ext-install pdo pdo_pgsql

# 3. Enable Apache rewrite module
RUN a2enmod rewrite

# 4. Set DocumentRoot to public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copy project files
COPY . /var/www/html/

# 6. Install Composer dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. Set permissions
RUN chown -R www-data:www-data /var/www/html

# 8. THE FIX: Explicitly expose port 80 so Railway maps external traffic here
EXPOSE 80

# 9. Start Apache properly
CMD bash -c "a2dismod mpm_event mpm_worker 2>/dev/null || true && a2enmod mpm_prefork && apache2-foreground"