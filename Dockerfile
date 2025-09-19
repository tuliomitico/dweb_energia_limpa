FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql

COPY src/ /var/www/html
COPY img /var/www/html/img
WORKDIR /var/www/html

