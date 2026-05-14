#!/bin/bash
set -e

echo "==> Starting Altair — Built for Ambition"

# Use production env if .env not set by Render env vars
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.production /var/www/html/.env
    echo "==> Copied .env.production to .env"
fi

# Generate app key
php artisan key:generate --force
echo "==> App key generated"

# Create SQLite database directory and file
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database
echo "==> SQLite database created"

# Set correct permissions on storage
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations
php artisan migrate --force
echo "==> Migrations complete"

# Cache config and routes (NOT views — asset() URLs must stay dynamic)
php artisan config:cache
php artisan route:cache
echo "==> Cache warmed"

# Create storage symlink
php artisan storage:link || true

echo "==> Altair is ready! Starting Apache..."
exec apache2-foreground
