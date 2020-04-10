@ECHO OFF

SET basepath=%~dp0

SET dc=docker-compose
SET dcp=docker-compose -f docker-compose-prod.yml

ECHO '### ENSURE THE DEV CONTAINERS ARE DOWN ###'
%dc% down

ECHO '### BRING THE DEV ENV UP ###'
%dc% up -d

ECHO '### INSTALL SPA DEPENDENCIES ###'
%dc% exec spa npm install

ECHO '### BUILD THE SPA DIST VERSION ###'
%dc% exec spa npm run build --mode=production

ECHO '### ENSURE THAT THE API BUILD FOLDER EXISTS ###'
%dc% exec api mkdir -p /var/dist/build

ECHO '### COPY FILES TO THE API BUILD FOLDER ###'
%dc% exec api rsync -av . /var/dist/build ^
--exclude vendor ^
--exclude node_modules ^
--exclude storage/* ^
--exclude tests ^
--exclude ./.* ^
--exclude phpunit.xml ^
--exclude README.md ^
--exclude server.php ^
--exclude webpack.mix.js


ECHO '### INSTALL API NPM DEPENDENCIES ###'
%dc% exec api npm --cwd=/home/dist/build install --production

ECHO '### BUILD THE API ASSETS ###'
%dc% exec api npm --cwd=/home/dist/build run prod

ECHO '### EXECUTE COMPOSER ON API ###'
%dc% exec api composer install --working-dir=/var/dist/build --no-ansi --no-dev --no-interaction --no-progress --no-suggest --optimize-autoloader

rem TODO - generate .env, run migrations, compile routes and view, generate key pair, run indexing remove artisan, remove migrations, remove routes, remove views

ECHO '### BRING THE DEV ENV DOWN ###'
rem bring the dev env down
%dc% down

ECHO '### BUILD THE PROD ENV ###'
%dcp% build

ECHO '### SET STORAGE PERMISSIONS ###'
%dcp% run p_api chown -R www-data: storage

ECHO '### CACHING THE VIEWS ###'
%dcp% run p_api php artisan view:cache









