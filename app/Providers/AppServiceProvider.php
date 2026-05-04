<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Customize password reset URL to use Filament's admin panel
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return url('/admin/password-reset/reset?token=' . $token . '&email=' . urlencode($notifiable->getEmailForPasswordReset()));
        });
    }
}
