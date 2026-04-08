FROM dunglas/frankenphp:1.4-php8.4-alpine

# Install system dependencies
RUN apk add --no-cache \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    bash \
    nodejs \
    npm

# Install PHP extensions using the script provided by FrankenPHP image
RUN install-php-extensions \
    pdo_mysql \
    zip \
    bcmath \
    gd \
    intl \
    opcache

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy existing application directory contents
COPY . /app

# Install PHP dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Install JS dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Enable FrankenPHP worker mode (optional, for performance)
# ENV FRANKENPHP_CONFIG="worker ./public/index.php"

EXPOSE 80
EXPOSE 443
