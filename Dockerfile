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

# 8. Create a bulletproof startup script
RUN echo '#!/bin/bash' > /start.sh && \
    echo 'sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf' >> /start.sh && \
    echo 'sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf' >> /start.sh && \
    echo 'a2dismod mpm_event mpm_worker 2>/dev/null || true' >> /start.sh && \
    echo 'a2enmod mpm_prefork' >> /start.sh && \
    echo 'apache2-foreground' >> /start.sh && \
    chmod +x /start.sh

# 9. Execute the script on startup
CMD ["/start.sh"]