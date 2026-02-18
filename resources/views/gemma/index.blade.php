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

            {{-- Welcome Message & First Question - Show AFTER history --}}
            @auth
                <div class="flex items-start gap-4 animate-fade-in-up" id="welcomeMessage">
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
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E8B49E]"></span>
                                    Ring
                                </button>
                                <button onclick="window.aiChat.selectOption('category', 'Necklace / Pendant')" 
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E8B49E]"></span>
                                    Necklace / Pendant
                                </button>
                                <button onclick="window.aiChat.selectOption('category', 'Earrings')" 
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E8B49E]"></span>
                                    Earrings
                                </button>
                                <button onclick="window.aiChat.selectOption('category', 'Bracelet')" 
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E8B49E]"></span>
                                    Bracelet
                                </button>
                            </div>
                            <p class="text-[10px] text-stone-400 italic mt-3 text-center">Or type your own choice below...</p>
                        </div>
                        <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; NOW</span>
                    </div>
                </div>
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
            <form id="chatForm" class="relative group">
                @csrf
                <div class="relative bg-stone-50 rounded-2xl border border-stone-200 overflow-hidden transition-all duration-300 focus-within:bg-white focus-within:ring-1 focus-within:ring-stone-300 focus-within:border-stone-300 focus-within:shadow-[0_4px_20px_rgb(0,0,0,0.06)]">
                    
                    <textarea 
                        id="promptInput" 
                        name="prompt" 
                        class="w-full px-5 py-4 pr-14 bg-transparent border-0 focus:ring-0 focus:outline-none text-stone-800 placeholder-stone-400 text-sm leading-relaxed resize-none"
                        rows="1"
                        maxlength="500"
                        placeholder="Type your choice or describe your jewelry..."></textarea>
                    
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
            this.chatContainer = document.getElementById('chatContainer');
            this.scrollArea = document.getElementById('scrollArea');
            this.chatForm = document.getElementById('chatForm');
            this.promptInput = document.getElementById('promptInput');
            this.sendButton = document.getElementById('sendButton');
            this.sendIcon = document.getElementById('sendIcon');
            this.loadingSpinner = document.getElementById('loadingSpinner');
            this.charCount = document.getElementById('charCount');
            
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
            
            this.init();
            
            // Make instance globally accessible
            window.aiChat = this;
        }

        init() {
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
                                    class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">18k Yellow Gold</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Classic, Luxurious</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('material', '18k White Gold / Platinum')" 
                                    class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">18k White Gold / Platinum</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Modern, Clean</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('material', '18k Rose Gold')" 
                                    class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">18k Rose Gold</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Romantic, Feminine</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('material', 'Mixed Metals')" 
                                    class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
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
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm transition-all duration-200">
                                Classic Diamond
                            </button>
                            <button onclick="window.aiChat.selectOption('gemstone', 'Blue Sapphire')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm transition-all duration-200">
                                Blue Sapphire
                            </button>
                            <button onclick="window.aiChat.selectOption('gemstone', 'Ruby')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm transition-all duration-200">
                                Ruby
                            </button>
                            <button onclick="window.aiChat.selectOption('gemstone', 'Emerald')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm transition-all duration-200">
                                Emerald
                            </button>
                            <button onclick="window.aiChat.selectOption('gemstone', 'No Gemstone / Plain Metal')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm transition-all duration-200 col-span-2">
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
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Round Brilliant</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Most Popular Cut</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('cut', 'Oval Cut')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Oval Cut</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Elegant & Elongated</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('cut', 'Emerald Cut')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Emerald Cut</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Rectangular, Vintage Style</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('cut', 'Pear Shape')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Pear Shape</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Teardrop, Unique</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('cut', 'Princess Cut')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
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
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Minimalist & Modern</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Simple, Clean, Sleek</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('style', 'Art Deco / Vintage')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Art Deco / Vintage</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Intricate Details, 1920s Style</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('style', 'Nature Inspired')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
                                <span class="font-medium">Nature Inspired</span>
                                <span class="block text-xs text-stone-500 mt-0.5">Floral, Leaves, Organic</span>
                            </button>
                            <button onclick="window.aiChat.selectOption('style', 'Royal & Luxurious')" 
                                    class="px-4 py-2.5 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-left text-stone-700 text-sm transition-all duration-200">
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
                        <button onclick="window.aiChat.generateFromSummary()" 
                                class="w-full px-6 py-3 bg-[#E8B49E] text-white text-sm font-medium uppercase tracking-wider hover:bg-[#D4A088] transition-all duration-200 shadow-sm">
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

        generateFromSummary() {
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
            const prompt = this.promptInput.value.trim();
            if (!prompt) return;

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
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ prompt: prompt })
                });

                const data = await response.json();
                
                console.log('🔍 Response data:', data);

                if (data.success) {
                    this.addAIImageMessage(data.image_url);
                    this.resetConversation();
                } else {
                    console.error('❌ Error response:', data);
                    const errorMessage = this.getUserFriendlyErrorMessage(data.error);
                    this.addAIMessage(errorMessage);
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

        resetConversation() {
            // Convert current session messages to history (remove the "current" session marker)
            const currentSessionMessages = this.chatContainer.querySelectorAll('[data-session="current"]');
            currentSessionMessages.forEach(msg => msg.removeAttribute('data-session'));
            
            setTimeout(() => {
                const messageElement = document.createElement('div');
                messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
                messageElement.innerHTML = `
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 border border-stone-200 overflow-hidden">
                        <img src="{{ asset('img/logo.PNG') }}" alt="Cecile AI" class="w-full h-full object-contain">
                    </div>
                    <div class="flex-1 max-w-lg">
                        <div class="bg-white border border-stone-100 rounded-2xl rounded-tl-none px-6 py-5 shadow-[0_2px_15px_rgb(0,0,0,0.04)]">
                            <p class="text-stone-700 text-sm leading-relaxed font-light mb-3">
                                Magnificent! Your design has been created. Would you like to create another piece?
                            </p>
                            <button onclick="window.aiChat.startNewDesign()" 
                                    class="w-full px-6 py-3 border border-stone-300 text-stone-700 text-sm font-medium uppercase tracking-wider hover:border-stone-900 hover:text-stone-900 transition-all duration-200">
                                Start New Design
                            </button>
                        </div>
                        <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                    </div>
                `;
                this.chatContainer.appendChild(messageElement);
                this.scrollToBottom();
            }, 1000);
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

                // Remove current session messages
                const currentSessionMessages = this.chatContainer.querySelectorAll('[data-session="current"]');
                currentSessionMessages.forEach(msg => msg.remove());

                // Show welcome message again
                const messageElement = document.createElement('div');
                messageElement.className = 'flex items-start gap-4 animate-fade-in-up';
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
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E8B49E]"></span>
                                    Ring
                                </button>
                                <button onclick="window.aiChat.selectOption('category', 'Necklace / Pendant')" 
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E8B49E]"></span>
                                    Necklace / Pendant
                                </button>
                                <button onclick="window.aiChat.selectOption('category', 'Earrings')" 
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E8B49E]"></span>
                                    Earrings
                                </button>
                                <button onclick="window.aiChat.selectOption('category', 'Bracelet')" 
                                        class="px-4 py-3 bg-stone-50 border border-stone-200 hover:border-[#E8B49E] hover:bg-[#E8B49E]/5 text-stone-700 text-sm font-medium transition-all duration-200 group">
                                    <span class="block text-xs uppercase tracking-wider text-stone-500 group-hover:text-[#E8B49E]"></span>
                                    Bracelet
                                </button>
                            </div>
                            <p class="text-[10px] text-stone-400 italic mt-3 text-center">Or type your own choice below...</p>
                        </div>
                        <span class="text-[10px] text-stone-400 mt-2 ml-2 block font-mono">CECILE AI &bull; ${this.getCurrentTime()}</span>
                    </div>
                `;
                this.chatContainer.appendChild(messageElement);
                this.scrollToBottom();
            }, 2000); // Wait 2 seconds after error message before showing welcome again
        }

        startNewDesign() {
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
            
            location.reload(); // Simplest way to reset
        }

        getUserFriendlyErrorMessage(error) {
            const errorMap = {
                'Prompt cannot be empty.': 'Please provide a description.',
                'Your prompt contains inappropriate content.': 'Please use appropriate language.',
                'Prompt should focus on jewelry.': 'Please describe jewelry items only.',
                'You must be logged in to generate images.': 'Please log in first.'
            };
            return errorMap[error] || 'Something went wrong. Please try again.';
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

        addAIImageMessage(imageUrl) {
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
                                onclick="this.requestFullscreen()">
                            <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="window.open('${imageUrl}', '_blank')" 
                                        class="bg-white/90 text-stone-900 p-2 rounded-full hover:bg-white shadow-sm transition-all hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
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
                this.sendButton.disabled = false;
                this.promptInput.disabled = false;
                this.sendIcon.classList.remove('hidden');
                this.loadingSpinner.classList.add('hidden');
                this.promptInput.focus();
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