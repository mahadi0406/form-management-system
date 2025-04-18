#!/bin/bash

# Run any artisan command inside Docker
docker compose exec app php artisan "$@"

# Fix ownership
echo "🔧 Fixing file permissions..."
sudo chown -R $USER:$USER .
