#!/bin/bash

# Exit on error
set -e

echo "🚀 Iniciando despliegue en Railway..."

# Cache clearing and optimization
echo "🧹 Limpiando caché..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# Create storage symlink
echo "🔗 Creando enlaces simbólicos de storage..."
php artisan storage:link --force

# Start the queue worker in background (se reinicia si se detiene)
echo "📬 Iniciando worker de colas de correo..."
(
  while true; do
    php artisan queue:work --sleep=3 --tries=3 --max-time=3500
    echo "⚠️ Worker de colas detenido, reiniciando en 2s..."
    sleep 2
  done
) &

# Start the application
echo "⚡ Iniciando servidor..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
