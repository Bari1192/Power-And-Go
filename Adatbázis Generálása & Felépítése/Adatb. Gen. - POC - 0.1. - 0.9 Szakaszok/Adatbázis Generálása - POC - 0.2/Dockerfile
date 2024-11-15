# Alap PHP image Composer-rel
FROM php:8.3.8-cli-alpine3.20

# Composer telepítése
COPY --from=composer:2.7.7 /usr/bin/composer /usr/bin/composer

# Munkakönyvtár beállítása
WORKDIR /app

# composer.json másolása és csomagok telepítése
COPY composer.json /app/
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN apk update && apk add --no-cache bash

# PHP fájlok másolása [Honnan ---> Hová] a konténerbe.
# NEM FOGOD LÁTNI, DE OTT VAN ÉS LEFUT a PHP FÁJLOD!!!!!
COPY . /app
COPY src src
COPY composer.json composer.json

RUN ["composer", "install"]

COPY index.php index.php
# A fő PHP fájl futtatása
ENTRYPOINT ["php", "index.php"]

# ENTRYPOINT["php", "index.php"]

# Ezzel a Dockerfile-lal, amikor a konténert elindítod, 
# az index.php automatikusan lefut,
# A Faker:Factory által generált adatok megjelennek.