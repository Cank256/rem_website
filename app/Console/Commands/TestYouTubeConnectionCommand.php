<?php

namespace App\Console\Commands;

use App\Models\LiveStream;
use App\Services\YouTubeService;
use Illuminate\Console\Command;

class TestYouTubeConnectionCommand extends Command
{
    protected $signature = 'youtube:test-connection 
                            {--channel-id= : YouTube Channel ID}
                            {--api-key= : YouTube API Key}';

    protected $description = 'Test YouTube API connection and display channel information';

    public function handle(): int
    {
        $this->info('Testing YouTube API connection...');
        $this->newLine();

        // Get credentials
        $channelId = $this->option('channel-id');
        $apiKey = $this->option('api-key');

        if (!$channelId || !$apiKey) {
            $liveStream = LiveStream::first();
            
            if ($liveStream) {
                $channelId = $channelId ?: $liveStream->youtube_channel_id;
                $apiKey = $apiKey ?: $liveStream->youtube_api_key;
            }
        }

        $channelId = $channelId ?: config('services.youtube.channel_id');
        $apiKey = $apiKey ?: config('services.youtube.api_key');

        if (!$channelId || !$apiKey) {
            $this->error('❌ YouTube Channel ID and API Key are required.');
            $this->newLine();
            $this->info('Please provide them via:');
            $this->info('  • Command options: --channel-id and --api-key');
            $this->info('  • LiveStream configuration in admin panel');
            $this->info('  • Environment variables: YOUTUBE_CHANNEL_ID and YOUTUBE_API_KEY');
            return self::FAILURE;
        }

        $this->info('Configuration found:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Channel ID', $channelId],
                ['API Key', substr($apiKey, 0, 10) . '...' . substr($apiKey, -5)],
            ]
        );
        $this->newLine();

        // Test connection
        $youtubeService = new YouTubeService($apiKey, $channelId);

        $this->info('Fetching live streams from YouTube...');
        
        try {
            $liveStreams = $youtubeService->getPreviousLiveStreams(5);

            if (empty($liveStreams)) {
                $this->warn('⚠️  No live streams found.');
                $this->info('This could mean:');
                $this->info('  • Your channel has no previous live streams');
                $this->info('  • The Channel ID is incorrect');
                $this->info('  • The API key doesn\'t have access');
                $this->info('  • Live streams are set to private');
                return self::SUCCESS;
            }

            $this->info('✅ Connection successful!');
            $this->newLine();
            $this->info('Found ' . count($liveStreams) . ' recent live stream(s):');
            $this->newLine();

            foreach ($liveStreams as $index => $stream) {
                $this->info(($index + 1) . '. ' . $stream['title']);
                $this->line('   Published: ' . \Carbon\Carbon::parse($stream['published_at'])->format('M d, Y'));
                $this->line('   URL: ' . $stream['youtube_url']);
                if ($stream['actual_start_time']) {
                    $this->line('   Streamed: ' . \Carbon\Carbon::parse($stream['actual_start_time'])->format('M d, Y H:i'));
                }
                $this->newLine();
            }

            $this->info('✨ Your YouTube API is configured correctly!');
            $this->info('You can now run: php artisan youtube:sync-sermons');

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Connection failed!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->info('Troubleshooting:');
            $this->info('  • Verify your API key is correct');
            $this->info('  • Ensure YouTube Data API v3 is enabled in Google Cloud Console');
            $this->info('  • Check that the Channel ID is correct');
            $this->info('  • Make sure your API key isn\'t restricted to specific IPs/domains');
            
            return self::FAILURE;
        }
    }
}
