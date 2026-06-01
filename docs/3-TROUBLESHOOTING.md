# Troubleshooting Guide

## Common Issues and Solutions

### Application Errors

#### 500 Internal Server Error
**Symptoms:** White page, "Server Error"

**Solutions:**
```bash
# Fix permissions
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/logs/*.log

# For cPanel (replace username)
chown -R username:username storage bootstrap/cache

# Clear all caches
php artisan optimize:clear
```

**Check:**
- `.env` file exists
- `.env` has correct database credentials
- `storage/` is writable
- Check `storage/logs/laravel.log` for actual error

#### 404 Error on All Pages (Except Homepage)
**Cause:** Missing `.htaccess` or mod_rewrite disabled

**Solution:**
Ensure `public/.htaccess` exists:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**Or contact hosting to enable mod_rewrite**

#### CSRF Token Mismatch
**Symptoms:** Forms fail with "419 Page Expired"

**Solutions:**
```bash
# Clear application cache
php artisan cache:clear
php artisan config:clear

# Check session configuration
php artisan config:show session
```

**Check `.env`:**
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

Then run:
```bash
php artisan migrate  # Ensure sessions table exists
```

### Database Issues

#### Database Connection Failed
**Error:** "SQLSTATE[HY000] [1045] Access denied"

**Solutions:**
1. Verify `.env` credentials:
```env
DB_HOST=localhost  # Try 127.0.0.1 if localhost fails
DB_DATABASE=correct_database_name
DB_USERNAME=correct_username
DB_PASSWORD=correct_password
```

2. Test in cPanel:
   - MySQL Databases → Check database exists
   - Check user exists
   - Check user assigned to database with ALL PRIVILEGES

3. Test connection via PHP:
```php
<?php
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=your_db",
        "your_user",
        "your_password"
    );
    echo "✅ Connection successful!";
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```

#### Table Doesn't Exist
**Error:** "SQLSTATE[42S02]: Base table or view not found"

**Solution:**
```bash
# Run migrations
php artisan migrate --force

# Or via web script (see DEPLOYMENT.md)
```

**If migrations fail**, check:
- Database user has CREATE permission
- No syntax errors in migration files
- Run migrations one by one

#### Migration Already Ran
**Error:** "Duplicate column name" or "Table already exists"

**Solutions:**
```bash
# Fresh migration (WARNING: Deletes all data!)
php artisan migrate:fresh

# Or rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

### File Upload / Storage Issues

#### Images Not Showing
**Symptoms:** Broken image links, 404 on images

**Solutions:**
```bash
# Create storage link
php artisan storage:link

# Check link exists
ls -la public/storage  # Should point to ../storage/app/public

# Fix permissions
chmod -R 755 storage/app/public
```

**For cPanel (no terminal):**
- Upload images directly to `public/storage/` folder
- Or create symlink via File Manager

#### Upload Fails / File Too Large
**Error:** "The file exceeds the maximum upload size"

**Solutions:**
1. Update PHP limits in `.user.ini` (cPanel):
```ini
upload_max_filesize = 50M
post_max_size = 55M
max_execution_time = 300
memory_limit = 512M
```

2. Update Livewire config in `.env`:
```env
LIVEWIRE_TEMP_FILE_UPLOAD_MAX_SIZE=51200
```

3. Wait 5 minutes for `.user.ini` changes to take effect

#### Image Compression Fails
**Error:** Images upload but not compressed

**Check:**
```bash
# Verify GD extension
php -m | grep -i gd

# Check memory limit
php -i | grep memory_limit
```

**Increase memory if needed:**
```ini
memory_limit = 512M
```

### Asset / Frontend Issues

#### CSS/JS Not Loading
**Symptoms:** Unstyled page, JavaScript not working

**Solutions:**
```bash
# Rebuild assets
npm run build

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check APP_URL in .env
APP_URL=https://yourdomain.com  # Must match actual domain
```

**Check:**
- `public/build/` folder exists and has files
- `public/.htaccess` is correct
- Browser console for specific errors (F12)

#### Vite/HMR Connection Errors
**In development only**

**Solution:**
```bash
# Stop and restart dev server
npm run dev

# Check vite.config.js server settings
# Ensure dev server running on correct port
```

### Admin Panel Issues

#### Cannot Access /admin
**Symptoms:** 404 on /admin or login page won't load

**Solutions:**
```bash
# Clear route cache
php artisan route:clear
php artisan config:clear

# Verify Filament installed
composer show filament/filament

# Check routes
php artisan route:list | grep admin
```

**Reinstall Filament if needed:**
```bash
composer require filament/filament:"^3.2"
php artisan filament:install --panels
```

#### Admin Login Fails
**Symptoms:** Credentials not working

**Solutions:**
1. Reset password via tinker:
```bash
php artisan tinker
>>> $user = User::where('email', 'admin@example.com')->first();
>>> $user->password = Hash::make('newpassword');
>>> $user->save();
>>> exit
```

2. Or create new admin:
```bash
php artisan make:filament-user
```

3. Check user can access panel:
```php
// In User model
public function canAccessPanel($panel): bool
{
    return true;  // Temporarily allow all
}
```

#### Filament Resources Not Showing
**Symptoms:** Menu items missing

**Solutions:**
```bash
# Clear cache
php artisan filament:cache-components
php artisan optimize:clear

# Check resource policy
# Ensure canViewAny() returns true
```

### YouTube Integration Issues

#### API Connection Failed
**Error:** "Configuration Missing" or "Invalid credentials"

**Solutions:**
1. Test connection:
```bash
php artisan youtube:test-connection
```

2. Verify credentials in `/admin` → Live Stream:
   - YouTube Channel ID is correct
   - API Key is valid

3. Check API enabled:
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Ensure "YouTube Data API v3" is enabled
   - Check API key restrictions

#### No Live Streams Found
**Cause:** Wrong channel ID or no public live streams

**Solutions:**
- Verify channel ID from YouTube Studio
- Ensure past live streams are public/unlisted (not private)
- Check you have actual live streams (not just videos)

#### API Quota Exceeded
**Error:** "Quota exceeded"

**Solution:**
- Wait 24 hours for quota reset
- Default quota: 10,000 units/day (plenty for normal use)
- Request quota increase from Google if needed

### Email Issues

#### Emails Not Sending
**Symptoms:** Welcome emails, password resets not arriving

**Solutions:**
1. Check `.env` configuration:
```env
MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
RESEND_API_KEY=re_your_actual_key
```

2. Test email:
```bash
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));
>>> exit
```

3. Check logs:
```bash
tail -f storage/logs/laravel.log
```

4. Verify Resend:
   - API key is valid
   - Domain verified (for production)
   - Check Resend dashboard for errors

#### Emails Go to Spam
**Solution:**
- Verify domain in Resend dashboard
- Add SPF, DKIM, DMARC records to DNS
- Use verified domain in FROM address

### Analytics Issues

#### Cookie Consent Not Appearing
**Cause:** Already accepted or localStorage issue

**Solutions:**
- Clear browser localStorage (F12 → Application → Local Storage)
- Try incognito/private mode
- Check `CookieConsent` component imported in Layout

#### Analytics Not Tracking
**Symptoms:** No data in admin panel

**Solutions:**
1. Accept cookies on website
2. Browse a few pages
3. Check database:
```bash
php artisan tinker
>>> App\Models\PageView::count()
>>> exit
```

4. Check middleware is active:
```bash
php artisan route:list
# Look for TrackPageView middleware
```

5. Check `.env`:
```env
APP_ENV=production  # Analytics may skip in local
```

### Performance Issues

#### Slow Page Load
**Solutions:**
```bash
# Enable caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize

# Enable OPcache (in php.ini or cPanel)
opcache.enable=1
opcache.memory_consumption=128
```

#### Out of Memory
**Error:** "Allowed memory size exhausted"

**Solution:**
Increase in `.user.ini`:
```ini
memory_limit = 512M
```

#### Maximum Execution Time
**Error:** "Maximum execution time of 30 seconds exceeded"

**Solution:**
```ini
max_execution_time = 300
```

## Debugging Tools

### Enable Debug Mode (Temporarily)
**⚠️ NEVER in production unless troubleshooting!**

`.env`:
```env
APP_DEBUG=true
```

Will show detailed error messages. Set back to `false` when done.

### Check Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Last 50 lines
tail -n 50 storage/logs/laravel.log

# Clear logs
echo "" > storage/logs/laravel.log
```

### Laravel Tinker (Interactive Shell)
```bash
php artisan tinker

# Examples:
>>> User::all()
>>> Sermon::count()
>>> cache()->flush()
>>> config('app.url')
>>> exit
```

### Route Debugging
```bash
# List all routes
php artisan route:list

# Search routes
php artisan route:list | grep admin
php artisan route:list --name=sermon
```

### Configuration Check
```bash
# View config
php artisan config:show database
php artisan config:show mail

# Test database connection
php artisan db:show
```

## Emergency Fixes

### Nuclear Option (Reset Everything)
**⚠️ USE WITH CAUTION - DELETES DATA**

```bash
# Fresh start
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
php artisan optimize:clear
php artisan make:filament-user
```

### Clear All Caches
```bash
php artisan optimize:clear
```

This runs:
- `config:clear`
- `cache:clear`
- `route:clear`
- `view:clear`
- `event:clear`

### Fix Permissions (Linux/cPanel)
```bash
# Folders writable
find storage bootstrap/cache -type d -exec chmod 755 {} \;

# Files writable
find storage bootstrap/cache -type f -exec chmod 644 {} \;

# Set owner (cPanel)
chown -R username:username storage bootstrap/cache
```

### Reinstall Dependencies
```bash
# PHP dependencies
rm -rf vendor/
composer install

# Node dependencies
rm -rf node_modules/ public/build/
npm install
npm run build
```

## Getting More Help

### Check Documentation
- Setup: `GETTING-STARTED.md`
- Deployment: `DEPLOYMENT.md`
- Commands: `COMMANDS.md`

### Check External Docs
- Laravel: https://laravel.com/docs/11.x
- Filament: https://filamentphp.com/docs/3.x
- Inertia: https://inertiajs.com

### Check GitHub Issues
Search for similar issues in:
- Laravel GitHub
- Filament GitHub
- Inertia GitHub

### Contact Hosting Support
For server-specific issues:
- PHP version/extensions
- File permissions
- .htaccess/mod_rewrite
- Database access
- SSL certificates

---

**Still stuck? Check the Laravel logs first: `storage/logs/laravel.log`**
