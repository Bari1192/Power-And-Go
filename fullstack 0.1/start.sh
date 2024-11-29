#!/bin/bash

if [ -f "backend/.env" ]; then
    echo "A .env fájl már létezik"
else
    echo "A .env fájl nem létezik, másolás folyamatban..."
    cp backend/.env.example backend/.env
fi

if [ ! -L ".env" ]; then
    ln -s backend/.env
    echo "Szimbolikus link létrehozva a .env fájlhoz"
else
    echo "A szimbolikus link már létezik"
fi

# Docker Compose konténerek indítása
docker compose up -d

# Composer csomagok telepítése a backendhez
docker compose exec backend composer install

# Laravel migrációk futtatása
docker compose exec backend php artisan migrate

# Laravel API kulcs generálása, ha még nem létezik
if ! docker compose exec backend php artisan key:generate | grep -q "Application key set"; then
    echo "Az API kulcs már létezik"
else
    echo "API kulcs sikeresen generálva"
fi

# Seeder-ek futtatása az automatikus adatfeltöltéshez
docker compose exec backend php artisan db:seed --force
echo "Az adatbázis sikeresen feltöltve"

# Ellenőrizzük, hogy a frontendhez szükséges node_modules mappa létezik-e
if [ ! -d "frontend/node_modules" ]; then
    echo "A frontend node_modules mappa nem található, NPM csomagok telepítése..."
    docker run --rm -v "$(pwd)/frontend:/app" --entrypoint npm idomi27/vue install
else
    echo "A frontend node_modules mappa már létezik"
fi

# Frontend build vagy fejlesztői mód indítása
docker compose exec frontend npm run dev

echo "A projekt sikeresen elindult!"



####    frontend indítása automatán --> npm run dev -vel.