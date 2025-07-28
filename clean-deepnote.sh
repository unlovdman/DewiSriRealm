#!/bin/bash

echo "🧹 Cleaning Deepnote workspace..."

# Check if we're in the right directory
if [ ! -f "Dockerfile" ]; then
    echo "❌ Not in DewiSriRealm directory"
    exit 1
fi

# Stop any running containers
echo "🛑 Stopping Docker containers..."
docker-compose down 2>/dev/null || true

# Remove Docker images
echo "🗑️ Removing Docker images..."
docker rmi $(docker images -q dewisrirealm_web) 2>/dev/null || true
docker rmi $(docker images -q dewisrirealm_db) 2>/dev/null || true

# Clean Docker system
echo "🧹 Cleaning Docker system..."
docker system prune -f

# Remove all files except .git (if exists)
echo "🗑️ Removing all project files..."
find . -mindepth 1 -maxdepth 1 ! -name '.git' -exec rm -rf {} +

echo "✅ Workspace cleaned successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Upload your project files again"
echo "2. Run: chmod +x deploy.sh && ./deploy.sh" 