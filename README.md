# Проект процессинга корзины и заказа

## Стек

- Symfony 7.0;
- Doctrine orm 3.1;
- PHP-8.2.

## Деплой

1) В директориях .deployment/docker и корне проекта скопировать файл `.env.dist` как `.env`.
2) Заполнить поля в `.env` файле. Для примера можно взять данные ниже:
- docker
```dotenv
COMPOSE_PROJECT_NAME=order-processing

###> php-fpm ###
PUID=1000
PGID=1000
INSTALL_XDEBUG=true
###< php-fpm ###

###> nginx ###
PHP_UPSTREAM_CONTAINER=order-proc-fpm
PHP_UPSTREAM_PORT=9000
NGINX_HOST_HTTP_PORT=80
###< nginx ###

###> postgres ###
POSTGRES_DB_HOST=order-proc-postgres
POSTGRES_DB_NAME=order-proc-db
POSTGRES_PORT=5432
POSTGRES_USER=apps
POSTGRES_PASSWORD=apps
###< postgres ###
```

- проект
```dotenv
###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=vcdcfshjmnbgvfdcsf
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
DATABASE_URL="postgresql://apps:apps@order-proc-postgres:5432/order-proc-db?serverVersion=16&charset=utf8"
###< doctrine/doctrine-bundle ###
```

3) Ввести команды (при необходимости можно изменить `docker-compose` или `docker compose` в Makefile):

```bash
make dc_up_build
```

4) Установите зависимости после успешного запуска проекта:

```bash
make com_i
```

Далее можно произвести адаптацию проекта под свои нужды.

## Реализация основного функционала

- [ ] Реализовать функционал получение корзины
  - [ ] переделать получение на Read Model
- [ ] Реализовать функционал добавления продукта из каталога
- [ ] Реализовать функционал установки параметров корзины
- [ ] Реализовать функционал изменения количества товара в корзине
- [ ] Модифицировать docker-compose - (добавление контейнеров для cli, workers, cron и других необходимых)
- [ ] Реализовать функционал создания заказа
- [ ] Реализовать функционал получения текущего заказа
- [ ] Реализовать функционал статуса заказа