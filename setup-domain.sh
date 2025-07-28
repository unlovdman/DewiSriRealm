#!/bin/bash

echo "🌐 Setting up domain for Desa Ngunut..."

# Check if we're in the right directory
if [ ! -f "Dockerfile" ]; then
    echo "❌ Not in DewiSriRealm directory"
    exit 1
fi

# Get domain name
echo "📋 Please provide domain information:"
read -p "Domain name (e.g., desa-ngunut): " domain_name
domain_name=${domain_name:-desa-ngunut}
full_domain="${domain_name}.deepnote.app"

echo "🌐 Setting up domain: ${full_domain}"

# Update Apache configuration
echo "🔧 Updating Apache configuration..."
cat > docker/apache.conf << EOF
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html
    
    # Custom domain
    ServerName ${full_domain}
    ServerAlias www.${full_domain}
    
    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # Logs
    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
    
    # Security
    ServerTokens Prod
    ServerSignature Off
</VirtualHost>
EOF

# Update .htaccess for HTTPS redirect
echo "🔒 Updating .htaccess for security..."
cat > .htaccess << EOF
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

# Prevent access to sensitive files
<FilesMatch "\.(env|log|sql|bak)$">
    Order allow,deny
    Deny from all
</FilesMatch>
EOF

# Update docker-compose.yml with domain environment
echo "🐳 Updating Docker Compose configuration..."
cat > docker-compose.yml << EOF
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
      - DOMAIN=${full_domain}
    depends_on:
      - db
    volumes:
      - .:/var/www/html
      - ./temp:/var/www/html/temp
  db:
    image: mysql:8.0
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: password
      MYSQL_DATABASE: village_services
    volumes:
      - mysql_data:/var/lib/mysql
      - ./db/village_services.sql:/docker-entrypoint-initdb.d/village_services.sql
volumes:
  mysql_data:
EOF

# Update Dockerfile for better performance
echo "🔨 Updating Dockerfile for optimization..."
cat > Dockerfile << EOF
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \\
    libzip-dev \\
    zip \\
    unzip \\
    git \\
    curl \\
    libpng-dev \\
    libjpeg-dev \\
    libfreetype6-dev \\
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \\
    && docker-php-ext-install -j\$(nproc) gd \\
    && docker-php-ext-install pdo_mysql \\
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
RUN chown -R www-data:www-data /var/www/html \\
    && chmod -R 755 /var/www/html

# Enable Apache modules
RUN a2enmod rewrite \\
    && a2enmod headers \\
    && a2enmod ssl

# Copy custom Apache config
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Create PHP optimization config
RUN echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/custom.ini \\
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/custom.ini \\
    && echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/custom.ini \\
    && echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/custom.ini \\
    && echo "opcache.enable = 1" >> /usr/local/etc/php/conf.d/custom.ini \\
    && echo "opcache.memory_consumption = 128" >> /usr/local/etc/php/conf.d/custom.ini

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
EOF

# Create backup script
echo "💾 Creating backup script..."
cat > backup-db.sh << 'EOF'
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
docker-compose exec db mysqldump -u root -ppassword village_services > backup_${DATE}.sql
echo "Backup created: backup_${DATE}.sql"
EOF

chmod +x backup-db.sh

# Create health check script
echo "🏥 Creating health check script..."
cat > health-check.sh << 'EOF'
#!/bin/bash
echo "🔍 Health Check - Desa Ngunut"

# Check containers
echo "📊 Container Status:"
docker-compose ps

# Check web server
echo "🌐 Web Server Test:"
curl -I http://localhost:8080

# Check database
echo "🗄️ Database Test:"
docker-compose exec db mysql -u root -ppassword -e "SHOW DATABASES;" 2>/dev/null

# Check logs
echo "📋 Recent Logs:"
docker-compose logs --tail=10 web
EOF

chmod +x health-check.sh

# Rebuild and restart containers
echo "🚀 Rebuilding containers with domain configuration..."
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Wait for services
echo "⏳ Waiting for services to be ready..."
sleep 30

# Show final information
echo ""
echo "🎉 Domain setup completed!"
echo ""
echo "📋 Domain Information:"
echo "   🌐 Domain: ${full_domain}"
echo "   🔒 SSL: https://${full_domain}"
echo "   📊 Local: http://localhost:8080"
echo ""
echo "🔑 Login Credentials:"
echo "   👤 Admin: BONBINSURABAYA / benderaseleraku123"
echo "   👤 User: SUKINAH / 3522064305530004"
echo ""
echo "🔧 Management Commands:"
echo "   📊 Health Check: ./health-check.sh"
echo "   💾 Backup DB: ./backup-db.sh"
echo "   📋 View Logs: docker-compose logs -f web"
echo "   🔄 Restart: docker-compose restart"
echo ""
echo "📚 Next Steps:"
echo "1. Setup port forwarding in Deepnote (Port 8080)"
echo "2. Configure custom domain in Deepnote settings"
echo "3. Test application functionality"
echo "4. Monitor performance and logs"
echo ""
echo "🌐 Your application will be available at:"
echo "   https://${full_domain}" 