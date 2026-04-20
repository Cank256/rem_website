# cPanel Deployment Guide (Without Terminal)

## Complete Guide for Deploying Laravel on Shared Hosting

This guide will help you deploy your Rural Evangelical Ministries website on cPanel shared hosting without terminal access.

---

## Prerequisites

Before you start, ensure you have:
- ✅ cPanel hosting account with PHP 8.2+ support
- ✅ MySQL database access
- ✅ FTP/File Manager access
- ✅ Your local project files ready
- ✅ Composer installed on your local machine

---

## Part 1: Prepare Your Project Locally

### Step 1: Install Dependencies Locally

On your local machine, run:

```bash
cd church-website

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies and build
npm install
npm run build
```

### Step 2: Optimize for Production

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 3: Create .env for Production

Create a new file called `.env.production` with these settings:

```env
APP_NAME="Rural Evangelical Ministries"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://yourdomain.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

**Important:** Generate a new APP_KEY:
```bash
php artisan key:generate --show
```
Copy the output and paste it in the `.env.production` file.

### Step 4: Create a Deployment Package

Create a ZIP file with these folders/files:
- `app/`
- `bootstrap/`
- `config/`
- `database/`
- `public/`
- `resources/`
- `routes/`
- `storage/`
- `vendor/` (after running composer install)
- `.env.production` (rename to `.env` after upload)
- `artisan`
- `composer.json`
- `composer.lock`
- `package.json`

**DO NOT include:**
- `node_modules/`
- `.git/`
- `.env` (use `.env.production` instead)
- `tests/`

---

## Part 2: Setup cPanel

### Step 1: Create MySQL Database

1. Log into cPanel
2. Go to **MySQL Databases**
3. Create a new database:
   - Database name: `rem_church` (or your choice)
   - Click **Create Database**
4. Create a database user:
   - Username: `rem_user` (or your choice)
   - Password: Generate a strong password
   - Click **Create User**
5. Add user to database:
   - Select the user and database
   - Grant **ALL PRIVILEGES**
   - Click **Add**

**Save these credentials - you'll need them!**

### Step 2: Setup PHP Version

1. In cPanel, go to **Select PHP Version** or **MultiPHP Manager**
2. Select your domain
3. Choose **PHP 8.2** or higher
4. Enable these extensions:
   - ✅ bcmath
   - ✅ ctype
   - ✅ fileinfo
   - ✅ json
   - ✅ mbstring
   - ✅ openssl
   - ✅ pdo
   - ✅ pdo_mysql
   - ✅ tokenizer
   - ✅ xml
   - ✅ zip
   - ✅ gd
   - ✅ curl

---

## Part 3: Upload Files

### Method 1: Using File Manager (Recommended)

1. In cPanel, go to **File Manager**
2. Navigate to your home directory (usually `/home/username/`)
3. Create a folder called `laravel` (or any name)
4. Upload your ZIP file to this folder
5. Right-click the ZIP file and select **Extract**
6. After extraction, delete the ZIP file

### Method 2: Using FTP

1. Use an FTP client (FileZilla, WinSCP, etc.)
2. Connect to your hosting:
   - Host: Your domain or server IP
   - Username: Your cPanel username
   - Password: Your cPanel password
   - Port: 21
3. Upload all files to `/home/username/laravel/`

---

## Part 4: Configure the Application

### Step 1: Setup .env File

1. In File Manager, navigate to `/home/username/laravel/`
2. Find `.env.production` file
3. Right-click and **Rename** it to `.env`
4. Right-click `.env` and select **Edit**
5. Update these values:
   ```env
   APP_URL=https://yourdomain.com
   DB_DATABASE=your_cpanel_database_name
   DB_USERNAME=your_cpanel_database_user
   DB_PASSWORD=your_cpanel_database_password
   ```
6. Save the file

### Step 2: Set Folder Permissions

Using File Manager, set these permissions:

1. `storage/` folder and all subfolders: **755**
2. `bootstrap/cache/` folder: **755**
3. All files in `storage/`: **644**
4. All files in `bootstrap/cache/`: **644**

**How to change permissions:**
- Right-click folder/file
- Select **Change Permissions**
- Enter the number (755 or 644)
- For folders, check "Recurse into subdirectories"
- Click **Change Permissions**

### Step 3: Point Domain to Public Folder

**Option A: If using main domain (yourdomain.com)**

1. In File Manager, go to `/home/username/public_html/`
2. Delete or backup all existing files
3. Create a new file called `.htaccess` with this content:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ /laravel/public/$1 [L]
</IfModule>
```

4. Create an `index.php` file with this content:

```php
<?php
header('Location: /laravel/public/');
exit;
?>
```

**Option B: If using subdomain (church.yourdomain.com)**

1. In cPanel, go to **Subdomains**
2. Create a subdomain: `church`
3. Set Document Root to: `/home/username/laravel/public`
4. Click **Create**

**Option C: Using Addon Domain**

1. In cPanel, go to **Addon Domains**
2. Add your domain
3. Set Document Root to: `/home/username/laravel/public`
4. Click **Add Domain**

---

## Part 5: Run Database Migrations

Since you don't have terminal access, we'll create a temporary migration script.

### Step 1: Create Migration Script

1. In File Manager, go to `/home/username/laravel/public/`
2. Create a new file called `migrate.php`
3. Add this content:

```php
<?php
// IMPORTANT: Delete this file after running migrations!

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Run migrations
$status = $kernel->call('migrate', [
    '--force' => true,
]);

echo "<h1>Migration Status</h1>";
echo "<pre>";
echo "Migrations completed with status: " . $status;
echo "\n\nIf you see no errors above, migrations were successful!";
echo "\n\n<strong style='color: red;'>IMPORTANT: Delete this file (migrate.php) immediately for security!</strong>";
echo "</pre>";
?>
```

4. Save the file

### Step 2: Run Migrations

1. Open your browser
2. Visit: `https://yourdomain.com/migrate.php`
3. You should see "Migrations completed"
4. **IMMEDIATELY delete `migrate.php` file for security!**

### Step 3: Create Storage Link

Create another file called `storage-link.php` in `/public/`:

```php
<?php
// IMPORTANT: Delete this file after running!

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->call('storage:link');

echo "<h1>Storage Link Status</h1>";
echo "<pre>";
echo "Storage link created with status: " . $status;
echo "\n\n<strong style='color: red;'>IMPORTANT: Delete this file (storage-link.php) immediately for security!</strong>";
echo "</pre>";
?>
```

Visit `https://yourdomain.com/storage-link.php` and then delete the file.

---

## Part 6: Create Admin User

Create a file called `create-admin.php` in `/public/`:

```php
<?php
// IMPORTANT: Delete this file after creating admin!

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Create admin user
$name = 'Admin User';
$email = 'admin@yourdomain.com'; // Change this
$password = 'ChangeThisPassword123!'; // Change this

$user = \App\Models\User::create([
    'name' => $name,
    'email' => $email,
    'password' => \Illuminate\Support\Facades\Hash::make($password),
    'email_verified_at' => now(),
]);

echo "<h1>Admin User Created</h1>";
echo "<pre>";
echo "Name: " . $name . "\n";
echo "Email: " . $email . "\n";
echo "Password: " . $password . "\n\n";
echo "Login at: " . config('app.url') . "/admin\n\n";
echo "<strong style='color: red;'>IMPORTANT: Delete this file (create-admin.php) immediately for security!</strong>";
echo "</pre>";
?>
```

**Before running:**
1. Edit the file and change the email and password
2. Visit `https://yourdomain.com/create-admin.php`
3. Save the credentials shown
4. **IMMEDIATELY delete the file!**

---

## Part 7: Security & Optimization

### Step 1: Secure .env File

Create/edit `.htaccess` in `/home/username/laravel/`:

```apache
# Deny access to .env file
<Files .env>
    Order allow,deny
    Deny from all
</Files>

# Deny access to storage folder
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^storage/.* - [F,L]
</IfModule>
```

### Step 2: Optimize Public Folder

Edit `/public/.htaccess` to ensure it has:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Step 3: Enable HTTPS

1. In cPanel, go to **SSL/TLS Status**
2. Enable AutoSSL for your domain
3. Wait for certificate to be issued
4. Update `.env`:
   ```env
   APP_URL=https://yourdomain.com
   ```

### Step 4: Setup Cron Jobs (Optional)

If you need scheduled tasks:

1. In cPanel, go to **Cron Jobs**
2. Add a new cron job:
   - Minute: `*`
   - Hour: `*`
   - Day: `*`
   - Month: `*`
   - Weekday: `*`
   - Command: `/usr/local/bin/php /home/username/laravel/artisan schedule:run >> /dev/null 2>&1`

---

## Part 8: Testing

### Checklist:

- [ ] Visit your website homepage
- [ ] Check all navigation links work
- [ ] Visit `/admin` and login
- [ ] Upload a test image in Gallery
- [ ] Create a test event
- [ ] Create a test sermon
- [ ] Check gallery page displays images
- [ ] Check events page shows events
- [ ] Check sermons page shows sermons
- [ ] Test on mobile device
- [ ] Check HTTPS is working

---

## Troubleshooting

### Issue: "500 Internal Server Error"

**Solution:**
1. Check `.env` file exists and has correct database credentials
2. Check folder permissions (storage and bootstrap/cache should be 755)
3. Enable error display temporarily:
   - Edit `.env`: `APP_DEBUG=true`
   - Visit site to see actual error
   - Set back to `false` after fixing

### Issue: "Database connection error"

**Solution:**
1. Verify database credentials in `.env`
2. Check database user has all privileges
3. Ensure database exists
4. Try `DB_HOST=localhost` or `DB_HOST=127.0.0.1`

### Issue: "Images not displaying"

**Solution:**
1. Run the storage link script again
2. Check `storage/app/public` folder exists
3. Check `public/storage` symlink exists
4. Upload images again through admin

### Issue: "CSS/JS not loading"

**Solution:**
1. Check `public/build` folder exists
2. Ensure you ran `npm run build` locally before uploading
3. Check `.htaccess` in public folder
4. Clear browser cache

### Issue: "Admin panel not accessible"

**Solution:**
1. Check `/admin` route exists
2. Verify admin user was created
3. Clear route cache by deleting `bootstrap/cache/routes-*.php`
4. Check Filament is installed in `vendor/filament`

---

## Maintenance

### Updating the Site

1. Make changes locally
2. Run `npm run build`
3. Run `composer install --optimize-autoloader --no-dev`
4. Upload changed files via FTP/File Manager
5. Clear caches (delete files in `bootstrap/cache/` and `storage/framework/cache/`)

### Backing Up

**Database Backup:**
1. cPanel > phpMyAdmin
2. Select your database
3. Click Export
4. Download SQL file

**Files Backup:**
1. cPanel > File Manager
2. Select `laravel` folder
3. Right-click > Compress
4. Download ZIP file

---

## Security Checklist

- [ ] `.env` file is not accessible via browser
- [ ] `APP_DEBUG=false` in production
- [ ] All temporary PHP scripts deleted (migrate.php, create-admin.php, etc.)
- [ ] Strong database password used
- [ ] Admin password is strong and unique
- [ ] HTTPS is enabled
- [ ] File permissions are correct (755 for folders, 644 for files)
- [ ] `storage/` folder is not publicly accessible

---

## Quick Reference

### File Structure on Server:
```
/home/username/
├── laravel/              # Your Laravel app
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/          # Web root points here
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   └── .env
└── public_html/         # Original web root
    ├── .htaccess        # Redirects to laravel/public
    └── index.php        # Redirects to laravel/public
```

### Important URLs:
- Website: `https://yourdomain.com`
- Admin: `https://yourdomain.com/admin`
- cPanel: `https://yourdomain.com:2083`

### Important Files:
- Configuration: `/laravel/.env`
- Database: cPanel > MySQL Databases
- Logs: `/laravel/storage/logs/laravel.log`

---

## Need Help?

If you encounter issues:

1. Check the error log: `/storage/logs/laravel.log`
2. Enable debug mode temporarily: `APP_DEBUG=true` in `.env`
3. Check cPanel error logs
4. Contact your hosting support for server-specific issues

---

## Summary

✅ **Deployment Steps:**
1. Prepare project locally (composer, npm build)
2. Create database in cPanel
3. Upload files via File Manager/FTP
4. Configure .env file
5. Set folder permissions
6. Point domain to public folder
7. Run migrations via PHP script
8. Create storage link via PHP script
9. Create admin user via PHP script
10. Delete all temporary scripts
11. Test everything

**Your website should now be live!** 🎉

---

**Last Updated:** April 20, 2026
**For:** Rural Evangelical Ministries Website
