# Millionaire Game

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)

## How to use:
**For initializing the project:**
- Clone the project
- Run `cd millionaire-game`
- Run `cp .env.example .env`
- Run `docker network create default-network`.
- Run `docker compose up --build -d`.
- Run `docker exec -it php chown -R www-data:www-data storage`.
- Run `docker exec -it php composer install`.
- Run `docker exec -it php php artisan migrate --seed`.

Open `localhost:8081` for playing the game!

**For running feature and unit tests:**
- Run `docker exec -it php php artisan test`

**For managing the questions:**
- Run `docker exec -it php php artisan admin:create {name} {surname} {password}` with arbitrary values to create an admin.
- Login with the created admin.
- Open `localhost:8081/admin/questions`.
