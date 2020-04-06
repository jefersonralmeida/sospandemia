@ECHO OFF

SET basepath=%~dp0

rem bring the dev env up
docker-compose up -d

rem install spa dependencies
docker-compose exec spa npm install

rem build the spa
docker-compose exec spa npm run build

rem create the API build folder
docker-compose exec api rm -rf ./dist/build
docker-compose exec api mkdir -p ./dist/build

rem install API npm dependencies
docker-compose exec api npm install

rem build the api npm
docker-compose exec api npm run prod

rem execute composer on the api
docker-compose exec api composer install --no-ansi --no-dev --no-interaction --no-progress --no-suggest --optimize-autoloader

rem copy the files to the dist folder
docker-compose exec api cp -r ./vendor ./dist/build
docker-compose exec api cp -r ./node-modules ./dist/build
docker-compose exec api cp -r ./app ./dist/build
docker-compose exec api cp -r ./bootstrap ./dist/build
docker-compose exec api cp -r ./config ./dist/build
docker-compose exec api mkdir ./dist/build/database
docker-compose exec api cp -r ./database/migrations ./dist/build/database
docker-compose exec api cp -r ./public ./dist/build
docker-compose exec api mkdir ./dist/buid/resources
docker-compose exec api cp -r ./resources/views ./dist/build/resources/views
docker-compose exec api cp -r ./routes ./dist/build
docker-compose exec api mkdir -p ./dist/build/storage/app/public
docker-compose exec api mkdir -p ./dist/build/storage/framework/sessions
docker-compose exec api mkdir -p ./dist/build/storage/framework/views
docker-compose exec api mkdir -p ./dist/build/storage/indexes
docker-compose exec api mkdir -p ./dist/build/storage/logs
docker-compose exec api cp ./artisan ./dist/build

rem TODO - generate .env, run migrations, compile routes and view, generate key pair, run indexing remove artisan, remove migrations, remove routes, remove views





rem bring the dev env down
docker-compose down
