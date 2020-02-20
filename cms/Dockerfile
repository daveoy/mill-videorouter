FROM php:apache
RUN apt-get update && apt-get install -y \
 default-mysql-client \
 python \
 python-memcache \
 memcached \
 telnet
RUN a2enmod rewrite vhost_alias headers && docker-php-ext-install pdo_mysql
COPY cms /var/www/html/cms
RUN ln -sf /var/www/html/cms/videorouter.conf /etc/apache2/sites-enabled/
ENTRYPOINT exec /var/www/html/cms/docker-entrypoint.sh
