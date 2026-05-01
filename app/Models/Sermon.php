<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sermon extends Model
{
    /** @use HasFactory<\Database\Factories\SermonFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'speaker_name',
        'date_preached',
        'youtube_url',
        'youtube_video_id',
        'imported_from_youtube',
        'audio_url',
        'description',
    ];

    protected $casts = [
        'date_preached' => 'date',
        'imported_from_youtube' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sermon) {
            if (empty($sermon->slug)) {
                $sermon->slug = Str::slug($sermon->title);
            }
        });
    }
}
