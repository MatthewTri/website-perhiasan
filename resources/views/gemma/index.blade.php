@extends('layout.ai')

@section('content')

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
                    <div class="w-9 h-9 bg-gradient-to-br from-[#E8B49E] to-[#D4A088] rounded-full flex items-center justify-center shadow-sm">
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
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#E8B49E] via-[#D4A088] to-[#E8B49E]"></div>
                
                {{-- Content --}}
                <div class="p-8 sm:p-12 text-center">
                    
                    {{-- Icon --}}
                    <div class="mb-6">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-[#E8B49E]/10 to-[#D4A088]/10 rounded-full flex items-center justify-center border border-[#E8B49E]/20">
                            <svg class="w-10 h-10 text-[#E8B49E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Title --}}
                    <h2 class="text-2xl sm:text-3xl font-serif text-gray-900 mb-3">
                        Authentication Required
                    </h2>
                    
                    <p class="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-gray-400 font-medium mb-6">
                        Access Restricted to Members Only
                    </p>

                    {{-- Description --}}
                    <div class="space-y-3 mb-8">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Our AI Design Studio is an exclusive feature available to registered members.
                        </p>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Create an account or sign in to start visualizing your custom jewelry designs with artificial intelligence.
                        </p>
                    </div>

                    {{-- Benefits List --}}
                    <div class="bg-gradient-to-br from-stone-50 to-stone-100/50 border border-stone-200 p-5 mb-8 text-left">
                        <p class="text-[10px] uppercase tracking-[0.15em] text-gray-500 font-medium mb-3">Members Benefits</p>
                        <ul class="space-y-2 text-xs text-gray-600">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-[#E8B49E] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>AI-powered jewelry design visualization</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-[#E8B49E] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Save and manage your design history</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="space-y-3">
                        <a href="{{ route('login') }}" 
                           class="block w-full px-8 py-4 bg-[#E8B49E] text-white text-xs uppercase tracking-[0.15em] font-medium hover:bg-[#D4A088] transition-all duration-200 shadow-sm hover:shadow-md">
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
            
            {{-- RIGHT: Status Indicator --}}
            <div class="flex items-center gap-2">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-widest">Active</span>
            </div>
        </div>
    </div>

    {{-- CHAT CONTAINER --}}
    <div class="flex-1 overflow-y-auto p-4 md:p-8" id="scrollArea">
        <div class="max-w-3xl mx-auto space-y-8" id="chatContainer">
            
            {{-- Welcome Message --}}
            <div class="flex items-start gap-4 animate-fade-in-up">
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                </div>
                <div class="flex-1">
                    <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                        <p class="text-stone-700 text-sm leading-relaxed font-light">
                            Welcome to your private design studio. I am Cecile AI. 
                            @auth
                                Describe your dream jewelry piece — perhaps an <em>"Art Deco emerald ring"</em> or a <em>"minimalist gold pendant"</em> — and I shall visualize it for you.
                            @else
                                Please <a href="{{ route('login') }}" class="text-stone-900 font-medium underline underline-offset-2 hover:text-amber-600 transition-colors">sign in</a> to begin your design journey.
                            @endauth
                        </p>
                    </div>
                    <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">SYSTEM &bull; NOW</span>
                </div>
            </div>

            {{-- Chat History --}}
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
                                         onclick="this.requestFullscreen()">
                                    
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
                            </div>
                            <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">AI Cecile &bull; {{ $chat->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            @endauth
            
        </div>
    </div>

    {{-- INPUT FORM --}}
    <div class="bg-white border-t border-stone-200 px-4 py-6 flex-shrink-0 z-40">
        <div class="max-w-3xl mx-auto w-full">
            <form id="chatForm" class="relative group">
                @csrf
                {{-- 
                    PERBAIKAN:
                    1. Menghapus 'focus-within:border-stone-400' dan menggantinya dengan 'ring' custom agar tidak ada 'jump' layout.
                    2. Menambahkan 'overflow-hidden' pada wrapper agar rapi.
                --}}
                <div class="relative bg-stone-50 rounded-2xl border border-stone-200 overflow-hidden transition-all duration-300 focus-within:bg-white focus-within:ring-1 focus-within:ring-stone-300 focus-within:border-stone-300 focus-within:shadow-[0_4px_20px_rgb(0,0,0,0.06)]">
                    
                    {{-- 
                        PERBAIKAN INPUT:
                        1. focus:outline-none: Memastikan tidak ada outline biru browser.
                        2. focus:ring-0: Mematikan ring Tailwind di textarea (kita pakai ring di wrapper).
                        3. border-0: Memastikan tidak ada border sama sekali di textarea.
                    --}}
                    <textarea 
                        id="promptInput" 
                        name="prompt" 
                        class="w-full px-5 py-4 pr-14 bg-transparent border-0 focus:ring-0 focus:outline-none text-stone-800 placeholder-stone-400 text-sm leading-relaxed resize-none"
                        rows="1"
                        maxlength="500"></textarea>
                    
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
    </div>
</div>

<script>
class AIChat {
    constructor() {
        this.chatContainer = document.getElementById('chatContainer'); // Selector tetap sama, tapi sekarang menunjuk ke wrapper inner
        this.scrollArea = document.getElementById('scrollArea'); // Selector baru untuk scrollable area
        this.chatForm = document.getElementById('chatForm');
        this.promptInput = document.getElementById('promptInput');
        this.sendButton = document.getElementById('sendButton');
        this.sendIcon = document.getElementById('sendIcon');
        this.loadingSpinner = document.getElementById('loadingSpinner');
        this.charCount = document.getElementById('charCount');
        
        this.init();
    }

    init() {
        // ... (Logic init sama persis, tidak berubah) ...
        this.promptInput.addEventListener('input', () => {
            this.handleInputChange();
            this.autoResize();
        });
        this.promptInput.addEventListener('input', () => {
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
        // Scroll ke bawah saat load
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
        const maxHeight = 120;
        const newHeight = Math.min(this.promptInput.scrollHeight, maxHeight);
        this.promptInput.style.height = newHeight + 'px';
    }

    async sendMessage() {
        const prompt = this.promptInput.value.trim();
        if (!prompt) return;

        this.addUserMessage(prompt);
        
        this.promptInput.value = '';
        this.charCount.textContent = '0';
        this.promptInput.style.height = 'auto';
        this.toggleSendButton();
        
        this.setLoading(true);
        
        try {
            const response = await fetch('{{ route("gemma.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ prompt: prompt })
            });

            const data = await response.json();

            if (data.success) {
                this.addAIImageMessage(data.image_url);
            } else {
                const errorMessage = this.getUserFriendlyErrorMessage(data.error);
                this.addAIMessage(errorMessage);
            }
        } catch (error) {
            console.error('Error:', error);
            let networkErrorMessage = 'Sorry, something went wrong. Please check your connection and try again.';
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                networkErrorMessage = 'Cannot connect to AI service. Please check if the service is running.';
            } else if (error.name === 'AbortError') {
                networkErrorMessage = 'Request timed out. The AI service might be busy. Please try again.';
            }
            this.addAIMessage(networkErrorMessage);
        } finally {
            this.setLoading(false);
        }
    }

    getUserFriendlyErrorMessage(error) {
        const errorMap = {
            'Prompt cannot be empty.': 'Please provide a description for the jewelry you want to generate.',
            'Your prompt contains inappropriate content.': 'I cannot generate images with inappropriate content. Please try a different description.',
            'Prompt should focus on jewelry.': 'Please describe jewelry items only. I specialize in creating jewelry images like rings, necklaces, earrings, etc.',
            'Server belum siap, coba beberapa saat lagi.': 'AI service is starting up. Please try again in a moment.',
            'Prompt tidak relevan dengan perhiasan.': 'Your description doesn\'t seem to be about jewelry. Please describe jewelry items like rings, necklaces, bracelets, etc.',
            'Sorry, there\'s an issue with the AI service.': 'The AI service is temporarily unavailable. Please try again later.',
            'AI service is not available. Please make sure the Node.js server is running on port 3001.': 'The AI service is temporarily unavailable. Please try again later.',
            'You must be logged in to generate images.': 'Please log in to generate images and save your chat history.'
        };
        return errorMap[error] || (error.length < 150 ? error : 'Sorry, something went wrong. Please try again.');
    }

    // --- HTML RENDER METHODS ---

    addUserMessage(message) {
        const messageElement = document.createElement('div');
        messageElement.className = 'flex justify-end animate-fade-in-up';
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
        messageElement.innerHTML = `
            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
            </div>
            <div class="flex-1 max-w-lg">
                <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                    <p class="text-stone-700 text-sm leading-relaxed font-light">${this.escapeHtml(message)}</p>
                </div>
                <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">AI Cecile &bull; ${this.getCurrentTime()}</span>
            </div>
        `;
        this.chatContainer.appendChild(messageElement);
        this.scrollToBottom();
    }

    addAIImageMessage(imageUrl) {
        const messageElement = document.createElement('div');
        messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
        messageElement.innerHTML = `
            <div class="w-8 h-8 bg-stone-100 rounded-full flex items-center justify-center flex-shrink-0 text-stone-600 border border-stone-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M9.661 2.237a.531.531 0 0 1 .678 0 11.947 11.947 0 0 0 6.878 2.743c.284 0 .548.06.748.243.2.184.288.455.288.79V11a9 9 0 0 1-9 9 9 9 0 0 1-9-9V6.013c0-.335.088-.606.288-.79.2-.184.464-.243.748-.243a11.947 11.947 0 0 0 6.878-2.744Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex-1 max-w-lg">
                <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none p-2 shadow-[0_2px_15px_rgb(0,0,0,0.04)] overflow-hidden">
                    <p class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-3 px-4 pt-3">Design Concept</p>
                    <div class="relative group">
                        <img src="${imageUrl}" 
                             alt="Generated jewelry" 
                             class="w-full rounded-xl shadow-inner cursor-zoom-in hover:brightness-105 transition duration-500 ease-in-out"
                             onclick="this.requestFullscreen()">
                        <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button onclick="window.open('${imageUrl}', '_blank')" 
                                    class="bg-white/90 text-stone-900 p-2 rounded-full hover:bg-white shadow-sm transition-all hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">AI Cecile &bull; ${this.getCurrentTime()}</span>
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
        if (loadingElement) {
            loadingElement.remove();
        }
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
            this.sendButton.disabled = false;
            this.promptInput.disabled = false;
            this.sendIcon.classList.remove('hidden');
            this.loadingSpinner.classList.add('hidden');
            this.promptInput.focus();
        }
    }

    // Scroll pada elemen pembungkus utama (#scrollArea), bukan inner wrapper (#chatContainer)
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
        if (data.success) {
            location.reload(); 
        } else {
            alert('Failed to delete chat');
        }
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