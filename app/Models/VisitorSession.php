<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorSession extends Model
{
    protected $fillable = [
        'session_id',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'ip_address',
        'country',
        'city',
        'first_visit_at',
        'last_activity_at',
        'page_views_count',
        'total_duration',
        'user_id',
    ];

    protected $casts = [
        'first_visit_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'page_views_count' => 'integer',
        'total_duration' => 'integer',
    ];

    public function pageViews()
    {
        return $this->hasMany(PageView::class);
    }

    public function analyticsEvents()
    {
        return $this->hasMany(AnalyticsEvent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
