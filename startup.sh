#!/bin/bash

cp .env.example .env
cp .env .env.testing
composer install
php artisan key:generate
php artisan migrate:fresh --seed
supervisord -c /etc/supervisor/conf.d/supervisord.conf &
sleep 5
php artisan import:csv example.csv
exec php-fpm