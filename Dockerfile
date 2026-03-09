FROM php:8.2-apache

# Install required PHP extensions for MySQL access.
RUN docker-php-ext-install pdo pdo_mysql

# Force exactly one Apache MPM module and verify it.
RUN set -eux; \
    rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf; \
    a2enmod mpm_prefork headers; \
    test "$(apache2ctl -M 2>/dev/null | grep -c 'mpm_.*_module')" -eq 1

WORKDIR /var/www/html
COPY . /var/www/html/

EXPOSE 80
