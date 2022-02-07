FROM php:8.0.15-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    g++

RUN docker-php-ext-install \
    pdo_mysql 

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./src /money-transaction-api

WORKDIR "/money-transaction-api"

RUN cd /money-transaction-api && composer install
RUN composer dump-autoload