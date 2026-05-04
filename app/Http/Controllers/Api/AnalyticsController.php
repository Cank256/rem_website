<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analytics;

    public function __construct(AnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * Track a custom event
     */
    public function trackEvent(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:100',
            'event_category' => 'nullable|string|max:100',
            'event_data' => 'nullable|array',
        ]);

        $event = $this->analytics->trackEvent(
            $validated['event_name'],
            $validated['event_category'] ?? null,
            $validated['event_data'] ?? null,
            $request
        );

        return response()->json([
            'success' => true,
            'event_id' => $event->id,
        ]);
    }

    /**
     * Update page view duration
     */
    public function updateDuration(Request $request)
    {
        $validated = $request->validate([
            'page_view_id' => 'required|exists:page_views,id',
            'duration' => 'required|integer|min:0',
        ]);

        $pageView = \App\Models\PageView::find($validated['page_view_id']);
        $pageView->update(['duration' => $validated['duration']]);

        return response()->json(['success' => true]);
    }
}
