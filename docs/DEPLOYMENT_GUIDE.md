# Church Website - Deployment Guide for cPanel

## Project Overview
This is a modern church website built with:
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: React 18, Inertia.js, Tailwind CSS
- **Admin Panel**: Filament v3
- **Hosting**: cPanel Shared Hosting

---

## 1. INITIALIZATION & SETUP COMMANDS

### Step 1: Create Laravel Project
```bash
composer create-project laravel/laravel church-website "11.*"
cd church-website
```

### Step 2: Install Laravel Breeze with React/Inertia
```bash
composer require laravel/breeze --dev
php artisan breeze:install react
npm install
npm run build
```

### Step 3: Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

---

## 2. DATABASE SETUP

### Create Models, Migrations, and Factories
```bash
php artisan make:model Sermon -mf
php artisan make:model Event -mf
php artisan make:model BlogPost -mf
```

### Run Migrations
```bash
php artisan migrate
```

### Seed Database (Optional - for testing)
```bash
php artisan db:seed
```

---

## 3. FILAMENT ADMIN PANEL SETUP

### Install Filament v3
```bash
composer require filament/filament:"^3.2"
php artisan filament:install --panels
```

### Create Admin User
```bash
php artisan make:filament-user
```
Follow the prompts to create your admin account.

### Create Filament Resources
```bash
php artisan make:filament-resource Sermon --generate
php artisan make:filament-resource Event --generate
php artisan make:filament-resource BlogPost --generate
```

### Access Admin Panel
After deployment, access at: `https://yourdomain.com/admin`

---

## 4. FRONTEND SETUP

### Install React Player (for YouTube/Audio)
```bash
npm install react-player
```

### Build Assets for Production
```bash
npm run build
```

---

## 5. CPANEL DEPLOYMENT CHECKLIST

### A. Prepare Your Files

1. **Build production assets**:
   ```bash
   npm run build
   ```

2. **Optimize Laravel**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Create a ZIP of your project**:
   ```bash
   zip -r church-website.zip . -x "node_modules/*" ".git/*" "storage/logs/*"
   ```

### B. cPanel File Structure

Your cPanel hosting should have this structure:
```
/home/username/
├── public_html/              # Web root (publicly accessible)
└── church-website/           # Laravel application (private)
```

### C. Upload Files

1. **Upload via cPanel File Manager or FTP**:
   - Upload the entire Laravel project to `/home/username/church-website/`
   - Do NOT upload to `public_html` directly

2. **Set Permissions**:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

### D. Configure Public Directory

**Option 1: Symlink Method (Recommended)**

1. Delete everything in `public_html`
2. Create a symlink from `public_html` to Laravel's `public` folder:
   ```bash
   ln -s /home/username/church-website/public/* /home/username/public_html/
   ```

**Option 2: Copy Public Files**

1. Copy contents of `church-website/public/` to `public_html/`
2. Update `public_html/index.php`:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Update these paths to point to your Laravel installation
require __DIR__.'/../church-website/vendor/autoload.php';

$app = require_once __DIR__.'/../church-website/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

### E. Environment Configuration

1. **Update `.env` file** in `/home/username/church-website/`:
   ```env
   APP_NAME="Church Name"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_cpanel_database
   DB_USERNAME=your_cpanel_db_user
   DB_PASSWORD=your_cpanel_db_password
   ```

2. **Generate application key** (if not already done):
   ```bash
   php artisan key:generate
   ```

### F. Database Setup on cPanel

1. **Create MySQL Database**:
   - Go to cPanel → MySQL Databases
   - Create a new database
   - Create a database user
   - Add user to database with ALL PRIVILEGES

2. **Import Database** (if migrating):
   - Go to cPanel → phpMyAdmin
   - Select your database
   - Import your SQL dump

3. **Or Run Migrations**:
   ```bash
   cd /home/username/church-website
   php artisan migrate --force
   ```

### G. Final Steps

1. **Clear all caches**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   ```

2. **Optimize for production**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Set up SSL Certificate**:
   - Go to cPanel → SSL/TLS
   - Install Let's Encrypt SSL (usually free)

4. **Test your site**:
   - Visit `https://yourdomain.com`
   - Visit `https://yourdomain.com/admin` (Filament admin panel)

---

## 6. POST-DEPLOYMENT MAINTENANCE

### Update Application
```bash
cd /home/username/church-website
git pull origin main  # If using Git
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Update Frontend Assets
```bash
npm install
npm run build
# Copy new build files to public_html if using Option 2
```

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

---

## 7. TROUBLESHOOTING

### Issue: 500 Internal Server Error
- Check `.env` file exists and has correct values
- Check file permissions: `chmod -R 755 storage bootstrap/cache`
- Check error logs in `storage/logs/laravel.log`

### Issue: Assets Not Loading
- Ensure `APP_URL` in `.env` matches your domain
- Check that `public/build` directory exists and has files
- Run `npm run build` again

### Issue: Database Connection Error
- Verify database credentials in `.env`
- Ensure database user has proper privileges
- Check if database host is `localhost` or `127.0.0.1`

### Issue: Admin Panel Not Accessible
- Clear caches: `php artisan config:clear`
- Ensure Filament is installed: `composer show filament/filament`
- Check routes: `php artisan route:list | grep admin`

### Issue: Composer/PHP Version
- Most cPanel hosts use PHP 7.4 by default
- Change PHP version in cPanel → Select PHP Version
- Select PHP 8.2 or higher
- Enable required extensions: mbstring, xml, pdo, openssl, tokenizer, json

---

## 8. SECURITY BEST PRACTICES

1. **Never commit `.env` file** to version control
2. **Set `APP_DEBUG=false`** in production
3. **Use strong database passwords**
4. **Keep Laravel and dependencies updated**:
   ```bash
   composer update
   ```
5. **Enable HTTPS** (SSL certificate)
6. **Restrict admin panel access** (optional):
   - Add IP whitelist in Filament config
   - Use strong passwords for admin users

---

## 9. USEFUL COMMANDS REFERENCE

### Laravel Artisan Commands
```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Seed database
php artisan db:seed

# Create admin user
php artisan make:filament-user

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# List all routes
php artisan route:list

# Run queue worker (if using queues)
php artisan queue:work
```

### NPM Commands
```bash
# Install dependencies
npm install

# Development build with hot reload
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

---

## 10. SUPPORT & RESOURCES

- **Laravel Documentation**: https://laravel.com/docs/11.x
- **Filament Documentation**: https://filamentphp.com/docs/3.x
- **Inertia.js Documentation**: https://inertiajs.com
- **React Documentation**: https://react.dev
- **Tailwind CSS Documentation**: https://tailwindcss.com/docs

---

## Project Structure

```
church-website/
├── app/
│   ├── Filament/Resources/      # Filament admin resources
│   ├── Http/Controllers/        # Laravel controllers
│   └── Models/                  # Eloquent models
├── database/
│   ├── migrations/              # Database migrations
│   └── factories/               # Model factories
├── public/                      # Public web root
│   └── build/                   # Compiled assets
├── resources/
│   ├── js/
│   │   ├── Components/          # React components
│   │   └── Pages/               # Inertia pages
│   └── views/                   # Blade templates
├── routes/
│   └── web.php                  # Web routes
├── storage/                     # Storage directory
├── .env                         # Environment configuration
├── composer.json                # PHP dependencies
├── package.json                 # Node dependencies
└── vite.config.js              # Vite configuration
```

---

## Admin Panel Features

The Filament admin panel includes:
- **Sermons Management**: Create, edit, delete sermons with YouTube/audio URLs
- **Events Management**: Schedule and manage church events
- **Blog Posts Management**: Write and publish blog articles
- **User Management**: Manage admin users
- **Rich Text Editor**: For blog content
- **Form Validation**: Built-in validation for all fields
- **Responsive Design**: Works on all devices

Access at: `https://yourdomain.com/admin`

---

## Frontend Features

The public website includes:
- **Homepage**: Displays recent sermons and upcoming events
- **Responsive Navigation**: Mobile-friendly menu
- **Sermon Cards**: With embedded YouTube/audio players
- **Event Cards**: With date, time, and location
- **Modern Design**: Built with Tailwind CSS
- **Fast Performance**: Optimized with Vite

---

**Deployment Date**: April 19, 2026
**Laravel Version**: 11.x
**PHP Version**: 8.2+
**Node Version**: 18+
