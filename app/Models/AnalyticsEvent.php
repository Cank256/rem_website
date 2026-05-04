<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsEvent extends Model
{
    protected $fillable = [
        'visitor_session_id',
        'page_view_id',
        'event_name',
        'event_category',
        'event_data',
        'url',
        'user_id',
    ];

    protected $casts = [
        'event_data' => 'array',
    ];

    public function visitorSession(): BelongsTo
    {
        return $this->belongsTo(VisitorSession::class);
    }

    public function pageView(): BelongsTo
    {
        return $this->belongsTo(PageView::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
