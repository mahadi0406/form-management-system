#!/bin/bash

set -e

echo "🚀 Starting Laravel + Vue Docker setup..."

# Step 1: Copy .env if not exists
if [ ! -f .env ]; then
    echo "📄 Copying .env file..."
    cp .env.example .env
fi

# Step 2: Start Docker containers
echo "🐳 Building Docker containers..."
docker compose up -d --build


# Step 2.1: Mark safe Git directory (if needed)
echo "🛡️ Marking /var/www/html as safe for Git..."
docker exec -it form_management_app git config --global --add safe.directory /var/www/html

# Step 3: Install Composer dependencies
echo "📦 Installing PHP dependencies..."
docker exec -it form_management_app composer install

# Step 4: Set Laravel key
echo "🔐 Generating app key..."
docker exec -it form_management_app php artisan key:generate

# Step 5: Run database migrations
echo "🧬 Running migrations..."
docker exec -it form_management_app php artisan migrate

# Step 6: Set permissions
echo "🔐 Setting storage permissions..."
docker exec -it form_management_app chmod -R 775 storage bootstrap/cache
docker exec -it form_management_app chown -R www-data:www-data storage bootstrap/cache

# Step 7: Install NPM dependencies & build assets
echo "🎨 Installing frontend dependencies..."
docker exec -it form_management_app npm install

echo "🛠️ Building Vue app..."
docker exec -it form_management_app npm run build

echo "✅ Setup complete. App running at: http://localhost:8000"
