FROM composer as builder
WORKDIR /var/www/html
COPY composer.* ./
RUN composer install
COPY . .


FROM php:7.3-fpm-alpine
# lumen packages
RUN docker-php-ext-install mbstring tokenizer mysqli pdo_mysql
