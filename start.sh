#!/bin/bash

git pull

composer install
composer update
sleep 10
docker compose up -d
sleep 10

php artisan migrate
php artisan serve

open http://127.0.0.1:8000/
