# Analytics Integration Summary

## What Was Added

### 🎯 Core Analytics Features

1. **Automatic Page Tracking**
   - Every page visit is tracked automatically
   - Captures: device type, browser, platform, IP, referrer, duration
   - No manual implementation needed - works via middleware

2. **Visitor Session Management**
   - Tracks unique visitors across multiple page views
   - 30-day session cookies
   - Aggregates session metrics (total duration, page count)

3. **Custom Event Tracking**
   - API endpoint for tracking user interactions
   - Examples: video plays, downloads, form submissions
   - Flexible JSON data storage

### 📊 Admin Dashboard

Access at `/admin` → Analytics section:

1. **Analytics Overview Widget**
   - Total page views today
   - Unique visitors (7 days)
   - Total page views (30 days)
   - Average session duration

2. **Analytics Chart Widget**
   - Visual line chart of page views over time
   - Filters: Today, 7 days, 30 days, 90 days

3. **Page Views Resource**
   - Detailed table of all page views
   - Filterable by device, browser, date range
   - Shows user info, duration, IP address

### 🔒 Privacy & Compliance

1. **Cookie Consent Banner**
   - Appears on first visit
   - Accept/Decline options
   - Stored in localStorage
   - Blocks tracking until accepted

2. **Privacy Policy Page**
   - Route: `/privacy-policy`
   - Comprehensive data collection disclosure
   - Explains cookies, analytics, user rights
   - GDPR-compliant language

3. **Terms of Use Page**
   - Route: `/terms-of-use`
   - Legal terms for website usage
   - User responsibilities
   - Intellectual property rights

4. **Footer Links**
   - Privacy Policy and Terms of Use added to footer
   - Easy access for visitors

### 🛠️ Technical Implementation

**Backend:**
- 3 new models: `PageView`, `VisitorSession`, `AnalyticsEvent`
- `AnalyticsService` for centralized tracking logic
- `TrackPageView` middleware (auto-tracks all web routes)
- API endpoints for frontend tracking
- Filament resources and widgets for admin panel

**Frontend:**
- `CookieConsent` component (auto-displays on first visit)
- `useAnalytics` hook for custom event tracking
- `usePageDuration` hook for time-on-page tracking
- Privacy Policy and Terms of Use pages

**Dependencies Added:**
- `jenssegers/agent` - User agent parsing
- `flowframe/laravel-trend` - Analytics charts

## How to Use

### For Administrators

1. **View Analytics:**
   - Go to `/admin`
   - Click "Analytics" → "Page Views"
   - See widgets at the top for quick stats
   - Browse detailed page view data below

2. **Filter Data:**
   - Use device type filter (mobile/tablet/desktop)
   - Filter by browser
   - Set date range filters
   - Search by path or IP

### For Developers

**Track Custom Events (Frontend):**
```javascript
import { useAnalytics } from '@/hooks/useAnalytics';

function MyComponent() {
    const { trackEvent } = useAnalytics();
    
    const handleClick = () => {
        trackEvent('button_click', 'engagement', {
            button_name: 'donate',
            amount: 50
        });
    };
}
```

**Track Custom Events (Backend):**
```php
use App\Services\AnalyticsService;

$analytics = app(AnalyticsService::class);
$analytics->trackEvent('sermon_download', 'content', [
    'sermon_id' => 123,
    'format' => 'mp3'
]);
```

## What Visitors See

1. **First Visit:**
   - Cookie consent banner appears at bottom
   - Can accept or decline tracking
   - Choice is remembered

2. **Navigation:**
   - Privacy Policy link in footer
   - Terms of Use link in footer
   - No visible tracking indicators (runs in background)

3. **Data Collection:**
   - Only if cookies accepted
   - Anonymous session tracking
   - No personal data without consent

## Database Tables

- `page_views` - Individual page visits
- `visitor_sessions` - Grouped sessions
- `analytics_events` - Custom events

## API Endpoints

- `POST /api/analytics/track-event` - Track custom events
- `POST /api/analytics/update-duration` - Update page duration

## Files Changed/Added

**New Files (26):**
- Models: PageView, VisitorSession, AnalyticsEvent
- Controllers: PrivacyPolicyController, TermsOfUseController, Api/AnalyticsController
- Middleware: TrackPageView
- Services: AnalyticsService
- Filament: PageViewResource, AnalyticsOverview, AnalyticsChart
- React: PrivacyPolicy.jsx, TermsOfUse.jsx, CookieConsent.jsx
- Hooks: useAnalytics.js
- Migrations: 3 new tables
- Routes: api.php (new)
- Documentation: ANALYTICS_README.md

**Modified Files (5):**
- bootstrap/app.php (added middleware & API routes)
- routes/web.php (added privacy/terms routes)
- resources/js/Components/Layout.jsx (added cookie consent & footer links)
- composer.json (added dependencies)

## Next Steps

1. **Test the System:**
   - Visit the website and accept cookies
   - Browse different pages
   - Check `/admin` analytics dashboard

2. **Customize:**
   - Update contact info in Privacy Policy
   - Update contact info in Terms of Use
   - Adjust cookie consent text if needed

3. **Monitor:**
   - Check analytics daily/weekly
   - Look for popular pages
   - Identify device/browser trends

4. **Maintain:**
   - Consider adding data cleanup job (delete old data)
   - Export reports periodically
   - Review privacy policy annually

## Compliance Notes

✅ Cookie consent required before tracking
✅ Privacy policy explains data collection
✅ Terms of use covers legal requirements
✅ User can decline tracking
✅ Anonymous session tracking
✅ No personal data without consent

## Support

For detailed documentation, see `ANALYTICS_README.md`

---

**Commit:** `08e45bf` - Add comprehensive analytics system with privacy compliance
**Date:** May 4, 2026
