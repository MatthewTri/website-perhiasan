@extends('layout.account')

@section('title', 'Register')

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
            <h1 class="text-3xl font-serif text-gray-900 mb-3">Create Account</h1>
            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-medium">
                Join us today and start your journey
            </p>
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
            <div class="bg-[#E3CFCF]/10 border border-[#E3CFCF]/30 px-4 py-3 text-center">
                <p class="text-xs text-gray-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Register Form --}}
        <form class="space-y-6" action="{{ route('register.store') }}" method="POST">
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
                           placeholder="Minimum 6 characters">
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

            {{-- Confirm Password Field --}}
            <div>
                <label for="password_confirmation" class="block text-[10px] uppercase tracking-[0.2em] text-gray-500 font-medium mb-3">
                    Confirm Password
                </label>
                <div class="relative">
                    <input id="password_confirmation" 
                           name="password_confirmation" 
                           type="password" 
                           required 
                           class="appearance-none block w-full px-4 py-3 pr-12 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-200' }} text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#E3CFCF] focus:ring-1 focus:ring-[#E3CFCF] transition-colors"
                           placeholder="Confirm your password">
                    <button type="button" 
                            onclick="togglePassword('password_confirmation')"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                        <svg id="password_confirmation-eye" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" 
                    class="w-full bg-[#E3CFCF] text-gray-900 py-3 px-4 text-xs uppercase tracking-[0.2em] font-medium hover:bg-[#d4b8b8] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E3CFCF] transition-all duration-200">
                Create Account
            </button>

            {{-- Login Link --}}
            <div class="text-center pt-4">
                <p class="text-xs text-gray-500">
                    Already have an account?
                    <a href="/login" class="text-[#E3CFCF] hover:text-[#d4b8b8] font-medium transition-colors ml-1">
                        Sign in here
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

    // Auto-hide success alert
    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.querySelector('.bg-\\[\\#E3CFCF\\]\\/10');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }, 5000);
        }
    });

    // Real-time password matching validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');

    function validatePasswordMatch() {
        if (confirmPassword.value && password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
            confirmPassword.classList.add('border-red-300');
            confirmPassword.classList.remove('border-gray-200');
        } else {
            confirmPassword.setCustomValidity('');
            confirmPassword.classList.remove('border-red-300');
            confirmPassword.classList.add('border-gray-200');
        }
    }

    password.addEventListener('input', validatePasswordMatch);
    confirmPassword.addEventListener('input', validatePasswordMatch);
</script>
@endsection