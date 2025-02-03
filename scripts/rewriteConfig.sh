#!/bin/bash -x

sed -i "s/tmpdbname/${MYSQL_BLOG_DB}/g" /var/www/html/wordpress/wp-config.php
sed -i "s/tmpdbuser/${MYSQL_BLOG_USER}/g" /var/www/html/wordpress/wp-config.php
sed -i "s/tmpdbpass/${MYSQL_BLOG_PASSWORD}/g" /var/www/html/wordpress/wp-config.php
sed -i "s/tmpdbhost/${MYSQL_HOST}/g" /var/www/html/wordpress/wp-config.php
sed -i "s/tmpdomain/${DOMAIN}/g" /var/www/html/wordpress/wp-config.php
sed -i "s/tmpdbport/${MYSQL_PORT}/g" /var/www/html/wordpress/wp-config.php

