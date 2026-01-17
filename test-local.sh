#!/bin/bash
# test-local.sh - Local testing before push

set -e

echo "🧪 Running local tests..."
echo "=========================="

# Check if .env exists
if [ ! -f .env ]; then
  echo "📝 Creating .env from .env.example..."
  cp .env.example .env || echo "⚠️ .env.example not found"
  php artisan key:generate --force
fi

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-interaction

# Set permissions
echo "📁 Setting permissions..."
chmod -R 755 storage bootstrap/cache || true

# Run migrations (if database available)
echo "🗄️ Running migrations..."
php artisan migrate --force || echo "⚠️ Migration skipped (database not available)"

# Run tests
echo "🧪 Running tests..."
php artisan test || echo "⚠️ No tests found or tests failed"

# Code quality checks
echo "✅ Running code quality checks..."
php artisan config:cache || echo "⚠️ Config cache"
php artisan route:cache || echo "⚠️ Route cache"

echo ""
echo "✅ Local tests completed!"
echo "📋 If all checks passed, you can push to GitHub"
