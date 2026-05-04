<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageView extends Model
{
    protected $fillable = [
        'visitor_session_id',
        'url',
        'path',
        'method',
        'referrer',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'ip_address',
        'country',
        'city',
        'duration',
        'user_id',
    ];

    protected $casts = [
        'duration' => 'integer',
    ];

    public function visitorSession(): BelongsTo
    {
        return $this->belongsTo(VisitorSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function analyticsEvents()
    {
        return $this->hasMany(AnalyticsEvent::class);
    }
}
