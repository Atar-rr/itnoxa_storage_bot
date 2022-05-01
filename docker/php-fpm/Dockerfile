FROM php:8.0-fpm

ARG USER_ID
ARG GROUP_ID

RUN apt-get update && apt-get install -y \
        git \
        curl \
        wget \
        zip \
        libzip-dev \
        unzip \
        libonig-dev \
        libfreetype6-dev \
    # Clear cache
    && apt-get clean \
    && rm -r /var/lib/apt/lists/* \
    # Install xdebug
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    # Install sodium
    && docker-php-ext-enable sodium \
    # Install PHP extensions
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable pdo_mysql \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip

RUN wget https://getcomposer.org/installer -O - -q \
        | php -- --install-dir=/bin --filename=composer --quiet

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

ADD ./conf.d/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www

USER "${USER_ID}:${GROUP_ID}"
USER $user
