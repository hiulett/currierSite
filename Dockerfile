# ───────────────────────────────────────────────────────────────
# STAGE 1: Compilar assets frontend (Vite)
# ───────────────────────────────────────────────────────────────
FROM node:22-slim AS node

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY . .
RUN npm run build

# ───────────────────────────────────────────────────────────────
# STAGE 2: Runtime PHP-FPM
# ───────────────────────────────────────────────────────────────
FROM php:8.2-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    tesseract-ocr \
    tesseract-ocr-spa \
    ghostscript \
    libmagickwand-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    bcmath \
    intl \
    pdo_mysql \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Instalar dependencias PHP (sin dev; scripts corren package discovery)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Código fuente + assets ya compilados
COPY . .
COPY --from=node /app/public/build /app/public/build

# Directorios de runtime y permisos (usuario no-root)
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache storage/app/public/logos \
    && chown -R www-data:www-data /app \
    && chmod +x railway-deploy.sh

USER www-data

EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -fsS http://127.0.0.1:8000/up || exit 1

CMD ["./railway-deploy.sh"]
