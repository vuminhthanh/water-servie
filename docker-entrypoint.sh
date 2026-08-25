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

case "${SEED_PRODUCTION_DATA:-false}" in
    true|TRUE|1|yes|YES)
        php artisan db:seed --class='Database\Seeders\ProductionBootstrapSeeder' --force
        ;;
esac

php artisan optimize:clear

exec php -S "0.0.0.0:${PORT:-8000}" -t public server.php
