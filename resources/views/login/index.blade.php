@extends('layout.account')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-10">
        
        {{-- Logo --}}
        <div class="flex justify-center">
            <a href="/" class="block">
                <img src="{{ asset('asset/img/logo.png') }}" alt="Cecile Private Collection" class="h-16 w-auto hover:opacity-80 transition-opacity">
            </a>
        </div>
        
        {{-- Header --}}
        <div class="text-center">
            <h1 class="text-3xl font-serif text-gray-900 mb-3">Welcome Back</h1>
            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-medium">
                Sign in to your account
            </p>
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
            <div class="bg-[#E3CFCF]/10 border border-[#E3CFCF]/30 px-4 py-3 text-center">
                <p class="text-xs text-gray-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Error Alert --}}
        @if(session('loginError'))
            <div class="bg-red-50 border border-red-200 px-4 py-3 text-center">
                <p class="text-xs text-red-700">{{ session('loginError') }}</p>
            </div>
        @endif

        {{-- Login Form --}}
        <form class="space-y-6" action="{{ route('login.authenticate') }}" method="POST">
            @csrf
            
            {{-- Email Field --}}
            <div>
                <label for="email" class="block text-[10px] uppercase tracking-[0.2em] text-gray-500 font-medium mb-3">
                    Email Address
                </label>
                <input id="email" 
                       name="email" 
                       type="email" 
                       value="{{ old('email') }}"
                       required 
                       class="appearance-none block w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-300' : 'border-gray-200' }} text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#E3CFCF] focus:ring-1 focus:ring-[#E3CFCF] transition-colors"
                       placeholder="your.email@example.com">
                @error('email')
                    <p class="text-[10px] text-red-600 mt-2 tracking-wide">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div>
                <label for="password" class="block text-[10px] uppercase tracking-[0.2em] text-gray-500 font-medium mb-3">
                    Password
                </label>
                <div class="relative">
                    <input id="password" 
                           name="password" 
                           type="password" 
                           required 
                           class="appearance-none block w-full px-4 py-3 pr-12 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-200' }} text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#E3CFCF] focus:ring-1 focus:ring-[#E3CFCF] transition-colors"
                           placeholder="Enter your password">
                    <button type="button" 
                            onclick="togglePassword('password')"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                        <svg id="password-eye" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-[10px] text-red-600 mt-2 tracking-wide">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me & Forgot Password --}}
            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center cursor-pointer">
                    <input id="remember" 
                           name="remember" 
                           type="checkbox" 
                           class="h-4 w-4 text-[#E3CFCF] focus:ring-[#E3CFCF] border-gray-300 cursor-pointer">
                    <span class="ml-2 text-xs text-gray-600">Remember me</span>
                </label>

                <a href="{{ route('password.request') }}" class="text-xs text-gray-500 hover:text-[#E3CFCF] transition-colors">
                    Forgot password?
                </a>
            </div>

            {{-- Submit Button --}}
            <button type="submit" 
                    class="w-full bg-[#E3CFCF] text-gray-900 py-3 px-4 text-xs uppercase tracking-[0.2em] font-medium hover:bg-[#d4b8b8] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E3CFCF] transition-all duration-200">
                Sign In
            </button>

            {{-- Register Link --}}
            <div class="text-center pt-4">
                <p class="text-xs text-gray-500">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-[#E3CFCF] hover:text-[#d4b8b8] font-medium transition-colors ml-1">
                        Create one here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(fieldId + '-eye');
        
        if (field.type === 'password') {
            field.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
            `;
        } else {
            field.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            `;
        }
    }

    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.bg-\\[\\#E3CFCF\\]\\/10, .bg-red-50');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });
</script>
@endsection