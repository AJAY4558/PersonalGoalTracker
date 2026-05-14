FROM php:8.2-apache

# Install only strictly required system libraries
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libzip-dev libsqlite3-dev libonig-dev libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (pdo_pgsql for Render PostgreSQL, pdo_sqlite as fallback)
RUN docker-php-ext-install pdo pdo_pgsql pdo_sqlite mbstring zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
