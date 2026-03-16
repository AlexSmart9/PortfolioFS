# 1. Usar la imagen oficial
FROM php:8.2-apache

# 2. Instalar herramientas y traductor de Postgres
RUN apt-get update && apt-get install -y libpq-dev unzip git && docker-php-ext-install pdo pdo_pgsql

# 3. Traer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Habilitar mod_rewrite para tu Router y .htaccess
RUN a2enmod rewrite

# 5. Crea una configuración pura de VirtualHost sin tocar archivos globales
RUN { \
    echo '<VirtualHost *:80>'; \
    echo '    DocumentRoot /var/www/html/public'; \
    echo '    <Directory /var/www/html/public>'; \
    echo '        AllowOverride All'; \
    echo '        Require all granted'; \
    echo '    </Directory>'; \
    echo '</VirtualHost>'; \
} > /etc/apache2/sites-available/000-default.conf

# 6. Copiar tu código
COPY . /var/www/html/

# 7. Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# 8. Dar permisos al servidor
RUN chown -R www-data:www-data /var/www/html