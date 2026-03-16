# 1. Official image
FROM php:8.2-apache

# 2. Dependencies
RUN apt-get update && apt-get install -y libpq-dev unzip git && docker-php-ext-install pdo pdo_pgsql

# 3. Apache Config
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. App Code & Composer
COPY . /var/www/html/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html

# 5. Expose port and apply the original MPM bug fix
EXPOSE 80
CMD bash -c "a2dismod mpm_event mpm_worker 2>/dev/null || true && a2enmod mpm_prefork && apache2-foreground"