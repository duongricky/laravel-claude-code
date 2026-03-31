up:
	docker compose up -d

build:
	docker compose up -d --build

down:
	docker compose down

restart:
	docker compose down
	docker compose up -d

ps:
	docker compose ps

logs:
	docker compose logs -f

app:
	docker compose exec app sh

composer-install:
	docker compose exec app composer install

key:
	docker compose exec app php artisan key:generate

migrate:
	docker compose exec app php artisan migrate

fresh:
	docker compose exec app php artisan migrate:fresh --seed

test:
	docker compose exec app php artisan test

tinker:
	docker compose exec app php artisan tinker

optimize-clear:
	docker compose exec app php artisan optimize:clear
