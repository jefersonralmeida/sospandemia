#!/bin/bash

dc="docker-compose";
dcp="docker-compose -f docker-compose-prod.yml";

echo
echo '### INSTALL SPA DEPENDENCIES ###'
$dc run --rm spa npm install

echo
echo '### BUILD THE SPA DIST VERSION ###'
$dc run --rm spa npm run build --mode=production

echo
echo '### ENSURE THAT THE API BUILD FOLDER EXISTS ###'
$dc run --rm api mkdir -p /var/dist/build

echo
echo '### COPY FILES TO THE API BUILD FOLDER ###'
$dc run --rm api rsync -av --delete . /var/dist/build \
--exclude vendor \
--exclude node_modules \
--exclude storage/* \
--exclude tests \
--exclude phpunit.xml \
--exclude README.md \
--exclude server.php \
--exclude webpack.mix.js;

echo
echo '### MANAGE .env FILES ###'
$dc run --rm api rm -f /var/dist/build/.env*;
$dc run --rm api cp ./.env.prod /var/dist/build/.env;

echo
echo '### INSTALL API NPM DEPENDENCIES ###'
$dc run --rm api npm install --cwd=/var/dist/build --prefix=/var/dist/build

echo
echo '### BUILD THE API ASSETS ###'
$dc run --rm api npm run prod --cwd=/var/dist/build --prefix=/var/dist/build

echo
echo '### EXECUTE COMPOSER ON API ###'
$dc run --rm api composer install --working-dir=/var/dist/build --no-ansi --no-dev --no-interaction --no-progress --no-suggest --optimize-autoloader

echo
echo '### REMOVE TEMPORARY CONTAINERS ###'
$dc down

echo
echo '### BUILD THE PROD ENV ###'
$dcp build

echo
echo '### CREATE STORAGE DIR STRUCTURE ###'
$dcp run --rm p_api mkdir -p storage/app/public
$dcp run --rm p_api mkdir -p storage/framework/cache/data
$dcp run --rm p_api mkdir -p storage/framework/sessions
$dcp run --rm p_api mkdir -p storage/framework/views
$dcp run --rm p_api mkdir -p storage/indexes
$dcp run --rm p_api mkdir -p storage/logs

echo
echo '### SET STORAGE PERMISSIONS ###'
$dcp run --rm p_api chown -R www-data: storage

echo
echo '### CACHING THE VIEWS ###'
$dcp run --rm p_api php artisan view:cache

echo
echo '### CACHING THE ROUTES ###'
$dcp run --rm p_api php artisan route:cache

echo
echo '### CACHING THE CONFIG FILES ###'
$dcp run --rm p_api php artisan config:cache

echo
echo '### ENSURING PASSPORT KEYS ###'
$dcp run --rm p_api php artisan passport:keys --quiet

echo
echo '### RUNNING MIGRATIONS ###'
$dcp run --rm p_api php artisan migrate --force

echo
echo '### IMPORTING THE DISTRICTS DATABASE ###'
$dcp run --rm p_api php artisan db:importDistricts;

echo
echo '### CREATE INDEXES ###'
$dcp run --rm p_api php artisan scout:import "App\Models\Demand";
$dcp run --rm p_api php artisan scout:import "App\Models\District";

echo
echo '### PUT THE SYSTEM ON MAINTENANCE MODE ###'
$dcp run --rm p_api php artisan down

echo
echo '### REPLACING THE RUNNING CONTAINERS ###'
$dcp up -d

echo
echo '### REMOVE MAINTENANCE MODE ###'
$dcp exec p_api php artisan up
