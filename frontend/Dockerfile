FROM php:a7.4.5-pache
ARG BUILD_DATE
LABEL org.label-schema.build-date=$BUILD_DATE
LABEL org.opencontainers.image.created=$BUILD_DATE
RUN apt-get update && apt-get install -y \
 default-mysql-client \
 python \
 python-pip \
 memcached \
 telnet
RUN a2enmod rewrite vhost_alias headers && docker-php-ext-install pdo_mysql
COPY . /var/www/html/frontend
ENTRYPOINT exec /var/www/html/frontend/docker-entrypoint.sh
