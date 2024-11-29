FROM php:8.3.8-cli-alpine3.20

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Autoload és Composer függőségek másolása
COPY composer.json composer.json

# Composer install futtatása
RUN composer install

# Fakerphp telepítése
RUN composer require fakerphp/faker --dev

WORKDIR /app

CMD ["php", "-a"]

# docker-compose build
# docker-compose up


# Korlátlan jogosultság beállítása rád:

# docker exec -it <konténer_neve> bash
# whoami
# id
# sudo chown -R $(whoami):$(whoami) src/output
# chmod -R 777 src/output
