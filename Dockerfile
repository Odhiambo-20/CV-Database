FROM php:8.2-apache

# Install required PHP extensions for MySQL access.
RUN docker-php-ext-install pdo pdo_mysql

# Ensure exactly one Apache MPM is loaded (avoids "More than one MPM loaded").
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork headers

WORKDIR /var/www/html
COPY . /var/www/html/

EXPOSE 80
