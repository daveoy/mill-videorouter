#!/bin/bash
/usr/bin/memcached -u root -vv &
sleep 5
/usr/bin/python /var/www/html/backend/blackmagiclistener.py &
/usr/sbin/apache2ctl -D FOREGROUND
