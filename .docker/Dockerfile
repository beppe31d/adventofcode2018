FROM php:7.2.8-fpm-alpine

ARG composer_version

WORKDIR /var/www
VOLUME /var/www

RUN apk add --update-cache $PHPIZE_DEPS && \
    pecl install xdebug-2.6.0 && \
    apk del $PHPIZE_DEPS

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
