FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . /var/www
COPY --chown=www-data:www-data . /var/www

RUN composer install --no-dev --optimize-autoloader
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

USER www-data
EXPOSE 9000
CMD ["php-fpm"]
