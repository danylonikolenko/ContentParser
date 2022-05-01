#!/bin/bash

composer install
docker compose up -d

sleep 20

php artisan migrate
php artisan serve

open http://127.0.0.1:8000/
