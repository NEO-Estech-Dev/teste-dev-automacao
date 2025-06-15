FROM php:8.3-fpm

ARG user=app
ARG uid=1000

RUN apt-get update && apt-get install -y \
   git  \
   curl \
   zip \
   libpng-dev \
   libonig-dev \
   libxml2-dev \
   unzip \
   supervisor

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

USER $user

ENTRYPOINT ["bash", "./startup.sh"]