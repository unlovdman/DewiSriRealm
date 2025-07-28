#!/bin/bash

echo "🚀 Uploading Desa Ngunut to GitHub..."

# Check if git is installed
if ! command -v git &> /dev/null; then
    echo "❌ Git is not installed. Please install Git first."
    exit 1
fi

# Check if we're in the right directory
if [ ! -f "Dockerfile" ]; then
    echo "❌ Not in DewiSriRealm directory"
    exit 1
fi

# Initialize git if not already initialized
if [ ! -d ".git" ]; then
    echo "📁 Initializing git repository..."
    git init
fi

# Add all files
echo "📦 Adding files to git..."
git add .

# Check if there are changes to commit
if git diff --cached --quiet; then
    echo "ℹ️ No changes to commit"
else
    # Create commit
    echo "💾 Creating commit..."
    git commit -m "Update Desa Ngunut - Sistem Layanan Desa

Features:
- Multi-source login (Database + CSV)
- Letter generation (4 types: Domisili, Keterangan, Kuasa, Pengantar)
- Interactive statistics with Chart.js
- Admin dashboard with user management
- PDF generation with Dompdf
- Responsive design with Bootstrap 5

Tech Stack:
- Backend: PHP 8.1 + Apache
- Database: MySQL 8.0
- Frontend: Bootstrap 5, Chart.js
- PDF: Dompdf
- Icons: Boxicons

Deployment:
- Docker containerization
- Docker Compose for multi-container
- Ready for Deepnote deployment"
fi

# Ask for GitHub username
echo ""
echo "📋 Please provide your GitHub information:"
read -p "GitHub Username: " github_username
read -p "Repository name (default: DewiSriRealm): " repo_name
repo_name=${repo_name:-DewiSriRealm}

# Add remote origin
echo "🔗 Adding remote origin..."
git remote remove origin 2>/dev/null || true
git remote add origin "https://github.com/$github_username/$repo_name.git"

# Set main branch
echo "🌿 Setting main branch..."
git branch -M main

# Push to GitHub
echo "📤 Pushing to GitHub..."
if git push -u origin main; then
    echo ""
    echo "🎉 Successfully uploaded to GitHub!"
    echo ""
    echo "📋 Repository Information:"
    echo "   URL: https://github.com/$github_username/$repo_name"
    echo "   Clone: git clone https://github.com/$github_username/$repo_name.git"
    echo ""
    echo "🚀 Next steps:"
    echo "1. Clone in Deepnote: git clone https://github.com/$github_username/$repo_name.git ."
    echo "2. Deploy: chmod +x quick-deploy.sh && ./quick-deploy.sh"
    echo ""
    echo "📊 Repository features:"
    echo "   ✅ Multi-source login system"
    echo "   ✅ Letter generation (4 types)"
    echo "   ✅ Interactive statistics"
    echo "   ✅ Admin dashboard"
    echo "   ✅ PDF generation"
    echo "   ✅ Responsive design"
    echo "   ✅ Docker containerization"
else
    echo "❌ Failed to push to GitHub"
    echo "📋 Possible solutions:"
    echo "1. Create repository on GitHub.com first"
    echo "2. Check your GitHub credentials"
    echo "3. Use Personal Access Token if needed"
    exit 1
fi 