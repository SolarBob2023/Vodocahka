#!/bin/bash

source .env

cd run/ || exit 1

cp '_api.js' 'index.js'
sed -i "s/{app_url}/${PROJECT_URL}/" 'index.js'
sed -i "s/{app_port}/${NGINX_PORT}/" 'index.js'
mv 'index.js' './../front/src/api/'

cp '_sanctum.php' 'sanctum.php'
sed -i "s/{app_url}/${PROJECT_URL}/" 'sanctum.php'
sed -i "s/{app_port}/${NGINX_PORT}/" 'sanctum.php'
mv 'sanctum.php' './../back/config/'

cd ../
docker compose up -d