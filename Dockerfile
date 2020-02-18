FROM php:7.2-cli

COPY ./composer.phar /usr/local/bin/composer

ADD ./ /app

WORKDIR /app
