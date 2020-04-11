#!/bin/bash

if ! [ -x "$(command -v docker-compose)" ]; then
  echo 'Error: docker-compose is not installed.' >&2
  exit 1
fi

domain=$1
rsa_key_size=4096
data_path="./env/prod/proxy/certbot/data";
email="jeferson@webage.solutions";
dcp="docker-compose -f docker-compose-prod.yml";
staging=0;

while [ $# -ne 0 ]
do
    arg="$1"
    case "$arg" in
        --staging)
            staging=1
            ;;
    esac
    shift
done

if [ -z "$domain" ]; then
  echo "domain parameter is required";
  exit 1;
fi

if [ -d "$data_path/live/$domain" ]; then
  read -p "Existing data found for $domain. Continue and replace existing certificate? (y/N) " decision
  if [ "$decision" != "Y" ] && [ "$decision" != "y" ]; then
    exit 2;
  fi
fi


if [ ! -e "$data_path/conf/options-ssl-nginx.conf" ] || [ ! -e "$data_path/conf/ssl-dhparams.pem" ]; then
  echo "### Downloading recommended TLS parameters ..."
  mkdir -p "$data_path/conf"
  curl -s https://raw.githubusercontent.com/certbot/certbot/master/certbot-nginx/certbot_nginx/_internal/tls_configs/options-ssl-nginx.conf > "$data_path/conf/options-ssl-nginx.conf"
  curl -s https://raw.githubusercontent.com/certbot/certbot/master/certbot/certbot/ssl-dhparams.pem > "$data_path/conf/ssl-dhparams.pem"
  echo
fi

echo "### Creating dummy certificate for $domain ..."
path="/etc/letsencrypt/live/$domain"
mkdir -p "$data_path/conf/live/$domain"
$dcp run --rm --entrypoint "openssl req -x509 -nodes -newkey rsa:1024 -days 1 -keyout '$path/privkey.pem' -out '$path/fullchain.pem' -subj '/CN=localhost'" certbot
echo

echo "### Starting nginx ..."
$dcp up --force-recreate -d p_proxy
echo

echo "### Deleting dummy certificate for $domain ..."
$dcp run --rm --entrypoint "rm -Rf /etc/letsencrypt/live/$domain && rm -Rf /etc/letsencrypt/archive/$domain && rm -Rf /etc/letsencrypt/renewal/$domain.conf" certbot
echo


echo "### Requesting Let's Encrypt certificate for $domain ..."

# Select appropriate email arg
case "$email" in
  "") email_arg="--register-unsafely-without-email" ;;
  *) email_arg="--email $email" ;;
esac

# Enable staging mode if needed
if [ $staging != "0" ]; then staging_arg="--staging"; fi

$dcp run --rm --entrypoint "certbot certonly --webroot -w /var/www/certbot $staging_arg $email_arg -d $domain --rsa-key-size $rsa_key_size --agree-tos --force-renewal" certbot
echo

echo "### Reloading nginx ..."
$dcp exec p_proxy nginx -s reload