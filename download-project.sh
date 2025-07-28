#!/bin/bash

echo "📥 Downloading project files..."

# Clean workspace
echo "🧹 Cleaning workspace..."
rm -rf * .[^.]* 2>/dev/null || true

# Create project structure
echo "📁 Creating project structure..."
mkdir -p asset/file asset/img db docker temp vendor

# Download main PHP files
echo "📥 Downloading PHP files..."
wget -O login.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/login.php"
wget -O homepage.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/homepage.php"
wget -O about.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/about.php"
wget -O statistics.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/statistics.php"
wget -O admin_dashboard.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/admin_dashboard.php"
wget -O manage_users.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/manage_users.php"
wget -O profile.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/profile.php"
wget -O print_letter.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/print_letter.php"

# Download surat files
wget -O surat_domisili.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/surat_domisili.php"
wget -O surat_keterangan.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/surat_keterangan.php"
wget -O surat_kuasa.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/surat_kuasa.php"
wget -O surat_pengantar.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/surat_pengantar.php"

# Download supporting files
wget -O db.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/db.php"
wget -O search.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/search.php"
wget -O add_user.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/add_user.php"
wget -O download_pdf.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/download_pdf.php"
wget -O user_dashboard.php "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/user_dashboard.php"

# Download CSS files
wget -O header.css "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/header.css"
wget -O homepage.css "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/homepage.css"
wget -O footer.css "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/footer.css"

# Download Docker files
wget -O Dockerfile "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/Dockerfile"
wget -O docker-compose.yml "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/docker-compose.yml"
wget -O docker/apache.conf "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/docker/apache.conf"

# Download database files
wget -O db/village_services.sql "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/db/village_services.sql"

# Download dependencies
wget -O composer.json "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/composer.json"
wget -O composer.lock "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/composer.lock"

# Download documentation
wget -O README.md "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/README.md"
wget -O deploy-deepnote.md "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/deploy-deepnote.md"
wget -O DEPLOYMENT_CHECKLIST.md "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/DEPLOYMENT_CHECKLIST.md"
wget -O .gitignore "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/.gitignore"

# Download scripts
wget -O deploy.sh "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/deploy.sh"
wget -O clean-deepnote.sh "https://raw.githubusercontent.com/yourusername/DewiSriRealm/main/clean-deepnote.sh"

echo "✅ Project files downloaded successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Upload CSV files to asset/file/"
echo "2. Upload images to asset/img/"
echo "3. Run: chmod +x deploy.sh && ./deploy.sh" 