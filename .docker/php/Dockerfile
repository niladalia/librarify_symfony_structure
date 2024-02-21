FROM php:8.2-fpm-buster
COPY .docker/php/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini
RUN apt-get update && apt-get install 
#RUN docker-php-ext-configure gd --with-jpeg --with-freetype 
RUN docker-php-ext-install \
    pdo pdo_mysql 

# Set timezone
RUN ln -snf /usr/share/zoneinfo/Europe/Madrid /etc/localtime && echo Europe/Madrid > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', Europe/Madrid > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version

WORKDIR /var/www/librarify

#COPY . .

#ENV COMPOSER_ALLOW_SUPERUSER=1

#RUN composer install 

RUN ls