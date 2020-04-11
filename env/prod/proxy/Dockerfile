FROM nginx:1.13

COPY ./conf/nginx.conf.template /etc/nginx/conf.d/default.conf.template
ARG server_name_arg
ENV SERVER_NAME=${server_name_arg}
RUN envsubst '${SERVER_NAME}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf
