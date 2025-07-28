#!/bin/bash

echo "🚀 Starting Desa Ngunut with PHP Built-in Server..."

# Check if we're in the right directory
if [ ! -f "login.php" ]; then
    echo "❌ Not in DewiSriRealm directory"
    exit 1
fi

# Install PHP and dependencies
echo "📦 Installing PHP and dependencies..."
apt-get update
apt-get install -y php php-mysql php-gd php-zip php-curl php-mbstring mysql-server

# Install Composer
echo "📦 Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

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

# Create health check script
echo "🏥 Creating health check script..."
cat > health-check.sh << 'EOF'
#!/bin/bash
echo "🔍 Health Check - Desa Ngunut"

# Check MySQL status
echo "🗄️ MySQL Status:"
service mysql status

# Check PHP
echo "🐘 PHP Version:"
php -v

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

# Check if server is running
echo "🌐 Server Status:"
curl -I http://localhost:8080 2>/dev/null || echo "Server not running"
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

# Show final information
echo ""
echo "🎉 Setup completed!"
echo ""
echo "📋 Access Information:"
echo "   🌐 Local URL: http://localhost:8080"
echo "   🗄️ Database: localhost:3306"
echo "   📊 Admin Panel: http://localhost:8080/admin_dashboard.php"
echo ""
echo "🔑 Login Credentials:"
echo "   👤 Admin: BONBINSURABAYA / benderaseleraku123"
echo "   👤 User: SUKINAH / 3522064305530004"
echo ""
echo "🔧 Management Commands:"
echo "   📊 Health Check: ./health-check.sh"
echo "   💾 Backup DB: ./backup-db.sh"
echo "   🚀 Start Server: php -S localhost:8080"
echo "   📋 View Logs: tail -f /var/log/mysql/error.log"
echo ""
echo "📚 Next Steps:"
echo "1. Start PHP server: php -S localhost:8080"
echo "2. Setup port forwarding in Deepnote (Port 8080)"
echo "3. Test application functionality"
echo ""
echo "🌐 Your application will be available at:"
echo "   http://localhost:8080"
echo ""
echo "🚀 To start the server, run:"
echo "   php -S localhost:8080" 