#!/bin/bash
set -e

if [ -n "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
fi

php artisan storage:link || true

echo "Running migrations..."
php artisan migrate --force || true

echo "Running seeders..."
php artisan db:seed --force || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec "$@"