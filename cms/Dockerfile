FROM php:7.4.5-apache
ARG BUILD_DATE
LABEL org.label-schema.build-date=$BUILD_DATE
LABEL org.opencontainers.image.created=$BUILD_DATE
RUN apt-get update && apt-get install -y \
 default-mysql-client \
 python \
 python-memcache \
 memcached \
 telnet
RUN a2enmod rewrite vhost_alias headers && docker-php-ext-install pdo_mysql
COPY . /var/www/html/backend
COPY . /var/www/html/cms
ENTRYPOINT exec /var/www/html/cms/docker-entrypoint.sh
