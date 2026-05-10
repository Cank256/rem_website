<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    protected string $apiKey;
    protected string $channelId;
    protected string $baseUrl = 'https://www.googleapis.com/youtube/v3';

    public function __construct(?string $apiKey = null, ?string $channelId = null)
    {
        $this->apiKey = $apiKey ?? config('services.youtube.api_key');
        $this->channelId = $channelId ?? config('services.youtube.channel_id');
    }

    /**
     * Get previous live streams from the YouTube channel
     *
     * @param int $maxResults Maximum number of results to fetch (default: 50, max: 50)
     * @return array
     */
    public function getPreviousLiveStreams(int $maxResults = 50): array
    {
        if (empty($this->apiKey) || empty($this->channelId)) {
            Log::warning('YouTube API key or Channel ID not configured');
            return [];
        }

        try {
            // First, get the uploads playlist ID
            $channelResponse = Http::get("{$this->baseUrl}/channels", [
                'part' => 'contentDetails',
                'id' => $this->channelId,
                'key' => $this->apiKey,
            ]);

            if (!$channelResponse->successful()) {
                Log::error('Failed to fetch YouTube channel details', [
                    'status' => $channelResponse->status(),
                    'body' => $channelResponse->body(),
                ]);
                return [];
            }

            $channelData = $channelResponse->json();
            
            if (empty($channelData['items'])) {
                Log::warning('No channel found with the provided ID');
                return [];
            }

            $uploadsPlaylistId = $channelData['items'][0]['contentDetails']['relatedPlaylists']['uploads'] ?? null;

            if (!$uploadsPlaylistId) {
                Log::warning('No uploads playlist found for channel');
                return [];
            }

            // Get videos from the uploads playlist
            $playlistResponse = Http::get("{$this->baseUrl}/playlistItems", [
                'part' => 'snippet,contentDetails',
                'playlistId' => $uploadsPlaylistId,
                'maxResults' => $maxResults,
                'key' => $this->apiKey,
            ]);

            if (!$playlistResponse->successful()) {
                Log::error('Failed to fetch YouTube playlist items', [
                    'status' => $playlistResponse->status(),
                    'body' => $playlistResponse->body(),
                ]);
                return [];
            }

            $playlistData = $playlistResponse->json();
            $videoIds = collect($playlistData['items'] ?? [])
                ->pluck('contentDetails.videoId')
                ->filter()
                ->join(',');

            if (empty($videoIds)) {
                return [];
            }

            // Get detailed video information including live broadcast details
            $videosResponse = Http::get("{$this->baseUrl}/videos", [
                'part' => 'snippet,liveStreamingDetails',
                'id' => $videoIds,
                'key' => $this->apiKey,
            ]);

            if (!$videosResponse->successful()) {
                Log::error('Failed to fetch YouTube video details', [
                    'status' => $videosResponse->status(),
                    'body' => $videosResponse->body(),
                ]);
                return [];
            }

            $videosData = $videosResponse->json();

            // Filter only videos that were live streams
            $liveStreams = collect($videosData['items'] ?? [])
                ->filter(function ($video) {
                    // Check if video has live streaming details (was a live stream)
                    return isset($video['liveStreamingDetails']);
                })
                ->map(function ($video) {
                    $snippet = $video['snippet'];
                    $liveDetails = $video['liveStreamingDetails'];

                    return [
                        'video_id' => $video['id'],
                        'title' => $snippet['title'],
                        'description' => $snippet['description'] ?? '',
                        'published_at' => $snippet['publishedAt'],
                        'thumbnail_url' => $snippet['thumbnails']['high']['url'] ?? $snippet['thumbnails']['default']['url'] ?? null,
                        'youtube_url' => "https://www.youtube.com/watch?v={$video['id']}",
                        'actual_start_time' => $liveDetails['actualStartTime'] ?? null,
                        'actual_end_time' => $liveDetails['actualEndTime'] ?? null,
                    ];
                })
                ->values()
                ->toArray();

            return $liveStreams;

        } catch (\Exception $e) {
            Log::error('Error fetching YouTube live streams', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return [];
        }
    }

    /**
     * Check if the channel is currently live and return the active broadcast details.
     * Returns an array with video_id, title, description, youtube_url on success,
     * or null if the channel is not live or an error occurs.
     *
     * @return array|null
     */
    public function getActiveLiveBroadcast(): ?array
    {
        if (empty($this->apiKey) || empty($this->channelId)) {
            Log::warning('YouTube API key or Channel ID not configured');
            return null;
        }

        try {
            $response = Http::get("{$this->baseUrl}/search", [
                'part'      => 'id,snippet',
                'channelId' => $this->channelId,
                'eventType' => 'live',
                'type'      => 'video',
                'key'       => $this->apiKey,
            ]);

            if (!$response->successful()) {
                Log::error('YouTube live broadcast check failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            $data  = $response->json();
            $items = $data['items'] ?? [];

            if (empty($items)) {
                // No active broadcast found
                return null;
            }

            $item    = $items[0];
            $videoId = $item['id']['videoId'];
            $snippet = $item['snippet'];

            return [
                'video_id'    => $videoId,
                'title'       => $snippet['title'],
                'description' => $snippet['description'] ?? '',
                'youtube_url' => "https://www.youtube.com/watch?v={$videoId}",
            ];

        } catch (\Exception $e) {
            Log::error('Error checking YouTube live broadcast', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get a single video details
     *
     * @param string $videoId
     * @return array|null
     */
    public function getVideoDetails(string $videoId): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning('YouTube API key not configured');
            return null;
        }

        try {
            $response = Http::get("{$this->baseUrl}/videos", [
                'part' => 'snippet,liveStreamingDetails',
                'id' => $videoId,
                'key' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                Log::error('Failed to fetch YouTube video details', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            if (empty($data['items'])) {
                return null;
            }

            $video = $data['items'][0];
            $snippet = $video['snippet'];
            $liveDetails = $video['liveStreamingDetails'] ?? null;

            return [
                'video_id' => $video['id'],
                'title' => $snippet['title'],
                'description' => $snippet['description'] ?? '',
                'published_at' => $snippet['publishedAt'],
                'thumbnail_url' => $snippet['thumbnails']['high']['url'] ?? $snippet['thumbnails']['default']['url'] ?? null,
                'youtube_url' => "https://www.youtube.com/watch?v={$video['id']}",
                'actual_start_time' => $liveDetails['actualStartTime'] ?? null,
                'actual_end_time' => $liveDetails['actualEndTime'] ?? null,
                'is_live_stream' => isset($liveDetails),
            ];

        } catch (\Exception $e) {
            Log::error('Error fetching YouTube video details', [
                'message' => $e->getMessage(),
                'video_id' => $videoId,
            ]);
            return null;
        }
    }
}
