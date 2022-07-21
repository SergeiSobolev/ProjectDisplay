##################
# Variables
##################

DOCKER_COMPOSE = sudo docker-compose -f docker-compose.yml
DOCKER_COMPOSE_PHP_FPM_EXEC = ${DOCKER_COMPOSE} exec -u www-data php

##################
# Docker compose
##################

dc_build:
	${DOCKER_COMPOSE} build

dc_start:
	${DOCKER_COMPOSE} start

dc_stop:
	${DOCKER_COMPOSE} stop

dc_up:
	${DOCKER_COMPOSE} up -d --remove-orphans

dc_ps:
	${DOCKER_COMPOSE} ps

dc_logs:
	${DOCKER_COMPOSE} logs -f

dc_down:
	${DOCKER_COMPOSE} down -v --rmi=all --remove-orphans


##################
# App
##################

app_bash:
	${DOCKER_COMPOSE} exec -u www-data php bash


##################
# Database
##################

db_doctrine:
	${DOCKER_COMPOSE} exec -u www-data php /var/www/project/symfony/bin/console doctrine:migrations:migrate --no-interaction
db_migrate:
	${DOCKER_COMPOSE} exec -u www-data php /var/www/project/symfony/bin/console make:migration --no-interaction
db_diff:
	${DOCKER_COMPOSE} exec -u www-data php /var/www/project/symfony/bin/console doctrine:migrations:diff --no-interaction
db_drop:
	${DOCKER_COMPOSE} exec -u www-data php /var/www/project/symfony/bin/console  doctrine:database:drop --force --no-interaction
db_create:
	${DOCKER_COMPOSE} exec -u www-data php /var/www/project/symfony/bin/console  doctrine:database:create --no-interaction
db_load:
	${DOCKER_COMPOSE} exec -u www-data php /var/www/project/symfony/bin/console doctrine:fixtures:load --no-interaction