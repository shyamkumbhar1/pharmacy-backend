#!/bin/bash
set -e

echo "Application Stop: Stopping containers"
cd /var/www/pharmacy-backend

# Stop containers gracefully
docker-compose stop || true

echo "Application Stop completed"
