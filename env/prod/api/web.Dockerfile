FROM nginx:1.13

COPY ./conf/nginx.conf /etc/nginx/conf.d/default.conf
COPY ./dist/build /var/www