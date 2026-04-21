{{-- Contact Us Modal Component --}}
<div id="contactModal" class="hidden fixed inset-0 z-[9999] bg-black/60 flex items-center justify-center p-4 transition-opacity duration-300 opacity-0">
    <div class="bg-white w-full max-w-lg shadow-2xl transform transition-all duration-300 scale-95 relative max-h-[90vh] overflow-y-auto">
        
        {{-- Close Button --}}
        <button type="button" onclick="closeContactModal()" class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-900 transition-colors p-2 rounded-full hover:bg-gray-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Modal Content --}}
        <div class="p-8 md:p-12">
            
            {{-- Header --}}
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-normal uppercase tracking-[0.2em] text-gray-900 mb-2">Get In Touch</h2>
                <p class="text-sm text-gray-500 tracking-wider">We're here to help you</p>
            </div>

            {{-- Contact Options --}}
            <div class="space-y-6">
                
                {{-- WhatsApp --}}
                <a href="https://wa.me/6287766999564" target="_blank" rel="noopener noreferrer" 
                   class="group flex items-start gap-4 p-6 bg-gray-50 hover:bg-gray-100 transition-all duration-300 border border-gray-100 hover:border-gray-300">
                    <div class="flex-shrink-0 w-12 h-12 bg-white rounded-full flex items-center justify-center text-green-600 group-hover:text-green-700 transition-colors shadow-sm">
                        {{-- WhatsApp Icon --}}
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-2">WhatsApp</h3>
                        <p class="text-sm text-gray-600 font-mono">+62 877 6699 9564</p>
                        <p class="text-xs text-gray-500 mt-2 font-light">Click to chat with us instantly</p>
                    </div>
                    <div class="flex-shrink-0 text-gray-300 group-hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                {{-- Email --}}
                     <a href="mailto:{{ config('mail.from.address') }}" 
                   class="group flex items-start gap-4 p-6 bg-gray-50 hover:bg-gray-100 transition-all duration-300 border border-gray-100 hover:border-gray-300">
                    <div class="flex-shrink-0 w-12 h-12 bg-white rounded-full flex items-center justify-center text-gray-700 group-hover:text-gray-900 transition-colors shadow-sm">
                        {{-- Email Icon --}}
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-2">Email</h3>
                        <p class="text-sm text-gray-600 break-all font-mono">{{ config('mail.from.address') }}</p>
                        <p class="text-xs text-gray-500 mt-2 font-light">Send us an email inquiry</p>
                    </div>
                    <div class="flex-shrink-0 text-gray-300 group-hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

            </div>

            {{-- Additional Info --}}
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-light leading-relaxed">
                    Our team typically responds within 24 hours during business hours.<br>
                    For urgent inquiries, please reach us via WhatsApp.
                </p>
            </div>

            {{-- Close Button --}}
            <div class="mt-8 text-center">
                <button type="button" onclick="closeContactModal()" 
                        class="px-8 py-3 bg-gray-900 text-white text-xs uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openContactModal() {
        const modal = document.getElementById('contactModal');
        if (!modal) return;
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Trigger transition
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
            modal.querySelector('div').classList.add('scale-100');
        });
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeContactModal() {
        const modal = document.getElementById('contactModal');
        if (!modal) return;
        
        // Trigger exit transition
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.remove('scale-100');
        modal.querySelector('div').classList.add('scale-95');
        
        // Hide after transition
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('contactModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeContactModal();
            }
        }
    });

    // Close on backdrop click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('contactModal');
        if (e.target === modal) {
            closeContactModal();
        }
    });
</script>
