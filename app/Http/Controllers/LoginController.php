<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if (Auth::attempt($credentials)) {
            $r->session()->regenerate();
            return redirect()->intended('/');
        }


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
