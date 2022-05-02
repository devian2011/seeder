FROM php:7.4-fpm-buster

RUN mkdir -p /app

WORKDIR /tmp

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && ls -l \
    && chmod +x composer.phar \
    && mv composer.phar /usr/bin/composer

RUN apt-get update && apt-get install -y git
RUN docker-php-ext-install pdo_mysql

WORKDIR /app
