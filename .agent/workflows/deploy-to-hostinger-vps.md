---
description: Deploy Laravel Daily Log App to Hostinger VPS
---

# Deploy Laravel Daily Log App to Hostinger VPS

Panduan lengkap untuk deploy aplikasi Daily Log ke VPS Hostinger yang baru.

## Prerequisites

Sebelum mulai, pastikan Anda sudah:
- ✅ Memiliki VPS Hostinger yang aktif
- ✅ Memiliki akses SSH ke VPS
- ✅ Memiliki domain (opsional, bisa pakai IP)
- ✅ Repository GitHub sudah up-to-date

## Step 1: Persiapan Informasi VPS

Kumpulkan informasi berikut dari Hostinger:
- IP Address VPS
- SSH Username (biasanya `root` atau `u123456789`)
- SSH Password atau SSH Key
- Domain (jika ada)

## Step 2: Koneksi ke VPS

```bash
ssh username@your-vps-ip
# Contoh: ssh root@123.456.789.012
```

Masukkan password ketika diminta.

## Step 3: Update System & Install Dependencies

```bash
# Update package list
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd unzip git curl

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install Node.js & npm (via NodeSource)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

## Step 4: Setup MySQL Database

```bash
# Login ke MySQL
sudo mysql

# Di dalam MySQL prompt, jalankan:
CREATE DATABASE dailylog_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'dailylog_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON dailylog_db.* TO 'dailylog_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Catat credentials database:**
- Database: `dailylog_db`
- Username: `dailylog_user`
- Password: `your_strong_password`

## Step 5: Clone Repository dari GitHub

```bash
# Buat directory untuk aplikasi
sudo mkdir -p /var/www/dailylog
cd /var/www/dailylog

# Clone repository (ganti dengan URL repo Anda)
sudo git clone https://github.com/YOUR_USERNAME/dailylog.git .

# Set ownership ke user www-data
sudo chown -R www-data:www-data /var/www/dailylog
sudo chmod -R 755 /var/www/dailylog
```

## Step 6: Setup Environment File

```bash
# Copy .env.example ke .env
sudo cp .env.example .env

# Edit .env file
sudo nano .env
```

**Update nilai berikut di .env:**

```env
APP_NAME="Daily Log"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://your-domain-or-ip

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dailylog_db
DB_USERNAME=dailylog_user
DB_PASSWORD=your_strong_password

SESSION_DRIVER=database
SESSION_LIFETIME=120

LOG_CHANNEL=daily
```

Simpan dengan `Ctrl+O`, Enter, lalu `Ctrl+X`.

## Step 7: Install Dependencies & Build Assets

```bash
# Install PHP dependencies
cd /var/www/dailylog
sudo -u www-data composer install --optimize-autoloader --no-dev

# Install npm dependencies
sudo npm install

# Build assets
sudo npm run build

# Generate application key
sudo php artisan key:generate

# Create storage link
sudo php artisan storage:link
```

## Step 8: Run Database Migrations

```bash
# Run migrations
sudo php artisan migrate --force

# (Optional) Seed database jika ada data awal
# sudo php artisan db:seed --force
```

## Step 9: Set Permissions

```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/dailylog
sudo chmod -R 755 /var/www/dailylog
sudo chmod -R 775 /var/www/dailylog/storage
sudo chmod -R 775 /var/www/dailylog/bootstrap/cache
```

## Step 10: Optimize Application

```bash
# Cache configuration
sudo php artisan config:cache

# Cache routes
sudo php artisan route:cache

# Cache views
sudo php artisan view:cache

# Optimize autoloader
sudo composer dump-autoload --optimize
```

## Step 11: Configure Nginx

```bash
# Create Nginx configuration
sudo nano /etc/nginx/sites-available/dailylog
```

**Paste konfigurasi berikut:**

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;  # Ganti dengan domain atau IP Anda
    root /var/www/dailylog/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Simpan dengan `Ctrl+O`, Enter, lalu `Ctrl+X`.

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/dailylog /etc/nginx/sites-enabled/

# Remove default site (optional)
sudo rm /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx

# Enable Nginx to start on boot
sudo systemctl enable nginx
```

## Step 12: Configure PHP-FPM

```bash
# Edit PHP-FPM pool configuration
sudo nano /etc/php/8.2/fpm/pool.d/www.conf
```

Pastikan baris berikut ada dan tidak di-comment:
```
user = www-data
group = www-data
listen = /var/run/php/php8.2-fpm.sock
listen.owner = www-data
listen.group = www-data
```

```bash
# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Enable PHP-FPM to start on boot
sudo systemctl enable php8.2-fpm
```

## Step 13: Configure Firewall (UFW)

```bash
# Allow SSH, HTTP, and HTTPS
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

## Step 14: Test Application

Buka browser dan akses:
- `http://your-vps-ip` atau
- `http://your-domain.com`

Anda seharusnya melihat halaman login Daily Log.

## Step 15: Create Admin User (Jika Belum Ada)

```bash
# Masuk ke Tinker
cd /var/www/dailylog
sudo php artisan tinker

# Di dalam Tinker, jalankan:
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@dailylog.com';
$user->password = bcrypt('password123');
$user->role = 'admin';
$user->save();
exit
```

## Step 16: Setup SSL dengan Let's Encrypt (Opsional tapi Recommended)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

Setelah SSL terinstall, update `.env`:
```bash
sudo nano /var/www/dailylog/.env
```

Ubah:
```env
APP_URL=https://your-domain.com
SESSION_SECURE_COOKIE=true
```

Lalu cache ulang config:
```bash
sudo php artisan config:cache
```

## 🔄 Update Aplikasi (Setelah Push ke GitHub)

Ketika Anda melakukan perubahan dan push ke GitHub:

```bash
# SSH ke VPS
ssh username@your-vps-ip

# Navigate to project
cd /var/www/dailylog

# Pull latest changes
sudo git pull origin main  # atau branch yang Anda gunakan

# Install/update dependencies jika ada perubahan
sudo -u www-data composer install --optimize-autoloader --no-dev
sudo npm install
sudo npm run build

# Run migrations jika ada perubahan database
sudo php artisan migrate --force

# Clear and cache
sudo php artisan config:clear
sudo php artisan cache:clear
sudo php artisan view:clear
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data /var/www/dailylog
sudo chmod -R 775 /var/www/dailylog/storage
sudo chmod -R 775 /var/www/dailylog/bootstrap/cache
```

## 🆘 Troubleshooting

### 1. 500 Internal Server Error
```bash
# Check Laravel logs
sudo tail -f /var/www/dailylog/storage/logs/laravel.log

# Check Nginx error logs
sudo tail -f /var/nginx/error.log

# Check PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log
```

### 2. Permission Issues
```bash
sudo chown -R www-data:www-data /var/www/dailylog
sudo chmod -R 755 /var/www/dailylog
sudo chmod -R 775 /var/www/dailylog/storage
sudo chmod -R 775 /var/www/dailylog/bootstrap/cache
```

### 3. Database Connection Error
```bash
# Test database connection
sudo mysql -u dailylog_user -p dailylog_db

# Check .env credentials
sudo cat /var/www/dailylog/.env | grep DB_
```

### 4. Assets Not Loading
```bash
cd /var/www/dailylog
sudo npm run build
sudo php artisan storage:link
```

### 5. Clear All Caches
```bash
sudo php artisan config:clear
sudo php artisan cache:clear
sudo php artisan route:clear
sudo php artisan view:clear
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

## 📊 Monitoring & Maintenance

### Check Service Status
```bash
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql
```

### Monitor Logs
```bash
# Laravel logs
sudo tail -f /var/www/dailylog/storage/logs/laravel.log

# Nginx access logs
sudo tail -f /var/log/nginx/access.log

# Nginx error logs
sudo tail -f /var/log/nginx/error.log
```

### Database Backup
```bash
# Create backup
sudo mysqldump -u dailylog_user -p dailylog_db > backup_$(date +%Y%m%d).sql

# Restore from backup
sudo mysql -u dailylog_user -p dailylog_db < backup_20250105.sql
```

## ✅ Checklist Deployment

- [ ] VPS sudah aktif dan bisa diakses via SSH
- [ ] System dependencies terinstall (Nginx, MySQL, PHP 8.2)
- [ ] Database dibuat dan user dikonfigurasi
- [ ] Repository di-clone dari GitHub
- [ ] File .env dikonfigurasi dengan benar
- [ ] Dependencies terinstall (composer & npm)
- [ ] Assets di-build (npm run build)
- [ ] Migrations dijalankan
- [ ] Permissions diset dengan benar
- [ ] Nginx dikonfigurasi dan running
- [ ] PHP-FPM dikonfigurasi dan running
- [ ] Firewall dikonfigurasi
- [ ] Aplikasi bisa diakses via browser
- [ ] Admin user dibuat
- [ ] SSL certificate terinstall (opsional)

---

**Catatan Penting:**
- Selalu backup database sebelum melakukan update
- Gunakan HTTPS di production
- Monitor logs secara berkala
- Update system secara berkala: `sudo apt update && sudo apt upgrade`
