@extends('layout.account')

@section('content')
<div class="min-h-screen bg-white flex items-center justify-center px-4 py-16">
    <div class="max-w-2xl mx-auto text-center">
        
        {{-- Error Icon --}}
        <div class="mb-8 flex justify-center">
            <div class="relative">
                <svg class="w-24 h-24 md:w-32 md:h-32 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>

        {{-- Error Message --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-4xl font-normal uppercase tracking-[0.2em] text-gray-900 mb-4">
                Access Restricted
            </h1>
            <p class="text-sm md:text-base text-gray-500 font-light leading-relaxed max-w-lg mx-auto">
                This page is reserved for authorized administrators only. 
                If you believe you should have access, please contact our support team.
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="/" 
               class="inline-flex items-center px-8 py-3 bg-gray-900 text-white text-xs uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors group">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Return Home
            </a>
        </div>

    </div>
</div>

<style>
    /* Optional: Add subtle animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .min-h-screen > div {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endsection
