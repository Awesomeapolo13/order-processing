FROM php:8.3-fpm-alpine AS php-base

# Install the base dependencies
RUN apk add --no-cache \
    curl \
    libpq

# Install the temporary dependencies to build extensions
RUN apk add --no-cache --virtual .build-deps \
    build-base \
    zlib-dev \
    oniguruma-dev \
    autoconf \
    linux-headers \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    bcmath

# Clear temporary dependencies
RUN apk del .build-deps

## Configure non-root user
ARG PUID
ENV PUID $PUID

ARG PGID
ENV PGID $PGID

RUN addgroup -g ${PGID} -S www-data || true && \
    adduser -u ${PUID} -D -S -G www-data www-data || true

RUN mkdir -p /var/www/order-processing && \
    chown -R www-data:www-data /var/www/order-processing

WORKDIR /var/www/order-processing
USER www-data

# =============================================================================
# Development stage - для разработки
# =============================================================================
FROM php-base AS development

USER root

# Install xdebug for development
RUN apk add --no-cache --virtual .xdebug-deps autoconf gcc g++ make \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .xdebug-deps

# Copy xdebug config
COPY ./.deployment/docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# PHP config for development
COPY ./.deployment/docker/php-fpm/php-dev.ini /usr/local/etc/php/conf.d/php-dev.ini

# FPM конфигурация для разработки
COPY ./.deployment/docker/php-fpm/fmp-dev.conf /usr/local/etc/php-fpm.d/zz-custom.conf

USER www-data

# Copy the code
COPY --chown=www-data:www-data ./ /var/www/order-processing

CMD ["php-fpm"]

EXPOSE 9000

# =============================================================================
# Production stage - для продакшена
# =============================================================================
FROM php-base AS production

USER root

# Install and set up OPcache for prod
RUN docker-php-ext-install opcache

# PHP configs for prod
COPY ./.deployment/docker/php-fpm/php-prod.ini /usr/local/etc/php/conf.d/php-prod.ini
COPY ./.deployment/docker/php-fpm/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Optimise FPM for prod
COPY ./.deployment/docker/php-fpm/fpm-prod.conf /usr/local/etc/php-fpm.d/zz-custom.conf

USER www-data

# Copy only necessary files (without dev-dependencies)
COPY --chown=www-data:www-data ./public /var/www/order-processing/public
COPY --chown=www-data:www-data ./src /var/www/order-processing/src
COPY --chown=www-data:www-data ./config /var/www/order-processing/config
COPY --chown=www-data:www-data ./vendor /var/www/order-processing/vendor
COPY --chown=www-data:www-data ./composer.json /var/www/order-processing/
COPY --chown=www-data:www-data ./composer.lock /var/www/order-processing/
COPY --chown=www-data:www-data ./.env /var/www/order-processing/

CMD ["php-fpm"]

EXPOSE 9000

## Если аргумент INSTALL_XDEBUG будет равен true, то устанавливаем xdebug (теперь можно исп. этот файл и для прод. деплоя)
#ARG INSTALL_XDEBUG=false
## Подтягиваем зависимости для xdebug и включаем расширение xdebug в php.ini
#RUN if [ ${INSTALL_XDEBUG} = true ];  \
#    then \
#      pecl install xdebug && docker-php-ext-enable xdebug; \
#    fi;
#
## Пробрасываем xdebug.ini в контейнер php
#COPY ./.deployment/docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
#
## Postgres
#RUN apk add --no-cache libpq-dev \
#    && docker-php-ext-install pdo_pgsql
#
## Other libs
#RUN docker-php-ext-install bcmath
#
#
#
#RUN apk --no-cache add shadow && \
#    groupmod -o -g ${PGID} www-data && \
#    usermod -o -u ${PUID} -g www-data www-data
#
## Source project
#RUN mkdir /var/www/order-processing \
#    && chown www-data:www-data /var/www/order-processing
#COPY --chown=www-data:www-data ./ /var/www/order-processing
#WORKDIR /var/www/order-processing
#
#USER www-data
#
## Composer instalation
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#
#CMD php-fpm
#
#EXPOSE 9000
