<?php

use App\Http\Controllers\Api\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::prefix('analytics')->group(function () {
    Route::post('/track-event', [AnalyticsController::class, 'trackEvent']);
    Route::post('/update-duration', [AnalyticsController::class, 'updateDuration']);
});
