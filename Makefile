init: down build up composer

build: docker-build
up: docker-up
down: docker-down
restart: down up

docker-up: ## Запуск контейнеров в режиме демонов
	docker-compose up -d

docker-down: ## Остановка контейнеров с удалением временных файлов
	docker-compose down --remove-orphans

docker-build: ## Сборка образов
	docker-compose build

ds: ## Войти в контейнер php-fpm c root правами
	docker-compose exec -u root php-fpm /bin/bash

backup: ## Сделать дамп базы mysql
	 docker-compose exec mysql /usr/bin/mysqldump -u ${PMA_USER} --password=${DB_ROOT_PASSWORD} camagru > backup.sql

mysql-down: ## Останавливаем mysql на хосте, если запущена
	sudo service mysql stop

migrate: ## выполнить миграции в контейнере
	docker-compose exec php-fpm php artisan migrate

ide-helper: ## генерация phpdoc моделях
	docker-compose exec php-fpm php artisan ide-helper:model -W

migrate-rollback: ## откатить последнею миграцию в контейнере
	docker-compose exec php-fpm php artisan migrate:rollback

db-seed: ## Наполнить бд тестовыми данными
	docker-compose exec php-fpm php artisan db:seed --class=ProductTypeSeeder

env: ## копируем env.example
	cp .env.example .env

composer: ## выполнить composer install
	composer install

help: ## Парсит сам себя и выводит форматированный список всех комманд
	@grep -E '(^[a-z].*[^:]\s*##)|(^##)' Makefile | \
	perl -pe "s/^##\s*//" | \
	awk ' \
		BEGIN { FS = ":.*##" } \
		$$2 { printf "\033[32m%-30s\033[0m %s\n", $$1, $$2 } \
		!$$2 { printf " \033[33m%-30s\033[0m\n", $$1 } \
	'
