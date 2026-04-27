# Live Stream Setup Guide

## Overview
Your church website now has a fully functional live streaming feature that integrates with YouTube. You can easily manage when the stream is live and display it on your website.

## How to Add a YouTube Live Stream

### Method 1: Manual Control (Recommended for Getting Started)

1. **Access the Admin Panel**
   - Go to your admin panel at `/admin`
   - Click on "Live Stream" in the sidebar (under Content section)

2. **Create or Edit a Live Stream**
   - Click "New Live Stream" or edit an existing one
   - Fill in the following:
     - **Title**: e.g., "Sunday Service Live"
     - **Description**: Optional description of the stream
     - **YouTube URL**: Paste your YouTube live stream URL
       - Format: `https://www.youtube.com/watch?v=VIDEO_ID`
       - Or: `https://youtu.be/VIDEO_ID`
       - The system will automatically extract the video ID

3. **Go Live**
   - Toggle "Stream is Live" to ON when you start streaming
   - Toggle it OFF when you finish
   - The live stream will immediately appear on your `/live` page

### Method 2: Using YouTube Channel ID (For Future Auto-Detection)

1. **Get Your YouTube Channel ID**
   - Go to your YouTube channel
   - Click on your profile picture → Settings → Advanced settings
   - Copy your Channel ID

2. **Add to Admin Panel**
   - In the Live Stream form, paste your Channel ID
   - Enable "Auto-detect Live Status"
   - This prepares your site for automatic detection (requires YouTube API setup)

## How It Works

### When Stream is LIVE:
- The `/live` page displays a full YouTube embed player
- Shows a red "LIVE" indicator with pulsing animation
- Displays stream title, description, and start time
- Viewers can watch, interact with chat, and use all YouTube features

### When Stream is OFFLINE:
- Shows a placeholder with "Stream Currently Offline" message
- Displays your service times
- Shows instructions on how to watch

## YouTube URL Formats Supported

The system automatically extracts video IDs from these formats:
- `https://www.youtube.com/watch?v=dQw4w9WgXcQ`
- `https://youtu.be/dQw4w9WgXcQ`
- `https://www.youtube.com/embed/dQw4w9WgXcQ`
- `https://www.youtube.com/v/dQw4w9WgXcQ`

## Quick Workflow for Sunday Services

1. **Before Service:**
   - Start your YouTube live stream
   - Copy the YouTube URL
   - Go to admin panel → Live Stream
   - Paste the URL
   - Toggle "Stream is Live" to ON
   - Save

2. **During Service:**
   - Your congregation can visit `/live` to watch
   - The stream plays automatically with full YouTube features

3. **After Service:**
   - Go back to admin panel → Live Stream
   - Toggle "Stream is Live" to OFF
   - Save

## Advanced: Automatic Live Detection (Optional)

To automatically detect when you go live on YouTube without manual toggling:

### Requirements:
- YouTube Data API v3 access
- Google Cloud Project with YouTube API enabled
- API Key or OAuth credentials

### Setup Steps:

1. **Enable YouTube API**
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Create a new project or select existing
   - Enable "YouTube Data API v3"
   - Create credentials (API Key)

2. **Add API Key to Environment**
   ```bash
   # Add to your .env file
   YOUTUBE_API_KEY=your_api_key_here
   ```

3. **Create a Scheduled Command**
   ```bash
   php artisan make:command CheckYouTubeLiveStatus
   ```

4. **Add the Command Code** (in `app/Console/Commands/CheckYouTubeLiveStatus.php`):
   ```php
   <?php

   namespace App\Console\Commands;

   use App\Models\LiveStream;
   use Illuminate\Console\Command;
   use Illuminate\Support\Facades\Http;

   class CheckYouTubeLiveStatus extends Command
   {
       protected $signature = 'youtube:check-live';
       protected $description = 'Check if YouTube channel is live';

       public function handle()
       {
           $liveStreams = LiveStream::where('auto_detect', true)
               ->whereNotNull('youtube_channel_id')
               ->get();

           foreach ($liveStreams as $stream) {
               $isLive = $this->checkIfChannelIsLive($stream->youtube_channel_id);
               
               if ($isLive !== $stream->is_live) {
                   $stream->update([
                       'is_live' => $isLive,
                       'stream_started_at' => $isLive ? now() : null,
                       'stream_ended_at' => !$isLive ? now() : null,
                   ]);
                   
                   $this->info("Updated {$stream->title}: " . ($isLive ? 'LIVE' : 'OFFLINE'));
               }
           }

           return 0;
       }

       private function checkIfChannelIsLive($channelId)
       {
           $apiKey = config('services.youtube.api_key');
           
           $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
               'part' => 'snippet',
               'channelId' => $channelId,
               'eventType' => 'live',
               'type' => 'video',
               'key' => $apiKey,
           ]);

           if ($response->successful()) {
               $data = $response->json();
               return !empty($data['items']);
           }

           return false;
       }
   }
   ```

5. **Add to config/services.php**:
   ```php
   'youtube' => [
       'api_key' => env('YOUTUBE_API_KEY'),
   ],
   ```

6. **Schedule the Command** (in `app/Console/Kernel.php`):
   ```php
   protected function schedule(Schedule $schedule)
   {
       // Check every 5 minutes during service times
       $schedule->command('youtube:check-live')
           ->everyFiveMinutes()
           ->between('7:00', '17:00')
           ->sundays();
       
       // Also check weekday evenings
       $schedule->command('youtube:check-live')
           ->everyFiveMinutes()
           ->between('17:00', '20:00')
           ->weekdays();
   }
   ```

7. **Start the Scheduler**:
   ```bash
   # Add to your crontab
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

## Troubleshooting

### Stream not showing?
- Check that "Stream is Live" is toggled ON in admin
- Verify the YouTube URL is correct
- Make sure the video is set to "Public" or "Unlisted" on YouTube

### Video ID not extracting?
- Try copying the URL directly from your browser's address bar
- Use the format: `https://www.youtube.com/watch?v=VIDEO_ID`

### Embed not loading?
- Check that the video allows embedding (YouTube settings)
- Verify your YouTube video is not set to "Private"
- Check browser console for any errors

## Tips for Best Experience

1. **Test Before Service**: Always test the stream before your actual service
2. **Use Unlisted**: Set your YouTube stream to "Unlisted" if you only want people with the link to watch
3. **Mobile Friendly**: The embed is fully responsive and works on all devices
4. **Chat Enabled**: YouTube chat is available in the embed for viewer interaction
5. **Archive**: After the stream ends, the video remains on YouTube for on-demand viewing

## Support

For issues or questions, check:
- YouTube's live streaming requirements
- Your YouTube channel's live streaming status
- Browser console for JavaScript errors
