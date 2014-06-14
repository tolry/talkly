#!/bin/bash

webserver_user=www-data
webserver_group=www-data

cd `dirname $0`

echo "installing composer dependencies (php)"
composer install

echo "installing bower dependencies (css/js)"
cd assets && bower install && cd ..

echo "setting file permissions"
sudo chown -R $webserver_user:$webserver_group app/logs app/cache

echo now you need to create your database
echo afterwards, run php bin/doctrine orm:schema-tool:create

