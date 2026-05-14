#!/bin/bash
set -e

echo "==> Starting Altair — Built for Ambition"

# Use production env if .env doesn't exist
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.production /var/www/html/.env
    echo "==> Copied .env.production to .env"
fi

# Generate app key
php artisan key:generate --force
echo "==> App key generated"

# Set correct permissions on storage
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations (PostgreSQL tables will be created here)
php artisan migrate --force
echo "==> Migrations complete"

# Cache config and routes
php artisan config:cache
php artisan route:cache
echo "==> Cache warmed"

# Create storage symlink
php artisan storage:link || true

echo "==> Altair is ready! Starting Apache..."
exec apache2-foreground
