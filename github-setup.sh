#!/bin/bash

echo "🚀 Setting up GitHub repository for Desa Ngunut..."

# Check if git is installed
if ! command -v git &> /dev/null; then
    echo "❌ Git is not installed. Please install Git first."
    exit 1
fi

echo "✅ Git is installed"

# Initialize git repository
echo "📁 Initializing git repository..."
git init

# Add all files
echo "📦 Adding files to git..."
git add .

# Create initial commit
echo "💾 Creating initial commit..."
git commit -m "Initial commit: Desa Ngunut - Sistem Layanan Desa

Features:
- Multi-source login (Database + CSV)
- Letter generation (4 types)
- Interactive statistics
- Admin dashboard
- PDF generation
- Responsive design

Tech Stack:
- PHP 8.1 + Apache
- MySQL 8.0
- Bootstrap 5
- Chart.js
- Dompdf"

echo "✅ Local repository created successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Create repository on GitHub.com"
echo "2. Run: git remote add origin https://github.com/yourusername/DewiSriRealm.git"
echo "3. Run: git branch -M main"
echo "4. Run: git push -u origin main" 