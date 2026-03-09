<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Show form untuk request reset password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send reset link via email dengan rate limiting
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Rate Limiting: Max 3 requests per 10 menit
        $key = 'password-reset.' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            throw ValidationException::withMessages([
                'email' => "Too many password reset requests. Please try again in {$minutes} minutes."
            ]);
        }

        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'We could not find a user with that email address.'
        ]);

        // Generate token yang aman
        $token = Str::random(64);
        
        // Hapus token lama jika ada dan simpan token baru
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Increment rate limiter (expire dalam 10 menit)
        RateLimiter::hit($key, 600);

        // Generate reset link
        $resetLink = url("/reset-password/{$token}?email=" . urlencode($request->email));
        
        // Kirim email
        try {
            Mail::send('mail.reset-password', [
                'resetLink' => $resetLink,
                'email' => $request->email
            ], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Your Password');
            });

            return back()->with('status', 'We have emailed your password reset link! Please check your inbox.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email. Please try again later.']);
        }
    }

    /**
     * Show form untuk reset password
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password dengan validasi keamanan
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed'
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'We could not find a user with that email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.'
        ]);

        // Cek apakah token ada di database
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Cek apakah token expired (1 jam)
        if (now()->diffInMinutes($passwordReset->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This password reset token has expired. Please request a new one.']);
        }

        // Verify token hash
        if (!Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Update password user
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete semua token untuk email ini
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Logout semua device user (force re-login dengan password baru)
        DB::table('sessions')->where('user_id', $user->id)->delete();

        return redirect('/login')->with('success', 'Your password has been reset successfully! Please login with your new password.');
    }
}
