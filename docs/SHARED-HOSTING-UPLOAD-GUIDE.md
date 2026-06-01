# Shared Hosting Upload Size Configuration Guide

## Overview
This guide explains how to increase PHP upload limits on your shared hosting server to allow larger image uploads (up to 20MB).

## Files Configured

### 1. `.user.ini` (Root & Public directories)
- **Location**: `/.user.ini` and `/public/.user.ini`
- **Purpose**: Sets PHP configuration for PHP-FPM/FastCGI (most modern shared hosting)
- **Settings**:
  ```ini
  upload_max_filesize = 20M
  post_max_size = 25M
  max_file_uploads = 50
  max_execution_time = 300
  max_input_time = 300
  memory_limit = 256M
  ```

### 2. `.htaccess` (Public directory)
- **Location**: `/public/.htaccess`
- **Purpose**: Sets PHP configuration for mod_php and FastCGI
- **Already configured** with the same limits as above

## Deployment Steps

### Step 1: Upload Files
When deploying to your shared hosting, ensure these files are uploaded:
- `/.user.ini`
- `/public/.user.ini`
- `/public/.htaccess` (updated)

### Step 2: Verify Configuration
1. Upload the `public/check-php-limits.php` file to your server
2. Visit: `https://yourdomain.com/check-php-limits.php`
3. Check if the limits show 20M or higher
4. **DELETE** `check-php-limits.php` after checking (security risk!)

### Step 3: If Limits Are Still Too Low

#### Option A: cPanel (Most Common)
1. Log into your cPanel
2. Go to **"Select PHP Version"** or **"MultiPHP INI Editor"**
3. Adjust these settings:
   - `upload_max_filesize`: 20M
   - `post_max_size`: 25M
   - `max_execution_time`: 300
   - `memory_limit`: 256M
4. Save changes

#### Option B: Plesk
1. Log into Plesk
2. Go to **"PHP Settings"** for your domain
3. Adjust the same settings as above
4. Apply changes

#### Option C: Contact Hosting Support
If you don't have access to PHP settings:
1. Contact your hosting provider's support
2. Request them to increase:
   - `upload_max_filesize` to **20M**
   - `post_max_size` to **25M**
   - `max_execution_time` to **300 seconds**
3. Mention it's for a Laravel application with image uploads

### Step 4: Clear Application Cache
After changing PHP settings, SSH into your server and run:
```bash
php artisan config:clear
php artisan cache:clear
```

Or use the web-based cache clearing script if available.

## Troubleshooting

### Issue: Settings not taking effect
**Solution**: 
- Wait 5-10 minutes (some hosts cache PHP settings)
- Restart PHP-FPM if you have access
- Check if `.user.ini` files have correct permissions (644)

### Issue: Still getting upload errors
**Possible causes**:
1. **Server-level limits**: Some hosts have hard limits that override .user.ini
2. **Nginx limits**: If using Nginx, check `client_max_body_size`
3. **Firewall/WAF**: Some security tools block large uploads

**Solution**: Contact hosting support with the error message

### Issue: .user.ini not supported
**Solution**: 
- Try creating `php.ini` instead of `.user.ini`
- Use cPanel/Plesk PHP settings
- Contact hosting support

## Current Application Settings

The Filament forms are currently set to:
- **Single image upload**: 2MB max
- **Bulk upload**: 2MB per image, 50 images max

### To Increase After PHP Configuration
Once PHP limits are confirmed at 20M+, update these files:

**File**: `app/Filament/Resources/GalleryResource/RelationManagers/ImagesRelationManager.php`
```php
// Change from:
->maxSize(2048)  // 2MB

// To:
->maxSize(20480)  // 20MB
```

**File**: `app/Filament/Resources/GalleryImageResource.php`
```php
// Change from:
->maxSize(2048)  // 2MB

// To:
->maxSize(20480)  // 20MB
```

## Security Notes

1. **Delete check-php-limits.php** after use
2. Keep `.user.ini` files (they're safe and needed)
3. The `.htaccess` blocks access to sensitive files
4. Never commit `.env` files to git

## Support

If you continue having issues:
1. Check the Laravel logs: `storage/logs/laravel.log`
2. Check server error logs (usually in cPanel)
3. Contact your hosting provider with specific error messages

## Common Hosting Providers

### Bluehost / HostGator / GoDaddy
- Use cPanel → MultiPHP INI Editor
- `.user.ini` usually works

### SiteGround
- Use Site Tools → PHP Manager
- `.user.ini` supported

### Namecheap
- Use cPanel → Select PHP Version
- `.user.ini` supported

### DigitalOcean / Linode / VPS
- Edit `/etc/php/8.x/fpm/php.ini` directly
- Restart PHP-FPM: `sudo systemctl restart php8.x-fpm`
