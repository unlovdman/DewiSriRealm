#!/bin/bash

echo "🚀 Uploading project via Git..."

# Clean workspace first
echo "🧹 Cleaning workspace..."
rm -rf * .[^.]* 2>/dev/null || true

# Clone from GitHub (replace with your repository URL)
echo "📥 Cloning repository..."
git clone https://github.com/yourusername/DewiSriRealm.git .

# If git clone fails, use alternative method
if [ $? -ne 0 ]; then
    echo "❌ Git clone failed. Using alternative method..."
    echo "📋 Please upload files manually or use wget/curl"
    exit 1
fi

echo "✅ Repository cloned successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Run: chmod +x deploy.sh"
echo "2. Run: ./deploy.sh" 