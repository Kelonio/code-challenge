#!/usr/bin/env bash
composer install
php /app/bin/console doctrine:schema:update --force

exec "$@"