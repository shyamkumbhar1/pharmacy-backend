#!/bin/bash
set -e

echo "Before Install: Stopping existing containers"
cd /var/www/pharmacy-backend

# Stop existing containers if running
docker-compose down || true

# Backup current deployment
if [ -d "/var/www/pharmacy-backend-backup" ]; then
    rm -rf /var/www/pharmacy-backend-backup
fi

if [ -d "/var/www/pharmacy-backend" ]; then
    cp -r /var/www/pharmacy-backend /var/www/pharmacy-backend-backup || true
fi

echo "Before Install completed"
