FROM php:7.4-fpm

ARG USER_ID
ARG GROUP_ID

RUN apt-get update && apt-get install -y \
        git \
        curl \
        wget \
        zip \
        unzip \
        libonig-dev \
        libfreetype6-dev \
    # Clear cache
    && apt-get clean \
    && rm -r /var/lib/apt/lists/* \
    # Install xdebug
    && pecl install xdebug-2.8.0 \
    && docker-php-ext-enable xdebug \
    # Install PHP extensions
    && docker-php-ext-install json \
    && docker-php-ext-install pdo \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install pdo_mysql

RUN wget https://getcomposer.org/installer -O - -q \
        | php -- --install-dir=/bin --filename=composer --quiet

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

#ADD ./conf.d/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www

USER "${USER_ID}:${GROUP_ID}"
USER $user
