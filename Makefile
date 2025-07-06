##################
# Variables
##################

DOCKER_COMPOSE = docker compose -f ./.deployment/docker/docker-compose.yml --env-file ./.deployment/docker/.env
DOCKER_EXEC_PHP = docker exec -it order-proc-cli

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
	${DOCKER_COMPOSE} up -d

dc_up_build:
	@if ! grep -q "BUILD_TARGET=" ./.deployment/docker/.env; then \
		echo "BUILD_TARGET=development" >> ./.deployment/docker/.env; \
	fi
	${DOCKER_COMPOSE} up -d --build

dc_ps:
	${DOCKER_COMPOSE} ps

dc_logs:
	${DOCKER_COMPOSE} logs -f

dc_down:
	${DOCKER_COMPOSE} down -v --rmi=all --remove-orphans

dc_restart:
	make dc_stop dc_start

##################
# Environment switching
##################

dc_dev:
	@echo "Switching to development environment..."
	@if grep -q "BUILD_TARGET=" ./.deployment/docker/.env; then \
		sed -i 's/BUILD_TARGET=.*/BUILD_TARGET=development/' ./.deployment/docker/.env; \
	else \
		echo "BUILD_TARGET=development" >> ./.deployment/docker/.env; \
	fi
	${DOCKER_COMPOSE} up -d --build

dc_prod:
	@echo "Switching to production environment..."
	@if grep -q "BUILD_TARGET=" ./.deployment/docker/.env; then \
		sed -i 's/BUILD_TARGET=.*/BUILD_TARGET=production/' ./.deployment/docker/.env; \
	else \
		echo "BUILD_TARGET=production" >> ./.deployment/docker/.env; \
	fi
	${DOCKER_COMPOSE} up -d --build

dc_env:
	@echo "Current environment:"
	@grep "BUILD_TARGET=" ./.deployment/docker/.env 2>/dev/null || echo "BUILD_TARGET=development (default)"

##################
# App
##################

app_bash:
	${DOCKER_EXEC_PHP} bash
com_i:
	${DOCKER_EXEC_PHP} composer install
com_r:
	${DOCKER_EXEC_PHP} composer require
test:
	${DOCKER_EXEC_PHP} php bin/phpunit
unit_test:
	${DOCKER_EXEC_PHP} composer ut
func_test:
	${DOCKER_EXEC_PHP} composer ft
cache:
	${DOCKER_EXEC_PHP} php bin/console cache:clear
m_run:
	${DOCKER_EXEC_PHP} php bin/console doctrine:migration:migrate
fx_load:
	${DOCKER_EXEC_PHP} php bin/console doctrine:fixtures:load
init:
	make com_i m_run fx_load

# Static analyzers

cs_check:
	${DOCKER_EXEC_PHP} composer cs-check
cs_fix:
	${DOCKER_EXEC_PHP} composer cs-fix
stan:
	${DOCKER_EXEC_PHP} composer stan
deptrac:
	${DOCKER_EXEC_PHP} composer deptrac
