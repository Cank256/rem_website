<?php

namespace App\Http\Controllers;

use App\Models\LiveStream;
use Inertia\Inertia;

class LiveStreamController extends Controller
{
    public function index()
    {
        $liveStream = LiveStream::getActive();

        return Inertia::render('Live', [
            'liveStream' => $liveStream ? [
                'id' => $liveStream->id,
                'title' => $liveStream->title,
                'description' => $liveStream->description,
                'youtube_url' => $liveStream->youtube_url,
                'youtube_video_id' => $liveStream->youtube_video_id,
                'embed_url' => $liveStream->embed_url,
                'is_live' => $liveStream->is_live,
                'stream_started_at' => $liveStream->stream_started_at?->format('M d, Y H:i'),
            ] : null,
        ]);
    }
}
