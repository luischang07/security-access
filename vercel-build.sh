#!/bin/bash

# Install production dependencies only
composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# Run any other build steps if needed
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
