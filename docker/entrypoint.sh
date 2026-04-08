#!/bin/bash

# Wait for database to be ready
echo "Waiting for database..."
until nc -z db 3306; do
  sleep 1
done
echo "Database is up!"

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Start FrankenPHP
echo "Starting FrankenPHP..."
exec frankenphp run --config /etc/caddy/Caddyfile
