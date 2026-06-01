# Getting Started Guide

## Overview

This is a complete church website built with Laravel 11, React 18, Inertia.js, and Filament v3 admin panel.

## Quick Setup (5 Minutes)

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/MariaDB

### Installation Steps

```bash
# 1. Navigate to project
cd church-website

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
DB_DATABASE=church_website
DB_USERNAME=root
DB_PASSWORD=your_password

# 5. Run migrations
php artisan migrate --seed

# 6. Create admin user
php artisan make:filament-user

# 7. Build assets
npm run build

# 8. Start server
php artisan serve
```

### Access Your Site
- **Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin

## Key Features

### Content Management (Filament Admin)
✅ **Sermons** - Manage sermons with YouTube/audio links
✅ **Events** - Schedule and manage church events  
✅ **Blog Posts** - Write and publish articles
✅ **Galleries** - Organize photos in albums
✅ **Users** - Role-based access control

### YouTube Integration
✅ **Live Streaming** - Embed YouTube live streams
✅ **Auto-Sync** - Import past live streams as sermons
✅ **Test Connection** - Verify API setup

### Analytics
✅ **Page Views** - Track visitor behavior
✅ **Session Tracking** - Monitor engagement
✅ **Privacy Compliant** - GDPR cookie consent
✅ **Dashboard Widgets** - Visual statistics

### Email System
✅ **Welcome Emails** - Automatic on registration
✅ **Password Resets** - Built-in recovery
✅ **Resend Integration** - Professional email delivery

## Configuration

### Update Church Name
Edit `.env`:
```env
APP_NAME="Your Church Name"
```

Edit `resources/js/Components/Layout.jsx` line 13:
```jsx
<Link href="/">Your Church Name</Link>
```

### YouTube Live Stream
1. Get your YouTube Channel ID from YouTube Studio
2. Update `resources/js/Pages/Live.jsx`:
```jsx
src="https://www.youtube.com/embed/live_stream?channel=YOUR_CHANNEL_ID&autoplay=1"
```

### Mobile Money (Give Page)
Edit `resources/js/Pages/Give.jsx` with your actual numbers.

### Email Configuration
1. Sign up at [Resend.com](https://resend.com)
2. Get API key
3. Update `.env`:
```env
MAIL_MAILER=resend
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
RESEND_API_KEY=your_api_key_here
```

## Adding Content

### Add a Sermon
1. Go to `/admin` → Sermons → New Sermon
2. Fill in: Title, Speaker, Date, YouTube URL
3. Save and publish

### Add an Event
1. Go to `/admin` → Events → New Event
2. Fill in: Title, Date/Time, Location, Description
3. Upload an image (optional)
4. Save

### Upload Gallery Images
1. Go to `/admin` → Galleries → New Gallery
2. Create a gallery (e.g., "Sunday Services")
3. Go to Gallery Images → New Gallery Image
4. Select gallery and upload images
5. Save

## YouTube Sermon Sync

### Setup
1. Get YouTube API Key from [Google Cloud Console](https://console.cloud.google.com/)
2. Get your YouTube Channel ID
3. Go to `/admin` → Live Stream
4. Enter Channel ID and API Key
5. Save

### Test Connection
```bash
php artisan youtube:test-connection
```

### Sync Sermons
**Option A: Admin Panel**
1. Go to `/admin` → Sermons
2. Click "Sync from YouTube" button
3. Confirm

**Option B: Command Line**
```bash
php artisan youtube:sync-sermons
```

## Common Commands

### Development
```bash
# Start dev server with hot reload
npm run dev

# Start Laravel server
php artisan serve
```

### Clear Caches
```bash
php artisan optimize:clear
```

### Build for Production
```bash
npm run build
```

## Troubleshooting

### 500 Error
```bash
chmod -R 755 storage bootstrap/cache
php artisan optimize:clear
```

### Assets Not Loading
```bash
npm run build
php artisan config:clear
```

### Images Not Showing
```bash
php artisan storage:link
chmod -R 775 storage
```

### Database Connection Error
Check `.env` database credentials.

## User Roles

### Admin
- Full access to everything
- Manage users, roles, permissions
- Manage all content

### Editor
- Manage content (sermons, events, blog, galleries)
- Cannot manage users or roles

### User
- Basic authenticated access
- No admin panel access

## Analytics Features

### Cookie Consent
- Appears on first visit
- Users can accept/decline tracking
- GDPR compliant

### View Analytics
1. Go to `/admin` → Analytics → Page Views
2. View widgets: Total views, unique visitors, session duration
3. Filter by device, browser, date range

### Privacy Pages
- Privacy Policy: `/privacy-policy`
- Terms of Use: `/terms-of-use`

## Next Steps

1. ✅ Customize church name and branding
2. ✅ Add real content through admin panel
3. ✅ Configure YouTube live streaming
4. ✅ Set up email integration
5. ✅ Test all features
6. ✅ Deploy to production (see DEPLOYMENT.md)

## Support Resources

- **Deployment**: See `DEPLOYMENT.md`
- **Troubleshooting**: See `TROUBLESHOOTING.md`
- **Commands**: See `COMMANDS.md`
- **Features**: See `FEATURES.md`

---

**Project Status**: ✅ Production Ready
**Laravel**: 11.x | **PHP**: 8.2+ | **Filament**: 3.x
