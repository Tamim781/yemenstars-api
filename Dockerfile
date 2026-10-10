FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    python3 \
    python3-pip \
    && docker-php-ext-install pdo_mysql pdo_pgsql bcmath zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN pip3 install --no-cache-dir -r yemen_stars_ai/requirements.txt --break-system-packages || pip3 install --no-cache-dir -r yemen_stars_ai/requirements.txt || true

RUN mkdir -p /var/www/html/storage/framework/views /var/www/html/storage/framework/cache /var/www/html/storage/framework/sessions /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

ENV APP_KEY="base64:owNmGemH30Fo2/z1vnNWafVb5MY0IGq+KfM3bRo04/M="
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV GEMINI_MODEL="gemini-1.5-flash"

EXPOSE 8080

CMD sh -c "mkdir -p /var/www/html/storage/framework/views /var/www/html/storage/framework/cache /var/www/html/storage/framework/sessions /var/www/html/database && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache && touch /var/www/html/database/database.sqlite && chmod 777 /var/www/html/database /var/www/html/database/database.sqlite && php artisan storage:link || true && php artisan migrate --force || true && php artisan db:seed --force || true && (cd /var/www/html/yemen_stars_ai && python3 -m uvicorn main:app --host 127.0.0.1 --port 8000 &) && exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
