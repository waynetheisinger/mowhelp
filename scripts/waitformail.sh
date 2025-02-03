#!/bin/bash -x

MAIL_IN_QUEUE=$(/usr/sbin/exim -bpc)

until [[ ${MAIL_IN_QUEUE} == 0 ]]
do
  sleep 5
  MAIL_IN_QUEUE=$(/usr/sbin/exim -bpc)
done
