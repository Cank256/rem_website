<?php

namespace App\Http\Controllers;

use App\Models\Sermon;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SermonController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        
        $sermons = Sermon::orderBy('date_preached', 'desc')
            ->paginate($perPage);

        return Inertia::render('Sermons', [
            'sermons' => $sermons
        ]);
    }

    public function show(Sermon $sermon)
    {
        return Inertia::render('SermonDetail', [
            'sermon' => $sermon
        ]);
    }
}
