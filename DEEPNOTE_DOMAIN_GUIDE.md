# 🌐 Panduan Setup Domain di Deepnote - Desa Ngunut

## 📋 **Step-by-Step Domain Setup**

### **Step 1: Deploy Application**

#### **1.1 Jalankan Deploy Script**
```bash
# Di Deepnote terminal
chmod +x deploy-deepnote-with-domain.sh
./deploy-deepnote-with-domain.sh
```

#### **1.2 Verifikasi Deployment**
```bash
# Cek container status
docker-compose ps

# Cek logs
docker-compose logs web
docker-compose logs db
```

### **Step 2: Setup Port Forwarding di Deepnote**

#### **2.1 Buka Port Forwarding**
1. Di Deepnote, klik **"Ports"** di sidebar kiri
2. Klik **"Add Port"**
3. **Port**: `8080`
4. **Protocol**: `HTTP`
5. Klik **"Add"**

#### **2.2 Test Local Access**
- URL: `http://localhost:8080`
- Admin: `BONBINSURABAYA` / `benderaseleraku123`
- User: `SUKINAH` / `3522064305530004`

### **Step 3: Setup Custom Domain**

#### **3.1 Deepnote Domain (Gratis)**
1. Di Deepnote, klik **"Settings"** (gear icon)
2. Scroll ke **"Custom Domain"**
3. **Domain**: `desa-ngunut.deepnote.app` (atau sesuai keinginan)
4. Klik **"Save"**

#### **3.2 External Domain (Berbayar)**
Jika punya domain sendiri:
1. **Domain Provider**: Cloudflare, Namecheap, dll
2. **DNS Settings**:
   ```
   Type: CNAME
   Name: @
   Value: deepnote.app
   TTL: 300
   ```

### **Step 4: SSL Certificate**

#### **4.1 Deepnote SSL (Otomatis)**
- Deepnote menyediakan SSL otomatis
- URL: `https://desa-ngunut.deepnote.app`

#### **4.2 Custom SSL (Jika perlu)**
```bash
# Install certbot
sudo apt-get update
sudo apt-get install certbot

# Generate certificate
sudo certbot certonly --standalone -d yourdomain.com
```

### **Step 5: Environment Configuration**

#### **5.1 Update Database Configuration**
```bash
# Edit db.php untuk production
nano db.php
```

```php
<?php
// Production configuration
$servername = $_ENV['DB_HOST'] ?? "db";
$username = $_ENV['DB_USER'] ?? "root";
$password = $_ENV['DB_PASS'] ?? "password";
$dbname = $_ENV['DB_NAME'] ?? "village_services";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

#### **5.2 Update Docker Environment**
```yaml
# docker-compose.yml
version: '3.8'
services:
  web:
    build: .
    ports:
      - "8080:80"
    environment:
      - DB_HOST=db
      - DB_NAME=village_services
      - DB_USER=root
      - DB_PASS=password
      - DOMAIN=desa-ngunut.deepnote.app
    depends_on:
      - db
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: password
      MYSQL_DATABASE: village_services
    volumes:
      - mysql_data:/var/lib/mysql
      - ./db/village_services.sql:/docker-entrypoint-initdb.d/village_services.sql
volumes:
  mysql_data:
```

### **Step 6: Security Configuration**

#### **6.1 Update .htaccess**
```apache
# .htaccess
RewriteEngine On

# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"

# Cache control
<FilesMatch "\.(css|js|png|jpg|jpeg|gif|ico)$">
    Header set Cache-Control "max-age=31536000, public"
</FilesMatch>
```

#### **6.2 Update Apache Configuration**
```apache
# docker/apache.conf
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html
    
    # Custom domain
    ServerName desa-ngunut.deepnote.app
    ServerAlias www.desa-ngunut.deepnote.app
    
    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # Logs
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
    
    # Security
    ServerTokens Prod
    ServerSignature Off
</VirtualHost>
```

### **Step 7: Performance Optimization**

#### **7.1 Enable Apache Modules**
```dockerfile
# Dockerfile
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Enable Apache modules
RUN a2enmod rewrite \
    && a2enmod headers \
    && a2enmod ssl

# Copy custom Apache config
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
```

#### **7.2 PHP Optimization**
```ini
# php.ini (create in docker/php.ini)
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
```

### **Step 8: Monitoring & Logs**

#### **8.1 Setup Logging**
```bash
# View logs
docker-compose logs -f web
docker-compose logs -f db

# Monitor resources
docker stats
```

#### **8.2 Health Check**
```bash
# Test application
curl -I http://localhost:8080
curl -I https://desa-ngunut.deepnote.app

# Test database
docker-compose exec db mysql -u root -ppassword -e "SHOW DATABASES;"
```

### **Step 9: Backup & Recovery**

#### **9.1 Database Backup**
```bash
# Create backup script
cat > backup-db.sh << 'EOF'
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
docker-compose exec db mysqldump -u root -ppassword village_services > backup_${DATE}.sql
echo "Backup created: backup_${DATE}.sql"
EOF

chmod +x backup-db.sh
```

#### **9.2 Application Backup**
```bash
# Backup application files
tar -czf app_backup_$(date +%Y%m%d_%H%M%S).tar.gz *.php *.css asset/ db/ docker/
```

## 🎯 **Success Criteria**

✅ **Application deployed successfully**
✅ **Domain configured**
✅ **SSL certificate active**
✅ **Database connected**
✅ **All features working**
✅ **Security headers configured**
✅ **Performance optimized**
✅ **Monitoring active**

## 🚀 **Final URLs**

- **Production**: `https://desa-ngunut.deepnote.app`
- **Local**: `http://localhost:8080`
- **Admin Panel**: `https://desa-ngunut.deepnote.app/admin_dashboard.php`

## 🔧 **Troubleshooting**

### **Domain not working:**
```bash
# Check DNS
nslookup desa-ngunut.deepnote.app

# Check SSL
openssl s_client -connect desa-ngunut.deepnote.app:443
```

### **Database connection failed:**
```bash
# Check container status
docker-compose ps

# Check database logs
docker-compose logs db

# Restart containers
docker-compose restart
```

### **Performance issues:**
```bash
# Check resource usage
docker stats

# Optimize images
docker-compose build --no-cache
```

---

## 🎉 **Domain Ready!**

Setelah semua step selesai, aplikasi Anda akan accessible via:
**`https://desa-ngunut.deepnote.app`**

**Features:**
- ✅ Multi-source login system
- ✅ Letter generation (4 types)
- ✅ Interactive statistics
- ✅ Admin dashboard
- ✅ PDF generation
- ✅ Responsive design
- ✅ SSL secured
- ✅ Performance optimized 