FROM php:8.2-cli

# Install required PHP extensions for MySQL access.
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html
COPY . /var/www/html/

# Railway sets PORT dynamically. Default to 8080 for local runs.
ENV PORT=8080
EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t /var/www/html"]
