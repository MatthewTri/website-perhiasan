<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index() {
        return view('login.index');
    }

    public function authenticate(Request $r) {

        $credentials = $r->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.'
        ]);

        // Rate Limiting: Cegah brute force attack (max 5 attempts per menit)
        $key = 'login.' . $r->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds."
            ]);
        }

        // Remember Me: Check apakah checkbox remember dicentang
        $remember = $r->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Clear rate limiter jika login berhasil
            RateLimiter::clear($key);
            
            // Regenerate session untuk mencegah session fixation
            $r->session()->regenerate();
            
            return redirect()->intended('/');
        }

        // Increment login attempt jika gagal (expire dalam 60 detik)
        RateLimiter::hit($key, 60);

        return back()->with('loginError', 'Invalid email or password. Please try again.')->withInput($r->only('email'));
    }


    public function logout(Request $r) {

        Auth::logout();
        // auth()->logout();

        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect('/login');
    }
}
