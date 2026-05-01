# YouTube Live Streams to Sermons Sync Guide

This guide explains how to automatically import previous YouTube live streams as sermons in your church website.

## Overview

The system can pull previous live streams from your YouTube channel and automatically create sermon entries with:
- Video title
- Description
- YouTube URL
- Date preached (based on when the stream started)
- Automatic slug generation

## Setup

### 1. Get YouTube API Credentials

#### Get YouTube API Key:
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the **YouTube Data API v3**
4. Go to "Credentials" and create an API key
5. (Optional but recommended) Restrict the API key to YouTube Data API v3

#### Get YouTube Channel ID:
1. Go to your YouTube channel
2. Click on your profile picture → "Settings" → "Advanced settings"
3. Copy your Channel ID

OR

1. Go to your YouTube channel page
2. Look at the URL - it will be either:
   - `youtube.com/channel/YOUR_CHANNEL_ID` (copy the ID after `/channel/`)
   - `youtube.com/@YourChannelName` (you'll need to use a tool to get the ID)

### 2. Configure in Your Application

You have two options to configure the YouTube credentials:

#### Option A: Via Live Stream Settings (Recommended)
1. Log in to your admin panel
2. Go to **Live Stream** settings
3. Enter your **YouTube Channel ID**
4. Enter your **YouTube API Key**
5. Save the settings

#### Option B: Via Environment Variables
Add these to your `.env` file:
```env
YOUTUBE_API_KEY=your_api_key_here
YOUTUBE_CHANNEL_ID=your_channel_id_here
```

## Usage

### Testing Your Connection First (Recommended)

Before syncing, test your YouTube API configuration:

```bash
php artisan youtube:test-connection
```

This command will:
- Verify your API key and Channel ID are correct
- Show you a preview of your 5 most recent live streams
- Help diagnose any configuration issues

### Method 1: Via Admin Panel (Easiest)

1. Log in to your admin panel
2. Navigate to **Sermons**
3. Click the **"Sync from YouTube"** button in the top right
4. Confirm the sync operation
5. Wait for the process to complete
6. You'll see a notification showing how many sermons were imported

**Note:** Existing sermons won't be duplicated. The system checks for existing YouTube URLs before importing.

### Method 2: Via Command Line

Run the artisan command:

```bash
php artisan youtube:sync-sermons
```

#### Command Options:

```bash
# Sync with custom maximum results (default: 50)
php artisan youtube:sync-sermons --max-results=100

# Override channel ID and API key
php artisan youtube:sync-sermons --channel-id=YOUR_CHANNEL_ID --api-key=YOUR_API_KEY

# Set a default speaker name for imported sermons
php artisan youtube:sync-sermons --speaker="Pastor John Smith"
```

#### Example with all options:
```bash
php artisan youtube:sync-sermons --max-results=50 --speaker="Pastor John" --channel-id=UCxxxxx --api-key=AIzaxxxxx
```

## What Gets Imported

The sync process will:
- ✅ Import only videos that were **live streams** (not regular uploads)
- ✅ Use the actual stream start time as the "date preached"
- ✅ Include the video description (limited to 500 characters)
- ✅ Mark sermons as "imported from YouTube" for tracking
- ✅ Skip sermons that already exist (based on YouTube URL)
- ✅ Generate SEO-friendly slugs automatically
- ✅ Handle duplicate titles by appending the video ID to the slug

**Note:** If multiple videos have the same title (e.g., "SUNDAY 1ST SERVICE"), the system automatically creates unique slugs by appending the YouTube video ID (e.g., `sunday-1st-service-abc123`). This ensures all sermons can be imported without conflicts.

## After Import

After importing, you can:
1. Edit any sermon to update:
   - Speaker name (defaults to "Pastor")
   - Title
   - Description
   - Add audio URL if available
2. Delete any sermons you don't want to keep
3. Re-run the sync anytime to import new live streams

## Troubleshooting

### "Configuration Missing" Error
- Make sure you've entered both YouTube Channel ID and API Key in either:
  - Live Stream settings in admin panel, OR
  - `.env` file

### "No Live Streams Found" Warning
- Verify your Channel ID is correct
- Check that your channel has previous live streams (not just regular videos)
- Ensure your live streams are public or unlisted (not private)

### API Quota Limits
- YouTube API has daily quota limits (10,000 units per day by default)
- Each sync uses approximately 3-5 quota units per video
- If you hit the limit, wait 24 hours or request a quota increase from Google

### Permission Errors
- Ensure your API key is valid and not restricted
- Check that YouTube Data API v3 is enabled in your Google Cloud project

## Database Migration

Before using this feature, run the migration:

```bash
php artisan migrate
```

This adds the following fields to the sermons table:
- `youtube_video_id` - Stores the YouTube video ID
- `imported_from_youtube` - Boolean flag to track imported sermons

## Security Notes

- API keys are stored securely in the database (hidden from JSON output)
- Never commit your `.env` file with real API keys to version control
- Consider restricting your API key to specific domains or IP addresses
- Regularly rotate your API keys for security

## Scheduling Automatic Syncs (Optional)

To automatically sync new live streams daily, add this to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('youtube:sync-sermons --max-results=10')
             ->daily()
             ->at('02:00');
}
```

Then ensure your cron is configured:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Support

For issues or questions:
1. Check the Laravel logs: `storage/logs/laravel.log`
2. Verify your YouTube API credentials
3. Ensure your server can make outbound HTTPS requests to YouTube API
