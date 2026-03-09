FROM php:8.2-apache

# Install required PHP extensions for MySQL access.
RUN docker-php-ext-install pdo pdo_mysql

# Force exactly one Apache MPM module.
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork headers

WORKDIR /var/www/html
COPY . /var/www/html/

EXPOSE 80
