#!/bin/sh
set -e

echo "🔄 Esperando que MySQL esté listo..."
until php -r "new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
  echo "   MySQL todavía no responde, reintentando en 2s..."
  sleep 2
done
echo "✅ MySQL listo."

# Si no existe vendor/, instalar dependencias
if [ ! -d "/var/www/vendor" ]; then
  echo "📦 Instalando dependencias de Composer..."
  composer install --no-interaction --prefer-dist
fi

# Si no existe node_modules/, instalar
if [ ! -d "/var/www/node_modules" ]; then
  echo "📦 Instalando dependencias de npm..."
  npm install
fi

# Generar APP_KEY si no existe
if ! grep -q "APP_KEY=base64:" /var/www/.env 2>/dev/null; then
  echo "🔑 Generando APP_KEY..."
  php artisan key:generate --force
fi

# Correr migraciones
echo "🗄️  Corriendo migraciones..."
php artisan migrate --force

# Correr seeders solo la primera vez (si existe el flag, no los corre de nuevo)
if [ ! -f "/var/www/storage/.seeded" ]; then
  echo "🌱 Corriendo seeders (primera vez)..."
  php artisan db:seed --force || echo "⚠️  Sin seeders o falló, continuando..."
  touch /var/www/storage/.seeded
fi

# Cachear config para mayor velocidad
php artisan config:cache
php artisan route:cache

echo "🚀 Arrancando php-fpm..."
exec "$@"
