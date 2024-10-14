#! /bin/sh

set -e

git pull origin main

php8.3 /usr/local/bin/composer install -o --no-dev

php8.3 artisan optimize

sudo chown -R www-data:www-data *
