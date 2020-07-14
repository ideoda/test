# ./docker/php/Dockerfile

FROM php:7.3-fpm

USER root

RUN pecl install apcu

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini
     
RUN docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y gnupg gnupg1 gnupg2
RUN echo "deb http://packages.dotdeb.org wheezy all" >> /etc/apt/sources.list
RUN echo "deb-src http://packages.dotdeb.org wheezy all" >> /etc/apt/sources.list
RUN curl -sS --insecure https://www.dotdeb.org/dotdeb.gpg | apt-key add -
RUN apt-get update && \
apt-get install -y zlib1g-dev libzip-dev

RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu
