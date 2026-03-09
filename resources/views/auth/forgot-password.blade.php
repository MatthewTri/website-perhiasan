@extends('layout.account')

@section('title', 'Forgot Password')

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
            <h1 class="text-3xl font-serif text-gray-900 mb-3">Forgot Password</h1>
            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-medium">
                No worries! Enter your email and we'll send you a reset link
            </p>
        </div>

        {{-- Success Alert --}}
        @if(session('status'))
            <div class="bg-[#E3CFCF]/10 border border-[#E3CFCF]/30 px-4 py-3 text-center">
                <p class="text-xs text-gray-700">{{ session('status') }}</p>
            </div>
        @endif

        {{-- Form --}}
        <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
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
                       placeholder="your.email@example.com"
                       autofocus>
                @error('email')
                    <p class="text-[10px] text-red-600 mt-2 tracking-wide">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit" 
                    class="w-full bg-[#E3CFCF] text-gray-900 py-3 px-4 text-xs uppercase tracking-[0.2em] font-medium hover:bg-[#d4b8b8] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E3CFCF] transition-all duration-200">
                Send Reset Link
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

        {{-- Security Info --}}
        <div class="p-4 bg-gray-50 border border-gray-100 text-center">
            <p class="text-[9px] uppercase tracking-[0.15em] text-gray-400 mb-1">Security Note</p>
            <p class="text-[10px] text-gray-600 leading-relaxed">
                The password reset link will expire in 60 minutes for your protection
            </p>
        </div>
    </div>
</div>

<script>
    // Auto-hide success alert
    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.querySelector('.bg-\\[\\#E3CFCF\\]\\/10');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }, 8000);
        }
    });
</script>
@endsection
