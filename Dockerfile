FROM php:7.4-apache

RUN apt-get -y update

RUN apt-get install -y zip unzip libzip-dev zlib1g-dev libonig-dev

RUN a2enmod rewrite headers
COPY vhost.conf /etc/apache2/sites-enabled/000-default.conf

RUN docker-php-ext-install mbstring mysqli pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u 1000 -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && chown -R devuser:devuser /home/devuser

WORKDIR /var/www/html
