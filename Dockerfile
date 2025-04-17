# Base image - using PHP 8.2 which is supported by Laravel 12
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nodejs \
    npm \
    default-mysql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Laravel 12
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js 20.x (recommended for newer projects)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Install global npm packages
RUN npm install -g npm@latest typescript

# Create system user
RUN useradd -G www-data,root -u 1000 -d /home/dev dev
RUN mkdir -p /home/dev/.composer && \
    chown -R dev:dev /home/dev

# Set proper permissions for the Laravel storage directory
RUN mkdir -p /var/www/html/storage/logs && \
    mkdir -p /var/www/html/storage/framework/sessions && \
    mkdir -p /var/www/html/storage/framework/views && \
    mkdir -p /var/www/html/storage/framework/cache && \
    chown -R dev:dev /var/www/html

# Set environment for Laravel
ENV APP_ENV=local
ENV APP_DEBUG=true

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
