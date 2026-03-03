<!-- Image Lightbox Modal Component -->
<div id="{{ $modalId ?? 'image-modal' }}" class="hidden fixed inset-0 bg-black/90 z-[10001] flex items-center justify-center p-4" onclick="closeLightbox('{{ $modalId ?? 'image-modal' }}')">
    <div class="relative" onclick="event.stopPropagation()">
        <!-- Close Button -->
        <button onclick="closeLightbox('{{ $modalId ?? 'image-modal' }}')" 
                class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors cursor-pointer z-[10002]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        <!-- Image Container -->
        <img id="{{ $modalId ?? 'image-modal' }}-image" 
             src="" 
             alt="{{ $alt ?? 'Image' }}" 
             class="{{ $imageClass ?? 'w-96 max-h-[80vh] object-contain rounded-lg' }}">
    </div>
</div>

@pushOnce('scripts')
<script>
(function() {
    'use strict';
    
    // Only define if not already defined
    if (typeof window.openLightbox === 'undefined') {
        /**
         * Open image lightbox modal for SINGLE image
         * @param {string} imageSrc - URL of the image
         * @param {string} modalId - ID of the modal (default: 'image-modal')
         */
        window.openLightbox = function(imageSrc, modalId = 'image-modal') {
            console.log('🖼️ Opening single image lightbox:', modalId);
            const modal = document.getElementById(modalId);
            const image = document.getElementById(modalId + '-image');
            
            if (modal && image) {
                image.src = imageSrc;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                console.error('❌ Lightbox modal or image not found:', modalId);
            }
        };
    }

    if (typeof window.closeLightbox === 'undefined') {
        /**
         * Close image lightbox modal
         * @param {string} modalId - ID of the modal (default: 'image-modal')
         */
        window.closeLightbox = function(modalId = 'image-modal') {
            console.log('❌ Closing single image lightbox:', modalId);
            const modal = document.getElementById(modalId);
            
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        };
    }

    // Close modal with ESC key - Only register once
    if (!window._imageLightboxEscListenerRegistered) {
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close all visible image lightbox modals
                document.querySelectorAll('[id*="image-modal"]:not(.hidden), [id*="-modal"]:not(.hidden)').forEach(modal => {
                    if (modal.id.includes('image') || modal.id.includes('lightbox')) {
                        window.closeLightbox(modal.id);
                    }
                });
            }
        });
        window._imageLightboxEscListenerRegistered = true;
    }
})();
</script>
@endPushOnce