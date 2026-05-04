<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class FilamentPasswordResetController extends Controller
{
    /**
     * Handle the password reset link click.
     * Validates the token, logs in the user, and redirects to custom password reset page.
     */
    public function handleResetLink(Request $request, $token)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect('/admin/login')->with('error', 'Invalid password reset link.');
        }

        // Find the user
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect('/admin/login')->with('error', 'User not found.');
        }

        // Verify the token exists and is not expired
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            return redirect('/admin/login')->with('error', 'Invalid or expired password reset link.');
        }

        // Check if token matches
        if (!Hash::check($token, $resetRecord->token)) {
            return redirect('/admin/login')->with('error', 'Invalid password reset link.');
        }

        // Check if token is expired (60 minutes)
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            // Delete expired token
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect('/admin/login')->with('error', 'Password reset link has expired.');
        }

        // Token is valid - log in the user
        Auth::login($user);

        // Redirect to our custom set password page (no signed URLs, no Filament routing!)
        return redirect()->route('password.set', [
            'token' => $token,
            'email' => $email,
        ]);
    }
}
