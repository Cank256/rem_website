<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SermonController;
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

Route::get('/events', [EventController::class, 'index'])->name('events');

Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

Route::get('/live', function () {
    return Inertia::render('Live');
})->name('live');

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
});

require __DIR__.'/auth.php';
