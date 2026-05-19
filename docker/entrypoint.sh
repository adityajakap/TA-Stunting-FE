#!/usr/bin/env bash
set -e

echo "Starting Docker Entrypoint for Frontend..."

# 1. Setup .env
if [ ! -f ".env" ]; then
    echo ".env not found, copying from .env.docker..."
    cp .env.docker .env
fi

# 2. Install dependencies
echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. Generate key
echo "Generating APP_KEY if needed..."
php artisan key:generate --no-interaction

# 4. Cache config
echo "Caching configuration..."
php artisan config:cache

echo "Setup complete! Starting PHP-FPM..."
# Execute the main container command (CMD from Dockerfile)
exec "$@"
