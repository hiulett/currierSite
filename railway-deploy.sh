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

# Start the application
echo "⚡ Iniciando servidor..."
# Usamos el servidor interno de PHP para este entorno,
# pero en producción real se recomienda Nginx + PHP-FPM.
# Railway detecta el puerto automáticamente si usamos 8000.
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
