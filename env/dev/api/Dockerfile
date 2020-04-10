FROM php:7.4-fpm

# apt-get update
RUN apt-get clean && apt-get update

RUN ln -s -f /usr/share/zoneinfo/America/Sao_Paulo  /etc/localtime

COPY conf/docker-php-opcache.ini $PHP_INI_DIR/conf.d/

# install the php extensions
RUN apt-get install -y libpq-dev libzip-dev unzip librdkafka-dev libzookeeper-mt-dev cron rsync procps libpng-dev libjpeg-dev zlib1g-dev nodejs --no-install-recommends && \
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

# install xdebug
RUN pecl install xdebug; \
    docker-php-ext-enable xdebug;

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"; \
    php -r "copy('https://composer.github.io/installer.sig', 'composer.sig');"; \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === file_get_contents('composer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"; \
    php composer-setup.php; \
    php -r "unlink('composer-setup.php');"; \
    php -r "unlink('composer.sig');"; \
    mv composer.phar /usr/local/bin/composer;

# install prestissimo for composer parallel execution
RUN composer global require hirak/prestissimo

RUN php -r "copy('https://npmjs.org/install.sh', 'npm-install.sh');"; \
    bash npm-install.sh; \
    php -r "unlink('npm-install.sh');";
