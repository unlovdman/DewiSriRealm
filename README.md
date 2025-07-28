# Desa Ngunut - Sistem Layanan Desa

Aplikasi web untuk layanan administrasi desa Ngunut, Kecamatan Dander, Bojonegoro.

## 🚀 Fitur Utama

- **Login Multi-Source**: Login menggunakan database atau data CSV
- **Generasi Surat Otomatis**: Surat Domisili, Keterangan, Kuasa, Pengantar
- **Admin Dashboard**: Manajemen user dan riwayat surat
- **Statistik Interaktif**: Visualisasi data penduduk
- **PDF Generation**: Export surat ke PDF
- **Responsive Design**: Kompatibel dengan semua device

## 📊 Statistik yang Tersedia

- Distribusi Jenis Kelamin
- Distribusi Agama
- Distribusi Pekerjaan
- Distribusi Status Perkawinan
- Distribusi Usia
- Distribusi RT/RW

## 🛠️ Teknologi

- **Backend**: PHP 8.1
- **Database**: MySQL 8.0
- **Frontend**: Bootstrap 5, Chart.js
- **PDF**: Dompdf
- **Icons**: Boxicons

## 🐳 Deployment dengan Docker

### Prerequisites
- Docker
- Docker Compose

### Langkah Deployment

1. **Clone Repository**
```bash
git clone <repository-url>
cd DewiSriRealm
```

2. **Build dan Run dengan Docker Compose**
```bash
docker-compose up --build
```

3. **Akses Aplikasi**
- Web: http://localhost:8080
- Database: localhost:3306

### Environment Variables

```env
DB_HOST=db
DB_NAME=village_services
DB_USER=root
DB_PASS=password
```

## 📁 Struktur File

```
DewiSriRealm/
├── asset/
│   ├── file/          # CSV files
│   └── img/           # Images
├── db/
│   └── village_services.sql
├── temp/              # Generated PDFs
├── vendor/            # Composer dependencies
├── docker/
│   └── apache.conf
├── Dockerfile
├── docker-compose.yml
└── README.md
```

## 🔧 Konfigurasi Database

1. Import database schema:
```sql
mysql -u root -p village_services < db/village_services.sql
```

2. Pastikan tabel `users` dan `letter_history` sudah dibuat.

## 👥 Login Default



### User CSV
- Username: Nama dari CSV (kolom 2)
- Password: NIK dari CSV (kolom 16)

## 📈 Statistik Data

Aplikasi membaca data dari file CSV di folder `asset/file/`:
- DAFTAR BIODATA PENDUDUK RT.001 RW.001.csv
- DAFTAR BIODATA PENDUDUK RT.002 RW.001.csv
- dst...

## 🎨 Fitur UI/UX

- **Glassmorphism Design**: Modern UI dengan efek glass
- **Interactive Charts**: Chart.js untuk visualisasi data
- **Responsive Layout**: Bootstrap 5 grid system
- **Form Validation**: Client dan server-side validation
- **Tooltips**: Bantuan untuk pengguna

## 🔒 Keamanan

- Prepared Statements untuk mencegah SQL Injection
- Input validation dan sanitization
- Session management
- File upload restrictions

## 📞 Support

Untuk bantuan teknis, hubungi admin desa atau developer. 