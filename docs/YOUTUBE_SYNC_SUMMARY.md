# YouTube to Sermons Sync - Implementation Summary

## What Was Added

### 1. YouTube Service (`app/Services/YouTubeService.php`)
A service class that handles all YouTube API interactions:
- Fetches previous live streams from a YouTube channel
- Filters only videos that were actual live streams
- Extracts video details including title, description, dates, and URLs
- Handles API errors gracefully with logging

### 2. Sync Command (`app/Console/Commands/SyncYouTubeLiveStreamsCommand.php`)
An artisan command to sync YouTube live streams to sermons:
```bash
php artisan youtube:sync-sermons
```
Features:
- Configurable max results (default: 50)
- Can override channel ID and API key via options
- Sets default speaker name
- Shows progress bar and summary statistics
- Prevents duplicate imports

### 3. Filament Admin Integration
Added a "Sync from YouTube" button in the Sermons admin panel:
- One-click sync directly from the admin interface
- Shows confirmation modal before syncing
- Displays success/error notifications
- Automatically prevents duplicates

### 4. Database Changes
New migration adds two fields to `sermons` table:
- `youtube_video_id` - Stores the YouTube video ID for reference
- `imported_from_youtube` - Boolean flag to track which sermons were auto-imported

### 5. Configuration
Added YouTube configuration to:
- `config/services.php` - Service configuration
- `.env.example` - Environment variable template

### 6. Documentation
- `YOUTUBE_SYNC_GUIDE.md` - Complete user guide with setup instructions
- `YOUTUBE_SYNC_SUMMARY.md` - This technical summary

## How It Works

1. **Configuration**: YouTube Channel ID and API Key are stored in either:
   - Live Stream settings (admin panel)
   - Environment variables (.env file)

2. **Fetching**: The YouTube service:
   - Gets the channel's uploads playlist
   - Fetches video details for all uploads
   - Filters only videos that have `liveStreamingDetails` (were live streams)

3. **Importing**: For each live stream:
   - Checks if it already exists (by YouTube URL)
   - Creates a new sermon with:
     - Title from video title
     - Auto-generated slug
     - Date preached from stream start time
     - YouTube URL and video ID
     - Description (truncated to 500 chars)
     - Default speaker name

4. **Tracking**: Imported sermons are marked with `imported_from_youtube = true`

## Usage Examples

### Via Admin Panel
1. Go to Sermons → Click "Sync from YouTube"
2. Confirm the action
3. Wait for completion notification

### Via Command Line
```bash
# Basic sync
php artisan youtube:sync-sermons

# With options
php artisan youtube:sync-sermons --max-results=100 --speaker="Pastor John"

# Override credentials
php artisan youtube:sync-sermons --channel-id=UCxxxxx --api-key=AIzaxxxxx
```

## API Quota Usage

Each sync operation uses approximately:
- 1 unit to fetch channel details
- 1 unit to fetch playlist items
- 1 unit to fetch video details

For 50 videos: ~3 units total (well within the 10,000 daily quota)

## Security Considerations

- API keys stored in database are hidden from JSON output
- Service uses Laravel's HTTP client with proper error handling
- All API calls are logged for debugging
- Credentials can be environment-based or database-based

## Future Enhancements (Optional)

Potential improvements you could add:
1. **Automatic scheduling**: Set up daily cron job to auto-sync
2. **Thumbnail import**: Download and store video thumbnails
3. **Category mapping**: Auto-assign sermon categories based on video tags
4. **Selective import**: UI to preview and select which videos to import
5. **Two-way sync**: Update sermon details if YouTube video is edited
6. **Playlist support**: Import from specific playlists instead of all live streams

## Testing

To test the implementation:

1. **Setup**:
   ```bash
   # Add to .env
   YOUTUBE_API_KEY=your_key
   YOUTUBE_CHANNEL_ID=your_channel_id
   ```

2. **Test via command**:
   ```bash
   php artisan youtube:sync-sermons --max-results=5
   ```

3. **Verify**:
   - Check sermons table for new entries
   - Verify `imported_from_youtube` flag is set
   - Check that YouTube URLs are correct
   - Confirm no duplicates on second run

4. **Test via admin**:
   - Login to admin panel
   - Go to Sermons
   - Click "Sync from YouTube"
   - Verify notification appears

## Files Modified/Created

### Created:
- `app/Services/YouTubeService.php`
- `app/Console/Commands/SyncYouTubeLiveStreamsCommand.php`
- `database/migrations/2026_05_01_075114_add_youtube_video_id_to_sermons_table.php`
- `YOUTUBE_SYNC_GUIDE.md`
- `YOUTUBE_SYNC_SUMMARY.md`

### Modified:
- `app/Models/Sermon.php` - Added new fillable fields and casts
- `app/Filament/Resources/SermonResource.php` - Added sync button and action
- `config/services.php` - Added YouTube configuration
- `.env.example` - Added YouTube environment variables

## Dependencies

No new Composer packages required! Uses:
- Laravel's built-in HTTP client
- Existing Filament components
- Standard Laravel features (commands, migrations, models)
