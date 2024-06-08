## Installation
cd docker

docker compose up -d --build

docker compose exec app bash

composer install

php artisan key:generate

php artisan migrate
