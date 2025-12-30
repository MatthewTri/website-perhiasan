{{-- resources/views/components/product-show-detail.blade.php --}}

<div id="productLightboxModal" class="hidden fixed inset-0 z-[10000] bg-white flex flex-col transition-opacity duration-300 opacity-0" role="dialog" aria-modal="true">

    {{-- Main Content: Image & Navigation --}}
    <div class="flex-1 relative flex items-center justify-center bg-white h-full w-full overflow-hidden">
        
        {{-- Previous Button --}}
        <button type="button" id="lightboxPrevBtn" class="absolute left-4 md:left-8 z-10 text-gray-400 hover:text-gray-900 transition-colors p-3 rounded-full hover:bg-gray-50 focus:outline-none hidden md:block">
            <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Image Container (Swiper) --}}
        <div class="swiper lightbox-swiper w-full h-full">
            <div class="swiper-wrapper" id="lightboxImageWrapper">
                {{-- Slides will be populated via JS --}}
            </div>
        </div>

        {{-- Next Button --}}
        <button type="button" id="lightboxNextBtn" class="absolute right-4 md:right-8 z-10 text-gray-400 hover:text-gray-900 transition-colors p-3 rounded-full hover:bg-gray-50 focus:outline-none hidden md:block">
            <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    {{-- Footer: Dots & Close (Bottom - Mobile Friendly) --}}
    <div class="absolute bottom-8 left-0 right-0 flex flex-col items-center justify-center gap-6 z-20 pointer-events-none">
        
        {{-- Pagination Dots --}}
        <div class="swiper-pagination !static !w-auto pointer-events-auto"></div>

        {{-- Bottom Close Button (Cancel) --}}
        <button type="button" onclick="closeLightbox()" class="pointer-events-auto bg-white border border-gray-200 shadow-sm rounded-full w-12 h-12 flex items-center justify-center text-gray-900 hover:bg-gray-50 transition-transform hover:scale-105 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<style>
    /* Swiper Styling for Lightbox */
    .lightbox-swiper .swiper-slide {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background-color: white;
        cursor: grab;
    }
    .lightbox-swiper .swiper-slide:active {
        cursor: grabbing;
    }
    /* Pastikan gambar tidak pecah */
    .lightbox-swiper .swiper-slide img {
        max-width: 100%;
        max-height: 85vh; /* Beri ruang atas bawah */
        object-fit: contain;
        user-select: none;
        /* Shadow halus agar gambar 'pop' */
        filter: drop-shadow(0 10px 15px rgba(0,0,0,0.05)); 
    }
    
    /* Dots Styling (Sama dengan QuickView) */
    .lightbox-swiper .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: #D1D5DB;
        opacity: 1;
        margin: 0 6px !important;
        transition: all 0.3s;
    }
    .lightbox-swiper .swiper-pagination-bullet-active {
        background: #111827;
        transform: scale(1.3);
    }
</style>

<script>
(function() {
    'use strict';
    
    let lightboxSwiper = null;

    // --- FUNGSI GLOBAL (Dipanggil dari Luar) ---

    window.openLightbox = function(images, startIndex = 0) {
        const modal = document.getElementById('productLightboxModal');
        const wrapper = document.getElementById('lightboxImageWrapper');

        // 1. Tampilkan Modal Dulu (PENTING untuk kalkulasi Swiper)
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
        });
        // --- TEKNIK KUNCI UNTUK SCROLLBAR ---
        // Simpan posisi scroll saat ini agar tidak loncat ke atas
        document.body.style.top = `-${window.scrollY}px`;
        document.body.style.position = 'fixed';
        document.body.style.width = '100%';
        document.body.style.overflowY = 'scroll';

        // 2. Isi Slide Baru
        wrapper.innerHTML = images.map(imgSrc => `
            <div class="swiper-slide bg-white flex items-center justify-center h-full">
                <img src="${imgSrc}" alt="Detail View" class="max-w-full max-h-[85vh] object-contain select-none shadow-sm" draggable="false">
            </div>
        `).join('');

        // 3. Init atau Update Swiper
        if (lightboxSwiper) {
            // Jika instance sudah ada:
            lightboxSwiper.update(); // Update jumlah slide
            
            // Trik Anti-Bug Loop: 
            // Kita gunakan slideToLoop dengan runCallbacks=false agar instan
            // Beri sedikit delay agar update() selesai memproses DOM
            setTimeout(() => {
                lightboxSwiper.slideToLoop(startIndex, 0); 
            }, 10);

        } else {
            // Jika instance belum ada (Pertama kali buka):
            lightboxSwiper = new Swiper(".lightbox-swiper", {
                slidesPerView: 1,
                spaceBetween: 30,
                // Loop aktif hanya jika gambar > 1
                loop: images.length > 1, 
                speed: 600,
                grabCursor: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: "#lightboxNextBtn",
                    prevEl: "#lightboxPrevBtn",
                },
                keyboard: {
                    enabled: true,
                },
                // Set slide awal langsung di config agar akurat
                initialSlide: startIndex, 
                observer: true,
                observeParents: true,
            });
        }
    };

    window.closeLightbox = function() {
        const modal = document.getElementById('productLightboxModal');
        
        modal.classList.add('opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            
            // --- KEMBALIKAN SCROLL ---
            const scrollY = document.body.style.top;
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.body.style.overflowY = '';
            
            // Scroll balik ke posisi semula
            window.scrollTo(0, parseInt(scrollY || '0') * -1);
            // -------------------------

            if (lightboxSwiper) {
                lightboxSwiper.destroy(true, true);
                lightboxSwiper = null;
            }
        }, 300);
    };

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('productLightboxModal');
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeLightbox();
        }
    });

})();
</script>