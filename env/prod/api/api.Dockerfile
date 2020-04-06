FROM php:7.4-fpm

# apt-get update
RUN apt-get clean && apt-get update

RUN ln -s -f /usr/share/zoneinfo/America/Sao_Paulo  /etc/localtime

COPY conf/docker-php-opcache.ini $PHP_INI_DIR/conf.d/

# install the php extensions
RUN apt-get install -y libpq-dev libzip-dev unzip cron rsync procps libpng-dev libjpeg-dev zlib1g-dev npm --no-install-recommends && \
    docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        opcache

# gd extension
RUN docker-php-ext-configure gd \
    && docker-php-ext-install gd

# redis extension
RUN pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis

RUN rm -rf /var/www/*
COPY ./dist/build /var/www
RUN chown -R www-data:www-data /var/www

