FROM php:8.3.8-cli-alpine3.20

# Composer telepítése és futtatása
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.json

RUN adduser -u 1000 -s /bin/bash -D phpdocker

COPY --from=composer:2.7.7 /usr/bin/composer /usr/bin/composer

# Composer install futtatása
RUN composer install

# Fakerphp telepítése
RUN composer require fakerphp/faker --dev

# Bash telepítése az Alpine konténerbe
RUN apk add --no-cache bash

# Munkakönyvtár beállítása és output mappa létrehozása írási jogosultsággal
WORKDIR /app
RUN mkdir -p /app/src/output && chmod -R 777 /app/src/output

# Fő parancs helyettesítése
CMD ["php"]

# docker exec -it <konténer_neve> bash
# whoami
# id
# sudo chown -R $(whoami):$(whoami) src/output
# chmod -R 777 src/output
