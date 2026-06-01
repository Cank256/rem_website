# Gallery Images Storage Change

## What Changed?

Gallery images are now stored in `public/gallery-images/` instead of `storage/app/public/gallery-images/`.

### Before:
```
storage/app/public/gallery-images/image.jpg
↓ (symlink: public/storage)
https://yourdomain.com/storage/gallery-images/image.jpg
```

### After:
```
public/gallery-images/image.jpg
↓ (direct access)
https://yourdomain.com/gallery-images/image.jpg
```

## Benefits

✅ **No symlink issues** - Files are directly accessible  
✅ **No 403 permission errors** - Public folder has correct permissions by default  
✅ **Simpler deployment** - No need to run `php artisan storage:link`  
✅ **Better for shared hosting** - Avoids common hosting restrictions  

## Deployment Steps

### Step 1: Pull the Latest Code
```bash
git pull origin main
```

### Step 2: Clear Config Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### Step 3: Migrate Existing Images (If Any)

If you have existing images in the old location, run the migration script:

**Via Browser:**
Visit: `https://yourdomain.com/migrate-images.php`

**Via SSH:**
```bash
php public/migrate-images.php
```

This will:
- Copy images from `storage/app/public/gallery-images/` to `public/gallery-images/`
- Update database records with new paths
- Keep old images as backup

### Step 4: Verify Images Work

1. Upload a new test image through Filament
2. Verify it appears in `public/gallery-images/`
3. Check that it displays correctly on the website
4. Check existing images still display

### Step 5: Cleanup

After verifying everything works:

1. **Delete migration script:**
   - `public/migrate-images.php`

2. **Delete diagnostic scripts:**
   - `public/fix-permissions.php`
   - `public/diagnose-storage.php`
   - `public/fix-storage-link.php`
   - `public/upload-info.php`
   - `public/check-php-limits.php`

3. **Optional: Delete old images** (after confirming new ones work):
   - `storage/app/public/gallery-images/*`

## Technical Details

### Configuration Changes

**File: `config/filesystems.php`**
```php
'public' => [
    'driver' => 'local',
    'root' => public_path(),  // Changed from storage_path('app/public')
    'url' => env('APP_URL'),  // Changed from env('APP_URL').'/storage'
    'visibility' => 'public',
],
```

**File: `app/Models/GalleryImage.php`**
```php
public function getImageUrlAttribute()
{
    return asset($this->image_path);  // Changed from Storage::disk('public')->url()
}
```

### New Image Paths

- **Database:** `gallery-images/filename.jpg`
- **Filesystem:** `public/gallery-images/filename.jpg`
- **URL:** `https://yourdomain.com/gallery-images/filename.jpg`

### Filament Forms

All Filament upload components now use:
```php
->directory('gallery-images')  // No ->disk('public') needed
```

## Rollback (If Needed)

If you need to revert to the old system:

1. Restore these files from git history:
   - `config/filesystems.php`
   - `app/Models/GalleryImage.php`
   - Filament resource files

2. Run `php artisan storage:link`

3. Move images back to `storage/app/public/gallery-images/`

4. Update database paths

## FAQ

### Q: Will existing image URLs break?
A: Yes, URLs will change from `/storage/gallery-images/` to `/gallery-images/`. The migration script updates database records automatically.

### Q: Do I need to run storage:link anymore?
A: No, the symlink is no longer needed.

### Q: What about file permissions?
A: The `public` folder typically has correct permissions by default (755 for directories, 644 for files).

### Q: Can I store images in subdirectories?
A: Yes, but you'll need to update the directory configuration in Filament forms.

### Q: What about security?
A: Images are meant to be public, so storing them in the public folder is appropriate. For private files, continue using `storage/app/private/`.

## Support

If you encounter issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify `public/gallery-images/` directory exists and is writable
3. Run `php artisan config:clear`
4. Check that migrated images have 644 permissions

For permission issues:
```bash
chmod 755 public/gallery-images
chmod 644 public/gallery-images/*
```
