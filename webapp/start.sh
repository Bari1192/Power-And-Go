#!/bin/bash

if [ -f "backend/.env" ]; then
    echo "A .env fájl már létezik"
else
    cp backend/.env.example backend/.env
fi

if [ -f ".env" ]; then
    echo "A .env fájl már létezik"
else
    ln -s backend/.env
fi

docker run --rm  -v "$(pwd)/frontend:/app" --entrypoint npm idomi27/vue install

docker compose up -d

docker compose exec backend composer install

docker compose exec backend php artisan storage:link

docker compose exec backend php artisan migrate:fresh --seed

# if [ -f "backend/tests/test-run-order.sh" ]; then
#     bash backend/tests/test-run-order.sh
# else
#     echo "test-run-order.sh fájl nem található."
# fi
docker compose exec backend php artisan migrate --path=database/migrations/dbViews

echo "A konténerek elindultak, a migrációk, nézetek, tesztek lefutottak."

if [ -z "${APP_KEY}" ]; then
    docker compose exec backend php artisan key:generate
else
    echo "Az API kulcs már létezik" 
fi