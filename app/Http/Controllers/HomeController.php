<?php

namespace App\Http\Controllers;

use App\Models\Sermon;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Get 3 most recent sermons
        $recentSermons = Sermon::orderBy('date_preached', 'desc')
            ->take(3)
            ->get();

        // Get 3 upcoming events
        $upcomingEvents = Event::upcoming()
            ->take(3)
            ->get();

        return Inertia::render('Welcome', [
            'recentSermons' => $recentSermons,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}
