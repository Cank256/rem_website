# Analytics System Documentation

## Overview

This church website now includes a comprehensive analytics system that tracks visitor behavior, page views, and custom events. The system is GDPR-compliant with cookie consent and includes privacy policy and terms of use pages.

## Features

### 1. Automatic Page View Tracking
- Every page visit is automatically tracked via middleware
- Captures device type, browser, platform, IP address, and more
- Tracks session duration and page-specific metrics

### 2. Visitor Session Management
- Unique session tracking across multiple page views
- Session duration calculation
- Device and browser fingerprinting

### 3. Custom Event Tracking
- Track specific user interactions (video plays, downloads, form submissions, etc.)
- Flexible event data structure
- API endpoint for frontend event tracking

### 4. Admin Dashboard Analytics
- **Analytics Overview Widget**: Real-time statistics including:
  - Total page views (today)
  - Unique visitors (7 days)
  - Total page views (30 days)
  - Average session duration
  
- **Analytics Chart Widget**: Visual representation of page views over time with filters:
  - Today
  - Last 7 days
  - Last 30 days
  - Last 90 days

- **Page Views Resource**: Detailed table view with:
  - Path and URL tracking
  - Device type breakdown
  - Browser and platform information
  - User identification (authenticated vs guest)
  - Advanced filtering options

### 5. Privacy & Compliance
- **Cookie Consent Banner**: Appears on first visit, allows users to accept or decline tracking
- **Privacy Policy Page**: Comprehensive privacy policy explaining data collection
- **Terms of Use Page**: Legal terms for website usage
- Links in footer for easy access

## Database Schema

### `page_views` Table
- Tracks individual page visits
- Links to visitor sessions and users
- Stores device, browser, and location data
- Records page duration

### `visitor_sessions` Table
- Groups page views into sessions
- Tracks session metrics (duration, page count)
- Stores visitor device information

### `analytics_events` Table
- Custom event tracking
- Flexible JSON data storage
- Links to sessions and page views

## Usage

### Backend (Laravel)

#### Track Custom Events
```php
use App\Services\AnalyticsService;

$analytics = app(AnalyticsService::class);

// Track a custom event
$analytics->trackEvent(
    eventName: 'video_play',
    eventCategory: 'engagement',
    eventData: ['video_id' => 123, 'duration' => 300]
);
```

#### Get Analytics Summary
```php
$analytics = app(AnalyticsService::class);
$summary = $analytics->getSummary('7days'); // or 'today', '30days', '90days', 'year'
```

### Frontend (React)

#### Track Custom Events
```javascript
import { useAnalytics } from '@/hooks/useAnalytics';

function MyComponent() {
    const { trackEvent } = useAnalytics();
    
    const handleVideoPlay = () => {
        trackEvent('video_play', 'engagement', {
            video_id: 123,
            video_title: 'Sunday Sermon'
        });
    };
    
    return <button onClick={handleVideoPlay}>Play Video</button>;
}
```

#### Track Page Duration
```javascript
import { usePageDuration } from '@/hooks/useAnalytics';

function MyPage({ pageViewId }) {
    // Automatically tracks how long user stays on page
    usePageDuration(pageViewId);
    
    return <div>Page content</div>;
}
```

## API Endpoints

### POST `/api/analytics/track-event`
Track a custom event from the frontend.

**Request Body:**
```json
{
    "event_name": "video_play",
    "event_category": "engagement",
    "event_data": {
        "video_id": 123,
        "duration": 300
    }
}
```

### POST `/api/analytics/update-duration`
Update the duration a user spent on a page.

**Request Body:**
```json
{
    "page_view_id": 1,
    "duration": 120
}
```

## Admin Panel Access

1. Navigate to `/admin` and log in
2. Look for the "Analytics" section in the sidebar
3. Click "Page Views" to see detailed analytics
4. View the dashboard widgets at the top for quick insights

## Privacy Compliance

### Cookie Consent
- Users must accept cookies before analytics tracking begins
- Consent is stored in localStorage
- Users can decline tracking

### Data Collection
The system collects:
- Page URLs and paths
- Device type, browser, and platform
- IP addresses (can be anonymized if needed)
- Session duration and page view counts
- Custom event data

### User Rights
Users can request:
- Access to their data
- Deletion of their data
- Opt-out of tracking

## Configuration

### Disable Tracking for Specific Routes
Edit `app/Services/AnalyticsService.php` and add paths to the `$skipPaths` array:

```php
$skipPaths = [
    'admin',
    'api',
    'your-custom-path',
];
```

### Customize Session Duration
Sessions are tracked via cookies with a 30-day expiration. To change this, edit the cookie duration in `AnalyticsService.php`:

```php
cookie()->queue('analytics_session_id', $sessionId, 60 * 24 * 30); // 30 days
```

## Maintenance

### Clean Old Analytics Data
Consider creating a scheduled task to clean old analytics data:

```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Delete analytics data older than 90 days
    $schedule->call(function () {
        \App\Models\PageView::where('created_at', '<', now()->subDays(90))->delete();
        \App\Models\VisitorSession::where('created_at', '<', now()->subDays(90))->delete();
        \App\Models\AnalyticsEvent::where('created_at', '<', now()->subDays(90))->delete();
    })->daily();
}
```

## Dependencies

- **jenssegers/agent**: User agent parsing
- **flowframe/laravel-trend**: Analytics charting
- **filament/filament**: Admin panel

## Future Enhancements

Consider adding:
- Geographic location tracking (country/city)
- Referrer analysis
- Conversion tracking
- A/B testing capabilities
- Export analytics data to CSV/PDF
- Email reports for administrators
- Real-time analytics dashboard
- Heatmap tracking
- Funnel analysis

## Support

For questions or issues with the analytics system, please contact the development team or refer to the Laravel and Filament documentation.
