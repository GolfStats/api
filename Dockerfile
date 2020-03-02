#
# PHP Dependencies
#
FROM composer:1.9 as vendor
COPY . .

ARG COMPOSER_ALLOW_SUPERUSER=1

RUN : \
        && composer global require hirak/prestissimo \
        && composer install \
            --ignore-platform-reqs \
            --no-interaction \
            --prefer-dist \
            --no-progress \
            --profile \
            --no-scripts \
            # Production only
            --no-dev \
            --classmap-authoritative \
        ;

#
# Nginx server
#
FROM nginx:alpine as nginx

WORKDIR /var/www/html

RUN apk --no-cache add shadow \
        && usermod -u 1000 nginx \
        && apk del shadow

COPY docker/nginx/conf /etc/nginx
COPY docker/nginx/start.sh /start.sh

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Storrage link
RUN mkdir /var/www/html/public/vendor \
        && chown -h 1000:1000 /var/www/html/public/vendor \
        && chmod +x /start.sh \
        ;

ENTRYPOINT /start.sh

#
# PHP Application
#
FROM registry.gitlab.com/nevax/docker-php-fpm-alpine-laravel as php_base

# Add configurations
COPY docker/php-fpm/custom.ini /usr/local/etc/php/conf.d/

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Importing composer and assets dependencies
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --chown=1000:1000 --from=composer /usr/bin/composer /usr/bin/composer

RUN : \
    # Check composer reqs
    && /usr/bin/composer check-platform-reqs \
    # Dummy dot env
    && touch .env \
    ;

COPY --chown=1000:1000 docker/php-fpm/start.sh /start.sh
COPY docker/php-fpm/crontab /etc/crontabs/root

USER 1000:1000

CMD /start.sh

FROM php_base AS php_app
