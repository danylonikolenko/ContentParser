#!/bin/bash

git pull

docker compose up -d
sleep 10
composer install
composer update

php artisan migrate

php -S localhost:8000 -t public

