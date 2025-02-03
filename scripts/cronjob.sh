#!/bin/bash -x

VAR_HOST=$(hostname -i)

/etc/init.d/exim4 stop

sed -i "s/tmpauthpass/${SMTP_API_KEY}/g" /etc/exim4/passwd.client
sed -i "s/tmphostname/${VAR_HOST}/g" /etc/exim4/update-exim4.conf.conf

/etc/init.d/exim4 start

rewriteConfig.sh

useradd -m -g www-data -s /bin/bash cronuser
