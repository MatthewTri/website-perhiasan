<!-- Image Lightbox Modal Component -->
<div id="{{ $modalId ?? 'image-modal' }}" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" onclick="closeLightbox('{{ $modalId ?? 'image-modal' }}')">
    <div class="relative" onclick="event.stopPropagation()">
        <!-- Close Button -->
        <button onclick="closeLightbox('{{ $modalId ?? 'image-modal' }}')" 
                class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors cursor-pointer">
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
    /**
     * Open image lightbox modal
     * @param {string} imageSrc - URL of the image
     * @param {string} modalId - ID of the modal (default: 'image-modal')
     */
    function openLightbox(imageSrc, modalId = 'image-modal') {
        const modal = document.getElementById(modalId);
        const image = document.getElementById(modalId + '-image');
        
        if (modal && image) {
            image.src = imageSrc;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * Close image lightbox modal
     * @param {string} modalId - ID of the modal (default: 'image-modal')
     */
    function closeLightbox(modalId = 'image-modal') {
        const modal = document.getElementById(modalId);
        
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close all visible lightbox modals
            document.querySelectorAll('[id$="-modal"]:not(.hidden)').forEach(modal => {
                closeLightbox(modal.id);
            });
        }
    });
</script>
@endPushOnce