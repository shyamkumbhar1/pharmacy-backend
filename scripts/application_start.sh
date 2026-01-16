#!/bin/bash
set -e

echo "Application Start: Starting containers"
cd /var/www/pharmacy-backend

# Pull latest images
docker-compose pull || true

# Build and start containers
docker-compose up -d --build

# Wait for services to be ready
sleep 10

# Run migrations
docker-compose exec -T app php artisan migrate --force || true

# Clear cache
docker-compose exec -T app php artisan config:clear || true
docker-compose exec -T app php artisan cache:clear || true
docker-compose exec -T app php artisan route:clear || true

# Health check
echo "Checking application health..."
for i in {1..30}; do
    if curl -f http://localhost:8000/api/health || curl -f http://localhost:8000; then
        echo "Application is healthy"
        exit 0
    fi
    echo "Waiting for application to start... ($i/30)"
    sleep 2
done

echo "Application Start completed"
