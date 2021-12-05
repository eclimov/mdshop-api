FROM phpdockerio/php80-fpm:latest

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php8.0-mysql \
    && apt-get -y --no-install-recommends install php8.0-gd \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

ENV APP_ENV=prod
RUN pwd
RUN ls
RUN composer install --no-dev --optimize-autoloader \
    && bin/console cache:warmup
