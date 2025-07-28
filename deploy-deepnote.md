# 🚀 Panduan Deploy ke Deepnote

## 📋 Persiapan Sebelum Upload

### 1. **Struktur File yang Siap Upload**
Pastikan semua file sudah ada:
```
DewiSriRealm/
├── asset/
│   ├── file/          # 23 file CSV
│   └── img/           # Logo desa
├── db/
│   └── village_services.sql
├── temp/              # Folder kosong untuk PDF
├── vendor/            # Composer dependencies
├── docker/
│   └── apache.conf
├── *.php              # Semua file PHP
├── Dockerfile
├── docker-compose.yml
├── composer.json
├── composer.lock
├── README.md
└── .gitignore
```

### 2. **File yang Harus Diupload ke Deepnote**

#### **Metode 1: Upload Folder (Recommended)**
1. Buka Deepnote
2. Klik **"Files"** di sidebar kiri
3. Klik **"+"** → **"Upload folder"**
4. Pilih folder `DewiSriRealm` lengkap
5. Tunggu upload selesai

#### **Metode 2: Upload File Individual**
Jika upload folder gagal, upload file satu per satu:
1. **Upload semua file PHP** (login.php, homepage.php, dll)
2. **Upload folder asset/** (CSV files + images)
3. **Upload folder db/** (database schema)
4. **Upload folder vendor/** (dependencies)
5. **Upload Dockerfile dan docker-compose.yml**

## 🐳 Setup Docker di Deepnote

### 1. **Buat Terminal di Deepnote**
1. Klik **"Terminals"** di sidebar
2. Klik **"+"** untuk buat terminal baru
3. Terminal akan terbuka di bagian bawah

### 2. **Install Docker (jika belum ada)**
```bash
# Cek apakah Docker sudah terinstall
docker --version

# Jika belum ada, install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
```

### 3. **Build dan Run Aplikasi**
```bash
# Masuk ke direktori aplikasi
cd DewiSriRealm

# Build Docker image
docker-compose build

# Run aplikasi
docker-compose up -d

# Cek status container
docker-compose ps
```

## 🌐 Akses Aplikasi

### **Port Forwarding di Deepnote**
1. Klik **"Machine"** di sidebar
2. Pilih **"Ports"** tab
3. Tambahkan port forwarding:
   - **Port 8080** → **localhost:8080** (untuk web)
   - **Port 3306** → **localhost:3306** (untuk database)

### **URL Akses**
- **Web Application**: `http://localhost:8080`
- **Database**: `localhost:3306`

## 🔧 Konfigurasi Database

### 1. **Import Database Schema**
```bash
# Masuk ke container database
docker-compose exec db mysql -u root -p

# Password: password

# Import schema
mysql -u root -p village_services < db/village_services.sql
```

### 2. **Verifikasi Database**
```sql
USE village_services;
SHOW TABLES;
SELECT * FROM users LIMIT 5;
```

## 🧪 Testing Aplikasi

### 1. **Test Login Admin**
- URL: `http://localhost:8080/login.php`
- Username: `BONBINSURABAYA`
- Password: `benderaseleraku123`

### 2. **Test Login User CSV**
- Username: `SUKINAH` (atau nama lain dari CSV)
- Password: `3522064305530004` (atau NIK dari CSV)

### 3. **Test Fitur Utama**
- ✅ Generate Surat Domisili
- ✅ Generate Surat Keterangan
- ✅ Generate Surat Kuasa
- ✅ Generate Surat Pengantar
- ✅ Admin Dashboard
- ✅ Statistik Interaktif

## 🔍 Troubleshooting

### **Masalah Umum:**

#### 1. **Port 8080 tidak bisa diakses**
```bash
# Cek apakah container berjalan
docker-compose ps

# Restart container
docker-compose restart

# Cek logs
docker-compose logs web
```

#### 2. **Database connection error**
```bash
# Cek status database
docker-compose logs db

# Restart database
docker-compose restart db

# Test connection
docker-compose exec web php -r "include 'db.php'; echo 'DB OK';"
```

#### 3. **File permission error**
```bash
# Fix permissions
docker-compose exec web chown -R www-data:www-data /var/www/html
docker-compose exec web chmod -R 755 /var/www/html
```

#### 4. **Composer dependencies error**
```bash
# Install dependencies di dalam container
docker-compose exec web composer install
```

## 📊 Monitoring

### **Cek Logs**
```bash
# Web server logs
docker-compose logs web

# Database logs
docker-compose logs db

# Follow logs real-time
docker-compose logs -f web
```

### **Cek Resource Usage**
```bash
# Cek memory dan CPU usage
docker stats

# Cek disk usage
docker system df
```

## 🔄 Update Aplikasi

### **Untuk update kode:**
1. Upload file yang diubah ke Deepnote
2. Restart container:
```bash
docker-compose restart web
```

### **Untuk update dependencies:**
```bash
docker-compose exec web composer update
docker-compose restart web
```

## 🎯 Tips Sukses Deploy

1. **Pastikan semua file CSV ada** di folder `asset/file/`
2. **Test di local dulu** sebelum deploy ke Deepnote
3. **Backup database** sebelum update besar
4. **Monitor logs** untuk debugging
5. **Gunakan port forwarding** untuk akses dari luar

## 📞 Support

Jika ada masalah:
1. Cek logs dengan `docker-compose logs`
2. Restart container dengan `docker-compose restart`
3. Rebuild jika perlu: `docker-compose up --build`
4. Hubungi developer untuk bantuan teknis

---

**🎉 Selamat! Aplikasi Desa Ngunut sudah siap di Deepnote!** 