#!/bin/bash

echo "🚀 Deploying Desa Ngunut di Deepnote dengan Domain..."

# Check if we're in the right directory
if [ ! -f "Dockerfile" ]; then
    echo "❌ Not in DewiSriRealm directory"
    exit 1
fi

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "📦 Installing Docker..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sh get-docker.sh
    sudo usermod -aG docker $USER
    echo "✅ Docker installed"
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "📦 Installing Docker Compose..."
    sudo curl -L "https://github.com/docker/compose/releases/download/v2.20.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
    echo "✅ Docker Compose installed"
fi

# Stop existing containers
echo "🛑 Stopping existing containers..."
docker-compose down 2>/dev/null || true

# Build and start containers
echo "🔨 Building Docker images..."
docker-compose build

echo "🚀 Starting containers..."
docker-compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 30

# Check if containers are running
echo "📊 Checking container status..."
docker-compose ps

# Show access information
echo ""
echo "🎉 Deployment completed!"
echo ""
echo "📋 Access Information:"
echo "   🌐 Local URL: http://localhost:8080"
echo "   🗄️ Database: localhost:3306"
echo "   📊 Admin Panel: http://localhost:8080/admin_dashboard.php"
echo ""
echo "🔑 Login Credentials:"
echo "   👤 Admin: BONBINSURABAYA / benderaseleraku123"
echo "   👤 User: SUKINAH / 3522064305530004"
echo ""
echo "📁 Project Structure:"
echo "   📄 PHP Files: $(ls *.php | wc -l) files"
echo "   🎨 CSS Files: $(ls *.css | wc -l) files"
echo "   📊 CSV Files: $(ls asset/file/*.csv | wc -l) files"
echo "   🐳 Docker Files: Dockerfile, docker-compose.yml"
echo ""
echo "🔧 Next Steps:"
echo "1. Setup domain in Deepnote"
echo "2. Configure port forwarding"
echo "3. Test application functionality"
echo ""
echo "📚 Documentation:"
echo "   📖 README.md - Project overview"
echo "   🚀 deploy-deepnote.md - Detailed deployment guide"
echo "   ✅ DEPLOYMENT_CHECKLIST.md - Verification checklist" 