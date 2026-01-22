FROM composer:2.8 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
  --no-dev \
  --prefer-dist \
  --no-interaction \
  --no-progress \
  --optimize-autoloader \
  --no-scripts

COPY . .

RUN mkdir -p bootstrap/cache

RUN composer dump-autoload --optimize

FROM oven/bun:1.3.6 AS bunbin

FROM php:8.5-cli AS assets
WORKDIR /app

COPY --from=bunbin /usr/local/bin/bun /usr/local/bin/bun

COPY package.json bun.lock* ./
RUN bun ci

COPY . .
COPY --from=vendor /app/vendor /app/vendor
COPY --from=vendor /app/bootstrap/cache /app/bootstrap/cache

RUN bun run build


FROM dunglas/frankenphp:1-php8.5 AS runtime
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
  ca-certificates \
  curl \
  gosu \
  && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
  pdo_sqlite \
  sqlite3 \
  opcache \
  pcntl

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-laravel-production.ini
COPY Caddyfile /etc/caddy/Caddyfile

COPY --from=vendor /app/vendor /var/www/html/vendor
COPY --from=assets /app/public/build /var/www/html/public/build
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/public

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
