# Fix 403 Forbidden Error on Gallery Images

## Problem
Images are uploaded to `storage/app/public/gallery-images` but show 403 Forbidden error when accessed via the website.

## Root Cause
This is a **file permissions issue** on your shared hosting server. The web server doesn't have permission to read the image files.

---

## Solution 1: Via SSH (Recommended)

If you have SSH access to your server:

### Step 1: Connect via SSH
```bash
ssh username@ruralevangelicalministries.org
cd /path/to/your/website
```

### Step 2: Run the fix script
```bash
bash fix-permissions.sh
```

### Step 3: If still getting 403, create .htaccess
```bash
bash fix-permissions.sh --create-htaccess
```

### Step 4: Verify
Visit: https://ruralevangelicalministries.org/storage/gallery-images/01KT2M03TPKC2QQK1V7PDJS91A.JPG

---

## Solution 2: Via cPanel File Manager

If you don't have SSH access:

### Step 1: Login to cPanel
Go to your hosting control panel.

### Step 2: Open File Manager
Navigate to: `storage/app/public/gallery-images`

### Step 3: Fix Directory Permissions
1. Right-click on `gallery-images` folder
2. Select "Change Permissions" or "Permissions"
3. Set to: **755** (rwxr-xr-x)
   - ✅ Owner: Read, Write, Execute
   - ✅ Group: Read, Execute
   - ✅ Public: Read, Execute
4. Click "Change Permissions"

### Step 4: Fix File Permissions
1. Select ALL image files in the gallery-images folder
2. Right-click → "Change Permissions"
3. Set to: **644** (rw-r--r--)
   - ✅ Owner: Read, Write
   - ✅ Group: Read
   - ✅ Public: Read
4. Click "Change Permissions"

### Step 5: Check Parent Directories
Also set these to 755:
- `storage/app/public` → 755
- `storage/app` → 755
- `storage` → 755

### Step 6: If Still Getting 403
Create a new file in `storage/app/public/gallery-images/.htaccess` with this content:
```apache
Options +FollowSymLinks
Require all granted
```

---

## Solution 3: Via Command Line (Manual)

If the script doesn't work, run these commands manually:

```bash
# Navigate to your website root
cd /path/to/your/website

# Fix directory permissions
chmod 755 storage/app/public
chmod 755 storage/app/public/gallery-images

# Fix file permissions
chmod 644 storage/app/public/gallery-images/*

# Create .htaccess to allow access
cat > storage/app/public/gallery-images/.htaccess << 'EOF'
Options +FollowSymLinks
Require all granted
EOF
```

---

## Solution 4: Contact Hosting Support

If none of the above work, contact your hosting provider support and ask them to:

1. **Set correct permissions:**
   - Directory `storage/app/public/gallery-images`: 755
   - Files in `storage/app/public/gallery-images/*`: 644

2. **Check for blocking rules:**
   - Apache/Nginx configuration blocking access to storage
   - ModSecurity rules blocking image access
   - Any .htaccess files in parent directories blocking access

3. **Verify symlink works:**
   - `public/storage` should be a symlink to `storage/app/public`
   - Symlinks should be allowed (FollowSymLinks option)

---

## Troubleshooting

### Issue: PHP scripts return 403
**Cause:** Server is blocking PHP execution in certain directories  
**Solution:** Use SSH commands or cPanel File Manager instead

### Issue: Permissions reset after upload
**Cause:** Server's default umask or upload settings  
**Solution:** Add this to your `.env`:
```env
FILESYSTEM_DISK=public
```

And ensure Filament uploads use the correct disk (already configured).

### Issue: Some images work, others don't
**Cause:** Inconsistent permissions on files  
**Solution:** Run the fix script again or manually set all files to 644

### Issue: Images work locally but not on production
**Cause:** Different server configurations  
**Solution:** This is normal - production servers have stricter permissions

---

## Prevention: Automatic Permission Fix

To prevent this issue in the future, you can set up a post-upload hook.

### Option A: Add to GalleryImage Model

Edit `app/Models/GalleryImage.php` and add:

```php
protected static function booted(): void
{
    static::created(function (GalleryImage $image) {
        // Fix permissions after upload
        $path = storage_path('app/public/' . $image->image_path);
        if (file_exists($path)) {
            @chmod($path, 0644);
        }
    });
    
    static::deleting(function (GalleryImage $image) {
        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
    });
}
```

### Option B: Server-Level Fix

Ask your hosting provider to set the default umask to 022, which will create files with 644 permissions automatically.

---

## Verification Checklist

After applying fixes, verify:

- [ ] Directory `storage/app/public/gallery-images` has 755 permissions
- [ ] Image files have 644 permissions
- [ ] Symlink `public/storage` exists and points to `storage/app/public`
- [ ] No blocking .htaccess files in storage directories
- [ ] Images load in browser without 403 error
- [ ] Images display correctly in Filament admin panel
- [ ] Images display correctly on public website

---

## Quick Reference: Permission Numbers

| Permission | Number | Meaning |
|------------|--------|---------|
| rwxr-xr-x  | 755    | Directories (owner can write, others can read/execute) |
| rw-r--r--  | 644    | Files (owner can write, others can read) |
| rwxrwxrwx  | 777    | ⚠️ Never use (security risk) |

---

## Need Help?

If you're still experiencing issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check server error logs (usually in cPanel)
3. Run `diagnose-storage.php` to see detailed diagnostics
4. Contact hosting support with this error message:
   > "Images in storage/app/public/gallery-images return 403 Forbidden. Please check file permissions and Apache/Nginx configuration for this directory."
