FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql bcmath zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

ENV APP_KEY="base64:owNmGemH30Fo2/z1vnNWafVb5MY0IGq+KfM3bRo04/M="
ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 8080

CMD sh -c "mkdir -p /var/www/html/database && touch /var/www/html/database/database.sqlite && chmod 777 /var/www/html/database /var/www/html/database/database.sqlite && php artisan storage:link || true && php artisan migrate --force || true && php artisan db:seed --force || true && exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"