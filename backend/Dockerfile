FROM php:7.4.5-apache
ARG BUILD_DATE
LABEL org.label-schema.build-date=$BUILD_DATE
LABEL org.opencontainers.image.created=$BUILD_DATE
RUN apt-get update && apt-get install -y \
 zlib1g \
 zlib1g-dev \
 default-mysql-client \
 python \
 python-memcache \
 memcached \
 telnet \
 vim
RUN a2enmod rewrite vhost_alias headers && docker-php-ext-install pdo pdo_mysql mysqli sockets && pecl install memcache-4.0.5.2
COPY . /var/www/html/backend
RUN ln -sf /var/www/html/backend/memcache.ini /usr/local/etc/php/conf.d/
ENTRYPOINT exec /var/www/html/backend/docker-entrypoint.sh
