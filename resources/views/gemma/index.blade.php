@extends('layout.ai')

@section('content')

@php
    $hasHistory = auth()->check() && isset($history) && $history->isNotEmpty();
    $isQuotaExceeded = isset($quotaInfo) && ($quotaInfo['is_exceeded'] ?? false);
@endphp

@if(isset($isGuest) && $isGuest)
{{-- GUEST VIEW: Login Required --}}
<div class="flex flex-col h-screen bg-gradient-to-br from-white via-stone-50 to-stone-100">
    
    {{-- HEADER --}}
    <div class="bg-white/80 backdrop-blur-md border-b border-stone-200 px-6 py-5 flex-shrink-0 sticky top-0 z-30">
        <div class="flex items-center justify-between max-w-4xl mx-auto w-full">
            <div class="flex items-center gap-4">
                <button onclick="window.history.back()" 
                        class="p-2 hover:bg-stone-100 rounded-full transition-all duration-200 group">
                    <svg class="w-5 h-5 text-stone-600 group-hover:text-stone-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </button>
                
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-[#E3CFCF] to-[#D4A088] rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-serif text-stone-900">AI Design Studio</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT: Login Prompt --}}
    <div class="flex-1 flex items-center justify-center p-4 sm:p-8">
        <div class="max-w-md w-full">
            
            {{-- Card --}}
            <div class="bg-white border border-stone-200 shadow-xl relative overflow-hidden">
                
                {{-- Decorative gradient --}}
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#E3CFCF]"></div>
                
                {{-- Content --}}
                <div class="p-8 sm:p-12 text-center">
                    
                    <p class="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-gray-400 font-medium mb-6">
                        Access Restricted to Members Only
                    </p>

                    {{-- Description --}}
                    <div class="space-y-3 mb-8">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Our AI Design Studio is an exclusive feature available to registered members.
                        </p>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Create an account or sign in to start visualizing your custom jewellery designs with artificial intelligence.
                        </p>
                    </div>

                    {{-- Benefits List --}}
                    <div class="bg-gradient-to-br from-stone-50 to-stone-100/50 border border-stone-200 p-5 mb-8 text-left">
                        <p class="text-[10px] uppercase tracking-[0.15em] text-gray-500 font-medium mb-3">Members Benefits</p>
                        <ul class="space-y-2 text-xs text-gray-600">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-[#E3CFCF] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>AI-powered jewellery design visualization</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-[#E3CFCF] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Save and manage your design history</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="space-y-3">
                        <a href="{{ route('login') }}" 
                           class="block w-full px-8 py-4 bg-[#E3CFCF] text-white text-xs uppercase tracking-[0.15em] font-medium hover:bg-[#D4A088] transition-all duration-200 shadow-sm hover:shadow-md">
                            Sign In to Continue
                        </a>
                        <a href="{{ route('register') }}" 
                           class="block w-full px-8 py-4 border border-gray-300 text-gray-700 text-xs uppercase tracking-[0.15em] font-medium hover:border-gray-900 hover:text-gray-900 transition-all duration-200">
                            Create New Account
                        </a>
                    </div>

                    {{-- Footer Note --}}
                    <p class="mt-6 text-[10px] text-gray-400 leading-relaxed">
                        No credit card required. Free to join.
                    </p>
                </div>
            </div>

            {{-- Additional Info --}}
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-gray-900 transition-colors uppercase tracking-wider">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

@else
{{-- AUTHENTICATED VIEW: Normal Chat Interface --}}
<div class="flex flex-col h-screen bg-stone-50/50">
    
    {{-- HEADER WITH BACK BUTTON --}}
    <div class="bg-white/80 backdrop-blur-md border-b border-stone-200 px-6 py-5 flex-shrink-0 sticky top-0 z-30">
        <div class="flex items-center justify-between max-w-4xl mx-auto w-full">
            
            {{-- LEFT: Back Button + Branding --}}
            <div class="flex items-center gap-4">
                {{-- Back Button --}}
                <button onclick="window.history.back()" 
                        class="group flex items-center justify-center w-9 h-9 rounded-lg border border-stone-200 hover:border-stone-900 hover:bg-stone-50 transition-all duration-300"
                        aria-label="Go back">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                         class="w-5 h-5 text-stone-400 group-hover:text-stone-900 group-hover:-translate-x-0.5 transition-all duration-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </button>
                
                {{-- AI Icon + Title --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-sm flex items-center justify-center shadow-lg border border-stone-100 overflow-hidden">
                        <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-xl font-serif text-stone-900 tracking-tight">Cecile AI</h1>
                        <p class="text-xs font-medium text-stone-500 uppercase tracking-widest">Bespoke Jewellery Visualization</p>
                    </div>
                </div>
            </div>
            
            {{-- RIGHT: Status Indicator & Quota --}}
            <div class="flex items-center gap-2 sm:gap-4">
                @if(!isset($isGuest) || !$isGuest)
                    <div class="flex flex-col items-end gap-1">
                        {{-- Quota Indicator - Responsive Design --}}
                        <div id="quotaIndicator" class="flex items-center gap-2 px-2.5 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/60 rounded-lg sm:rounded-xl shadow-sm hover:shadow-md transition-all duration-300 group">
                            <div class="flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 bg-white rounded-md shadow-sm group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-1.5">
                                <span class="text-xs sm:text-sm font-bold text-amber-900 tabular-nums leading-tight">
                                    <span id="quotaRemaining">{{ $quotaInfo['remaining'] ?? 0 }}</span><span class="text-amber-600/60">/</span><span id="quotaLimit">{{ $quotaInfo['limit'] ?? 20 }}</span>
                                </span>
                                <span class="hidden sm:inline text-[10px] font-semibold text-amber-600 uppercase tracking-wide">
                                    Left
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- CHAT CONTAINER --}}
    <div class="flex-1 overflow-y-auto p-4 md:p-8" id="scrollArea">
        <div class="max-w-3xl mx-auto space-y-8" id="chatContainer">
            
            {{-- Chat History - Show FIRST (if exists) --}}
            @auth
                @foreach($history as $chat)
                    {{-- User Message --}}
                    <div class="flex justify-end animate-fade-in-up">
                        <div class="max-w-[85%] md:max-w-lg">
                            <div class="bg-stone-900 text-stone-50 rounded-2xl rounded-tr-none px-6 py-4 shadow-md">
                                <p class="text-sm font-light leading-relaxed">{{ $chat->prompt }}</p>
                            </div>
                            <span class="text-[10px] text-stone-400 mt-2 mr-2 block text-right font-mono">{{ $chat->created_at->format('H:i') }}</span>
                        </div>
                    </div>

                    {{-- AI Response --}}
                    <div class="flex items-start gap-4 animate-fade-in-up">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                            <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1 max-w-lg">
                            <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none p-2 shadow-[0_2px_15px_rgb(0,0,0,0.04)] overflow-hidden">
                                <p class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-3 px-4 pt-3">Design Concept</p>
                                
                                <div class="relative group">
                                    <img src="{{ $chat->image_url }}" 
                                         alt="Generated jewelry" 
                                         class="w-full rounded-xl shadow-inner cursor-zoom-in hover:brightness-105 transition duration-500 ease-in-out"
                                         onclick="openLightbox('{{ $chat->image_url }}', 'ai-image-modal')">
                                    
                                    <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <button onclick="window.open('{{ $chat->image_url }}', '_blank')" 
                                                class="bg-white/90 text-stone-900 p-2 rounded-full hover:bg-white shadow-sm transition-all hover:scale-105" title="Open Full Size">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </button>
                                        <button onclick="deleteChat({{ $chat->id }})" 
                                                class="bg-white/90 text-rose-600 p-2 rounded-full hover:bg-white shadow-sm transition-all hover:scale-105" title="Remove Design">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                {{-- Consult (Full Width) --}}
                                <div class="px-4 pb-2 pt-3">
                                    <a href="{{ route('consultation.index') }}" 
                                       class="block w-full bg-[#E3CFCF] hover:bg-[#E3CFCF] text-white text-center py-3 px-4 rounded-lg transition-all duration-300 font-medium text-sm shadow-sm hover:shadow-md group">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                            </svg>
                                            <span class="uppercase tracking-wide text-xs font-bold">Consult with this Design</span>
                                        </div>
                                    </a>
                                </div>

                                {{-- Actions (Two Buttons Side-by-Side) --}}
                                <div class="px-4 pb-4 pt-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" onclick="window.aiChat.startModify({{ $chat->id }})"
                                                {{ $isQuotaExceeded ? 'disabled' : '' }}
                                                class="w-full border border-stone-300 text-stone-700 text-center py-3 px-4 rounded-lg transition-all duration-300 font-medium text-sm hover:border-stone-900 hover:text-stone-900 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span class="uppercase tracking-wide text-xs font-bold">Modify this Design</span>
                                        </button>
                                        <button type="button" onclick="window.aiChat.createNewDesign()"
                                                class="w-full border border-stone-300 text-stone-700 text-center py-3 px-4 rounded-lg transition-all duration-300 font-medium text-sm hover:border-stone-900 hover:text-stone-900">
                                            <span class="uppercase tracking-wide text-xs font-bold">Create new Design</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">AI Cecile &bull; {{ $chat->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            @endauth

            {{-- Welcome Message & First Question - Show AFTER history --}}
            @auth
                @if(isset($quotaInfo) && $quotaInfo['is_exceeded'])
                    {{-- Quota Exceeded Message --}}
                    <div class="flex items-start gap-4 animate-fade-in-up" id="quotaExceededMessage">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                            <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1 max-w-lg">
                            <div class="bg-white border border-stone-200 rounded-2xl rounded-tl-none px-6 py-5 shadow-sm">
                                <p class="text-xs font-bold text-stone-500 uppercase tracking-widest mb-3">Daily Limit Reached</p>
                                <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                                    You have reached your daily limit of <strong class="font-semibold">{{ $quotaInfo['limit'] }} AI generations</strong>. Your creativity knows no bounds, but our quota does!
                                </p>
                                <div class="bg-stone-50 rounded-lg px-4 py-3.5 border border-stone-100">
                                    <div class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-stone-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs text-stone-600 leading-relaxed">
                                            Your quota will automatically reset tomorrow at midnight. In the meantime, feel free to browse your previous designs or explore our jewellery collection.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; {{ now()->format('H:i') }}</span>
                        </div>
                    </div>
                @else
                    {{-- Normal Welcome Message --}}
                <div class="flex items-start gap-4 animate-fade-in-up {{ $hasHistory ? 'hidden' : '' }}" id="welcomeMessage">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                        <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                    </div>
                    <div class="flex-1">
                        <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                            <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                                Welcome to Cecile Private Collection. I am your personal design consultant. To begin our design journey, what type of masterpiece are you envisioning today?
                            </p>
                            {{-- Category Buttons --}}
                            <div class="grid grid-cols-2 gap-2 mt-4">
                                <button onclick="window.aiChat.selectOption('category', 'Ring')" 
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E3CFCF]"></span>
                                    Ring
                                </button>
                                <button onclick="window.aiChat.selectOption('category', 'Necklace / Pendant')" 
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E3CFCF]"></span>
                                    Necklace / Pendant
                                </button>
                                <button onclick="window.aiChat.selectOption('category', 'Earrings')" 
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E3CFCF]"></span>
                                    Earrings
                                </button>
                                <button onclick="window.aiChat.selectOption('category', 'Bracelet')" 
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E3CFCF]"></span>
                                    Bracelet
                                </button>
                            </div>
                            <p class="text-[10px] text-stone-400 italic mt-3 text-center">Or type your own choice below...</p>
                        </div>
                        <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; NOW</span>
                    </div>
                </div>
                @endif
            @else
                {{-- Guest view welcome message --}}
                <div class="flex items-start gap-4 animate-fade-in-up" id="welcomeMessage">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                        <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                    </div>
                    <div class="flex-1">
                        <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                            <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                                Welcome to Cecile Private Collection. I am your personal design consultant. 
                                Please <a href="{{ route('login') }}" class="text-stone-900 font-medium underline underline-offset-2 hover:text-amber-600 transition-colors">sign in</a> to begin your design journey.
                            </p>
                        </div>
                        <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; NOW</span>
                    </div>
                </div>
            @endauth
            
        </div>
    </div>

    {{-- INPUT FORM - PERBAIKAN --}}
    <div class="bg-white border-t border-stone-200 px-4 py-6 flex-shrink-0 z-40">
        <div class="max-w-3xl mx-auto w-full">
            @if(isset($quotaInfo) && $quotaInfo['is_exceeded'])
                {{-- Disabled Input Form (Quota Exceeded) --}}
                <div class="relative">
                    <div class="bg-stone-100 border border-stone-200 rounded-2xl px-6 py-4 opacity-60">
                        <div class="flex items-center justify-center gap-2 text-stone-500">
                            <span class="text-xs uppercase tracking-wider font-medium">Daily quota reached</span>
                        </div>
                    </div>
                </div>
            @else
                {{-- Normal Input Form --}}
                <div id="quotaInputForm">
                    <form id="chatForm" class="relative group">
                    @csrf
                    <div class="relative bg-stone-50 rounded-2xl border border-stone-200 overflow-hidden transition-all duration-300 focus-within:bg-white focus-within:ring-1 focus-within:ring-stone-300 focus-within:border-stone-300 focus-within:shadow-[0_4px_20px_rgb(0,0,0,0.06)]">
                        
                        <textarea 
                            id="promptInput" 
                            name="prompt" 
                            class="w-full px-5 py-4 pr-14 bg-transparent border-0 focus:ring-0 focus:outline-none text-stone-800 placeholder-stone-400 text-sm leading-relaxed resize-none"
                            rows="1"
                            maxlength="500"
                            placeholder="Type your choice or describe your jewellery..."></textarea>
                        
                        <button 
                            type="submit" 
                            id="sendButton"
                            class="absolute right-2 bottom-2 w-10 h-10 bg-stone-900 text-white rounded-xl transition-all duration-300 flex items-center justify-center opacity-0 scale-90 pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed hover:bg-black hover:scale-100 shadow-md">
                            <svg id="sendIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                            <div id="loadingSpinner" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin hidden"></div>
                        </button>
                    </div>
                    
                    <div class="text-[10px] font-mono text-stone-400 mt-2 text-right opacity-0 group-focus-within:opacity-100 transition-opacity">
                        <span id="charCount">0</span>/500
                    </div>
                    </form>
                </div>

                {{-- Dynamic Disabled Input (shown when quota becomes exceeded without refresh) --}}
                <div id="quotaInputDisabled" class="hidden">
                    <div class="relative">
                        <div class="bg-stone-100 border border-stone-200 rounded-2xl px-6 py-4 opacity-60">
                            <div class="flex items-center justify-center gap-2 text-stone-500">
                                <span class="text-xs uppercase tracking-wider font-medium">Daily quota reached</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Image Lightbox Component --}}
<x-image-lightbox modalId="ai-image-modal" alt="AI Generated Jewelry Design" />

<script>
    class AIChat {
        constructor() {
            this.chatContainer = document.getElementById('chatContainer');
            this.scrollArea = document.getElementById('scrollArea');
            this.chatForm = document.getElementById('chatForm');
            this.promptInput = document.getElementById('promptInput');
            this.sendButton = document.getElementById('sendButton');
            this.sendIcon = document.getElementById('sendIcon');
            this.loadingSpinner = document.getElementById('loadingSpinner');
            this.charCount = document.getElementById('charCount');
            this.defaultPlaceholder = this.promptInput?.getAttribute('placeholder') || '';
            this.welcomeMessage = document.getElementById('welcomeMessage');
            this.hasHistory = {{ $hasHistory ? 'true' : 'false' }};
            this.inputLocked = false;

            // Quota UI elements
            this.quotaRemainingEl = document.getElementById('quotaRemaining');
            this.quotaLimitEl = document.getElementById('quotaLimit');
            this.quotaIndicatorEl = document.getElementById('quotaIndicator');
            this.quotaStatusMessageEl = document.getElementById('quotaStatusMessage');
            this.quotaInputFormEl = document.getElementById('quotaInputForm');
            this.quotaInputDisabledEl = document.getElementById('quotaInputDisabled');
            
            // Guided conversation state
            this.guidedMode = true;
            this.currentStep = 1;
            this.userSelections = {
                category: null,
                material: null,
                gemstone: null,
                cut: null,
                style: null,
                customNote: null
            };
            
            // Quota state
            this.quotaExceeded = {{ isset($quotaInfo) && $quotaInfo['is_exceeded'] ? 'true' : 'false' }};

            // Modify flow state
            this.modifyMode = false;
            this.modifyChatId = null;
            
            this.init();
            
            // Make instance globally accessible
            window.aiChat = this;
        }

        clearCurrentSessionMessages() {
            if (!this.chatContainer) return;

            const loadingElement = document.getElementById('loading-message');
            if (loadingElement) loadingElement.remove();

            const currentSessionMessages = this.chatContainer.querySelectorAll('[data-session="current"]');
            currentSessionMessages.forEach(msg => msg.remove());
        }

        applyQuotaFromResponse(data) {
            if (!data) return;

            // Preferred shape: { quota: { remaining, limit, is_exceeded } }
            if (data.quota && typeof data.quota === 'object') {
                this.setQuota(data.quota);
                return;
            }

            // Backward compatibility: remaining_quota, quota_limit
            const remaining = typeof data.remaining_quota === 'number' ? data.remaining_quota : null;
            const limit = typeof data.quota_limit === 'number'
                ? data.quota_limit
                : (this.quotaLimitEl ? parseInt(this.quotaLimitEl.textContent || '0', 10) : null);

            if (remaining !== null && limit !== null) {
                this.setQuota({ remaining, limit, is_exceeded: remaining <= 0 });
            }
        }

        setQuota(quota) {
            if (!quota) return;
            const remaining = typeof quota.remaining === 'number' ? quota.remaining : null;
            const limit = typeof quota.limit === 'number' ? quota.limit : null;
            const isExceeded = typeof quota.is_exceeded === 'boolean'
                ? quota.is_exceeded
                : (remaining !== null ? remaining <= 0 : false);

            const wasExceeded = !!this.quotaExceeded;

            if (this.quotaRemainingEl && remaining !== null) {
                this.quotaRemainingEl.textContent = String(Math.max(remaining, 0));
            }
            if (this.quotaLimitEl && limit !== null) {
                this.quotaLimitEl.textContent = String(limit);
            }

            if (this.quotaStatusMessageEl) {
                this.quotaStatusMessageEl.classList.toggle('hidden', !isExceeded);
            }

            if (isExceeded) {
                this.quotaExceeded = true;
                this.toggleQuotaInputUI(true);

                // On transition, show the same quota panel dynamically (no refresh)
                if (!wasExceeded) {
                    const quotaLimit = limit !== null ? limit : (typeof quota.quota_limit === 'number' ? quota.quota_limit : null);
                    this.showQuotaExceededPanel(quotaLimit);
                }
                // If form exists, lock it immediately (no refresh)
                if (this.promptInput && this.sendButton) {
                    this.setInputLocked(true);
                    this.setPlaceholder('Daily quota reached');
                }
            } else {
                this.quotaExceeded = false;
                this.toggleQuotaInputUI(false);
            }
        }

        toggleQuotaInputUI(exceeded) {
            if (this.quotaInputFormEl) {
                this.quotaInputFormEl.classList.toggle('hidden', !!exceeded);
            }
            if (this.quotaInputDisabledEl) {
                this.quotaInputDisabledEl.classList.toggle('hidden', !exceeded);
            }
        }

        showQuotaExceededPanel(quotaLimit) {
            if (!this.chatContainer) return;

            // Remove any existing quota exceeded panels
            const existingPanels = this.chatContainer.querySelectorAll('[data-purpose="quota-exceeded"]');
            existingPanels.forEach(el => el.remove());

            // Remove current session prompts (guided/modify/etc.) so UI is consistent
            this.clearCurrentSessionMessages();
            this.setWelcomeVisible(false);

            const limitText = typeof quotaLimit === 'number' ? quotaLimit : null;

            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.setAttribute('data-purpose', 'quota-exceeded');

            messageElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 max-w-lg">
                    <div class="bg-white border border-stone-200 rounded-2xl rounded-tl-none px-6 py-5 shadow-sm">
                        <p class="text-xs font-bold text-stone-500 uppercase tracking-widest mb-3">Daily Limit Reached</p>
                        <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                            You have reached your daily limit${limitText ? ` of <strong class=\"font-semibold\">${limitText} AI generations</strong>` : ''}. Your creativity knows no bounds, but our quota does!
                        </p>
                        <div class="bg-stone-50 rounded-lg px-4 py-3.5 border border-stone-100">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-stone-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs text-stone-600 leading-relaxed">
                                    Your quota will automatically reset tomorrow at midnight. In the meantime, feel free to browse your previous designs or explore our jewellery collection.
                                </p>
                            </div>
                        </div>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                </div>
            `;

            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();
        }

        init() {
            // When quota exceeded, the input form is not rendered.
            if (!this.promptInput || !this.chatForm || !this.sendButton) {
                this.scrollToBottom();
                if (this.quotaExceeded && this.quotaStatusMessageEl) {
                    this.quotaStatusMessageEl.classList.remove('hidden');
                }
                return;
            }

            this.promptInput.addEventListener('input', () => {
                this.handleInputChange();
                this.autoResize();
                this.toggleSendButton();
            });
            
            this.chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.sendMessage();
            });
            
            this.promptInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });
            
            this.toggleSendButton();
            this.scrollToBottom();

            // If the user already has history, lock input until they choose Modify/Create new.
            if (this.hasHistory) {
                this.setWelcomeVisible(false);
                this.setInputLocked(true);
            }

            if (this.quotaExceeded && this.quotaStatusMessageEl) {
                this.quotaStatusMessageEl.classList.remove('hidden');
            }

            // If quota already exceeded (future-proof), swap input UI
            this.toggleQuotaInputUI(this.quotaExceeded);
        }

        setWelcomeVisible(visible) {
            if (!this.welcomeMessage) return;
            this.welcomeMessage.classList.toggle('hidden', !visible);
        }

        setInputLocked(locked) {
            this.inputLocked = locked;
            if (!this.promptInput || !this.sendButton) return;

            this.promptInput.disabled = locked;
            this.sendButton.disabled = locked;
            this.promptInput.classList.toggle('opacity-60', locked);
            this.promptInput.classList.toggle('cursor-not-allowed', locked);

            if (locked) {
                this.promptInput.value = '';
                if (this.charCount) this.charCount.textContent = '0';
                this.promptInput.style.height = 'auto';
            }

            this.toggleSendButton();
        }

        createNewDesign() {
            if (this.quotaExceeded) return;
            if (!this.promptInput) return;

            // Replace any current session (modify prompts, guided steps, errors, etc.)
            this.clearCurrentSessionMessages();

            // Exit modify mode
            this.modifyMode = false;
            this.modifyChatId = null;

            // Reset guided state
            this.guidedMode = true;
            this.currentStep = 1;
            this.userSelections = {
                category: null,
                material: null,
                gemstone: null,
                cut: null,
                style: null,
                customNote: null
            };

            this.setPlaceholder(this.defaultPlaceholder);
            this.setInputLocked(false);
            this.setWelcomeVisible(true);
            this.scrollToBottom();
            this.promptInput.focus();
        }

        selectOption(stepName, value) {
            this.userSelections[stepName] = value;
            this.addUserMessage(value);
            this.currentStep++;
            this.askNextQuestion();
        }

        goBack() {
            if (this.currentStep <= 1) return;
            
            // Remove last user message
            const messages = this.chatContainer.querySelectorAll('.flex.justify-end');
            if (messages.length > 0) {
                messages[messages.length - 1].remove();
            }
            
            // Remove last AI question
            const aiMessages = this.chatContainer.querySelectorAll('.flex.items-start');
            if (aiMessages.length > 0) {
                const lastAI = aiMessages[aiMessages.length - 1];
                // Don't remove the initial welcome message
                if (!lastAI.id || lastAI.id !== 'welcomeMessage') {
                    lastAI.remove();
                }
            }
            
            // Go back one step
            this.currentStep--;
            
            // Clear the selection for current step
            const stepNames = ['', 'category', 'material', 'gemstone', 'cut', 'style'];
            this.userSelections[stepNames[this.currentStep]] = null;
            
            this.scrollToBottom();
        }

        startOver() {
            // Reset all state
            this.guidedMode = true;
            this.currentStep = 1;
            this.userSelections = {
                category: null,
                material: null,
                gemstone: null,
                cut: null,
                style: null,
                customNote: null
            };
            
            // Remove only current session messages (not history or welcome message)
            const currentSessionMessages = this.chatContainer.querySelectorAll('[data-session="current"]');
            currentSessionMessages.forEach(msg => msg.remove());
            
            this.scrollToBottom();
        }

        askNextQuestion() {
            setTimeout(() => {
                if (this.currentStep === 2) {
                    this.askMaterial();
                } else if (this.currentStep === 3) {
                    this.askGemstone();
                } else if (this.currentStep === 4) {
                    if (this.userSelections.gemstone && this.userSelections.gemstone !== 'No Gemstone / Plain Metal') {
                        this.askCut();
                    } else {
                        this.currentStep++;
                        this.askStyle();
                    }
                } else if (this.currentStep === 5) {
                    this.askStyle();
                } else if (this.currentStep === 6) {
                    this.showSummary();
                }
            }, 500);
        }

        askMaterial() {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 max-w-lg">
                    <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                        <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                            Excellent choice. Now, which precious metal speaks to your personal style?
                        </p>
                        <div class="grid grid-cols-1 gap-2 mt-4">
                            <button onclick="window.aiChat.selectOption('material', '18k Yellow Gold')" 
                                    class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">18k Yellow Gold</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Classic, Luxurious</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('material', '18k White Gold / Platinum')" 
                                    class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">18k White Gold / Platinum</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Modern, Clean</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('material', '18k Rose Gold')" 
                                    class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">18k Rose Gold</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Romantic, Feminine</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('material', 'Mixed Metals')" 
                                    class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Mixed Metals</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Unique, Combination</span>
                            </button>
                        </div>
                        <p class="text-[10px] text-stone-400 italic mt-3 text-center">Or type your own choice...</p>
                        <div class="flex gap-2 mt-4 pt-4 border-t border-stone-100">
                            <button onclick="window.aiChat.goBack()" 
                                    class="flex-1 px-3 py-2 text-xs text-stone-500 hover:text-stone-700 border border-stone-200 hover:border-stone-400 transition-all duration-200">
                                ← Back
                            </button>
                            <button onclick="window.aiChat.startOver()" 
                                    class="flex-1 px-3 py-2 text-xs text-stone-500 hover:text-rose-600 border border-stone-200 hover:border-rose-400 transition-all duration-200">
                                ↺ Start Over
                            </button>
                        </div>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                </div>
            `;
            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();
        }

        askGemstone() {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 max-w-lg">
                    <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                        <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                            Every piece has a heart. What gemstone should take center stage?
                        </p>
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <button onclick="window.aiChat.selectOption('gemstone', 'Classic Diamond')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm transition-all duration-200">
                                Classic Diamond
                            </button>
                            <button onclick="window.aiChat.selectOption('gemstone', 'Blue Sapphire')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm transition-all duration-200">
                                Blue Sapphire
                            </button>
                            <button onclick="window.aiChat.selectOption('gemstone', 'Ruby')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm transition-all duration-200">
                                Ruby
                            </button>
                            <button onclick="window.aiChat.selectOption('gemstone', 'Emerald')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm transition-all duration-200">
                                Emerald
                            </button>
                            <button onclick="window.aiChat.selectOption('gemstone', 'No Gemstone / Plain Metal')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm transition-all duration-200 col-span-2">
                                No Gemstone / Plain Metal
                            </button>
                        </div>
                        <p class="text-[10px] text-stone-400 italic mt-3 text-center">Or type your own (e.g., Amethyst, Opal)...</p>
                        <div class="flex gap-2 mt-4 pt-4 border-t border-stone-100">
                            <button onclick="window.aiChat.goBack()" 
                                    class="flex-1 px-3 py-2 text-xs text-stone-500 hover:text-stone-700 border border-stone-200 hover:border-stone-400 transition-all duration-200">
                                ← Back
                            </button>
                            <button onclick="window.aiChat.startOver()" 
                                    class="flex-1 px-3 py-2 text-xs text-stone-500 hover:text-rose-600 border border-stone-200 hover:border-rose-400 transition-all duration-200">
                                ↺ Start Over
                            </button>
                        </div>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                </div>
            `;
            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();
        }

        askCut() {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 max-w-lg">
                    <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                        <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                            How should the gemstone be cut to capture the light?
                        </p>
                        <div class="grid grid-cols-1 gap-2 mt-4">
                            <button onclick="window.aiChat.selectOption('cut', 'Round Brilliant')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Round Brilliant</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Most Popular Cut</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('cut', 'Oval Cut')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Oval Cut</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Elegant & Elongated</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('cut', 'Emerald Cut')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Emerald Cut</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Rectangular, Vintage Style</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('cut', 'Pear Shape')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Pear Shape</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Teardrop, Unique</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('cut', 'Princess Cut')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Princess Cut</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Square, Sharp & Modern</span>
                            </button>
                        </div>
                        <p class="text-[10px] text-stone-400 italic mt-3 text-center">Or type your preferred cut...</p>
                        <div class="flex gap-2 mt-4 pt-4 border-t border-stone-100">
                            <button onclick="window.aiChat.goBack()" 
                                    class="flex-1 px-3 py-2 text-xs text-stone-500 hover:text-stone-700 border border-stone-200 hover:border-stone-400 transition-all duration-200">
                                ← Back
                            </button>
                            <button onclick="window.aiChat.startOver()" 
                                    class="flex-1 px-3 py-2 text-xs text-stone-500 hover:text-rose-600 border border-stone-200 hover:border-rose-400 transition-all duration-200">
                                ↺ Start Over
                            </button>
                        </div>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                </div>
            `;
            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();
        }

        askStyle() {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 max-w-lg">
                    <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                        <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                            Final touch. What is the aesthetic or era that inspires this piece?
                        </p>
                        <div class="grid grid-cols-1 gap-2 mt-4">
                            <button onclick="window.aiChat.selectOption('style', 'Minimalist & Modern')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Minimalist & Modern</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Simple, Clean, Sleek</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('style', 'Art Deco / Vintage')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Art Deco / Vintage</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Intricate Details, 1920s Style</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('style', 'Nature Inspired')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Nature Inspired</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Floral, Leaves, Organic</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('style', 'Royal & Luxurious')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Royal & Luxurious</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Halo Setting, Ornate, Regal</span>
                            </button>
                        </div>
                        <p class="text-[10px] text-stone-400 italic mt-3 text-center">Or type your preferred style...</p>
                        <div class="flex gap-2 mt-4 pt-4 border-t border-stone-100">
                            <button onclick="window.aiChat.goBack()" 
                                    class="flex-1 px-3 py-2 text-xs text-stone-500 hover:text-stone-700 border border-stone-200 hover:border-stone-400 transition-all duration-200">
                                ← Back
                            </button>
                            <button onclick="window.aiChat.startOver()" 
                                    class="flex-1 px-3 py-2 text-xs text-stone-500 hover:text-rose-600 border border-stone-200 hover:border-rose-400 transition-all duration-200">
                                ↺ Start Over
                            </button>
                        </div>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                </div>
            `;
            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();
        }

        showSummary() {
            let summaryParts = [];
            if (this.userSelections.category) summaryParts.push(this.userSelections.category);
            if (this.userSelections.material) summaryParts.push(`made of ${this.userSelections.material}`);
            if (this.userSelections.gemstone && this.userSelections.gemstone !== 'No Gemstone / Plain Metal') {
                if (this.userSelections.cut) {
                    summaryParts.push(`featuring a ${this.userSelections.cut} ${this.userSelections.gemstone}`);
                } else {
                    summaryParts.push(`featuring ${this.userSelections.gemstone}`);
                }
            }
            if (this.userSelections.style) summaryParts.push(`in a ${this.userSelections.style} style`);
            
            const summary = summaryParts.join(', ');
            
            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 max-w-lg">
                    <div class="bg-gradient-to-br from-white to-stone-50 border border-stone-200 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                        <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                            Perfect! I have noted your preferences:
                        </p>
                        <div class="bg-white border border-stone-200 px-4 py-3 rounded-lg mb-4">
                            <p class="text-stone-900 text-sm font-medium italic">"A ${summary}"</p>
                        </div>
                        <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                            Is there any specific detail you would like to add? 
                            <span class="text-stone-500 text-xs block mt-1">(e.g., "add small diamonds on the band", "make it look like a flower")</span>
                        </p>
                        <button id="generateButton-${Date.now()}" onclick="window.aiChat.generateFromSummary(this)" 
                                class="w-full px-6 py-3 bg-[#E3CFCF] text-white text-sm font-medium uppercase tracking-wider hover:bg-[#D4A088] transition-all duration-200 shadow-sm">
                            Generate Design
                        </button>
                        <div class="flex gap-2 mt-3">
                            <button onclick="window.aiChat.goBack()" 
                                    class="flex-1 px-3 py-2 text-xs text-stone-500 hover:text-stone-700 border border-stone-200 hover:border-stone-400 transition-all duration-200">
                                ← Back
                            </button>
                            <button onclick="window.aiChat.startOver()" 
                                    class="flex-1 px-3 py-2 text-xs text-stone-500 hover:text-rose-600 border border-stone-200 hover:border-rose-400 transition-all duration-200">
                                ↺ Start Over
                            </button>
                        </div>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                </div>
            `;
            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();
            
            this.guidedMode = false;
        }

        generateFromSummary(buttonElement) {
            // Disable the button to prevent multiple clicks
            if (buttonElement) {
                buttonElement.disabled = true;
                buttonElement.classList.add('opacity-50', 'cursor-not-allowed');
                buttonElement.classList.remove('hover:bg-[#D4A088]');
            }
            
            const finalPrompt = this.buildFinalPrompt();
            this.submitToAI(finalPrompt);
        }

        buildFinalPrompt() {
            let prompt = '';
            
            if (this.userSelections.category) {
                prompt += this.userSelections.category;
            }
            if (this.userSelections.material) {
                prompt += ` made of ${this.userSelections.material}`;
            }
            if (this.userSelections.gemstone && this.userSelections.gemstone !== 'No Gemstone / Plain Metal') {
                if (this.userSelections.cut) {
                    prompt += `, featuring a ${this.userSelections.cut} ${this.userSelections.gemstone}`;
                } else {
                    prompt += `, featuring ${this.userSelections.gemstone}`;
                }
            }
            if (this.userSelections.style) {
                prompt += `, in a ${this.userSelections.style} style`;
            }
            if (this.userSelections.customNote) {
                prompt += `, ${this.userSelections.customNote}`;
            }
            
            return prompt;
        }

        async sendMessage() {
            if (this.inputLocked) return;
            if (this.quotaExceeded) return;
            if (!this.promptInput) return;
            const prompt = this.promptInput.value.trim();
            if (!prompt) return;

            // Modify mode: send instruction to edit endpoint
            if (this.modifyMode && this.modifyChatId) {
                const instruction = prompt;
                this.addUserMessage(instruction);

                this.promptInput.value = '';
                this.charCount.textContent = '0';
                this.promptInput.style.height = 'auto';
                this.toggleSendButton();

                const chatId = this.modifyChatId;
                this.modifyMode = false;
                this.modifyChatId = null;
                this.setPlaceholder(this.defaultPlaceholder);

                await this.submitEdit(chatId, instruction);
                return;
            }

            // ✅ FIX: Langsung submit ke AI tanpa guided mode complexity
            if (this.guidedMode && this.currentStep <= 5) {
                const stepNames = ['', 'category', 'material', 'gemstone', 'cut', 'style'];
                const currentStepName = stepNames[this.currentStep];
                
                this.userSelections[currentStepName] = prompt;
                this.addUserMessage(prompt);
                
                this.promptInput.value = '';
                this.charCount.textContent = '0';
                this.promptInput.style.height = 'auto';
                this.toggleSendButton();
                
                this.currentStep++;
                this.askNextQuestion();
                return;
            }
            
            if (this.currentStep === 6 && !this.guidedMode) {
                this.userSelections.customNote = prompt;
                this.addUserMessage(prompt);
                
                this.promptInput.value = '';
                this.charCount.textContent = '0';
                this.promptInput.style.height = 'auto';
                this.toggleSendButton();
                
                const finalPrompt = this.buildFinalPrompt();
                this.submitToAI(finalPrompt);
                return;
            }
            
            // Free mode
            this.addUserMessage(prompt);
            this.promptInput.value = '';
            this.charCount.textContent = '0';
            this.promptInput.style.height = 'auto';
            this.toggleSendButton();
            
            this.submitToAI(prompt);
        }

        async submitToAI(prompt) {
            this.setLoading(true);
            
            try {
                // ✅ ADD: Mock parameter untuk testing tanpa API
                const useMock = false; // SET false untuk production
                
                const response = await fetch('{{ route("gemma.generate") }}' + (useMock ? '?mock=1' : ''), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ prompt: prompt })
                });

                const data = await response.json();
                
                console.log('🔍 Response data:', data);

                this.applyQuotaFromResponse(data);

                if (data.success) {
                    this.addAIImageMessage(data.image_url, data.chat_id);
                    this.setWelcomeVisible(false);
                    this.setInputLocked(true);
                    this.modifyMode = false;
                    this.modifyChatId = null;
                    this.resetConversation();

                    // If this success consumed the last quota, show the panel right away
                    if (this.quotaExceeded) {
                        const limit = data?.quota?.limit ?? (typeof data.quota_limit === 'number' ? data.quota_limit : null);
                        this.showQuotaExceededPanel(typeof limit === 'number' ? limit : null);
                    }
                } else {
                    console.error('❌ Error response:', data);
                    
                    // ✅ Special handling for quota exceeded
                    if (data.error === 'quota_exceeded') {
                        this.quotaExceeded = true;
                        this.applyQuotaFromResponse(data);
                        this.addQuotaExceededMessage(data.quota_limit, data.message);
                        this.toggleQuotaInputUI(true);
                    } else {
                        const errorMessage = this.getUserFriendlyErrorMessage(data.error);
                        this.addAIMessage(errorMessage);
                    }
                    this.resetAfterError();
                }
            } catch (error) {
                console.error('❌ Fetch error:', error);
                this.addAIMessage('Sorry, something went wrong. Please try again.');
                this.resetAfterError();
            } finally {
                this.setLoading(false);
            }
        }

        async submitEdit(chatId, instruction) {
            this.setLoading(true);

            try {
                const response = await fetch('{{ route("gemma.edit") }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ chat_id: chatId, instruction })
                });

                const data = await response.json();
                console.log('🔍 Edit response data:', data);

                this.applyQuotaFromResponse(data);

                if (data.success) {
                    this.addAIImageMessage(data.image_url, data.chat_id);
                    this.setWelcomeVisible(false);
                    this.setInputLocked(true);
                    this.modifyMode = false;
                    this.modifyChatId = null;
                    this.resetConversation();
                } else {
                    if (data.error === 'quota_exceeded') {
                        this.quotaExceeded = true;
                        this.applyQuotaFromResponse(data);
                        this.addQuotaExceededMessage(data.quota_limit, data.message);
                    } else {
                        const errorMessage = this.getUserFriendlyErrorMessage(data.error);
                        this.addAIMessage(errorMessage);
                        // Keep user in Modify flow and ask again
                        this.startModify(chatId, false);
                    }
                }
            } catch (error) {
                console.error('❌ Edit fetch error:', error);
                this.addAIMessage('Sorry, something went wrong. Please try again.');
                // Keep user in Modify flow and ask again
                this.startModify(chatId, false);
            } finally {
                this.setLoading(false);
            }
        }

        startModify(chatId, replaceSession = true) {
            if (this.quotaExceeded) return;
            if (!this.promptInput) return;

            // If user spams the button, keep it single and consistent
            if (this.modifyMode && this.modifyChatId === chatId) {
                this.setPlaceholder('Describe what you want to change...');
                this.promptInput.focus();
                return;
            }

            // Replace any current session messages (welcome/guided/previous modify prompt)
            // When called after an error, keep the error message visible.
            if (replaceSession) {
                this.clearCurrentSessionMessages();
            }

            // Always keep only one modify prompt card
            const existingModifyPrompts = this.chatContainer.querySelectorAll('[data-purpose="modify-prompt"]');
            existingModifyPrompts.forEach(el => el.remove());

            this.setWelcomeVisible(false);
            this.setInputLocked(false);
            this.modifyMode = true;
            this.modifyChatId = chatId;

            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.setAttribute('data-purpose', 'modify-prompt');
            messageElement.setAttribute('data-chat-id', String(chatId));
            messageElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 max-w-lg">
                    <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                        <p class="text-stone-700 text-sm leading-relaxed font-light mb-3">
                            What would you like to enhance or change in this design?
                        </p>
                        <p class="text-stone-500 text-xs leading-relaxed font-light">
                            Example: “change the leaf motif to an animal style”, “add small diamonds on the band”, “change the gemstone to emerald”.
                        </p>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                </div>
            `;
            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();

            this.setPlaceholder('Describe what you want to change...');
            this.promptInput.value = '';
            if (this.charCount) this.charCount.textContent = '0';
            this.promptInput.focus();
        }

        setPlaceholder(text) {
            if (!this.promptInput) return;
            this.promptInput.setAttribute('placeholder', text);
        }

        resetConversation() {
            // Convert current session messages to history (remove the "current" session marker)
            const currentSessionMessages = this.chatContainer.querySelectorAll('[data-session="current"]');
            currentSessionMessages.forEach(msg => msg.removeAttribute('data-session'));
            this.scrollToBottom();
        }

        resetAfterError() {
            setTimeout(() => {
                // Reset state
                this.guidedMode = true;
                this.currentStep = 1;
                this.userSelections = {
                    category: null,
                    material: null,
                    gemstone: null,
                    cut: null,
                    style: null,
                    customNote: null
                };

                // ✅ DON'T remove error messages - keep them visible
                // Error message should stay, then show welcome message below it
                
                // Only show welcome message if quota is not exceeded
                if (!this.quotaExceeded) {
                    const messageElement = document.createElement('div');
                    messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
                    messageElement.setAttribute('data-session', 'current');
                    messageElement.innerHTML = `
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                            <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1">
                            <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                                <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                                    Let's try again. What type of masterpiece would you like to create?
                                </p>
                                <div class="grid grid-cols-2 gap-2 mt-4">
                                    <button onclick="window.aiChat.selectOption('category', 'Ring')" 
                                            class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                        <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E3CFCF]"></span>
                                        Ring
                                    </button>
                                    <button onclick="window.aiChat.selectOption('category', 'Necklace / Pendant')" 
                                            class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                        <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E3CFCF]"></span>
                                        Necklace / Pendant
                                    </button>
                                    <button onclick="window.aiChat.selectOption('category', 'Earrings')" 
                                            class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                        <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E3CFCF]"></span>
                                        Earrings
                                    </button>
                                    <button onclick="window.aiChat.selectOption('category', 'Bracelet')" 
                                            class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E3CFCF] hover:bg-[#E3CFCF]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                        <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E3CFCF]"></span>
                                        Bracelet
                                    </button>
                                </div>
                                <p class="text-[10px] text-stone-400 italic mt-3 text-center">Or type your own choice below...</p>
                            </div>
                            <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                        </div>
                    `;
                    this.chatContainer.appendChild(messageElement);
                }
                this.scrollToBottom();
            }, 2000); // Wait 2 seconds after error message before showing welcome again
        }

        startNewDesign() {
            this.createNewDesign();
        }

        getUserFriendlyErrorMessage(error) {
            if (typeof error === 'string') {
                if (error.includes("Invalid file 'image'") || error.includes('unsupported mimetype')) {
                    return 'Modify failed: the server received an invalid image format. Please try again.';
                }
            }
            const errorMap = {
                'Prompt cannot be empty.': 'Please provide a description.',
                'Your prompt contains inappropriate content.': 'Please use appropriate language.',
                'Prompt should focus on jewelry.': 'Please describe jewellery items only.',
                'You must be logged in to generate images.': 'Please log in first.',
                'quota_exceeded': 'Daily quota limit reached.'
            };
            return errorMap[error] || 'Something went wrong. Please try again.';
        }

        // ✅ NEW: Special message for quota exceeded
        addQuotaExceededMessage(quotaLimit, message) {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 max-w-lg">
                    <div class="bg-white border border-stone-200 rounded-2xl rounded-tl-none px-6 py-5 shadow-sm">
                        <p class="text-xs font-bold text-stone-500 uppercase tracking-widest mb-3">Daily Limit Reached</p>
                        <p class="text-stone-700 text-sm leading-relaxed font-light mb-4">
                            You have reached your daily limit of <strong class="font-semibold">${quotaLimit} AI generations</strong>. Your creativity knows no bounds, but our quota does!
                        </p>
                        <div class="bg-stone-50 rounded-lg px-4 py-3.5 border border-stone-100">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-stone-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs text-stone-600 leading-relaxed">
                                    Your quota will automatically reset tomorrow at midnight. In the meantime, feel free to browse your previous designs or explore our jewellery collection.
                                </p>
                            </div>
                        </div>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                </div>
            `;
            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();
        }

        handleInputChange() {
            const count = this.promptInput.value.length;
            this.charCount.textContent = count;
            if (count > 450) {
                this.charCount.classList.add('text-rose-500');
            } else {
                this.charCount.classList.remove('text-rose-500');
            }
        }

        toggleSendButton() {
            const hasText = this.promptInput.value.trim().length > 0;
            if (hasText) {
                this.sendButton.classList.remove('opacity-0', 'scale-90', 'pointer-events-none');
                this.sendButton.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
            } else {
                this.sendButton.classList.add('opacity-0', 'scale-90', 'pointer-events-none');
                this.sendButton.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
            }
        }

        autoResize() {
            this.promptInput.style.height = 'auto';
            const newHeight = Math.min(this.promptInput.scrollHeight, 120);
            this.promptInput.style.height = newHeight + 'px';
        }

        addUserMessage(message) {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex justify-end animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.innerHTML = `
                <div class="max-w-[85%] md:max-w-lg">
                    <div class="bg-stone-900 text-stone-50 rounded-2xl rounded-tr-none px-6 py-4 shadow-md">
                        <p class="text-sm font-light leading-relaxed">${this.escapeHtml(message)}</p>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 mr-2 block text-right font-mono">${this.getCurrentTime()}</span>
                </div>
            `;
            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();
        }

        addAIMessage(message) {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 max-w-lg">
                    <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                        <p class="text-stone-700 text-sm leading-relaxed font-light">${this.escapeHtml(message)}</p>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">AI CECILE &bull; ${this.getCurrentTime()}</span>
                </div>
            `;
            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();
        }

        addAIImageMessage(imageUrl, chatId = null) {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
            messageElement.setAttribute('data-session', 'current');
            messageElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 max-w-lg">
                    <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none p-2 shadow-[0_2px_15px_rgb(0,0,0,0.04)] overflow-hidden">
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-3 px-4 pt-3">Design Concept</p>
                        <div class="relative group">
                            <img src="${imageUrl}" 
                                alt="Generated jewelry" 
                                class="w-full rounded-xl shadow-inner cursor-zoom-in hover:brightness-105 transition duration-500"
                                onclick="openLightbox('${imageUrl}', 'ai-image-modal')">
                            <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="window.open('${imageUrl}', '_blank')" 
                                        class="bg-white/90 text-stone-900 p-2 rounded-full hover:bg-white shadow-sm transition-all hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Consult (Full Width) -->
                        <div class="px-4 pb-2 pt-3">
                            <a href="{{ route('consultation.index') }}" 
                               class="block w-full bg-[#E3CFCF] hover:bg-[#E3CFCF] text-white text-center py-3 px-4 rounded-lg transition-all duration-300 font-medium text-sm shadow-sm hover:shadow-md group">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                    <span class="uppercase tracking-wide text-xs font-bold">Consult with this Design</span>
                                </div>
                            </a>
                        </div>

                        <!-- Actions (Two Buttons Side-by-Side) -->
                        <div class="px-4 pb-4 pt-2">
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick="window.aiChat.startModify(${chatId})"
                                        ${chatId ? '' : 'disabled'}
                                        class="w-full border border-stone-300 text-stone-700 text-center py-3 px-4 rounded-lg transition-all duration-300 font-medium text-sm hover:border-stone-900 hover:text-stone-900 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="uppercase tracking-wide text-xs font-bold">Modify this Design</span>
                                </button>
                                <button type="button" onclick="window.aiChat.createNewDesign()"
                                        class="w-full border border-stone-300 text-stone-700 text-center py-3 px-4 rounded-lg transition-all duration-300 font-medium text-sm hover:border-stone-900 hover:text-stone-900">
                                    <span class="uppercase tracking-wide text-xs font-bold">Create new Design</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">AI CECILE &bull; ${this.getCurrentTime()}</span>
                </div>
            `;
            this.chatContainer.appendChild(messageElement);
            this.scrollToBottom();
        }

        addLoadingMessage() {
            const loadingElement = document.createElement('div');
            loadingElement.className = 'flex items-start gap-4 animate-fade-in-up';
            loadingElement.id = 'loading-message';
            loadingElement.innerHTML = `
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="bg-white rounded-2xl rounded-tl-none px-6 py-4 shadow-sm border border-stone-100">
                    <div class="flex items-center gap-3">
                        <div class="flex space-x-1">
                            <div class="w-1.5 h-1.5 bg-stone-400 rounded-full animate-bounce"></div>
                            <div class="w-1.5 h-1.5 bg-stone-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-1.5 h-1.5 bg-stone-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                        <span class="text-stone-400 text-xs font-medium uppercase tracking-wider">Crafting Design...</span>
                    </div>
                </div>
            `;
            this.chatContainer.appendChild(loadingElement);
            this.scrollToBottom();
        }

        removeLoadingMessage() {
            const loadingElement = document.getElementById('loading-message');
            if (loadingElement) loadingElement.remove();
        }

        setLoading(isLoading) {
            if (isLoading) {
                this.addLoadingMessage();
                this.sendButton.disabled = true;
                this.promptInput.disabled = true;
                this.sendIcon.classList.add('hidden');
                this.loadingSpinner.classList.remove('hidden');
            } else {
                this.removeLoadingMessage();
                this.sendButton.disabled = this.inputLocked;
                this.promptInput.disabled = this.inputLocked;
                this.sendIcon.classList.remove('hidden');
                this.loadingSpinner.classList.add('hidden');
                if (!this.inputLocked) {
                    this.promptInput.focus();
                }
            }
        }

        scrollToBottom() {
            this.scrollArea.scrollTop = this.scrollArea.scrollHeight;
        }

        getCurrentTime() {
            return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }

    function deleteChat(chatId) {
        if (!confirm('Are you sure you want to discard this design?')) return;
        
        fetch(`/gemma/delete/${chatId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
            else alert('Failed to delete chat');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        new AIChat();
    });
</script>

<style>
    #promptInput::-webkit-scrollbar { display: none; }
    #promptInput { scrollbar-width: none; -ms-overflow-style: none; }
    
    /* Animation Keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }
</style>
@endif
@endsection