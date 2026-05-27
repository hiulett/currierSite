FROM php:8.2-fpm

# Install system dependencies
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
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    bcmath \
    intl \
    pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Install Node dependencies and build assets
RUN npm install && npm run build

# Create necessary directories
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views

# Set permissions
RUN chown -R www-data:www-data /app \
    && chmod +x railway-deploy.sh

# Expose port
EXPOSE 8000

# Start via deployment script
CMD ["./railway-deploy.sh"]

