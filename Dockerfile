FROM php:8.3

RUN apt-get update && apt-get install -y bash git zip
RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
