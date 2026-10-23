#!/bin/sh
set -e

# Render binds on a dynamic port defined in $PORT
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

# Setup SQLite database for sessions & cache
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi
chown -R www-data:www-data /var/www/html/database
chmod -R 777 /var/www/html/database

# Check if FIREBASE_CREDENTIALS_JSON or FIREBASE_CREDENTIALS_BASE64 is set
if [ -n "$FIREBASE_CREDENTIALS_BASE64" ]; then
    mkdir -p /var/www/html/storage/app/firebase
    echo "$FIREBASE_CREDENTIALS_BASE64" | base64 -d > /var/www/html/storage/app/firebase/credentials.json
    export FIREBASE_CREDENTIALS="/var/www/html/storage/app/firebase/credentials.json"
elif [ -n "$FIREBASE_CREDENTIALS_JSON" ]; then
    mkdir -p /var/www/html/storage/app/firebase
    echo "$FIREBASE_CREDENTIALS_JSON" > /var/www/html/storage/app/firebase/credentials.json
    export FIREBASE_CREDENTIALS="/var/www/html/storage/app/firebase/credentials.json"
fi

if [ -f "$FIREBASE_CREDENTIALS" ]; then
    export GOOGLE_APPLICATION_CREDENTIALS="$FIREBASE_CREDENTIALS"
fi

# Run migrations (for session table)
php artisan migrate --force || true

# Clear/optimize cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

exec apache2-foreground
