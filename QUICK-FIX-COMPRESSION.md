# Quick Fix: Install Image Compression

## The Issue
Intervention Image package is not installed on production, causing the error:
```
Class "Intervention\Image\Laravel\Facades\Image" not found
```

## Quick Fix (Run on Production Server)

### Step 1: Navigate to your project
```bash
cd /home2/ruralevangelical/public_html
```

### Step 2: Pull latest code
```bash
git pull origin main
```

### Step 3: Install the package
```bash
composer require intervention/image
```

### Step 4: Clear caches
```bash
php artisan config:clear
php artisan cache:clear
```

### Step 5: Test
Upload an image through Filament admin panel. It should now compress automatically.

---

## Alternative: If Composer Require Fails

If `composer require` doesn't work, try:

```bash
# Update all dependencies
composer update --no-dev --optimize-autoloader

# Or install from composer.json
composer install --no-dev --optimize-autoloader
```

---

## Verify Installation

Check if the package is installed:
```bash
composer show intervention/image
```

You should see:
```
name     : intervention/image
descrip. : Image handling and manipulation library
versions : * 4.1.2
```

---

## Check GD Extension

Image compression requires GD extension:
```bash
php -m | grep -i gd
```

If not installed, contact your hosting provider or install via cPanel:
- WHM → EasyApache 4 → PHP Extensions → Enable "gd"

---

## Test Compression

After installation, upload a large image (5-10MB) and check:

1. **File uploads successfully** ✅
2. **File size is reduced** (check in cPanel File Manager)
3. **Image displays correctly** on website

---

## If Still Having Issues

### Check Laravel Logs
```bash
tail -50 storage/logs/laravel.log
```

### Check Composer Autoload
```bash
composer dump-autoload
```

### Verify Package in vendor
```bash
ls -la vendor/intervention/
```

You should see an `image` directory.

---

## Temporary Workaround

If you can't install the package right now, the code will work without compression:
- Images will upload successfully
- They just won't be compressed
- No errors will occur (fallback is in place)

Install the package when convenient to enable compression.
