# Fixing 403 Forbidden Error on Filament Admin

## 🚨 Problem

You can login to `/admin` but get **403 Forbidden** error after authentication.

---

## 🎯 Quick Fixes (Try These First)

### Fix 1: Run Diagnostic Scripts

**Step 1:** Upload and run `check-admin.php`
- Upload to `public/` folder
- Visit: `https://yourdomain.com/check-admin.php`
- Read the output carefully
- Delete the file

**Step 2:** Upload and run `fix-permissions.php`
- Upload to `public/` folder
- Visit: `https://yourdomain.com/fix-permissions.php`
- Should fix permission issues
- Delete the file

### Fix 2: Check Document Root

**Most common cause of 403 errors!**

Your domain MUST point to the `public` folder, not the root Laravel folder.

**Option A: Set Document Root in cPanel**
1. Go to cPanel > Domains
2. Click on your domain
3. Set Document Root to: `/public_html/public` (or wherever your public folder is)
4. Save changes
5. Wait 5 minutes for changes to propagate

**Option B: Move Files to Root**
If you can't change document root:
1. Move everything from `public/` folder to `public_html/` (root)
2. Edit `index.php` in root:
   ```php
   // Change this line:
   require __DIR__.'/../vendor/autoload.php';
   // To this:
   require __DIR__.'/vendor/autoload.php';
   
   // And change this line:
   $app = require_once __DIR__.'/../bootstrap/app.php';
   // To this:
   $app = require_once __DIR__.'/bootstrap/app.php';
   ```
3. Update `.htaccess` if needed

### Fix 3: File Permissions

**In cPanel File Manager:**

1. Select all **folders** → Right-click → Permissions → Set to **755**
2. Select all **files** → Right-click → Permissions → Set to **644**
3. Specifically check these folders are **755**:
   - `storage/`
   - `storage/framework/`
   - `storage/framework/cache/`
   - `storage/framework/sessions/`
   - `storage/framework/views/`
   - `storage/logs/`
   - `bootstrap/cache/`

### Fix 4: Clear All Caches

**Delete these files manually in File Manager:**
- `bootstrap/cache/config.php`
- `bootstrap/cache/routes-v7.php`
- `bootstrap/cache/packages.php`
- `bootstrap/cache/services.php`

Keep only `.gitignore` in `bootstrap/cache/`

### Fix 5: Check .htaccess

Make sure `public/.htaccess` exists and contains:

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

Permissions: **644**

---

## 🔍 Detailed Diagnostics

### Check 1: Verify PHP Version

Laravel 11 requires **PHP 8.2 or higher**

**In cPanel:**
1. Go to "Select PHP Version" or "MultiPHP Manager"
2. Select PHP 8.2 or 8.3
3. Save changes

### Check 2: Enable Required PHP Extensions

**Required extensions:**
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- cURL
- GD (for image processing)

**In cPanel:**
1. Go to "Select PHP Version"
2. Click "Extensions"
3. Enable all required extensions
4. Save

### Check 3: Verify mod_rewrite

**Contact your hosting support** and ask:
- "Is mod_rewrite enabled for my account?"
- "Can you enable it if it's not?"

### Check 4: Check Error Logs

**In cPanel:**
1. Go to "Error Logs"
2. Look for recent errors
3. Note the exact error message

**Or check Laravel logs:**
- File Manager → `storage/logs/laravel.log`
- Look at the most recent entries

### Check 5: Enable Debug Mode (Temporarily)

**Edit `.env` file:**
```env
APP_DEBUG=true
```

**Visit `/admin` again** - you'll see the actual error message

**IMPORTANT:** Set back to `false` after fixing!

---

## 🛠️ Advanced Fixes

### Fix A: Regenerate Application Key

If you see "No application encryption key" error:

**Create this file:** `public/generate-key.php`

```php
<?php
$basePath = dirname(__DIR__);
require $basePath . '/vendor/autoload.php';

$app = require_once $basePath . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<pre>";
echo "Generating application key...\n\n";
$kernel->call('key:generate');
echo "\n✅ Key generated!\n";
echo "Check your .env file for APP_KEY\n";
echo "</pre>";
?>
```

Upload, run, delete.

### Fix B: Rebuild Config Cache

**Create this file:** `public/rebuild-cache.php`

```php
<?php
$basePath = dirname(__DIR__);

echo "<h1>Rebuilding Cache</h1>";
echo "<pre>";

// Delete old cache files
$cacheFiles = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes-v7.php',
    'bootstrap/cache/packages.php',
    'bootstrap/cache/services.php',
];

foreach ($cacheFiles as $file) {
    $fullPath = $basePath . '/' . $file;
    if (file_exists($fullPath)) {
        unlink($fullPath);
        echo "✓ Deleted $file\n";
    }
}

echo "\n✅ Cache cleared!\n";
echo "Try accessing /admin again.\n";
echo "\n⚠️  DELETE this file now!\n";
echo "</pre>";
?>
```

Upload, run, delete.

### Fix C: Check Session Configuration

**Edit `.env` file:**

```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

Make sure `storage/framework/sessions/` folder exists and is writable (755).

### Fix D: Verify Database Connection

Run `check-admin.php` to verify:
- Database is connected
- Users table exists
- Admin user exists and is verified

---

## 📋 Checklist

Go through this checklist:

- [ ] Domain points to `public` folder (or files moved to root)
- [ ] PHP version is 8.2 or higher
- [ ] All required PHP extensions enabled
- [ ] mod_rewrite is enabled
- [ ] File permissions: folders 755, files 644
- [ ] storage/ and bootstrap/cache/ are writable (755)
- [ ] .htaccess exists in public/ folder (644)
- [ ] All cache files deleted from bootstrap/cache/
- [ ] .env file has correct database credentials
- [ ] Admin user exists in database
- [ ] Browser cache cleared
- [ ] Tried incognito/private browsing

---

## 🎯 Most Likely Causes

**Based on shared hosting experience:**

1. **Document Root Issue (80% of cases)**
   - Domain not pointing to `public` folder
   - Fix: Set document root or move files

2. **File Permissions (15% of cases)**
   - storage/ or bootstrap/cache/ not writable
   - Fix: Set folders to 755

3. **Cached Configuration (3% of cases)**
   - Old cached config from local machine
   - Fix: Delete bootstrap/cache/*.php files

4. **mod_rewrite Disabled (2% of cases)**
   - Server doesn't have mod_rewrite enabled
   - Fix: Contact hosting support

---

## 📞 Still Not Working?

### Get Detailed Error Information

1. **Enable debug mode:**
   ```env
   APP_DEBUG=true
   ```

2. **Visit `/admin` and note the exact error**

3. **Check error logs:**
   - cPanel > Error Logs
   - storage/logs/laravel.log

4. **Share the error message** for specific help

### Contact Hosting Support

Ask them to check:
- Is mod_rewrite enabled?
- Are all PHP extensions available?
- Are there any server-level restrictions on my account?
- Can I use .htaccess files?
- Are symbolic links allowed?

---

## ✅ Success Indicators

You'll know it's fixed when:
- ✅ Can access `/admin/login`
- ✅ Can login with credentials
- ✅ See Filament dashboard after login
- ✅ Can navigate to Resources (Sermons, Events, etc.)
- ✅ Can upload images
- ✅ No 403 or 500 errors

---

## 🔐 Security Reminder

After fixing:
- Set `APP_DEBUG=false` in `.env`
- Delete all diagnostic PHP files:
  - `check-admin.php`
  - `fix-permissions.php`
  - `generate-key.php`
  - `rebuild-cache.php`
  - Any other test scripts

---

**Last Updated:** April 20, 2026
**Status:** Ready to troubleshoot
