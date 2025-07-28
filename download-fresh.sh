#!/bin/bash

echo "📥 Downloading fresh copy of DewiSriRealm..."

# Remove existing directory if exists
rm -rf DewiSriRealm
mkdir DewiSriRealm
cd DewiSriRealm

# Create directories
mkdir -p asset/file asset/img db docker temp

echo "📦 Downloading PHP files..."

# Main application files
wget -O login.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/login.php
wget -O homepage.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/homepage.php
wget -O about.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/about.php
wget -O statistics.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/statistics.php
wget -O admin_dashboard.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/admin_dashboard.php
wget -O manage_users.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/manage_users.php
wget -O profile.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/profile.php
wget -O print_letter.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/print_letter.php
wget -O user_dashboard.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/user_dashboard.php

# Letter generation files
wget -O surat_domisili.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/surat_domisili.php
wget -O surat_keterangan.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/surat_keterangan.php
wget -O surat_kuasa.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/surat_kuasa.php
wget -O surat_pengantar.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/surat_pengantar.php

# Supporting files
wget -O db.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/db.php
wget -O search.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/search.php
wget -O add_user.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/add_user.php
wget -O download_pdf.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/download_pdf.php
wget -O dashboard.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/dashboard.php
wget -O new.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/new.php
wget -O footer.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/footer.php
wget -O header.php https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/header.php

# CSS files
wget -O header.css https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/header.css
wget -O homepage.css https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/homepage.css
wget -O footer.css https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/footer.css
wget -O login.css https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/login.css

# Configuration files
wget -O .htaccess https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/.htaccess
wget -O .gitignore https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/.gitignore

# Dependencies
wget -O composer.json https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/composer.json
wget -O composer.lock https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/composer.lock

# Database
wget -O db/village_services.sql https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/db/village_services.sql

# Docker files
wget -O Dockerfile https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/Dockerfile
wget -O docker-compose.yml https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/docker-compose.yml
wget -O docker/apache.conf https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/docker/apache.conf

# Documentation
wget -O README.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/README.md
wget -O deploy-deepnote.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/deploy-deepnote.md
wget -O DEPLOYMENT_CHECKLIST.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/DEPLOYMENT_CHECKLIST.md
wget -O GITHUB_GUIDE.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/GITHUB_GUIDE.md
wget -O GITHUB_CHECKLIST.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/GITHUB_CHECKLIST.md
wget -O DEEPNOTE_DOMAIN_GUIDE.md https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/DEEPNOTE_DOMAIN_GUIDE.md

# Scripts
wget -O deploy.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/deploy.sh
wget -O clean-deepnote.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/clean-deepnote.sh
wget -O upload-via-git.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/upload-via-git.sh
wget -O download-project.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/download-project.sh
wget -O quick-deploy.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/quick-deploy.sh
wget -O github-setup.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/github-setup.sh
wget -O upload-to-github.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/upload-to-github.sh
wget -O deploy-deepnote-with-domain.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/deploy-deepnote-with-domain.sh
wget -O setup-domain.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/setup-domain.sh
wget -O deploy-simple.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/deploy-simple.sh
wget -O start-php-server.sh https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/start-php-server.sh

# Download CSV files (sample files for testing)
echo "📊 Downloading sample CSV files..."
for i in {001..023}; do
    wget -O "asset/file/DAFTAR BIODATA PENDUDUK RT.${i} RW.001.csv" "https://raw.githubusercontent.com/unlovdman/DewiSriRealm/main/asset/file/DAFTAR%20BIODATA%20PENDUDUK%20RT.${i}%20RW.001.csv" 2>/dev/null || echo "CSV file RT.${i} not found, creating sample..."
done

# Create sample images
echo "🖼️ Creating sample images..."
echo "Sample image data" > asset/img/desa.jpg
echo "Sample image data" > asset/img/bojonegoro.png

# Make scripts executable
chmod +x *.sh

echo "✅ Download completed!"
echo ""
echo "📋 Files downloaded:"
echo "   📄 PHP files: $(ls *.php | wc -l)"
echo "   🎨 CSS files: $(ls *.css | wc -l)"
echo "   📊 CSV files: $(ls asset/file/*.csv 2>/dev/null | wc -l)"
echo "   🐳 Docker files: Dockerfile, docker-compose.yml"
echo "   📚 Documentation: $(ls *.md | wc -l)"
echo "   🔧 Scripts: $(ls *.sh | wc -l)"
echo ""
echo "🚀 Next steps:"
echo "1. Setup environment: ./start-php-server.sh"
echo "2. Start server: php -S localhost:8080"
echo "3. Setup port forwarding in Deepnote"
echo ""
echo "🌐 Your application will be available at:"
echo "   http://localhost:8080" 