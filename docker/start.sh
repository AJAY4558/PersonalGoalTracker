#!/bin/bash
set -e

echo "==> Starting Altair — Built for Ambition"

# Use production env if .env not set by Render env vars
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.production /var/www/html/.env
fi

# Generate app key if not set
php artisan key:generate --force

# Create SQLite database file
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chown www-data:www-data /var/www/html/database/database.sqlite

# Run migrations
php artisan migrate --force

# Cache configuration and routes for speed (NOT views — asset() URLs must stay dynamic)
php artisan config:cache
php artisan route:cache

# Create storage symlink (public/storage → storage/app/public)
php artisan storage:link || true

echo "==> Altair is ready. Starting Apache..."
exec apache2-foreground
