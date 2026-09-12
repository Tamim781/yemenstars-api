#!/bin/bash

if [ -z "$APP_KEY" ]; then
    export APP_KEY="base64:owNmGemH30Fo2/z1vnNWafVb5MY0IGq+KfM3bRo04/M="
fi

mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database
chmod -R 777 /var/www/html/database
chmod 666 /var/www/html/database/database.sqlite

if [ -n "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/000-default.conf
fi

php artisan storage:link || true

echo "Running migrations..."
php artisan migrate --force || true

echo "Running seeders..."
php artisan db:seed --force || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Starting Apache server..."
exec "$@"