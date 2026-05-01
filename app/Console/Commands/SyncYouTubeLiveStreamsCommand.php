<?php

namespace App\Console\Commands;

use App\Models\LiveStream;
use App\Models\Sermon;
use App\Services\YouTubeService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncYouTubeLiveStreamsCommand extends Command
{
    protected $signature = 'youtube:sync-sermons 
                            {--max-results=50 : Maximum number of videos to fetch}
                            {--channel-id= : YouTube Channel ID (overrides config)}
                            {--api-key= : YouTube API Key (overrides config)}
                            {--speaker= : Default speaker name for imported sermons}';

    protected $description = 'Sync previous YouTube live streams as sermons';

    public function handle(): int
    {
        $this->info('Starting YouTube live streams sync...');

        // Get configuration from options or LiveStream model or config
        $channelId = $this->option('channel-id');
        $apiKey = $this->option('api-key');

        // If not provided via options, try to get from LiveStream model
        if (!$channelId || !$apiKey) {
            $liveStream = LiveStream::first();
            
            if ($liveStream) {
                $channelId = $channelId ?: $liveStream->youtube_channel_id;
                $apiKey = $apiKey ?: $liveStream->youtube_api_key;
            }
        }

        // If still not available, try config
        $channelId = $channelId ?: config('services.youtube.channel_id');
        $apiKey = $apiKey ?: config('services.youtube.api_key');

        if (!$channelId || !$apiKey) {
            $this->error('YouTube Channel ID and API Key are required.');
            $this->info('Please provide them via:');
            $this->info('  1. Command options: --channel-id and --api-key');
            $this->info('  2. LiveStream configuration in the admin panel');
            $this->info('  3. Environment variables: YOUTUBE_CHANNEL_ID and YOUTUBE_API_KEY');
            return self::FAILURE;
        }

        $maxResults = (int) $this->option('max-results');
        $defaultSpeaker = $this->option('speaker') ?: 'Pastor';

        $youtubeService = new YouTubeService($apiKey, $channelId);

        $this->info("Fetching up to {$maxResults} videos from YouTube channel...");
        
        $liveStreams = $youtubeService->getPreviousLiveStreams($maxResults);

        if (empty($liveStreams)) {
            $this->warn('No live streams found or error occurred while fetching.');
            return self::SUCCESS;
        }

        $this->info('Found ' . count($liveStreams) . ' previous live streams.');

        $imported = 0;
        $skipped = 0;
        $errors = 0;

        $progressBar = $this->output->createProgressBar(count($liveStreams));
        $progressBar->start();

        foreach ($liveStreams as $stream) {
            try {
                // Check if sermon already exists with this YouTube URL
                $exists = Sermon::where('youtube_url', $stream['youtube_url'])->exists();

                if ($exists) {
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                // Create sermon from live stream
                $datePreached = $stream['actual_start_time'] 
                    ? \Carbon\Carbon::parse($stream['actual_start_time'])
                    : \Carbon\Carbon::parse($stream['published_at']);

                // Generate unique slug by appending video ID if slug already exists
                $baseSlug = Str::slug($stream['title']);
                $slug = $baseSlug;
                $counter = 1;
                
                while (Sermon::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $stream['video_id'];
                    if (Sermon::where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . $counter;
                        $counter++;
                    } else {
                        break;
                    }
                }

                Sermon::create([
                    'title' => $stream['title'],
                    'slug' => $slug,
                    'speaker_name' => $defaultSpeaker,
                    'date_preached' => $datePreached->toDateString(),
                    'youtube_url' => $stream['youtube_url'],
                    'youtube_video_id' => $stream['video_id'],
                    'imported_from_youtube' => true,
                    'description' => $stream['description'] ? Str::limit($stream['description'], 500) : null,
                ]);

                $imported++;

            } catch (\Exception $e) {
                $this->error("\nError importing: {$stream['title']}");
                $this->error($e->getMessage());
                $errors++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info('Sync completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Imported', $imported],
                ['Skipped (already exists)', $skipped],
                ['Errors', $errors],
            ]
        );

        return self::SUCCESS;
    }
}
