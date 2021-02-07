FROM php:8.0-cli-alpine

RUN apk update \
    && apk add --no-cache curl g++ make git autoconf bash \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete \
    && rm -rf /tmp/*

COPY ./php-cli/configurations/* /usr/local/etc/php/conf.d/