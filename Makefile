execute:
	docker-compose up

first_run:
    docker-compose exec web npm install
    docker-compose exec web npm run prod
    docker-compose exec web php artisan migrate

npm_install:
    docker-compose exec web npm run prod

npm_seed:
    php artisan seed

shell: ## Access the web docker container shell
	docker-compose exec web /bin/sh

shell_pg: ## Access the postgres docker container shell
	docker-compose exec pgserver /bin/sh

stop:
	docker-compose stop

destroy:
	docker-compose down

disk_usage:
	docker system df

build: Dockerfile
	mkdir php7 && cd php7
	mv ../Dockerfile .
	docker build --no-cache -t php7-alpine .
	cd ..
	rm -rf php7

.ONESHELL:
Dockerfile:
	cat <<- EOF > $@
	FROM php:7-alpine
    RUN apk upgrade --no-cache \
    && apk add --no-cache postgresql-dev postgresql-client npm \
    && docker-php-ext-install pdo_pgsql
    CMD [ "php", "artisan", "serve", "--host=0.0.0.0", "--port=8080" ]
	EOF
