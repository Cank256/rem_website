<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    protected $fillable = [
        'title',
        'youtube_url',
        'youtube_video_id',
        'youtube_channel_id',
        'youtube_api_key',
        'check_interval_minutes',
        'is_live',
        'auto_detect',
        'description',
        'stream_started_at',
        'stream_ended_at',
    ];

    protected $casts = [
        'is_live' => 'boolean',
        'auto_detect' => 'boolean',
        'check_interval_minutes' => 'integer',
        'stream_started_at' => 'datetime',
        'stream_ended_at' => 'datetime',
    ];

    /**
     * Hide the API key from array/JSON output for security
     */
    protected $hidden = ['youtube_api_key'];

    /**
     * Get the embed URL for YouTube
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if (!$this->youtube_video_id) {
            return null;
        }

        return "https://www.youtube.com/embed/{$this->youtube_video_id}?autoplay=1&mute=0";
    }

    /**
     * Extract video ID from YouTube URL
     */
    public function setYoutubeUrlAttribute($value): void
    {
        $this->attributes['youtube_url'] = $value;
        
        if ($value) {
            // Extract video ID from various YouTube URL formats
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $value, $matches);
            
            if (isset($matches[1])) {
                $this->attributes['youtube_video_id'] = $matches[1];
            }
        }
    }

    /**
     * Get the active live stream
     */
    public static function getActive(): ?self
    {
        return self::where('is_live', true)->first();
    }
}
