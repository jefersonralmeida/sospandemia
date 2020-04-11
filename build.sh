#!/bin/bash

dc="docker-compose";
dcp="docker-compose -f docker-compose-prod.yml";

echo '### INSTALL SPA DEPENDENCIES ###'
$dc run -rm spa npm install

echo '### BUILD THE SPA DIST VERSION ###'
$dc run -rm spa npm run build --mode=production

echo '### ENSURE THAT THE API BUILD FOLDER EXISTS ###'
$dc run -rm api mkdir -p /var/dist/build

echo '### COPY FILES TO THE API BUILD FOLDER ###'
$dc run -rm api rsync -av --delete . /var/dist/build \
--exclude vendor \
--exclude node_modules \
--exclude storage/* \
--exclude tests \
--exclude phpunit.xml \
--exclude README.md \
--exclude server.php \
--exclude webpack.mix.js;

echo '### MANAGE .env FILES ###'
$dc run -rm api rm -f /var/dist/build/.env*;
$dc run -rm api cp ./.env.prod /var/dist/build/.env;


echo '### INSTALL API NPM DEPENDENCIES ###'
$dc run -rm api npm install --cwd=/var/dist/build --prefix=/var/dist/build

echo '### BUILD THE API ASSETS ###'
$dc run -rm api npm run prod --cwd=/var/dist/build --prefix=/var/dist/build

echo '### EXECUTE COMPOSER ON API ###'
$dc run -rm api composer install --working-dir=/var/dist/build --no-ansi --no-dev --no-interaction --no-progress --no-suggest --optimize-autoloader

echo '### REMOVE TEMPORARY CONTAINERS ###'
$dc down

echo '### BUILD THE PROD ENV ###'
$dcp build

echo '### SET STORAGE PERMISSIONS ###'
$dcp run -rm p_api chown -R www-data: storage

echo '### CACHING THE VIEWS ###'
$dcp run -rm p_api php artisan view:cache

echo '### CACHING THE ROUTES ###'
$dcp run -rm p_api php artisan route:cache

echo '### CACHING THE CONFIG FILES ###'
$dcp run -rm p_api php artisan config:cache

echo '### IMPORTING THE DISTRICTS DATABASE ###'
$dcp run -rm p_api php artisan db:importDistricts;

echo '### CREATE INDEXES ###'
$dcp run -rm p_api php artisan scout:import "App\Models\Demand";
$dcp run -rm p_api php artisan scout:import "App\Models\District";

echo '### PUT THE SYSTEM ON MAINTENANCE MODE ###'
$dcp run -rm p_api php artisan down

echo '### RUNNING MIGRATIONS ###'
$dcp run -rm p_api php artisan migrate --force

echo '### REPLACING THE RUNNING CONTAINERS ###'
$dcp up -d

echo '### REMOVE MAINTENANCE MODE ###'
$dcp exec p_api php artisan up
