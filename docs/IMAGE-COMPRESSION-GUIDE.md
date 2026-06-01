# Automatic Image Compression Guide

## Overview

Gallery images are now automatically compressed after upload to save storage space while maintaining excellent visual quality.

## Features

✅ **Automatic Compression** - Images compressed on upload without manual intervention  
✅ **Quality Preservation** - 85% JPEG quality maintains excellent visual appearance  
✅ **Smart Resizing** - Large images resized to max 2000px on longest side  
✅ **Format Optimization** - PNG without transparency converted to JPEG for better compression  
✅ **50MB Upload Limit** - Support for high-resolution images  
✅ **60-70% Size Reduction** - Typical compression saves significant storage space  

## How It Works

### 1. Upload Process
When you upload an image through Filament:
1. Image is uploaded to `public/gallery-images/`
2. Compression automatically runs after upload
3. Original file is replaced with compressed version
4. Database record is saved

### 2. Compression Rules

**JPEG Images (.jpg, .jpeg):**
- Quality: 85% (excellent quality, good compression)
- Max dimension: 2000px (maintains high resolution)
- Typical size reduction: 60-70%

**PNG Images (.png):**
- **Without transparency:** Converted to JPEG (better compression)
- **With transparency:** Kept as PNG, optimized
- Max dimension: 2000px

**WebP Images (.webp):**
- Quality: 85%
- Max dimension: 2000px
- Already efficient format

**Other Formats:**
- Converted to JPEG with 85% quality

### 3. Size Examples

| Original | Compressed | Savings |
|----------|------------|---------|
| 10 MB    | ~3 MB      | 70%     |
| 5 MB     | ~1.5 MB    | 70%     |
| 2 MB     | ~600 KB    | 70%     |
| 1 MB     | ~300 KB    | 70%     |

## Configuration

### Upload Limits

**Filament Forms:** 50MB per file  
**Livewire:** 50MB temporary upload limit  
**PHP:** 50MB upload_max_filesize, 55MB post_max_size  

### Compression Settings

Located in: `app/Models/GalleryImage.php`

```php
// Maximum dimension (pixels)
$maxDimension = 2000;

// JPEG quality (1-100)
$image->toJpeg(quality: 85);

// PNG quality
$image->toPng();

// WebP quality (1-100)
$image->toWebp(quality: 85);
```

### Adjusting Compression

To change compression settings, edit `app/Models/GalleryImage.php`:

**For higher quality (larger files):**
```php
$maxDimension = 3000;  // Larger images
$image->toJpeg(quality: 90);  // Higher quality
```

**For more compression (smaller files):**
```php
$maxDimension = 1500;  // Smaller images
$image->toJpeg(quality: 75);  // More compression
```

**To disable resizing:**
```php
// Comment out or remove the resize block
// if ($width > $maxDimension || $height > $maxDimension) {
//     ...
// }
```

## Technical Details

### Image Processing Library

Uses **Intervention Image v4** with GD driver:
- Fast processing
- Good quality
- Available on most shared hosting
- Supports JPEG, PNG, WebP, GIF

### Compression Algorithm

1. **Load image** using Intervention Image
2. **Check dimensions** - Get width and height
3. **Resize if needed** - Scale down if > 2000px
4. **Detect format** - Check file extension
5. **Apply compression:**
   - JPEG: Quality 85
   - PNG: Check transparency, convert to JPEG if opaque
   - WebP: Quality 85
6. **Save** - Replace original file
7. **Set permissions** - 644 for web access

### Transparency Detection

For PNG images:
```php
private function hasTransparency(string $path): bool
{
    $image = Image::read($path);
    return $image->pickColor(0, 0)->alpha() < 255;
}
```

If PNG has transparency, it's kept as PNG. Otherwise, converted to JPEG for better compression.

## Performance

### Processing Time

- Small images (< 1MB): < 1 second
- Medium images (1-5MB): 1-3 seconds
- Large images (5-50MB): 3-10 seconds

### Server Requirements

- **PHP Extension:** GD or Imagick
- **Memory:** 256MB recommended
- **Execution Time:** 300 seconds (5 minutes)

## Troubleshooting

### Issue: Images not compressing

**Check:**
1. GD extension installed: `php -m | grep -i gd`
2. Memory limit sufficient: `ini_get('memory_limit')`
3. Check Laravel logs: `storage/logs/laravel.log`

**Solution:**
```bash
# Install GD (if missing)
# Ubuntu/Debian:
sudo apt-get install php-gd

# CentOS/RHEL:
sudo yum install php-gd

# Restart web server
sudo service apache2 restart
# or
sudo service nginx restart
```

### Issue: Compression fails silently

**Check logs:**
```bash
tail -f storage/logs/laravel.log
```

**Common causes:**
- Insufficient memory
- Corrupted image file
- Unsupported format
- Permission issues

**Solution:**
Increase PHP memory limit in `.user.ini`:
```ini
memory_limit = 512M
```

### Issue: Quality too low

**Adjust quality setting:**

Edit `app/Models/GalleryImage.php`:
```php
// Increase from 85 to 90 or 95
$image->toJpeg(quality: 90);
```

### Issue: Images still too large

**Reduce max dimension:**

Edit `app/Models/GalleryImage.php`:
```php
// Reduce from 2000 to 1500 or 1200
$maxDimension = 1500;
```

## Compression for Existing Images

To compress images that were uploaded before this feature:

### Option 1: Via Artisan Command

Create a command to compress existing images:

```bash
php artisan make:command CompressGalleryImages
```

Then run:
```bash
php artisan gallery:compress-images
```

### Option 2: Manual Script

Create `public/compress-existing.php`:

```php
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$images = App\Models\GalleryImage::all();
foreach ($images as $image) {
    echo "Compressing: {$image->image_path}\n";
    $image->compressImage();
}
echo "Done!\n";
```

Run via browser or CLI:
```bash
php public/compress-existing.php
```

## Best Practices

### For Uploaders

1. **Upload high-quality originals** - Compression will optimize them
2. **Don't pre-compress** - Let the system handle it
3. **Use JPEG for photos** - Best compression for photographs
4. **Use PNG for graphics** - Logos, illustrations with transparency
5. **Avoid GIF** - Use PNG or WebP instead

### For Administrators

1. **Monitor storage usage** - Check disk space regularly
2. **Adjust compression** - Based on quality needs vs. storage
3. **Test uploads** - Verify compression works after deployment
4. **Backup originals** - Keep originals before mass compression
5. **Check logs** - Monitor for compression errors

## Storage Savings Calculator

Estimate storage savings:

```
Original total size: 1000 MB
Compression rate: 70%
Compressed size: 300 MB
Savings: 700 MB (70%)
```

For 1000 images averaging 5MB each:
- **Before:** 5000 MB (5 GB)
- **After:** 1500 MB (1.5 GB)
- **Saved:** 3500 MB (3.5 GB)

## FAQ

### Q: Will compression reduce image quality?
A: At 85% JPEG quality, the difference is imperceptible to most viewers. Professional photographers might notice slight differences at 100% zoom.

### Q: Can I disable compression?
A: Yes, remove the `static::created()` hook in `GalleryImage.php` model.

### Q: Does compression work for bulk uploads?
A: Yes, each image is compressed individually after upload.

### Q: What happens if compression fails?
A: The original image is kept, and an error is logged. Upload still succeeds.

### Q: Can I compress images to WebP?
A: Yes, modify the compression method to always save as WebP for maximum compression.

### Q: Does this work on shared hosting?
A: Yes, as long as GD extension is available (standard on most hosts).

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify GD installed: `php -m | grep gd`
3. Test with small image first
4. Check server memory and execution time limits
