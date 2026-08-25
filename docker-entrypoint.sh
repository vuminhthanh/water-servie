#!/bin/sh
set -eu

attempt=1
max_attempts=10

until php artisan migrate --force; do
    if [ "$attempt" -ge "$max_attempts" ]; then
        echo "Không thể kết nối database sau $max_attempts lần thử." >&2
        exit 1
    fi

    echo "Database chưa sẵn sàng, thử lại sau 3 giây ($attempt/$max_attempts)..."
    attempt=$((attempt + 1))
    sleep 3
done

if [ "${SEED_PRODUCTION_DATA:-false}" = "true" ]; then
    php artisan db:seed --class='Database\Seeders\ProductionBootstrapSeeder' --force
fi

php artisan optimize:clear

exec php -S "0.0.0.0:${PORT:-8000}" server.php
