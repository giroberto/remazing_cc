FROM php:7-alpine
RUN apk upgrade --no-cache \
    && apk add --no-cache postgresql-dev postgresql-client npm \
    && docker-php-ext-install pdo_pgsql
RUN  php -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" \
   	&& php composer-setup.php --install-dir=/bin --filename=composer \
   	&& php -r "unlink('composer-setup.php');" \
   	&& composer global require hirak/prestissimo
CMD [ "php", "artisan", "serve", "--host=0.0.0.0", "--port=8080" ]
