<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Password;

class SetPasswordController extends Controller
{
    /**
     * Show the set password form.
     */
    public function show(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        // Validate token
        if (!$this->validateToken($token, $email)) {
            return redirect('/admin/login')
                ->with('error', 'Invalid or expired password reset link.');
        }

        // Auto-login the user
        $user = User::where('email', $email)->first();
        if ($user && !Auth::check()) {
            Auth::login($user);
        }

        return view('auth.set-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Handle the password reset.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $token = $request->input('token');
        $email = $request->input('email');

        // Validate token again
        if (!$this->validateToken($token, $email)) {
            return back()->withErrors(['email' => 'Invalid or expired password reset link.']);
        }

        // Update password
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Delete the used token
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        // Log in the user
        Auth::login($user);

        return redirect('/admin')->with('success', 'Password set successfully!');
    }

    /**
     * Validate the password reset token.
     */
    protected function validateToken(?string $token, ?string $email): bool
    {
        if (empty($token) || empty($email)) {
            return false;
        }

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            return false;
        }

        // Check if token matches
        if (!Hash::check($token, $resetRecord->token)) {
            return false;
        }

        // Check if token is expired (60 minutes)
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return false;
        }

        return true;
    }
}
