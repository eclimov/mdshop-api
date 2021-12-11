FROM phpdockerio/php80-fpm:latest

WORKDIR "/application"

COPY . /application
#COPY /docker/php-fpm/php-ini-overrides.ini /etc/php/8.0/fpm/conf.d/99-overrides.ini

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php8.0-mysql \
    && apt-get -y --no-install-recommends install php8.0-gd \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

## Using 'dummy' values to avoid errors in cases, when required env variables aren't provided
#ENV APP_ENV=prod

# Waming-up cache + Providing write permissions
# https://github.com/denderello/symfony-docker-example/issues/2
RUN composer install --no-dev --optimize-autoloader
