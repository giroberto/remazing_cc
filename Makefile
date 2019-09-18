executar: ## Executa o sistema mantendo os logs no shell
	docker-compose up

executar_sem_shell: ## Executa e libera o shell
	docker-compose up -d

shell: ## Acessa a linha de comando do docker - o serviço precisa estar em execuçao
	docker-compose exec web /bin/sh

shell_pg: ## Acessa a linha de comando do docker - o serviço precisa estar em execuçao
	docker-compose exec pgserver /bin/sh

shell_postgres: ## Acessa a linha de comando do banco de dados - o serviço precisa estar em execuçao
	docker-compose exec web /bin/sh

parar:
	docker-compose stop

destruir:
	docker-compose down

apagar_volumes: ## apaga todos os volumes criados no docker
	docker volume prune

uso_disco:
	docker system df

criar_imagem: Dockerfile
	mkdir serprophp5 && cd serprophp5
	mv ../Dockerfile .
	docker build --no-cache -t serpro/php5-alpine .
	cd ..
	rm -rf serprophp5

criar_link_sf:
	ln -sf ../lib/vendor/symfony/data/web/sf web/sf


.ONESHELL:
Dockerfile:
	cat <<- EOF > $@
	FROM alpine:3.5
	RUN apk upgrade --no-cache \
	&& apk add --no-cache php5 php5-xdebug php5-pdo_pgsql php5-openssl php5-json php5-phar php5-zlib php5-iconv php5-ctype php5-dom php5-xml php5-pgsql php5-xsl php5-bcmath php5-soap php5-curl php5-mcrypt nano
	RUN  php5 -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" \
	&& php5 composer-setup.php --install-dir=/bin --filename=composer \
	&& php5 -r "unlink('composer-setup.php');"
	RUN composer global require hirak/prestissimo
	RUN echo "zend_extension=xdebug.so" >>  /etc/php5/conf.d/xdebug.ini \
	&& echo "xdebug.default_enable = 1" >>  /etc/php5/conf.d/xdebug.ini \
	&& echo "xdebug.remote_autostart = 0" >>  /etc/php5/conf.d/xdebug.ini \
	&& echo "xdebug.remote_enable = 1" >>  /etc/php5/conf.d/xdebug.ini \
	&& echo "xdebug.remote_connect_back = 1" >>  /etc/php5/conf.d/xdebug.ini \
	&& echo "xdebug.remote_handler = dbgp" >>  /etc/php5/conf.d/xdebug.ini \
	&& echo "xdebug.remote_port = 9000" >>  /etc/php5/conf.d/xdebug.ini
	RUN mkdir -p /opt/appconf/d_80745_sipes/config/ \
	&& echo "all:" >  /opt/appconf/d_80745_sipes/config/databases.yml \
	&& echo "  propel:" >>  /opt/appconf/d_80745_sipes/config/databases.yml \
	&& echo "    class:          sfPropelDatabase" >>  /opt/appconf/d_80745_sipes/config/databases.yml \
	&& echo "    param:" >>  /opt/appconf/d_80745_sipes/config/databases.yml \
	&& echo "      dsn:          pgsql://postgres@pgserver/sipes" >>  /opt/appconf/d_80745_sipes/config/databases.yml \
	&& echo "      username:     postgres" >>  /opt/appconf/d_80745_sipes/config/databases.yml \
	&& echo "      password:     postgres" >>  /opt/appconf/d_80745_sipes/config/databases.yml
	RUN cp -a /opt/appconf/d_80745_sipes /opt/appconf/p_80745_sipes \
	&& cp -a /opt/appconf/d_80745_sipes /opt/appconf/h_80745_sipes
	CMD [ "php5", "-S", "0.0.0.0:8080", "-t", "/srv/http/web" ]
	EOF