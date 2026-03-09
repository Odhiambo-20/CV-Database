FROM php:8.2-apache

# Install required PHP extensions for MySQL access.
RUN docker-php-ext-install pdo pdo_mysql

# Set recommended Apache options.
RUN a2enmod headers

WORKDIR /var/www/html
COPY . /var/www/html/

EXPOSE 80
