<?php

namespace App\Console\Commands;

use App\Models\LiveStream;
use App\Services\YouTubeService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckYouTubeLiveStatusCommand extends Command
{
    protected $signature = 'youtube:check-live
                            {--force : Run even if auto_detect is disabled}';

    protected $description = 'Check YouTube channel for an active live broadcast and update the live stream status';

    public function handle(): int
    {
        $liveStream = LiveStream::first();

        if (!$liveStream) {
            $this->warn('No live stream record found. Create one in the admin panel first.');
            return self::FAILURE;
        }

        if (!$liveStream->auto_detect && !$this->option('force')) {
            $this->info('Auto-detect is disabled for this live stream. Skipping.');
            return self::SUCCESS;
        }

        $apiKey    = $liveStream->youtube_api_key;
        $channelId = $liveStream->youtube_channel_id;

        if (!$apiKey || !$channelId) {
            $this->error('YouTube API key and Channel ID must be set in the Live Stream admin panel.');
            Log::error('youtube:check-live — missing API key or channel ID in live_streams record.');
            return self::FAILURE;
        }

        $this->info('Checking YouTube channel for active live broadcast...');

        $youtube   = new YouTubeService($apiKey, $channelId);
        $broadcast = $youtube->getActiveLiveBroadcast();

        if ($broadcast) {
            // Channel is live
            if (!$liveStream->is_live) {
                // Transition: offline → live
                $liveStream->update([
                    'is_live'           => true,
                    'youtube_url'       => $broadcast['youtube_url'],
                    'youtube_video_id'  => $broadcast['video_id'],
                    'title'             => $broadcast['title'] ?: $liveStream->title,
                    'description'       => $broadcast['description'] ?: $liveStream->description,
                    'stream_started_at' => Carbon::now(),
                    'stream_ended_at'   => null,
                ]);

                $this->info("🔴 Channel is LIVE — updated record (video: {$broadcast['video_id']}).");
                Log::info('youtube:check-live — stream went live.', ['video_id' => $broadcast['video_id']]);
            } else {
                $this->info("🔴 Channel is still LIVE (video: {$liveStream->youtube_video_id}). No change needed.");
            }
        } else {
            // Channel is not live
            if ($liveStream->is_live) {
                // Transition: live → offline
                $liveStream->update([
                    'is_live'         => false,
                    'stream_ended_at' => Carbon::now(),
                ]);

                $this->info('⚫ Channel is now OFFLINE — marked stream as ended.');
                Log::info('youtube:check-live — stream ended.');
            } else {
                $this->info('⚫ Channel is OFFLINE. No change needed.');
            }
        }

        return self::SUCCESS;
    }
}
