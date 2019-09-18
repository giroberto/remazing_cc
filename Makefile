execute:
	docker-compose up

before_first_run:
    docker-compose up --no-start
    docker-compose run web composer install
    docker-compose run web npm install
    docker-compose run web npm run prod

db_seed:
    docker-compose exec web php artisan migrate
    docker-compose exec web php artisan seed

shell: ## Access the web docker container shell
	docker-compose exec web /bin/sh

shell_pg: ## Access the postgres docker container shell
	docker-compose exec postgres /bin/sh

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
    RUN php -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" \
    	&& php composer-setup.php --install-dir=/bin --filename=composer \
    	&& php -r "unlink('composer-setup.php');"
    RUN composer global require hirak/prestissimo
    CMD [ "php", "artisan", "serve", "--host=0.0.0.0", "--port=8080" ]
	EOF
