FROM php:7.2.8-fpm-alpine

ARG composer_version

WORKDIR /var/www
VOLUME /var/www

# Composer
RUN curl --silent https://getcomposer.org/download/${composer_version}/composer.phar > /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

# Prestissimo
RUN composer global require --prefer-dist --no-progress --no-interaction hirak/prestissimo && \
    composer clear-cache

RUN apk add --update-cache $PHPIZE_DEPS && \
    pecl install xdebug-2.6.0 && \
    apk del $PHPIZE_DEPS

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
