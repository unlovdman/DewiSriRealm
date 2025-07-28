#!/bin/bash

echo "📥 Downloading DewiSriRealm manually..."

# Create directory
mkdir -p DewiSriRealm
cd DewiSriRealm

# Download main files
echo "📦 Downloading PHP files..."
wget -O login.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/login.php
wget -O homepage.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/homepage.php
wget -O about.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/about.php
wget -O statistics.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/statistics.php
wget -O admin_dashboard.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/admin_dashboard.php
wget -O manage_users.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/manage_users.php
wget -O profile.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/profile.php
wget -O print_letter.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/print_letter.php
wget -O user_dashboard.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/user_dashboard.php

# Download letter generation files
echo "📝 Downloading letter generation files..."
wget -O surat_domisili.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/surat_domisili.php
wget -O surat_keterangan.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/surat_keterangan.php
wget -O surat_kuasa.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/surat_kuasa.php
wget -O surat_pengantar.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/surat_pengantar.php

# Download supporting files
echo "🔧 Downloading supporting files..."
wget -O db.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/db.php
wget -O search.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/search.php
wget -O add_user.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/add_user.php
wget -O download_pdf.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/download_pdf.php
wget -O dashboard.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/dashboard.php
wget -O new.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/new.php
wget -O footer.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/footer.php

# Download CSS files
echo "🎨 Downloading CSS files..."
wget -O header.css https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/header.css
wget -O homepage.css https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/homepage.css
wget -O footer.css https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/footer.css
wget -O login.css https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/login.css

# Download Docker files
echo "🐳 Downloading Docker files..."
wget -O Dockerfile https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/Dockerfile
wget -O docker-compose.yml https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/docker-compose.yml

# Create directories
mkdir -p db docker temp asset/file asset/img

# Download database schema
echo "🗄️ Downloading database schema..."
wget -O db/village_services.sql https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/db/village_services.sql

# Download Apache config
echo "🌐 Downloading Apache config..."
wget -O docker/apache.conf https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/docker/apache.conf

# Download dependencies
echo "📦 Downloading dependencies..."
wget -O composer.json https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/composer.json
wget -O composer.lock https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/composer.lock

# Download documentation
echo "📚 Downloading documentation..."
wget -O README.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/README.md
wget -O deploy-deepnote.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/deploy-deepnote.md
wget -O DEPLOYMENT_CHECKLIST.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/DEPLOYMENT_CHECKLIST.md
wget -O GITHUB_GUIDE.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/GITHUB_GUIDE.md
wget -O GITHUB_CHECKLIST.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/GITHUB_CHECKLIST.md

# Download scripts
echo "🔧 Downloading scripts..."
wget -O deploy.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/deploy.sh
wget -O clean-deepnote.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/clean-deepnote.sh
wget -O upload-via-git.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/upload-via-git.sh
wget -O download-project.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/download-project.sh
wget -O quick-deploy.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/quick-deploy.sh
wget -O github-setup.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/github-setup.sh
wget -O upload-to-github.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/upload-to-github.sh

# Download other files
echo "📄 Downloading other files..."
wget -O .gitignore https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/.gitignore
wget -O .htaccess https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/.htaccess

# Make scripts executable
echo "🔧 Making scripts executable..."
chmod +x *.sh

echo "✅ Download completed!"
echo ""
echo "📋 Next steps:"
echo "1. Install dependencies: composer install"
echo "2. Deploy: ./quick-deploy.sh"
echo ""
echo "📊 Files downloaded:"
echo "   ✅ PHP files (20+)"
echo "   ✅ CSS files (4)"
echo "   ✅ Docker files"
echo "   ✅ Database schema"
echo "   ✅ Documentation"
echo "   ✅ Scripts" 