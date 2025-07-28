# 🚀 Panduan Upload ke GitHub - Desa Ngunut

## 📋 **Step-by-Step Guide**

### **Step 1: Persiapan File**

#### **1.1 Check File Structure**
Pastikan semua file sudah ada:
```bash
# Di terminal, cek struktur file
ls -la
```

#### **1.2 Update .gitignore**
File `.gitignore` sudah dibuat untuk exclude file yang tidak perlu:
- `/vendor/` (akan di-install via composer)
- `/temp/*.pdf` (file PDF yang di-generate)
- File log dan temporary

### **Step 2: Setup Git Repository**

#### **2.1 Initialize Git**
```bash
# Jalankan script setup
chmod +x github-setup.sh
./github-setup.sh
```

#### **2.2 Manual Setup (jika script gagal)**
```bash
# Initialize git
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit: Desa Ngunut - Sistem Layanan Desa"
```

### **Step 3: Buat Repository di GitHub**

#### **3.1 Buka GitHub.com**
1. Login ke [GitHub.com](https://github.com)
2. Klik **"New"** atau **"+"** → **"New repository"**

#### **3.2 Konfigurasi Repository**
- **Repository name**: `DewiSriRealm`
- **Description**: `Sistem Layanan Desa Ngunut - Web application for village administration`
- **Visibility**: Public (atau Private sesuai kebutuhan)
- **Initialize**: ❌ **JANGAN** centang "Add a README file"
- **Add .gitignore**: ❌ **JANGAN** centang (sudah ada)
- **Choose a license**: MIT License (atau sesuai kebutuhan)

#### **3.3 Klik "Create repository"**

### **Step 4: Connect Local ke GitHub**

#### **4.1 Add Remote Origin**
```bash
# Ganti 'yourusername' dengan username GitHub Anda
git remote add origin https://github.com/yourusername/DewiSriRealm.git
```

#### **4.2 Set Main Branch**
```bash
git branch -M main
```

#### **4.3 Push ke GitHub**
```bash
git push -u origin main
```

### **Step 5: Verifikasi Upload**

#### **5.1 Cek di GitHub**
1. Buka repository di GitHub
2. Pastikan semua file terupload:
   - ✅ File PHP (login.php, homepage.php, dll)
   - ✅ Folder asset/ (CSV files + images)
   - ✅ Folder db/ (database schema)
   - ✅ Docker files (Dockerfile, docker-compose.yml)
   - ✅ Documentation (README.md, dll)

#### **5.2 Cek File Size**
- Pastikan file CSV tidak terlalu besar
- Jika ada file besar, gunakan Git LFS

### **Step 6: Setup GitHub Pages (Optional)**

#### **6.1 Enable GitHub Pages**
1. Buka repository settings
2. Scroll ke "Pages"
3. Source: "Deploy from a branch"
4. Branch: "main"
5. Folder: "/ (root)"
6. Klik "Save"

#### **6.2 Custom Domain (Optional)**
- Tambahkan custom domain jika ada

## 🔧 **Troubleshooting**

### **Masalah: File terlalu besar**
```bash
# Install Git LFS
git lfs install

# Track file besar
git lfs track "*.csv"
git lfs track "*.pdf"
git lfs track "asset/file/*"

# Add .gitattributes
git add .gitattributes
git commit -m "Add Git LFS tracking"
```

### **Masalah: Authentication failed**
```bash
# Setup GitHub CLI
gh auth login

# Atau gunakan Personal Access Token
git remote set-url origin https://YOUR_TOKEN@github.com/yourusername/DewiSriRealm.git
```

### **Masalah: Push rejected**
```bash
# Force push (hati-hati!)
git push -u origin main --force

# Atau pull dulu
git pull origin main --allow-unrelated-histories
```

## 📊 **Repository Structure**

Setelah upload, repository akan memiliki struktur:
```
DewiSriRealm/
├── 📁 asset/
│   ├── 📁 file/          # 23 CSV files
│   └── 📁 img/           # Images
├── 📁 db/
│   └── 📄 village_services.sql
├── 📁 docker/
│   └── 📄 apache.conf
├── 📁 temp/              # Empty folder
├── 📁 vendor/            # Composer dependencies
├── 📄 *.php              # PHP files
├── 📄 Dockerfile
├── 📄 docker-compose.yml
├── 📄 composer.json
├── 📄 README.md
└── 📄 .gitignore
```

## 🎯 **Success Criteria**

✅ **Repository created on GitHub**
✅ **All files uploaded successfully**
✅ **README.md displays correctly**
✅ **Docker files included**
✅ **Documentation complete**
✅ **No sensitive data exposed**

## 🚀 **Next Steps After GitHub Upload**

### **1. Clone di Deepnote**
```bash
# Di Deepnote terminal
git clone https://github.com/yourusername/DewiSriRealm.git .
```

### **2. Deploy di Deepnote**
```bash
chmod +x quick-deploy.sh
./quick-deploy.sh
```

### **3. Setup CI/CD (Optional)**
- GitHub Actions untuk auto-deploy
- Docker Hub untuk container registry

---

## 🎉 **Repository Ready!**

Setelah semua step selesai, repository Anda akan siap untuk:
- ✅ Clone di Deepnote
- ✅ Deploy di server lain
- ✅ Collaboration dengan tim
- ✅ Version control
- ✅ Backup otomatis

**Repository URL**: `https://github.com/yourusername/DewiSriRealm` 