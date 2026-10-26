#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
command -v composer >/dev/null || { echo 'Composer 2 is required'; exit 1; }
command -v php >/dev/null || { echo 'PHP 8.3+ is required'; exit 1; }
php -r 'if (PHP_VERSION_ID < 80300) { fwrite(STDERR, "Need PHP 8.3+\n"); exit(1); }'
if [[ -f artisan ]]; then composer install; exit 0; fi
TMP_DIR="$(mktemp -d)"
trap 'rm -rf "$TMP_DIR"' EXIT
composer create-project laravel/laravel "$TMP_DIR/base" '^13.0' --no-interaction
cp -an "$TMP_DIR/base"/. .
composer install
php artisan about
printf '\nBootstrap complete. Configure .env then migrate.\n'
