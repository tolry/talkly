#!/bin/bash

webserver_user=www-data
webserver_group=www-data

cd `dirname $0`

echo "installing composer dependencies (php)"
composer install

echo "use the right node version (nvm)"
nvm use

echo "run setup command"
bin/console talkly:setup

echo "setting file permissions"
sudo chown -R $webserver_user:$webserver_group var/logs var/cache

rm -f web/index_dev.php
rm -f web/app_dev.php