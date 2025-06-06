FROM composer:2 AS composer_builder

WORKDIR /backend
COPY ./test-backend ./
RUN composer install --no-dev --no-interaction --optimize-autoloader

FROM php:8.2-fpm AS base
WORKDIR /var/www/test-backend

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    zip \
    curl \
    git \
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/log/supervisor /etc/supervisor/conf.d
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisor.conf

COPY --from=composer_builder /backend/vendor ./vendor

FROM base AS development
WORKDIR /var/www/test-backend
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisor.conf"]