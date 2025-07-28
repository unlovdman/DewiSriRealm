#!/bin/bash

echo "🚀 Quick Deploy - Desa Ngunut"

# Check if we're in the right directory
if [ ! -f "Dockerfile" ]; then
    echo "❌ Not in DewiSriRealm directory"
    echo "📋 Please make sure you're in the correct directory"
    exit 1
fi

# Check required files
echo "🔍 Checking required files..."

required_files=(
    "login.php"
    "homepage.php"
    "about.php"
    "statistics.php"
    "admin_dashboard.php"
    "Dockerfile"
    "docker-compose.yml"
    "composer.json"
    "db/village_services.sql"
)

missing_files=()
for file in "${required_files[@]}"; do
    if [ ! -f "$file" ]; then
        missing_files+=("$file")
    fi
done

if [ ${#missing_files[@]} -ne 0 ]; then
    echo "❌ Missing required files:"
    for file in "${missing_files[@]}"; do
        echo "   - $file"
    done
    echo ""
    echo "📋 Please upload all files first"
    exit 1
fi

echo "✅ All required files found!"

# Install Docker if not available
if ! command -v docker &> /dev/null; then
    echo "🐳 Installing Docker..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sudo sh get-docker.sh
    sudo usermod -aG docker $USER
    echo "✅ Docker installed"
fi

# Stop existing containers
echo "🛑 Stopping existing containers..."
docker-compose down 2>/dev/null || true

# Build and start
echo "🔨 Building application..."
docker-compose build

echo "🚀 Starting application..."
docker-compose up -d

# Wait for containers
echo "⏳ Waiting for containers to be ready..."
sleep 15

# Check status
if docker-compose ps | grep -q "Up"; then
    echo ""
    echo "🎉 Application deployed successfully!"
    echo ""
    echo "🌐 Access your application:"
    echo "   Web: http://localhost:8080"
    echo "   Database: localhost:3306"
    echo ""
    echo "👥 Login credentials:"
    echo "   Admin: BONBINSURABAYA / benderaseleraku123"
    echo "   User: SUKINAH / 3522064305530004"
    echo ""
    echo "📊 Check status: docker-compose ps"
    echo "📋 View logs: docker-compose logs -f"
else
    echo "❌ Deployment failed"
    echo "📋 Check logs: docker-compose logs"
    exit 1
fi 