#!/bin/bash

echo "🚀 Deploying Desa Ngunut di Deepnote (Simple Mode)..."

# Check if we're in the right directory
if [ ! -f "login.php" ]; then
    echo "❌ Not in DewiSriRealm directory"
    exit 1
fi

# Install PHP and dependencies
echo "📦 Installing PHP and dependencies..."
apt-get update
apt-get install -y php php-mysql php-gd php-zip php-curl php-mbstring apache2 mysql-server

# Install Composer
echo "📦 Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Setup Apache
echo "🌐 Setting up Apache..."
a2enmod rewrite
a2enmod headers

# Create Apache virtual host
echo "🔧 Creating Apache configuration..."
cat > /etc/apache2/sites-available/desa-ngunut.conf << 'EOF'
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /work/DewiSriRealm
    
    ServerName localhost
    ServerAlias *.deepnote.app
    
    <Directory /work/DewiSriRealm>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Enable site
a2ensite desa-ngunut.conf
a2dissite 000-default.conf

# Setup MySQL
echo "🗄️ Setting up MySQL..."
service mysql start

# Create database and user
mysql -e "CREATE DATABASE IF NOT EXISTS village_services;"
mysql -e "CREATE USER IF NOT EXISTS 'village_user'@'localhost' IDENTIFIED BY 'village_pass';"
mysql -e "GRANT ALL PRIVILEGES ON village_services.* TO 'village_user'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# Import database schema
echo "📊 Importing database schema..."
mysql village_services < db/village_services.sql

# Update database configuration
echo "🔧 Updating database configuration..."
cat > db.php << 'EOF'
<?php
$servername = "localhost";
$username = "village_user";
$password = "village_pass";
$dbname = "village_services";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
EOF

# Create temp directory
echo "📁 Creating temp directory..."
mkdir -p temp
chmod 777 temp

# Create .htaccess for security
echo "🔒 Creating .htaccess for security..."
cat > .htaccess << 'EOF'
RewriteEngine On

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"

# Cache control
<FilesMatch "\.(css|js|png|jpg|jpeg|gif|ico)$">
    Header set Cache-Control "max-age=31536000, public"
</FilesMatch>

# Prevent access to sensitive files
<FilesMatch "\.(env|log|sql|bak)$">
    Order allow,deny
    Deny from all
</FilesMatch>
EOF

# Restart Apache
echo "🔄 Restarting Apache..."
service apache2 restart

# Create health check script
echo "🏥 Creating health check script..."
cat > health-check.sh << 'EOF'
#!/bin/bash
echo "🔍 Health Check - Desa Ngunut"

# Check Apache status
echo "🌐 Apache Status:"
service apache2 status

# Check MySQL status
echo "🗄️ MySQL Status:"
service mysql status

# Check PHP
echo "🐘 PHP Version:"
php -v

# Check web server
echo "🌐 Web Server Test:"
curl -I http://localhost

# Check database connection
echo "🗄️ Database Test:"
php -r "
\$conn = new mysqli('localhost', 'village_user', 'village_pass', 'village_services');
if (\$conn->connect_error) {
    echo 'Database connection failed: ' . \$conn->connect_error;
} else {
    echo 'Database connection successful';
    \$result = \$conn->query('SHOW TABLES');
    echo 'Tables: ' . \$result->num_rows;
}
"
EOF

chmod +x health-check.sh

# Create backup script
echo "💾 Creating backup script..."
cat > backup-db.sh << 'EOF'
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u village_user -pvillage_pass village_services > backup_${DATE}.sql
echo "Backup created: backup_${DATE}.sql"
EOF

chmod +x backup-db.sh

# Wait for services
echo "⏳ Waiting for services to be ready..."
sleep 10

# Show final information
echo ""
echo "🎉 Deployment completed!"
echo ""
echo "📋 Access Information:"
echo "   🌐 Local URL: http://localhost"
echo "   🗄️ Database: localhost:3306"
echo "   📊 Admin Panel: http://localhost/admin_dashboard.php"
echo ""
echo "🔑 Login Credentials:"
echo "   👤 Admin: BONBINSURABAYA / benderaseleraku123"
echo "   👤 User: SUKINAH / 3522064305530004"
echo ""
echo "📁 Project Structure:"
echo "   📄 PHP Files: $(ls *.php | wc -l) files"
echo "   🎨 CSS Files: $(ls *.css | wc -l) files"
echo "   📊 CSV Files: $(ls asset/file/*.csv | wc -l) files"
echo ""
echo "🔧 Management Commands:"
echo "   📊 Health Check: ./health-check.sh"
echo "   💾 Backup DB: ./backup-db.sh"
echo "   📋 View Logs: tail -f /var/log/apache2/error.log"
echo "   🔄 Restart Apache: service apache2 restart"
echo ""
echo "📚 Next Steps:"
echo "1. Setup port forwarding in Deepnote (Port 80)"
echo "2. Test application functionality"
echo "3. Configure custom domain if needed"
echo ""
echo "🌐 Your application is available at:"
echo "   http://localhost" 