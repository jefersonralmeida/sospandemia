FROM nginx:1.13

COPY ./conf/nginx.conf.template /etc/nginx/conf.d/default.conf.template
RUN envsubst '${SERVER_NAME}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf
