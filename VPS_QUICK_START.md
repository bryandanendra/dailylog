# Quick Start - Deploy ke VPS Hostinger Baru

## 📋 Yang Perlu Disiapkan

1. **Informasi VPS dari Hostinger:**
   - IP Address VPS
   - Username SSH (biasanya `root`)
   - Password SSH
   - Domain (opsional)

2. **Informasi GitHub:**
   - Repository URL: `https://github.com/bryandanendra/dailylog.git`

## 🚀 Langkah Cepat

### 1. Koneksi ke VPS
```bash
ssh root@YOUR_VPS_IP
```

### 2. Install Semua Dependencies (Copy-Paste Semua)
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd unzip git curl
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### 3. Setup Database
```bash
sudo mysql
```

Di dalam MySQL:
```sql
CREATE DATABASE dailylog_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'dailylog_user'@'localhost' IDENTIFIED BY 'GantiDenganPasswordKuat123!';
GRANT ALL PRIVILEGES ON dailylog_db.* TO 'dailylog_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Clone & Setup Project
```bash
sudo mkdir -p /var/www/dailylog
cd /var/www/dailylog
sudo git clone https://github.com/bryandanendra/dailylog.git .
sudo chown -R www-data:www-data /var/www/dailylog
sudo chmod -R 755 /var/www/dailylog
```

### 5. Configure Environment
```bash
sudo cp .env.example .env
sudo nano .env
```

**Edit nilai berikut:**
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=http://YOUR_IP_OR_DOMAIN`
- `DB_DATABASE=dailylog_db`
- `DB_USERNAME=dailylog_user`
- `DB_PASSWORD=GantiDenganPasswordKuat123!`
- `SESSION_DRIVER=database`
- `LOG_CHANNEL=daily`

Simpan: `Ctrl+O`, Enter, `Ctrl+X`

### 6. Install Dependencies & Setup
```bash
cd /var/www/dailylog
sudo -u www-data composer install --optimize-autoloader --no-dev
sudo npm install
sudo npm run build
sudo php artisan key:generate
sudo php artisan storage:link
sudo php artisan migrate --force
```

### 7. Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/dailylog
sudo chmod -R 755 /var/www/dailylog
sudo chmod -R 775 /var/www/dailylog/storage
sudo chmod -R 775 /var/www/dailylog/bootstrap/cache
```

### 8. Optimize
```bash
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
```

### 9. Configure Nginx
```bash
sudo nano /etc/nginx/sites-available/dailylog
```

**Paste ini (ganti YOUR_IP_OR_DOMAIN):**
```nginx
server {
    listen 80;
    server_name YOUR_IP_OR_DOMAIN;
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

Simpan: `Ctrl+O`, Enter, `Ctrl+X`

```bash
sudo ln -s /etc/nginx/sites-available/dailylog /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl enable nginx
sudo systemctl restart php8.2-fpm
sudo systemctl enable php8.2-fpm
```

### 10. Configure Firewall
```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### 11. Create Admin User
```bash
cd /var/www/dailylog
sudo php artisan tinker
```

Di dalam Tinker:
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@dailylog.com';
$user->password = bcrypt('password123');
$user->role = 'admin';
$user->save();
exit
```

### 12. Test!
Buka browser: `http://YOUR_VPS_IP`

Login dengan:
- Email: `admin@dailylog.com`
- Password: `password123`

## 🔄 Update Aplikasi (Setelah Push ke GitHub)

```bash
ssh root@YOUR_VPS_IP
cd /var/www/dailylog
sudo git pull origin main
sudo -u www-data composer install --optimize-autoloader --no-dev
sudo npm install
sudo npm run build
sudo php artisan migrate --force
sudo php artisan config:clear
sudo php artisan cache:clear
sudo php artisan view:clear
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
sudo chown -R www-data:www-data /var/www/dailylog
sudo chmod -R 775 /var/www/dailylog/storage
sudo chmod -R 775 /var/www/dailylog/bootstrap/cache
```

## 🆘 Troubleshooting Cepat

### Error 500
```bash
sudo tail -f /var/www/dailylog/storage/logs/laravel.log
```

### Permission Error
```bash
sudo chown -R www-data:www-data /var/www/dailylog
sudo chmod -R 775 /var/www/dailylog/storage
sudo chmod -R 775 /var/www/dailylog/bootstrap/cache
```

### Clear Semua Cache
```bash
sudo php artisan config:clear
sudo php artisan cache:clear
sudo php artisan route:clear
sudo php artisan view:clear
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

## 📞 Kontak Hostinger

Jika ada masalah dengan VPS, hubungi support Hostinger:
- Live Chat di hPanel
- Email support

---

**Untuk panduan lengkap, lihat:** `.agent/workflows/deploy-to-hostinger-vps.md`
