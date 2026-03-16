
FROM php:8.2-apache


RUN a2dismod mpm_event mpm_worker || true


RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf


RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN a2enmod rewrite


ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


COPY . /var/www/html/
RUN composer install --no-dev --optimize-autoloader


RUN chown -R www-data:www-data /var/www/html