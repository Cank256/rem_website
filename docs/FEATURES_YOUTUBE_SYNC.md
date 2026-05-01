# YouTube Live Streams to Sermons - Feature Documentation

## 🎯 Feature Overview

This feature automatically imports previous YouTube live streams from your church's YouTube channel and creates sermon entries in your website. It eliminates manual data entry and keeps your sermon archive synchronized with your YouTube content.

## ✨ Key Features

### 1. **Automatic Import**
- Pulls all previous live streams from your YouTube channel
- Filters out regular video uploads (only imports actual live streams)
- Extracts video title, description, URL, and streaming date
- Creates properly formatted sermon entries

### 2. **Smart Duplicate Prevention**
- Checks existing sermons before importing
- Won't create duplicates if you run the sync multiple times
- Safe to run as often as needed

### 3. **Multiple Access Methods**
- **Admin Panel**: One-click sync button with visual feedback
- **Command Line**: Artisan command for automation and scheduling
- **Flexible Configuration**: Store credentials in database or environment variables

### 4. **Comprehensive Tracking**
- Marks imported sermons with `imported_from_youtube` flag
- Stores YouTube video ID for reference
- Maintains link to original YouTube video

### 5. **User-Friendly**
- Progress indicators and notifications
- Detailed error messages and troubleshooting
- Test command to verify configuration before syncing

## 📦 What's Included

### Core Components

1. **YouTubeService** (`app/Services/YouTubeService.php`)
   - Handles all YouTube API interactions
   - Fetches channel information and video details
   - Filters live streams from regular uploads
   - Robust error handling and logging

2. **Sync Command** (`app/Console/Commands/SyncYouTubeLiveStreamsCommand.php`)
   - CLI command: `php artisan youtube:sync-sermons`
   - Configurable options (max results, speaker name, credentials)
   - Progress bar and detailed statistics
   - Can be scheduled for automatic execution

3. **Test Command** (`app/Console/Commands/TestYouTubeConnectionCommand.php`)
   - CLI command: `php artisan youtube:test-connection`
   - Verifies API credentials
   - Shows preview of available live streams
   - Helps diagnose configuration issues

4. **Admin Panel Integration**
   - "Sync from YouTube" button in Sermons list
   - Confirmation modal before syncing
   - Real-time notifications (success/error/warning)
   - Seamless user experience

5. **Database Schema**
   - New fields: `youtube_video_id`, `imported_from_youtube`
   - Migration included and already run
   - Backward compatible with existing sermons

### Documentation

1. **QUICK_START_YOUTUBE_SYNC.md** - Get started in 3 steps
2. **YOUTUBE_SYNC_GUIDE.md** - Complete user guide with troubleshooting
3. **YOUTUBE_SYNC_SUMMARY.md** - Technical implementation details
4. **FEATURES_YOUTUBE_SYNC.md** - This file (feature overview)

## 🚀 Quick Start

### For End Users (Church Staff)

1. **Get YouTube credentials** (5 min)
   - YouTube API Key from Google Cloud Console
   - YouTube Channel ID from your channel settings

2. **Configure in admin panel** (2 min)
   - Go to Live Stream settings
   - Enter Channel ID and API Key
   - Save

3. **Sync sermons** (1 min)
   - Go to Sermons
   - Click "Sync from YouTube"
   - Confirm and wait

**See:** `QUICK_START_YOUTUBE_SYNC.md` for detailed steps

### For Developers

```bash
# Test the connection
php artisan youtube:test-connection

# Sync sermons
php artisan youtube:sync-sermons

# Sync with options
php artisan youtube:sync-sermons --max-results=100 --speaker="Pastor John"

# Schedule automatic daily syncs (add to app/Console/Kernel.php)
$schedule->command('youtube:sync-sermons')->daily();
```

**See:** `YOUTUBE_SYNC_GUIDE.md` for complete documentation

## 🔧 Configuration Options

### Option 1: Database Configuration (Recommended)
Store credentials in Live Stream settings via admin panel:
- More user-friendly
- Can be changed without code deployment
- Accessible to non-technical staff

### Option 2: Environment Variables
Add to `.env` file:
```env
YOUTUBE_API_KEY=your_api_key_here
YOUTUBE_CHANNEL_ID=your_channel_id_here
```

### Option 3: Command Line Override
Pass credentials directly to command:
```bash
php artisan youtube:sync-sermons --channel-id=UCxxxxx --api-key=AIzaxxxxx
```

## 📊 What Gets Imported

For each YouTube live stream, the system imports:

| Field | Source | Notes |
|-------|--------|-------|
| Title | Video title | Used as-is |
| Slug | Auto-generated | From title, SEO-friendly |
| Speaker Name | Default/configurable | Defaults to "Pastor" |
| Date Preached | Stream start time | Falls back to publish date |
| YouTube URL | Video URL | Full watch URL |
| YouTube Video ID | Extracted from URL | For API reference |
| Description | Video description | Truncated to 500 chars |
| Imported Flag | Set to true | Tracks auto-imported sermons |

## 🎨 User Interface

### Admin Panel Button
- **Location**: Sermons list page, top right
- **Icon**: Refresh/sync icon (heroicon-o-arrow-path)
- **Color**: Green (success color)
- **Behavior**: 
  - Shows confirmation modal
  - Displays progress during sync
  - Shows notification with results

### Command Line Output
```
Starting YouTube live streams sync...
Fetching up to 50 videos from YouTube channel...
Found 25 previous live streams.
 25/25 [============================] 100%

Sync completed!
+---------------------------+-------+
| Status                    | Count |
+---------------------------+-------+
| Imported                  | 23    |
| Skipped (already exists)  | 2     |
| Errors                    | 0     |
+---------------------------+-------+
```

## 🔒 Security Features

1. **API Key Protection**
   - Stored securely in database
   - Hidden from JSON output
   - Never exposed in frontend

2. **Error Handling**
   - All API calls wrapped in try-catch
   - Errors logged to Laravel log
   - User-friendly error messages

3. **Input Validation**
   - URL validation for YouTube links
   - Slug uniqueness checks
   - Duplicate prevention

4. **Best Practices**
   - Uses Laravel's HTTP client
   - Follows Laravel conventions
   - Proper database transactions

## 📈 API Quota Management

YouTube API has daily quotas (10,000 units/day by default):

| Operation | Quota Cost | Notes |
|-----------|------------|-------|
| Fetch channel | 1 unit | Once per sync |
| Fetch playlist | 1 unit | Once per sync |
| Fetch video details | 1 unit | Once per sync (batch) |
| **Total per sync** | **~3 units** | For up to 50 videos |

**You can sync ~3,300 times per day** before hitting quota limits.

## 🔄 Workflow Examples

### Weekly Manual Sync
1. Every Monday, admin logs in
2. Goes to Sermons
3. Clicks "Sync from YouTube"
4. Reviews new sermons and updates speaker names

### Automated Daily Sync
```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('youtube:sync-sermons --max-results=10')
             ->daily()
             ->at('02:00');
}
```

### One-Time Bulk Import
```bash
# Import all available live streams (up to 50)
php artisan youtube:sync-sermons --max-results=50

# Then edit sermons in admin panel to update details
```

## 🐛 Troubleshooting

### Common Issues

| Issue | Cause | Solution |
|-------|-------|----------|
| "Configuration Missing" | No API credentials | Configure in Live Stream settings |
| "No Live Streams Found" | No live streams or wrong channel ID | Verify channel ID and stream visibility |
| "API Quota Exceeded" | Too many API calls | Wait 24 hours or request quota increase |
| Duplicates not prevented | Different YouTube URLs | Check URL format consistency |

### Debug Commands

```bash
# Test connection
php artisan youtube:test-connection

# Check logs
tail -f storage/logs/laravel.log

# Verify configuration
php artisan tinker
>>> config('services.youtube')
>>> App\Models\LiveStream::first()
```

## 🎯 Use Cases

### 1. Initial Setup
Import entire sermon archive from YouTube in one go:
```bash
php artisan youtube:sync-sermons --max-results=50 --speaker="Pastor Smith"
```

### 2. Regular Maintenance
Weekly sync to catch new live streams:
- Use admin panel button
- Quick and easy for staff

### 3. Automated Sync
Set up daily cron job:
- Runs automatically
- No manual intervention needed
- Always up-to-date

### 4. Migration
Moving from another platform:
- Import all YouTube content
- Edit details as needed
- Maintain YouTube links

## 🚦 Status & Compatibility

- ✅ **Status**: Fully implemented and tested
- ✅ **Laravel Version**: Compatible with Laravel 11.x
- ✅ **Filament Version**: Compatible with Filament 3.x
- ✅ **PHP Version**: Requires PHP 8.1+
- ✅ **Database**: Works with SQLite, MySQL, PostgreSQL
- ✅ **Dependencies**: No additional packages required

## 📝 Future Enhancements

Potential improvements for future versions:

1. **Thumbnail Import**
   - Download and store video thumbnails
   - Display in sermon listings

2. **Category Mapping**
   - Map YouTube tags to sermon categories
   - Auto-categorize imported sermons

3. **Selective Import**
   - UI to preview videos before importing
   - Checkbox selection for specific videos

4. **Two-Way Sync**
   - Update sermon if YouTube video is edited
   - Sync view counts and engagement metrics

5. **Playlist Support**
   - Import from specific playlists
   - Multiple playlist sources

6. **Advanced Filtering**
   - Date range selection
   - Title pattern matching
   - Duration filters

## 📞 Support

For questions or issues:

1. **Check documentation**:
   - `QUICK_START_YOUTUBE_SYNC.md` - Getting started
   - `YOUTUBE_SYNC_GUIDE.md` - Complete guide
   - `YOUTUBE_SYNC_SUMMARY.md` - Technical details

2. **Test your setup**:
   ```bash
   php artisan youtube:test-connection
   ```

3. **Check logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Verify configuration**:
   - Admin panel → Live Stream settings
   - Check `.env` file for environment variables

## 🎉 Success Metrics

After implementation, you should be able to:

- ✅ Import 50+ sermons in under 30 seconds
- ✅ Sync new live streams with one click
- ✅ Prevent duplicate sermon entries
- ✅ Track which sermons came from YouTube
- ✅ Maintain links to original YouTube videos
- ✅ Automate the entire process with scheduling

---

**Ready to get started?** See `QUICK_START_YOUTUBE_SYNC.md` for step-by-step instructions!
