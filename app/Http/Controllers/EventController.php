<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        
        $events = Event::where('end_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->paginate($perPage);

        return Inertia::render('Events', [
            'events' => $events
        ]);
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        return Inertia::render('EventDetail', [
            'event' => $event
        ]);
    }
}
