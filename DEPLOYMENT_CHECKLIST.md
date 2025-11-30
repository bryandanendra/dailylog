# Deployment Checklist - Daily Log Application

## ✅ Pre-Deployment Checklist

### 1. Environment Configuration
- [ ] Create `.env` file for production (copy from `.env.example` if exists)
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate `APP_KEY` (run `php artisan key:generate`)
- [ ] Set `APP_URL` to production domain (e.g., `https://yourdomain.com`)
- [ ] Configure database credentials:
  - `DB_CONNECTION=mysql` (or your preferred database)
  - `DB_HOST=your-db-host`
  - `DB_PORT=3306`
  - `DB_DATABASE=your-database-name`
  - `DB_USERNAME=your-username`
  - `DB_PASSWORD=your-password`
- [ ] Configure session driver:
  - `SESSION_DRIVER=database` (recommended for production)
  - `SESSION_LIFETIME=120` (minutes)
- [ ] Configure mail settings (if using email notifications)
- [ ] Set `LOG_CHANNEL=daily` or `single` for production logging

### 2. Database Setup
- [ ] Create production database
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Import initial data (if needed): `php artisan db:seed --force`
- [ ] Verify all tables are created correctly
- [ ] Check foreign key constraints

### 3. Dependencies & Assets
- [ ] Install production dependencies: `composer install --optimize-autoloader --no-dev`
- [ ] Install npm dependencies: `npm install`
- [ ] Build assets: `npm run build`
- [ ] Verify `public/build` directory exists with compiled assets

### 4. Storage & Permissions
- [ ] Create storage link: `php artisan storage:link`
- [ ] Set proper permissions:
  - `storage/` and `bootstrap/cache/` should be writable (755 or 775)
  - `storage/logs/` should be writable
- [ ] Verify `public/storage` symlink exists

### 5. Cache & Optimization
- [ ] Clear all caches: `php artisan config:clear && php artisan cache:clear && php artisan view:clear`
- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Optimize autoloader: `composer dump-autoload --optimize`

### 6. Security
- [ ] Verify `.env` file is not accessible via web (should be outside public directory)
- [ ] Check `.gitignore` includes `.env`
- [ ] Verify `APP_DEBUG=false` in production
- [ ] Check CSRF protection is enabled
- [ ] Verify session security settings:
  - `SESSION_SECURE_COOKIE=true` (if using HTTPS)
  - `SESSION_HTTP_ONLY=true`
  - `SESSION_SAME_SITE=lax` or `strict`

### 7. Server Configuration
- [ ] Web server (Apache/Nginx) configured correctly
- [ ] Document root points to `public/` directory
- [ ] PHP version >= 8.2
- [ ] Required PHP extensions installed:
  - `pdo_mysql` (or `pdo_sqlite` if using SQLite)
  - `mbstring`
  - `openssl`
  - `tokenizer`
  - `xml`
  - `ctype`
  - `json`
  - `bcmath`
  - `fileinfo`
  - `gd` (for image processing if needed)
- [ ] PHP `memory_limit` >= 256M
- [ ] PHP `upload_max_filesize` and `post_max_size` configured appropriately

### 8. Application-Specific Checks
- [ ] Verify helper functions are autoloaded (ReportHelper.php)
- [ ] Check all routes are working
- [ ] Verify authentication is working
- [ ] Test approval workflow
- [ ] Test report generation (SPDR, Monthly, BI, Category, TMA)
- [ ] Verify employee management features
- [ ] Check daily log functionality
- [ ] Test notification system

### 9. Testing
- [ ] Test login/logout functionality
- [ ] Test daily log creation and editing
- [ ] Test approval process
- [ ] Test report generation
- [ ] Test employee management (admin only)
- [ ] Test role-based access control
- [ ] Test supervisor hierarchy
- [ ] Test concurrent user access

### 10. Monitoring & Logging
- [ ] Set up log rotation (if using daily logs)
- [ ] Configure error logging
- [ ] Set up monitoring (optional but recommended)
- [ ] Verify logs directory is writable

## 🚀 Deployment Steps

1. **Prepare Server**
   ```bash
   # Create project directory
   mkdir -p /var/www/dailylog
   cd /var/www/dailylog
   ```

2. **Upload Files**
   ```bash
   # Upload all files except vendor, node_modules, .env
   # Or use git clone if using version control
   git clone your-repo-url .
   ```

3. **Install Dependencies**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install
   npm run build
   ```

4. **Configure Environment**
   ```bash
   cp .env.example .env  # If .env.example exists
   # Edit .env with production values
   php artisan key:generate
   ```

5. **Database Setup**
   ```bash
   php artisan migrate --force
   # If needed: php artisan db:seed --force
   ```

6. **Storage & Permissions**
   ```bash
   php artisan storage:link
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache  # Adjust user/group as needed
   ```

7. **Optimize**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   composer dump-autoload --optimize
   ```

8. **Verify**
   ```bash
   php artisan about  # Check application status
   ```

## ⚠️ Important Notes

- **Never commit `.env` file** to version control
- **Always set `APP_DEBUG=false`** in production
- **Use HTTPS** in production (set `SESSION_SECURE_COOKIE=true`)
- **Backup database** before running migrations
- **Test in staging environment** first if possible
- **Monitor logs** after deployment for any errors

## 🔧 Post-Deployment

- [ ] Test all major features
- [ ] Monitor error logs
- [ ] Check performance
- [ ] Verify all users can login
- [ ] Test approval workflow
- [ ] Verify reports are generating correctly
- [ ] Check storage permissions
- [ ] Monitor database performance

## 📝 Rollback Plan

If something goes wrong:
1. Restore previous code version
2. Restore database backup
3. Clear caches: `php artisan config:clear && php artisan cache:clear`
4. Check error logs: `storage/logs/laravel.log`

## 🆘 Troubleshooting

### Common Issues:
- **500 Error**: Check `storage/logs/laravel.log`, verify permissions
- **Assets not loading**: Run `npm run build`, check `public/build` exists
- **Database errors**: Verify `.env` database credentials
- **Session issues**: Check `SESSION_DRIVER` and database `sessions` table
- **Permission denied**: Check `storage/` and `bootstrap/cache/` permissions

---

**Last Updated**: 2025-01-XX
**Application Version**: Laravel 12.x


