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
            <div id="successAlert" class="bg-[#E3CFCF]/10 border border-[#E3CFCF]/30 px-4 py-3 text-center">
                <p class="text-xs text-gray-700">{{ session('status') }}</p>
            </div>
        @endif

        {{-- Form --}}
        <form id="resetForm" class="space-y-6" action="{{ route('password.email') }}" method="POST">
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

            {{-- Submit Button with Countdown --}}
            <button type="submit" 
                    id="submitBtn"
                    class="w-full bg-[#E3CFCF] text-gray-900 py-3 px-4 text-xs uppercase tracking-[0.2em] font-medium hover:bg-[#d4b8b8] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E3CFCF] transition-all duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed disabled:hover:bg-gray-300">
                <span id="btnText">Send Reset Link</span>
            </button>

            {{-- Countdown Notice --}}
            <div id="countdownNotice" class="hidden text-center py-2 px-4 bg-gray-50 border border-gray-200">
                <p class="text-[10px] text-gray-600">
                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    You can resend the link in <strong id="countdown">60</strong> seconds
                </p>
            </div>

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
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('resetForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const countdownNotice = document.getElementById('countdownNotice');
        const countdownElement = document.getElementById('countdown');
        const emailInput = document.getElementById('email');
        
        let countdownInterval = null;
        
        // Cek apakah ada error (jika ada, jangan jalankan countdown)
        const hasError = {{ $errors->has('email') ? 'true' : 'false' }};

        // Check if there's a server-side resend timestamp (hanya jika tidak ada error)
        const serverTimestamp = {{ session('resend_available_at', 0) }};
        if (serverTimestamp > 0 && !hasError) {
            startCountdown(serverTimestamp);
        } else if (!hasError) {
            // Check localStorage for existing countdown (case browser refresh)
            const storedData = localStorage.getItem('password_reset_cooldown');
            if (storedData) {
                const data = JSON.parse(storedData);
                if (data.email === emailInput.value && data.expiresAt > Math.floor(Date.now() / 1000)) {
                    startCountdown(data.expiresAt);
                } else {
                    localStorage.removeItem('password_reset_cooldown');
                }
            }
        }

        // Form submission handler
        form.addEventListener('submit', function(e) {
            if (submitBtn.disabled) {
                e.preventDefault();
                return;
            }
            
            // Simpan countdown ke localStorage untuk handle page reload
            const expiresAt = Math.floor(Date.now() / 1000) + 60;
            localStorage.setItem('password_reset_cooldown', JSON.stringify({
                email: emailInput.value,
                expiresAt: expiresAt
            }));
        });

        function startCountdown(targetTimestamp) {
            const now = Math.floor(Date.now() / 1000);
            let remaining = targetTimestamp - now;

            if (remaining <= 0) {
                resetButton();
                return;
            }

            // Disable button dan show countdown
            submitBtn.disabled = true;
            countdownNotice.classList.remove('hidden');
            countdownElement.textContent = remaining;
            btnText.textContent = 'Email Sent';

            // Update countdown setiap detik
            countdownInterval = setInterval(() => {
                remaining--;
                
                if (remaining <= 0) {
                    clearInterval(countdownInterval);
                    resetButton();
                    localStorage.removeItem('password_reset_cooldown');
                } else {
                    countdownElement.textContent = remaining;
                }
            }, 1000);
        }

        function resetButton() {
            submitBtn.disabled = false;
            btnText.textContent = 'Resend Reset Link';
            countdownNotice.classList.add('hidden');
            
            // Update button style untuk resend
            submitBtn.classList.remove('bg-gray-300');
            submitBtn.classList.add('bg-[#E3CFCF]', 'hover:bg-[#d4b8b8]');
        }

        // Auto-hide success alert setelah 10 detik
        const successAlert = document.getElementById('successAlert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }, 10000);
        }

        // Clear localStorage on email change
        emailInput.addEventListener('input', function() {
            localStorage.removeItem('password_reset_cooldown');
            if (countdownInterval) {
                clearInterval(countdownInterval);
                resetButton();
            }
        });
    });
</script>
@endsection
