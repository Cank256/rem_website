<?php

namespace App\Services;

use App\Models\PageView;
use App\Models\VisitorSession;
use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class AnalyticsService
{
    protected Agent $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    /**
     * Track a page view
     */
    public function trackPageView(Request $request): ?PageView
    {
        // Skip tracking for admin panel and API routes
        if ($this->shouldSkipTracking($request)) {
            return null;
        }

        $session = $this->getOrCreateSession($request);
        
        $pageView = PageView::create([
            'visitor_session_id' => $session->id,
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'method' => $request->method(),
            'referrer' => $request->header('referer'),
            'user_agent' => $request->userAgent(),
            'device_type' => $this->getDeviceType(),
            'browser' => $this->agent->browser(),
            'platform' => $this->agent->platform(),
            'ip_address' => $request->ip(),
            'user_id' => auth()->id(),
        ]);

        // Update session
        $session->increment('page_views_count');
        $session->update(['last_activity_at' => now()]);

        return $pageView;
    }

    /**
     * Track a custom event
     */
    public function trackEvent(
        string $eventName,
        ?string $eventCategory = null,
        ?array $eventData = null,
        ?Request $request = null
    ): AnalyticsEvent {
        $request = $request ?? request();
        $session = $this->getOrCreateSession($request);

        return AnalyticsEvent::create([
            'visitor_session_id' => $session->id,
            'event_name' => $eventName,
            'event_category' => $eventCategory,
            'event_data' => $eventData,
            'url' => $request->fullUrl(),
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Get or create visitor session
     */
    protected function getOrCreateSession(Request $request): VisitorSession
    {
        $sessionId = $request->cookie('analytics_session_id') ?? Str::uuid()->toString();
        
        $session = VisitorSession::where('session_id', $sessionId)->first();

        if (!$session) {
            $this->agent->setUserAgent($request->userAgent());
            
            $session = VisitorSession::create([
                'session_id' => $sessionId,
                'user_agent' => $request->userAgent(),
                'device_type' => $this->getDeviceType(),
                'browser' => $this->agent->browser(),
                'platform' => $this->agent->platform(),
                'ip_address' => $request->ip(),
                'first_visit_at' => now(),
                'last_activity_at' => now(),
                'user_id' => auth()->id(),
            ]);

            // Set cookie for 30 days
            cookie()->queue('analytics_session_id', $sessionId, 60 * 24 * 30);
        }

        return $session;
    }

    /**
     * Get device type
     */
    protected function getDeviceType(): string
    {
        if ($this->agent->isMobile()) {
            return 'mobile';
        } elseif ($this->agent->isTablet()) {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }

    /**
     * Check if tracking should be skipped
     */
    protected function shouldSkipTracking(Request $request): bool
    {
        $path = $request->path();
        
        // Skip admin panel, API, and system routes
        $skipPaths = [
            'admin',
            'api',
            'livewire',
            '_ignition',
            'telescope',
            'horizon',
            'analytics/track', // Skip analytics tracking endpoint itself
        ];

        foreach ($skipPaths as $skipPath) {
            if (Str::startsWith($path, $skipPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get analytics summary
     */
    public function getSummary(string $period = '7days'): array
    {
        $startDate = $this->getStartDate($period);

        return [
            'total_page_views' => PageView::where('created_at', '>=', $startDate)->count(),
            'unique_visitors' => VisitorSession::where('created_at', '>=', $startDate)->count(),
            'total_events' => AnalyticsEvent::where('created_at', '>=', $startDate)->count(),
            'avg_session_duration' => VisitorSession::where('created_at', '>=', $startDate)
                ->avg('total_duration'),
            'device_breakdown' => PageView::where('created_at', '>=', $startDate)
                ->selectRaw('device_type, COUNT(*) as count')
                ->groupBy('device_type')
                ->pluck('count', 'device_type')
                ->toArray(),
            'browser_breakdown' => PageView::where('created_at', '>=', $startDate)
                ->selectRaw('browser, COUNT(*) as count')
                ->groupBy('browser')
                ->pluck('count', 'browser')
                ->toArray(),
            'top_pages' => PageView::where('created_at', '>=', $startDate)
                ->selectRaw('path, COUNT(*) as views')
                ->groupBy('path')
                ->orderByDesc('views')
                ->limit(10)
                ->get()
                ->toArray(),
        ];
    }

    /**
     * Get start date based on period
     */
    protected function getStartDate(string $period): \DateTime
    {
        return match($period) {
            'today' => now()->startOfDay(),
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            '90days' => now()->subDays(90),
            'year' => now()->subYear(),
            default => now()->subDays(7),
        };
    }
}
