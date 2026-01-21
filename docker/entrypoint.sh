#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

mkdir -p storage/framework/cache \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

if [ ! -f storage/database.sqlite ]; then
  touch storage/database.sqlite
fi

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

if [ "${STORAGE_LINK:-true}" = "true" ]; then
  gosu www-data php artisan storage:link || true
fi

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  gosu www-data php artisan migrate --force
fi

if [ "${CACHE_CONFIG:-true}" = "true" ]; then
  gosu www-data php artisan config:cache
fi

if [ "${CACHE_ROUTES:-true}" = "true" ]; then
  gosu www-data php artisan route:cache
fi

if [ "${CACHE_VIEWS:-true}" = "true" ]; then
  gosu www-data php artisan view:cache
fi

if [ "${CACHE_EVENTS:-true}" = "true" ]; then
  gosu www-data php artisan event:cache || true
fi

exec gosu www-data "$@"
