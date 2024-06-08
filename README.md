## Installation
cd docker

docker compose up -d --build

docker compose exec app bash

php artisan migrate
