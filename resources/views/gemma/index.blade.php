@extends('layout.main')

@section('content')
<div class="flex flex-col h-screen"> <!-- Full height viewport -->
    <!-- Header -->
    <div class="bg-white shadow-sm border-b px-6 py-4 flex-shrink-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold">🤖</span>
                </div>
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">AI Image Generator</h1>
                    <p class="text-sm text-gray-500">Generate beautiful jewelry images with AI</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">● Online</span>
            </div>
        </div>
    </div>

    <!-- Chat Container - Flexible height -->
    <div class="flex-1 bg-gray-50 overflow-y-auto p-6 space-y-4" id="chatContainer">
        <!-- Welcome Message -->
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-sm">🤖</span>
            </div>
            <div class="bg-white rounded-lg px-4 py-3 shadow-sm max-w-md">
                <p class="text-gray-700 text-sm">
                    Hi! I'm your AI jewelry image generator. Send me a description of the jewelry you'd like to see, and I'll create a beautiful image for you!
                </p>
                <span class="text-xs text-gray-400 mt-1 block">Just now</span>
            </div>
        </div>

        <!-- Chat messages will be appended here -->
    </div>

    <!-- Input Area - Fixed at bottom -->
    <div class="bg-white shadow-sm border-t px-6 py-4 flex-shrink-0">
        <form id="chatForm" class="relative">
            @csrf
            <div class="relative">
                <textarea 
                    id="promptInput" 
                    name="prompt" 
                    placeholder="Describe the jewelry you want me to generate... (e.g., 'elegant gold necklace with diamonds')"
                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    rows="1"
                    maxlength="500"></textarea>
                
                <!-- Send Button - Initially hidden -->
                <button 
                    type="submit" 
                    id="sendButton"
                    class="absolute right-2 bottom-3 w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full transition-all duration-200 flex items-center justify-center opacity-0 pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed">
                    <!-- Send Icon -->
                    <svg id="sendIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <!-- Loading Spinner (hidden by default) -->
                    <div id="loadingSpinner" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin hidden"></div>
                </button>
            </div>
            
            <!-- Character counter -->
            <div class="text-xs text-gray-400 mt-2 text-right">
                <span id="charCount">0</span>/500 characters
            </div>
        </form>
    </div>
</div>

<script>
class AIChat {
    constructor() {
        this.chatContainer = document.getElementById('chatContainer');
        this.chatForm = document.getElementById('chatForm');
        this.promptInput = document.getElementById('promptInput');
        this.sendButton = document.getElementById('sendButton');
        this.sendIcon = document.getElementById('sendIcon');
        this.loadingSpinner = document.getElementById('loadingSpinner');
        this.charCount = document.getElementById('charCount');
        
        this.init();
    }

    init() {
        // Auto-resize textarea
        this.promptInput.addEventListener('input', () => {
            this.handleInputChange();
            this.autoResize();
        });

        // Show/hide send button based on input
        this.promptInput.addEventListener('input', () => {
            this.toggleSendButton();
        });

        // Form submission
        this.chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessage();
        });

        // Enter key to send (Shift+Enter for new line)
        this.promptInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });

        // Initial button state
        this.toggleSendButton();
    }

    handleInputChange() {
        const count = this.promptInput.value.length;
        this.charCount.textContent = count;
        
        if (count > 450) {
            this.charCount.classList.add('text-red-500');
        } else {
            this.charCount.classList.remove('text-red-500');
        }
    }

    toggleSendButton() {
        const hasText = this.promptInput.value.trim().length > 0;
        
        if (hasText) {
            this.sendButton.classList.remove('opacity-0', 'pointer-events-none');
            this.sendButton.classList.add('opacity-100', 'pointer-events-auto');
        } else {
            this.sendButton.classList.add('opacity-0', 'pointer-events-none');
            this.sendButton.classList.remove('opacity-100', 'pointer-events-auto');
        }
    }

    autoResize() {
        // Auto-resize textarea
        this.promptInput.style.height = 'auto';
        const maxHeight = 120; // Max height in pixels (about 5 lines)
        const newHeight = Math.min(this.promptInput.scrollHeight, maxHeight);
        this.promptInput.style.height = newHeight + 'px';
    }

    async sendMessage() {
        const prompt = this.promptInput.value.trim();
        if (!prompt) return;

        // Disable input while processing
        this.setLoading(true);

        // Add user message to chat
        this.addUserMessage(prompt);

        // Clear input
        this.promptInput.value = '';
        this.charCount.textContent = '0';
        this.promptInput.style.height = 'auto'; // Reset height
        this.toggleSendButton();

        try {
            // Send to backend
            const response = await fetch('{{ route("gemma.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ prompt: prompt })
            });

            const data = await response.json();

            if (data.success) {
                this.addAIImageMessage(data.image_url);
            } else {
                this.addAIMessage('Sorry, I encountered an error while generating the image. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            this.addAIMessage('Sorry, something went wrong. Please check your connection and try again.');
        } finally {
            this.setLoading(false);
        }
    }


    addUserMessage(message) {
        const messageElement = document.createElement('div');
        messageElement.className = 'flex justify-end';
        messageElement.innerHTML = `
            <div class="bg-blue-600 text-white rounded-lg px-4 py-3 max-w-md">
                <p class="text-sm">${this.escapeHtml(message)}</p>
                <span class="text-xs text-blue-100 mt-1 block">${this.getCurrentTime()}</span>
            </div>
        `;
        
        this.chatContainer.appendChild(messageElement);
        this.scrollToBottom();
    }

    addAIMessage(message) {
        const messageElement = document.createElement('div');
        messageElement.className = 'flex items-start space-x-3';
        messageElement.innerHTML = `
            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-sm">🤖</span>
            </div>
            <div class="bg-white rounded-lg px-4 py-3 shadow-sm max-w-md">
                <p class="text-gray-700 text-sm">${this.escapeHtml(message)}</p>
                <span class="text-xs text-gray-400 mt-1 block">${this.getCurrentTime()}</span>
            </div>
        `;
        
        this.chatContainer.appendChild(messageElement);
        this.scrollToBottom();
    }

    addAIImageMessage(imageUrl) {
        const messageElement = document.createElement('div');
        messageElement.className = 'flex items-start space-x-3';
        messageElement.innerHTML = `
            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-sm">🤖</span>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm max-w-md">
                <p class="text-gray-700 text-sm mb-3">Here's your generated jewelry image:</p>
                <div class="relative">
                    <img src="${imageUrl}" 
                         alt="Generated jewelry image" 
                         class="w-full rounded-lg shadow-sm cursor-pointer hover:opacity-90 transition"
                         onclick="this.requestFullscreen()">
                    <div class="absolute top-2 right-2">
                        <button onclick="window.open('${imageUrl}', '_blank')" 
                                class="bg-black bg-opacity-50 text-white p-1 rounded-full hover:bg-opacity-70">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <span class="text-xs text-gray-400 mt-2 block">${this.getCurrentTime()}</span>
            </div>
        `;
        
        this.chatContainer.appendChild(messageElement);
        this.scrollToBottom();
    }

    addLoadingMessage() {
        const loadingElement = document.createElement('div');
        loadingElement.className = 'flex items-start space-x-3';
        loadingElement.id = 'loading-message';
        loadingElement.innerHTML = `
            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-sm">🤖</span>
            </div>
            <div class="bg-white rounded-lg px-4 py-3 shadow-sm">
                <div class="flex items-center space-x-2">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                    <span class="text-gray-500 text-sm">Generating image...</span>
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
        }
    }

    scrollToBottom() {
        this.chatContainer.scrollTop = this.chatContainer.scrollHeight;
    }

    getCurrentTime() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    downloadImage(url) {
        const link = document.createElement('a');
        link.href = url;
        link.download = 'ai-generated-jewelry.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Initialize chat when page loads
document.addEventListener('DOMContentLoaded', function() {
    new AIChat();
});
</script>

<style>
    #promptInput::-webkit-scrollbar { display: none; }
    #promptInput { scrollbar-width: none; -ms-overflow-style: none; }
</style>
@endsection