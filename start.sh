#!/bin/bash

git pull

composer install
composer update
sleep 10
docker compose up -d
sleep 10

php artisan migrate

php -S localhost:8000 -t public

