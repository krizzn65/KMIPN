FROM php:8.1-fpm

# Set memory limit untuk composer
ENV COMPOSER_MEMORY_LIMIT=-1

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first (untuk caching)
COPY composer.json composer.lock* ./

# Install dependencies dengan error handling
RUN composer install --no-dev --no-scripts --no-autoloader --ansi || \
    composer install --no-dev --no-scripts --no-autoloader --ansi --ignore-platform-reqs

# Copy aplikasi
COPY . /var/www

# Generate autoloader
RUN composer dump-autoload --no-dev --optimize

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Switch to www-data user
USER www-data

EXPOSE 9000
CMD ["php-fpm"]
