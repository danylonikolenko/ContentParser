#!/usr/bin/env bash
php-fpm --daemonize
nginx -g "daemon off;"
