#!/bin/bash -x

VAR_HOST=$(hostname -i)

rewriteConfig.sh

service cron start

if [[ ${STAGED_ENVIRONMENT} == 'production' ]]
then
crontab -u www-data /tmp/cronjobs.txt
fi

if [[ ${STAGED_ENVIRONMENT} == 'staging' ]]
then
crontab -u www-data /tmp/staging-cronjobs.txt
fi

chown -R www-data:www-data /var/www/html/magento

apache2-foreground
tail -f /dev/null

wait
