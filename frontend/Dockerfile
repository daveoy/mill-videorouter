FROM php:apache
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
COPY frontend /var/www/html/frontend
RUN ln -sf /var/www/html/frontend/videorouter.conf /etc/apache2/sites-enabled/
ENTRYPOINT exec /var/www/html/frontend/docker-entrypoint.sh
