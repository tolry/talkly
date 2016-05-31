#!/bin/bash

webserver_user=www-data
webserver_group=www-data

cd `dirname $0`

echo "installing composer dependencies (php)"
composer install

echo "use the right node version (nvm)"
nvm use

echo "installing bower dependencies (css/js)"
npm install

echo "setting file permissions"
sudo chown -R $webserver_user:$webserver_group var/logs var/cache

rm -f web/index_dev.php
rm -f web/app_dev.php

echo "generate ssh keys"
mkdir -p var/jwt
openssl genrsa -passout pass:talkly -out var/jwt/private.pem -aes256 4096
openssl rsa -pubout -in var/jwt/private.pem -passin pass:talkly -out var/jwt/public.pem

echo now you need to create your database
echo afterwards, run php bin/console migrations:migrate