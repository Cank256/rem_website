<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SermonController;
use App\Http\Controllers\LiveStreamController;
use App\Http\Controllers\SetupController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Pages
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/ministries', function () {
    return Inertia::render('Ministries');
})->name('ministries');

Route::get('/sermons', [SermonController::class, 'index'])->name('sermons');

Route::get('/sermons/{sermon:slug}', [SermonController::class, 'show'])->name('sermons.show');

Route::get('/events', [EventController::class, 'index'])->name('events');

Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

Route::get('/live', [LiveStreamController::class, 'index'])->name('live');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::get('/give', function () {
    return Inertia::render('Give');
})->name('give');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Setup / maintenance panel
    Route::get('/setup', [SetupController::class, 'index'])->name('setup.index');
    Route::post('/setup/run', [SetupController::class, 'run'])->name('setup.run');
});

require __DIR__.'/auth.php';
