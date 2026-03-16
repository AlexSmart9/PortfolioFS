# 1. Official PHP Apache base image
FROM php:8.2-apache

# 2. Install system tools and PostgreSQL extensions
RUN apt-get update && apt-get install -y libpq-dev unzip git && docker-php-ext-install pdo pdo_pgsql

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Enable Apache rewrite module for the Router
RUN a2enmod rewrite

# 5. Configure VirtualHost to point to the 'public' directory
RUN { \
    echo '<VirtualHost *:80>'; \
    echo '    DocumentRoot /var/www/html/public'; \
    echo '    <Directory /var/www/html/public>'; \
    echo '        AllowOverride All'; \
    echo '        Require all granted'; \
    echo '    </Directory>'; \
    echo '</VirtualHost>'; \
} > /etc/apache2/sites-available/000-default.conf

# 6. Copy the project files
COPY . /var/www/html/

# 7. Install PHP dependencies via Composer
RUN composer install --no-dev --optimize-autoloader

# 8. Set proper permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# 9. Explicitly expose port 80 to Railway's internal network
EXPOSE 80

# 10. THE MASTER FIX: Disable conflicting MPM modules at STARTUP, then launch Apache
CMD bash -c "a2dismod mpm_event mpm_worker 2>/dev/null || true && a2enmod mpm_prefork && apache2-foreground"