#!/bin/bash

dc="docker-compose";
dcp="docker-compose -f docker-compose-prod.yml";

echo '### INSTALL SPA DEPENDENCIES ###'
$dc run spa npm install

echo '### BUILD THE SPA DIST VERSION ###'
$dc run spa npm run build --mode=production

echo '### ENSURE THAT THE API BUILD FOLDER EXISTS ###'
$dc run api mkdir -p /var/dist/build

echo '### COPY FILES TO THE API BUILD FOLDER ###'
$dc run api rsync -av --delete . /var/dist/build \
--exclude vendor \
--exclude node_modules \
--exclude storage/* \
--exclude tests \
--exclude phpunit.xml \
--exclude README.md \
--exclude server.php \
--exclude webpack.mix.js;

echo '### MANAGE .env FILES ###'
$dc run api rm -f /var/dist/build/.env*;
$dc run api cp ./.env.prod /var/dist/build/.env;


echo '### INSTALL API NPM DEPENDENCIES ###'
$dc run api npm install --cwd=/var/dist/build --prefix=/var/dist/build

echo '### BUILD THE API ASSETS ###'
$dc run api npm run prod --cwd=/var/dist/build --prefix=/var/dist/build

echo '### EXECUTE COMPOSER ON API ###'
$dc run api composer install --working-dir=/var/dist/build --no-ansi --no-dev --no-interaction --no-progress --no-suggest --optimize-autoloader

echo '### REMOVE TEMPORARY CONTAINERS ###'
$dc down

echo '### BUILD THE PROD ENV ###'
$dcp build

echo '### SET STORAGE PERMISSIONS ###'
$dcp run p_api chown -R www-data: storage

echo '### CACHING THE VIEWS ###'
$dcp run p_api php artisan view:cache

echo '### CACHING THE ROUTES ###'
$dcp run p_api php artisan route:cache

echo '### CACHING THE CONFIG FILES ###'
$dcp run p_api php artisan config:cache

echo '### CREATE INDEXES ###'
$dcp run p_api php artisan db:importDistricts;

echo '### CREATE INDEXES ###'
$dcp run p_api php artisan scout:import "App\Models\Demand";
$dcp run p_api php artisan scout:import "App\Models\District";

echo '### PUT THE SYSTEM ON MAINTENANCE MODE ###'
$dcp run p_api php artisan down

echo '### RUNNING MIGRATIONS ###'
$dcp run p_api php artisan migrate --force

echo '### REPLACING THE RUNNING CONTAINERS ###'
$dcp up -d

echo '### REMOVE MAINTENANCE MODE ###'
$dcp run p_api php artisan up
