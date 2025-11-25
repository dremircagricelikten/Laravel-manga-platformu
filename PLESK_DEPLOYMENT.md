# PLESK DEPLOYMENT GUIDE

## Complete step-by-step guide for deploying this Manga CMS on Plesk

---

## Prerequisites

Before you begin:
- [ ] Access to Plesk control panel
- [ ] MySQL database created in Plesk
- [ ] Domain configured in Plesk
- [ ] SSH access (recommended but optional)

---

## Deployment Steps

### 1. Upload Files to Plesk

**Option A: Via File Manager**
1. Log in to Plesk
2. Go to Files → File Manager
3. Navigate to your domain folder (e.g., `httpdocs` or `public_html`)
4. Upload ALL files and folders from your local project

**Option B: Via FTP**
1. Use FTP client (FileZilla, WinSCP)
2. Connect using credentials from Plesk
3. Upload all files to your domain's root directory

**Option C: Via Plesk Git (if available)**
1. Initialize Git repository
2. Push to your remote repository
3. Configure Plesk Git deployment

---

### 2. Set Document Root

1. In Plesk, go to: **Hosting Settings**
2. Find "Document root" setting
3. Change from `/httpdocs` to `/httpdocs/public`
4. Save changes

**Important**: Laravel's entry point must be the `public` directory!

---

### 3. Install Composer Dependencies

**Via Plesk Composer Tool:**
1. Go to **Composer** in Plesk sidebar
2. Click "Install" or run command:
   ```
   install --optimize-autoloader --no-dev
   ```

**Via SSH:**
```bash
cd /var/www/vhosts/yourdomain.com/httpdocs
composer install --optimize-autoloader --no-dev
```

---

### 4. Configure Environment

1. In File Manager, locate `.env.example`
2. Copy it and rename to `.env`
3. Edit `.env` file:

```env
APP_NAME="Your Manga Site Name"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_plesk_database_name
DB_USERNAME=your_plesk_database_user
DB_PASSWORD=your_plesk_database_password
```

**Get database credentials from Plesk:**
- Go to **Databases** in Plesk
- Note down database name, username, and password

---

### 5. Generate Application Key

Via SSH or Plesk Terminal:
```bash
php artisan key:generate
```

This will update your `.env` file with `APP_KEY`.

---

### 6. Run Database Migrations

```bash
php artisan migrate
```

This creates all 15 tables:
- categories
- series
- category_series
- volumes
- chapters
- users (enhanced)
- wallets
- coin_packages
- transactions
- chapter_unlocks
- carts
- cart_items
- pages
- menu_items
- settings

---

### 7. Install FilamentPHP

```bash
php artisan filament:install --panels
```

Create your admin user:
```bash
php artisan make:filament-user
```

Enter:
- Name
- Email
- Password

---

### 8. Install Spatie Permissions

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

---

### 9. Create Storage Symlink

```bash
php artisan storage:link
```

This links `public/storage` to `storage/app/public`.

---

### 10. Set Directory Permissions

In Plesk File Manager or via SSH, set permissions:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

Or via Plesk File Manager:
- Right-click on `storage` folder → Properties → Set to 775
- Right-click on `bootstrap/cache` → Properties → Set to 775

---

### 11. Configure PHP Settings (if needed)

In Plesk → **PHP Settings**, ensure:
- PHP version: 8.2 or higher
- Extensions enabled:
  - mbstring
  - xml
  - curl
  - zip
  - gd
  - pdo_mysql

---

### 12. Set Up Cron Job (Scheduler)

In Plesk → **Scheduled Tasks** → Add:

```
* * * * * cd /var/www/vhosts/yourdomain.com/httpdocs && php artisan schedule:run >> /dev/null 2>&1
```

---

### 13. Install Frontend Dependencies (if building locally first)

If you have Node.js on Plesk or via SSH:

```bash
npm install
npm run build
```

Otherwise, build locally and upload `public/build` folder.

---

### 14. Configure FTP Storage (Optional)

If storing manga images on external FTP:

Edit `.env`:
```env
MANGA_STORAGE_DRIVER=ftp_manga
FTP_HOST=your-ftp-server.com
FTP_USERNAME=ftp-user
FTP_PASSWORD=ftp-password
FTP_PORT=21
FTP_ROOT=/manga
FTP_SSL=false
```

---

### 15. SSL Certificate (Recommended)

In Plesk:
1. Go to **SSL/TLS Certificates**
2. Install Let's Encrypt certificate (free)
3. Enable "Redirect from HTTP to HTTPS"

---

### 16. Test Installation

1. Visit: `https://yourdomain.com/admin`
2. Log in with admin credentials
3. You should see FilamentPHP dashboard

---

## Post-Deployment Tasks

### Create Filament Resources

You need to create Filament resources for:
- Series
- Chapters (with bulk upload)
- Volumes
- Categories
- CoinPackages
- Users
- Transactions
- Settings (custom page)

**Code templates are in `implementation_plan.md`.**

### Configure Site Settings

Log in to admin panel → Create Site Settings page (code in implementation_plan.md).

Set:
- Site name
- Logo
- Colors
- Social media links
- Coin name ("Ki")
- Default chapter costs

### Create Roles & Permissions

Suggested roles:
- Super Admin
- Admin
- Editor
- Uploader
- VIP User

```bash
php artisan permission:create-role "Super Admin"
php artisan permission:create-role "Editor"
php artisan permission:create-role "Uploader"
```

---

## Troubleshooting

### Issue: 500 Internal Server Error

**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Check error logs: `/var/www/vhosts/domain.com/logs/error_log`

---

### Issue: FilamentPHP not loading

**Solution:**
1. Run `php artisan filament:upgrade`
2. Clear cache
3. Check APP_URL in `.env` matches your domain

---

### Issue: Images not displaying

**Solution:**
1. Check storage link: `php artisan storage:link`
2. Verify permissions on `storage/` folder
3. Check APP_URL in `.env`

---

### Issue: Database connection error

**Solution:**
1. Verify database credentials in `.env`
2. Ensure database exists in Plesk
3. Check DB_HOST (usually `localhost` in Plesk)

---

## Optimization for Production

### Enable OPcache

In Plesk PHP Settings, enable OPcache extension.

### Queue Workers

Set up queue worker for better performance:

In Plesk Scheduled Tasks:
```
* * * * * cd /var/www/vhosts/domain.com/httpdocs && php artisan queue:work --stop-when-empty
```

### Configure Cache

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Note:** After any config changes, clear cache:
```bash
php artisan config:clear
```

---

## Security Checklist

- [ ] APP_DEBUG=false in `.env`
- [ ] Strong APP_KEY generated
- [ ] SSL certificate installed
- [ ] Database user has limited privileges
- [ ] Firewall configured in Plesk
- [ ] Regular backups enabled
- [ ] .env file not publicly accessible

---

## Maintenance

### Backup Database

In Plesk → **Databases** → **Export Dump**

### Backup Files

In Plesk → **Backup Manager** → **Back Up**

### Update Laravel

```bash
composer update
php artisan migrate
php artisan config:clear
```

---

## Support

For issues, check:
1. Plesk error logs
2. Laravel logs: `storage/logs/laravel.log`
3. Implementation plan documentation

---

**Deployment Complete! 🎉**

Your Manga CMS is now live on Plesk. Next steps:
1. Add Filament resources
2. Build frontend views
3. Create initial content
4. Configure site settings
