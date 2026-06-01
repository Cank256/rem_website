<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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
}
