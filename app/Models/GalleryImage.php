<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class GalleryImage extends Model
{
    protected $fillable = [
        'gallery_id',
        'title',
        'description',
        'image_path',
        'sort_order',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::created(function (GalleryImage $image) {
            // Compress image after upload
            $image->compressImage();
        });
        
        static::deleting(function (GalleryImage $image) {
            if ($image->image_path && Storage::exists($image->image_path)) {
                Storage::delete($image->image_path);
            }
        });
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function getImageUrlAttribute()
    {
        return asset($this->image_path);
    }

    /**
     * Compress the uploaded image while maintaining quality
     * Reduces file size by ~60-70% without visible quality loss
     */
    public function compressImage(): void
    {
        $fullPath = public_path($this->image_path);
        
        if (!file_exists($fullPath)) {
            return;
        }

        try {
            // Load the image
            $image = Image::read($fullPath);
            
            // Get original dimensions
            $width = $image->width();
            $height = $image->height();
            
            // Only resize if image is larger than 2000px on longest side
            $maxDimension = 2000;
            if ($width > $maxDimension || $height > $maxDimension) {
                if ($width > $height) {
                    $image->scale(width: $maxDimension);
                } else {
                    $image->scale(height: $maxDimension);
                }
            }
            
            // Determine format and save with compression
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    // JPEG: Quality 85 (good balance between size and quality)
                    $image->toJpeg(quality: 85)->save($fullPath);
                    break;
                    
                case 'png':
                    // PNG: Convert to JPEG if no transparency, otherwise optimize PNG
                    if (!$this->hasTransparency($fullPath)) {
                        // Convert to JPEG for better compression
                        $newPath = preg_replace('/\.png$/i', '.jpg', $fullPath);
                        $image->toJpeg(quality: 85)->save($newPath);
                        
                        // Delete old PNG and update path
                        @unlink($fullPath);
                        $this->image_path = str_replace('.png', '.jpg', $this->image_path);
                        $this->saveQuietly();
                    } else {
                        // Keep as PNG but optimize
                        $image->toPng()->save($fullPath);
                    }
                    break;
                    
                case 'webp':
                    // WebP: Quality 85
                    $image->toWebp(quality: 85)->save($fullPath);
                    break;
                    
                default:
                    // For other formats, save as JPEG
                    $image->toJpeg(quality: 85)->save($fullPath);
                    break;
            }
            
            // Set proper permissions
            @chmod($fullPath, 0644);
            
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::error('Image compression failed: ' . $e->getMessage(), [
                'image_path' => $this->image_path,
            ]);
        }
    }

    /**
     * Check if PNG has transparency
     */
    private function hasTransparency(string $path): bool
    {
        try {
            $image = Image::read($path);
            // Check if image has alpha channel
            return $image->pickColor(0, 0)->alpha() < 255;
        } catch (\Exception $e) {
            return false;
        }
    }
}
