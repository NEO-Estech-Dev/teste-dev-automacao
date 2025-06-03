FROM composer:2 AS composer_builder

FROM php:8.2-fpm AS base

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    zip \
    curl \
    git \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

FROM base AS development
WORKDIR /var/www/test-backend