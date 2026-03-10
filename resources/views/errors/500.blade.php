@extends('layout.account')

@section('content')
<div class="min-h-screen bg-white flex items-center justify-center px-4 py-16">
    <div class="max-w-2xl mx-auto text-center">
        
        {{-- Error Icon --}}
        <div class="mb-8 flex justify-center">
            <div class="relative">
                <svg class="w-24 h-24 md:w-32 md:h-32 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>

        {{-- Error Message --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-4xl font-normal uppercase tracking-[0.2em] text-gray-900 mb-4">
                Something Went Wrong
            </h1>
            <p class="text-sm md:text-base text-gray-500 font-light leading-relaxed max-w-lg mx-auto">
                We're experiencing a temporary issue on our end. 
                Our team has been notified and is working to resolve it. Please try again in a few moments.
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center px-8 py-3 bg-gray-900 text-white text-xs uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors group">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Try Again
            </button>

            <a href="/" 
               class="inline-flex items-center px-8 py-3 bg-white text-gray-900 border border-gray-900 text-xs uppercase tracking-[0.2em] hover:bg-gray-900 hover:text-white transition-colors">
                Return Home
            </a>
        </div>

    </div>
</div>

<style>
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
