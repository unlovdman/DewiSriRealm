#!/bin/bash

echo "🚀 Starting Desa Ngunut Deployment..."

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

echo "✅ Docker and Docker Compose are installed"

# Check if required files exist
if [ ! -f "Dockerfile" ]; then
    echo "❌ Dockerfile not found"
    exit 1
fi

if [ ! -f "docker-compose.yml" ]; then
    echo "❌ docker-compose.yml not found"
    exit 1
fi

if [ ! -f "composer.json" ]; then
    echo "❌ composer.json not found"
    exit 1
fi

echo "✅ Required files found"

# Stop existing containers
echo "🛑 Stopping existing containers..."
docker-compose down

# Build the application
echo "🔨 Building Docker image..."
docker-compose build

# Start the application
echo "🚀 Starting application..."
docker-compose up -d

# Wait for containers to be ready
echo "⏳ Waiting for containers to be ready..."
sleep 10

# Check if containers are running
if docker-compose ps | grep -q "Up"; then
    echo "✅ Application is running!"
    echo ""
    echo "🌐 Access your application:"
    echo "   Web: http://localhost:8080"
    echo "   Database: localhost:3306"
    echo ""
    echo "👥 Login credentials:"
    echo "   Admin: BONBINSURABAYA / benderaseleraku123"
    echo "   User CSV: SUKINAH / 3522064305530004"
    echo ""
    echo "📊 Check application status:"
    echo "   docker-compose ps"
    echo ""
    echo "📋 View logs:"
    echo "   docker-compose logs -f"
else
    echo "❌ Application failed to start"
    echo "📋 Check logs:"
    docker-compose logs
    exit 1
fi 