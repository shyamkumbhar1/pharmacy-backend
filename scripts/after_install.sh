#!/bin/bash
set -e

echo "After Install: Setting up environment"
cd /var/www/pharmacy-backend

# Determine environment
if [ -f .env.production ]; then
    cp .env.production .env
    echo "Using production environment"
elif [ -f .env.example ]; then
    cp .env.example .env
    echo "Using example environment (update with actual values)"
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    docker-compose run --rm app php artisan key:generate || true
fi

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || chown -R $(whoami):$(whoami) storage bootstrap/cache

# Install/update dependencies
if [ -f composer.json ]; then
    docker-compose run --rm app composer install --no-dev --optimize-autoloader || true
fi

echo "After Install completed"
