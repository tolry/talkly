#!/bin/sh

DEVICE=`ip addr | grep "^3:" | awk '{ print $2 }' | sed -r 's/://g'`
IP=`ip addr | grep "global $DEVICE" | awk '{ print $2 }' | sed -r 's/\/24//g'`

export XDEBUG_CONFIG="idekey=PHPSTORM"; 
export PHP_IDE_CONFIG="serverName=$IP"; 

php -d xdebug.remote_enable=1 -d xdebug.remote_host=`ip route | grep default | awk '{ print $3 }'` $@
