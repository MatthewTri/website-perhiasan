@extends('layout.account')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-10">
        
        {{-- Logo --}}
        <div class="flex justify-center">
            <a href="/" class="block">
                <img src="{{ asset('img/logo.png') }}" alt="Cecile Private Collection" class="h-16 w-auto hover:opacity-80 transition-opacity">
            </a>
        </div>
        
        {{-- Header --}}
        <div class="text-center">
            <h1 class="text-3xl font-serif text-gray-900 mb-3">Reset Password</h1>
            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-medium">
                Create your new password below
            </p>
        </div>

        <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            
            {{-- Hidden fields --}}
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            {{-- Email Display (Read-only) --}}
            <div>
                <label for="email_display" class="block text-[10px] uppercase tracking-[0.2em] text-gray-500 font-medium mb-3">
                    Email Address
                </label>
                <input type="email" 
                       value="{{ $email }}"
                       readonly
                       class="appearance-none block w-full px-4 py-3 border border-gray-200 bg-gray-50 text-gray-600 text-sm focus:outline-none cursor-not-allowed">
            </div>

            {{-- Password Field --}}
            <div>
                <label for="password" class="block text-[10px] uppercase tracking-[0.2em] text-gray-500 font-medium mb-3">
                    New Password
                </label>
                <div class="relative">
                    <input id="password" 
                           name="password" 
                           type="password" 
                           required 
                           class="appearance-none block w-full px-4 py-3 pr-10 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-200' }} text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#E3CFCF] focus:ring-1 focus:ring-[#E3CFCF] transition-colors"
                           placeholder="Enter your new password"
                           autofocus>
                    
                    {{-- Toggle Password Visibility --}}
                    <button type="button" 
                            onclick="togglePassword('password')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg id="password-eye" class="w-4 h-4 text-gray-400 hover:text-[#E3CFCF] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-[10px] text-red-600 mt-2 tracking-wide">{{ $message }}</p>
                @enderror
                <p class="text-[9px] text-gray-400 mt-2 uppercase tracking-wider">Minimum 6 characters</p>
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
                           class="appearance-none block w-full px-4 py-3 pr-10 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-200' }} text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#E3CFCF] focus:ring-1 focus:ring-[#E3CFCF] transition-colors"
                           placeholder="Confirm your new password">
                    
                    {{-- Toggle Password Visibility --}}
                    <button type="button" 
                            onclick="togglePassword('password_confirmation')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg id="password_confirmation-eye" class="w-4 h-4 text-gray-400 hover:text-[#E3CFCF] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Error Alert --}}
            @error('email')
                <div class="bg-[#E3CFCF]/10 border border-[#E3CFCF]/30 px-4 py-3 text-center">
                    <p class="text-xs text-red-700">{{ $message }}</p>
                </div>
            @enderror

            {{-- Submit Button --}}
            <button type="submit" 
                    class="w-full bg-[#E3CFCF] text-gray-900 py-3 px-4 text-xs uppercase tracking-[0.2em] font-medium hover:bg-[#d4b8b8] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E3CFCF] transition-all duration-200">
                Reset Password
            </button>

            {{-- Back to Login --}}
            <div class="text-center pt-4">
                <a href="{{ route('login') }}" class="inline-flex items-center text-xs text-gray-500 hover:text-[#E3CFCF] transition-colors">
                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Login
                </a>
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
