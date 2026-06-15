#!/bin/sh
set -e

php artisan storage:link --quiet 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --force

php-fpm -D
exec nginx -g "daemon off;"
