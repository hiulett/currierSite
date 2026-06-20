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

# Start the queue worker in background
echo "📬 Iniciando worker de colas de correo..."
php artisan queue:work --sleep=3 --tries=3 --max-time=3600 &

# Start the application
echo "⚡ Iniciando servidor..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
