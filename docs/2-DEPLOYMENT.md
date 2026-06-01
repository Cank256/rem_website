# Deployment Guide

## cPanel / Shared Hosting Deployment

Complete guide for deploying to shared hosting without terminal access.

## Pre-Deployment

### 1. Prepare Locally
```bash
cd church-website

# Install production dependencies
composer install --no-dev --optimize-autoloader

# Build frontend
npm install
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Create Production .env
Create `.env.production`:
```env
APP_NAME="Your Church Name"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpanel_database
DB_USERNAME=your_cpanel_user
DB_PASSWORD=your_cpanel_password

SESSION_DRIVER=database
QUEUE_CONNECTION=database

MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
RESEND_API_KEY=your_production_key
```

Generate APP_KEY:
```bash
php artisan key:generate --show
```

### 3. Create Deployment Package
Create ZIP excluding:
- `node_modules/`
- `.git/`
- `.env` (use `.env.production`)
- `tests/`
- `storage/logs/*`

## cPanel Setup

### 1. Create MySQL Database
1. cPanel → MySQL Databases
2. Create database: `rem_church`
3. Create user with strong password
4. Add user to database with ALL PRIVILEGES
5. Save credentials

### 2. Configure PHP Version
1. cPanel → Select PHP Version
2. Choose PHP 8.2 or higher
3. Enable extensions:
   - ✅ bcmath, ctype, fileinfo, json, mbstring
   - ✅ openssl, pdo, pdo_mysql, tokenizer
   - ✅ xml, zip, gd, curl

### 3. Upload Files
**Via File Manager:**
1. Upload ZIP to `/home/username/`
2. Create folder: `laravel`
3. Extract ZIP to `/home/username/laravel/`
4. Delete ZIP file

**Via FTP:**
1. Connect with FileZilla/WinSCP
2. Upload all files to `/home/username/laravel/`

### 4. Configure Environment
1. Navigate to `/home/username/laravel/`
2. Rename `.env.production` to `.env`
3. Edit `.env` with database credentials
4. Save

### 5. Set Permissions
Set these permissions using File Manager:
- `storage/` folders: **755** (recursive)
- `bootstrap/cache/`: **755** (recursive)
- All files: **644**

### 6. Point Domain to Laravel

**Option A: Main Domain Redirect**
Create `.htaccess` in `/public_html/`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ /laravel/public/$1 [L]
</IfModule>
```

**Option B: Subdomain**
1. cPanel → Subdomains
2. Create subdomain: `church`
3. Document Root: `/home/username/laravel/public`

**Option C: Addon Domain**
1. cPanel → Addon Domains
2. Add domain
3. Document Root: `/home/username/laravel/public`

## Database Setup (No Terminal Access)

### 1. Run Migrations via PHP Script
Create `public/migrate.php`:
```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Artisan::call('migrate', ['--force' => true]);
    echo "✅ Migrations completed!\n";
    echo "<pre>" . Artisan::output() . "</pre>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```

Visit: `https://yourdomain.com/migrate.php`
**DELETE immediately after success!**

### 2. Create Storage Link
Create `public/storage-link.php`:
```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Artisan::call('storage:link');
    echo "✅ Storage link created!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```

Visit: `https://yourdomain.com/storage-link.php`
**DELETE immediately after!**

### 3. Create Admin User
Create `public/create-admin.php`:
```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// CHANGE THESE!
$name = 'Admin User';
$email = 'admin@yourdomain.com';
$password = 'YourStrongPassword123!';

try {
    $user = App\Models\User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'email_verified_at' => now(),
    ]);
    
    // Assign admin role if using Spatie
    if (class_exists('Spatie\Permission\Models\Role')) {
        $user->assignRole('admin');
    }
    
    echo "✅ Admin user created!\n\n";
    echo "Email: {$email}\n";
    echo "Password: {$password}\n\n";
    echo "Login: https://yourdomain.com/admin\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```

**IMPORTANT:**
1. Edit the file and change email/password
2. Visit: `https://yourdomain.com/create-admin.php`
3. Save the credentials
4. **DELETE the file immediately!**

## Security

### 1. Protect .env File
Create `.htaccess` in `/laravel/`:
```apache
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

### 2. Enable HTTPS
1. cPanel → SSL/TLS Status
2. Enable AutoSSL (Let's Encrypt)
3. Wait for certificate
4. Update `.env`: `APP_URL=https://yourdomain.com`

### 3. Verify Security
- [ ] `APP_DEBUG=false` in `.env`
- [ ] `.env` not accessible via browser
- [ ] All temporary scripts deleted
- [ ] HTTPS enabled
- [ ] Strong passwords used

## Post-Deployment Testing

### Checklist
- [ ] Homepage loads without errors
- [ ] Can access `/admin`
- [ ] Can login with admin credentials
- [ ] Can upload images
- [ ] Can create events
- [ ] Can create sermons
- [ ] Gallery displays images
- [ ] Events page shows events
- [ ] Sermons page shows sermons
- [ ] Mobile responsive
- [ ] HTTPS working

## Deployment with Terminal Access

If your host provides SSH access:

```bash
# SSH into server
ssh username@yourdomain.com

# Navigate to project
cd laravel

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Create admin user
php artisan make:filament-user

# Clear and optimize
php artisan optimize:clear
php artisan optimize
```

## Updating the Site

### Deploy Updates
1. Make changes locally
2. Test thoroughly
3. Run `npm run build`
4. Run `composer install --no-dev`
5. Create backup of live site
6. Upload changed files via FTP
7. Clear caches (delete `bootstrap/cache/*.php`)
8. Test live site

### Run New Migrations
Create `public/migrate-new.php` (same as migrate.php)
Visit, then delete.

## Troubleshooting

### 500 Internal Server Error
- Check `.env` exists with correct database credentials
- Check folder permissions (755 for folders, 644 for files)
- Check error logs: cPanel → Error Logs
- Check Laravel logs: `storage/logs/laravel.log`

### Database Connection Failed
- Verify database credentials in `.env`
- Try `DB_HOST=localhost` or `DB_HOST=127.0.0.1`
- Ensure database user has all privileges
- Check database exists

### Images Not Displaying
- Run storage link script again
- Check `public/storage` exists
- Check folder permissions: `storage/app/public/` = 755
- Re-upload images

### CSS/JS Not Loading
- Check `public/build` folder exists
- Verify you ran `npm run build` before upload
- Check `.htaccess` in public folder exists
- Clear browser cache

### Admin Panel Not Accessible
- Check admin user was created
- Clear route cache: delete `bootstrap/cache/routes-*.php`
- Check URL: `https://yourdomain.com/admin`
- Check Filament installed: verify `vendor/filament` exists

### White Screen / Blank Page
- Enable debug temporarily: `APP_DEBUG=true` in `.env`
- Check error message
- Fix issue
- Set back to `APP_DEBUG=false`

## Maintenance

### Backups
**Database:**
1. cPanel → phpMyAdmin
2. Select database → Export
3. Download SQL file
4. Schedule weekly

**Files:**
1. cPanel → File Manager
2. Compress `laravel` folder
3. Download ZIP
4. Schedule monthly

### Monitor Logs
Check regularly:
- `storage/logs/laravel.log`
- cPanel Error Logs

### Keep Updated
```bash
# Update dependencies (locally, then deploy)
composer update
npm update
```

## Performance Optimization

### Enable Caching
Via temporary script or SSH:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Enable OPcache
cPanel → Select PHP Version → Enable OPcache

### Compress Assets
Already done by `npm run build`

## Support

**Documentation:**
- Setup: `GETTING-STARTED.md`
- Issues: `TROUBLESHOOTING.md`
- Commands: `COMMANDS.md`

**External Resources:**
- Laravel Docs: https://laravel.com/docs
- Filament Docs: https://filamentphp.com/docs

---

**Deployment Checklist**: See `DEPLOYMENT-CHECKLIST.md`
