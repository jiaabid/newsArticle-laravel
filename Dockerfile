FROM php:8.1.20RC1-zts-alpine3.16

RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql sockets


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app/server
COPY . .
RUN composer install
EXPOSE 8000
# CMD ["php","artisan","migrate;serve --host=0.0.0.0 --port=8000"]
# CMD ["php artisan serve --host=0.0.0.0 --port=8000"]
# CMD php artisan migrate;php artisan serve --host=0.0.0.0 --port=8000
CMD php artisan serve --host=0.0.0.0 --port=8000