# Millionaire Game

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## How to use:
**For initializing the project:**
- Clone the project
- run `cd millionaire-game`
- run `cp .env.example .env`
- run `docker network create default-network`.
- run `docker compose up --build -d`.
- run `docker exec -it php chown -R www-data:www-data storage`.
- run `docker exec -it php composer install`.
- run `docker exec -it php php artisan migrate --seed`.

Open `localhost:8081/game` for playing the game!

**For running feature and unit tests:**
- run `docker exec -it php php artisan test`
