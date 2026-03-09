<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
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
     * Send reset link via email dengan rate limiting dan countdown resend
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi dulu SEBELUM rate limiting
        // Jadi kalau email salah/tidak ada, user bisa langsung coba lagi
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'We could not find a user with that email address.'
        ]);

        // Rate Limiting: 60 detik cooldown per email (hanya untuk email yang valid)
        $emailKey = 'password-reset-email.' . $request->email;
        $ipKey = 'password-reset-ip.' . $request->ip();
        
        // Check per-email cooldown (60 detik)
        if (RateLimiter::tooManyAttempts($emailKey, 1)) {
            $seconds = RateLimiter::availableIn($emailKey);
            
            // Return dengan email tetap di input dan timestamp untuk countdown
            return back()
                ->withInput(['email' => $request->email])
                ->with([
                    'resend_available_at' => now()->addSeconds($seconds)->timestamp
                ])
                ->withErrors([
                    'email' => "Please wait {$seconds} seconds before requesting another reset link."
                ]);
        }
        
        // Check per-IP rate limit (max 5 requests per 10 menit untuk prevent abuse)
        if (RateLimiter::tooManyAttempts($ipKey, 5)) {
            $minutes = ceil(RateLimiter::availableIn($ipKey) / 60);
            return back()
                ->withInput(['email' => $request->email])
                ->withErrors([
                    'email' => "Too many password reset attempts. Please try again in {$minutes} minutes."
                ]);
        }

        // Generate token yang aman
        $token = Str::random(64);
        
        // Hapus token lama (expire token sebelumnya) dan simpan token baru
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Increment rate limiters (HANYA setelah semua validasi pass)
        RateLimiter::hit($emailKey, 60); // 60 detik cooldown untuk email ini
        RateLimiter::hit($ipKey, 600);   // 10 menit untuk IP (max 5 attempts)

        // Generate reset link
        $resetLink = url("/reset-password/{$token}?email=" . urlencode($request->email));
        
        // Kirim email via queue (asynchronous)
        // Email akan dikirim di background oleh queue worker
        Mail::to($request->email)->queue(new ResetPasswordMail($resetLink, $request->email));

        // Return success immediately dengan email tetap di input
        // User akan langsung lihat countdown tanpa perlu tunggu email terkirim
        return back()
            ->withInput(['email' => $request->email])
            ->with([
                'status' => 'Password reset link has been sent! Please check your email inbox.',
                'resend_available_at' => now()->addSeconds(60)->timestamp
            ]);
    }

    /**
     * Show form untuk reset password
     */
    public function showResetForm(Request $request, $token)
    {
        // Validasi apakah token masih valid di database SEBELUM tampilkan form
        $email = $request->email;
        
        if (!$email) {
            return redirect('/forgot-password')->withErrors([
                'email' => 'Invalid password reset link. Please request a new one.'
            ]);
        }
        
        // Cek apakah token ada di database
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$passwordReset) {
            return redirect('/forgot-password')
                ->withInput(['email' => $email])
                ->withErrors([
                    'email' => 'This reset link is no longer valid. A new reset link may have been requested.'
                ]);
        }

        // Cek apakah token expired (1 jam)
        if (now()->diffInMinutes($passwordReset->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect('/forgot-password')
                ->withInput(['email' => $email])
                ->withErrors([
                    'email' => 'This password reset link has expired. Please request a new one.'
                ]);
        }

        // Verify token hash
        if (!Hash::check($token, $passwordReset->token)) {
            return redirect('/forgot-password')
                ->withInput(['email' => $email])
                ->withErrors([
                    'email' => 'Invalid reset link. This link may have been replaced by a newer request.'
                ]);
        }

        // Jika semua validasi pass, tampilkan form reset password
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
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
