FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql bcmath zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

ENV APP_KEY="base64:owNmGemH30Fo2/z1vnNWafVb5MY0IGq+KfM3bRo04/M="
ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 80

CMD sh -c "mkdir -p /var/www/html/database; touch /var/www/html/database/database.sqlite; chmod 777 /var/www/html/database /var/www/html/database/database.sqlite; php artisan storage:link || true; if [ -n \"\$PORT\" ]; then sed -i \"s/Listen 80/Listen \$PORT/g\" /etc/apache2/ports.conf; sed -i \"s/<VirtualHost \*:80>/<VirtualHost \*:\$PORT>/g\" /etc/apache2/sites-available/000-default.conf; fi; php artisan migrate --force || true; php artisan db:seed --force || true; exec apache2-foreground"