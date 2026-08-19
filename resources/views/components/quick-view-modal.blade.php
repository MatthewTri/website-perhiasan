<div id="quickViewModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white max-w-4xl w-full max-h-[85vh] overflow-y-auto relative shadow-2xl">
        
        {{-- Close Button --}}
        <button type="button" id="closeModalBtn" class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-900 transition-colors cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Modal Content --}}
        <div id="quickViewContent" class="grid grid-cols-1 md:grid-cols-2 gap-0">
            
            {{-- Left: Product Image & Dots --}}
            <div class="relative bg-[#F7F7F7] flex flex-col items-center justify-center p-8 md:p-10">
                
                {{-- Swiper Container --}}
                <div class="w-full max-w-md aspect-square bg-white overflow-hidden mb-4 relative">
                    <div class="swiper h-full" id="modalImageSwiper">
                        <div class="swiper-wrapper" id="modalImageSwiperWrapper">
                            {{-- Slides will be injected by JS --}}
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination" id="modalImageSwiperPagination"></div>
            </div>

            {{-- Right: Product Info --}}
            <div class="flex flex-col h-full p-8 md:p-10 bg-white">
                <h2 id="modalProductName" class="text-lg md:text-xl font-normal uppercase tracking-[0.15em] text-gray-900 mb-2 leading-tight"></h2>
                
                <p id="modalProductPrice" class="text-base md:text-lg text-gray-500 tracking-[0.05em] font-light mb-6"></p>

                {{-- Rating --}}
                <div id="modalProductRating" class="mb-6 hidden"></div>

                {{-- Color Selection --}}
                <div id="modalColorSection" class="mb-6 hidden">
                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-3">
                        Colour: <span id="modalSelectedColor" class="text-gray-900 font-medium"></span>
                    </p>
                    <div id="modalColorOptions" class="flex items-center gap-2"></div>
                </div>

                {{-- Size Selection --}}
                <div id="modalSizeSection" class="mb-6 hidden">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs uppercase tracking-widest text-gray-500">
                            Size: <span id="modalSelectedSizeText" class="text-gray-900 font-medium"></span>
                        </p>
                        <button type="button" onclick="openSizeChart()" class="text-xs text-gray-400 hover:text-gray-900 underline cursor-pointer">Size chart</button>
                    </div>
                    
                    <div class="relative">
                        <select id="modalSizeSelect" 
                                class="w-full px-4 py-3 border border-gray-200 text-sm text-gray-900 focus:outline-none focus:ring-0 focus:border-black appearance-none bg-white cursor-pointer">
                            <option value="">Select size</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4">
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-8 space-y-4">
                    <a id="modalWhatsAppInquiryBtn"
                       href="#"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-full inline-flex items-center justify-center gap-3 bg-[#E3CFCF] hover:bg-[#ddc8c8] text-white py-4 px-8 text-xs uppercase tracking-[0.2em] font-bold transition-all duration-300 cursor-pointer">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Inquiries Via WhatsApp
                    </a>
                </div>

                {{-- View Details Link --}}
                <a id="modalViewDetailsLink" href="#" class="block text-center text-[10px] uppercase tracking-[0.2em] text-gray-900 underline hover:no-underline mt-2">
                    View details
                </a>
            </div>
        </div>
    </div>
</div>


<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }

    /* Custom Swiper Dots */
    #modalImageSwiperPagination {
        text-align: center;
        width: 100%;
        position: static !important;
    }
    #modalImageSwiperPagination .swiper-pagination-bullet {
        width: 6px;
        height: 6px;
        background-color: #D1D5DB;
        opacity: 1;
        margin: 0 4px !important;
        transition: all 0.2s;
    }
    #modalImageSwiperPagination .swiper-pagination-bullet-active {
        background-color: #111827;
    }
    #modalImageSwiperPagination .swiper-pagination-bullet:hover {
        background-color: #6B7280;
    }
</style>

<script>
(function() {
    'use strict';
    
    let currentModalProduct = null;
    let currentModalImages = [];
    let modalSwiper = null; // ✅ Swiper instance

    // ✅ Event Delegation
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('quickViewModal');
        
        // Quick view button click
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.quick-view-btn');
            if (!btn) return;
            
            e.preventDefault();
            
            const productData = {
                id: parseInt(btn.dataset.productId),
                slug: btn.dataset.productSlug,
                name: btn.dataset.productName,
                price_idr: parseFloat(btn.dataset.priceIdr),
                price_usd: parseFloat(btn.dataset.priceUsd),
                url: btn.dataset.productUrl,
                rating_avg: parseFloat(btn.dataset.ratingAvg),
                rating_count: parseInt(btn.dataset.ratingCount),
                image: btn.dataset.featuredImage,
                colors: JSON.parse(btn.dataset.colors || '[]'),
                sizes: JSON.parse(btn.dataset.sizes || '[]')
            };
            
            openQuickView(productData);
        });

        // Size select change
        document.getElementById('modalSizeSelect')?.addEventListener('change', function() {
            const selectedSize = this.value;
            document.getElementById('modalSelectedSizeText').textContent = selectedSize || '-';
            updateModalInquiryLink();
        });

        // Close button
        document.getElementById('closeModalBtn')?.addEventListener('click', closeQuickView);

        // ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeQuickView();
            }
        });

        // Backdrop click
        modal?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeQuickView();
            }
        });
    });

    function openQuickView(productData) {
        currentModalProduct = productData;
        const modal = document.getElementById('quickViewModal');

        // Set featured image sebagai default
        currentModalImages = [productData.image];
        
        // Sembunyikan modal dulu
        modal.classList.add('hidden');

        // Load additional images via API (async)
        fetch(`/api/product/${productData.slug}/images`)
            .then(res => res.json())
            .then(data => {
                if (data.images && data.images.length > 0) {
                    currentModalImages = data.images;
                }
                populateModalContent(productData);
                modal.classList.remove('hidden'); // ✅ Tampilkan setelah semua siap
            })
            .catch(err => {
                console.error('Failed to load images:', err);
                populateModalContent(productData);
                modal.classList.remove('hidden');
            });
    }

    function populateModalContent(productData) {
        const modal = document.getElementById('quickViewModal');
        
        // Basic info
        document.getElementById('modalProductName').textContent = productData.name;
        document.getElementById('modalViewDetailsLink').href = productData.url;
        
        // ✅ Render Swiper Slides
        const wrapper = document.getElementById('modalImageSwiperWrapper');
        wrapper.innerHTML = '';
        
        currentModalImages.forEach((imgUrl) => {
            const slide = document.createElement('div');
            slide.className = 'swiper-slide flex items-center justify-center';
            slide.innerHTML = `<img src="${imgUrl}" alt="${productData.name}" class="w-full h-full object-cover" loading="lazy">`;
            wrapper.appendChild(slide);
        });

        // ✅ Initialize or Update Swiper
        if (modalSwiper) {
            modalSwiper.destroy(true, true); // Destroy old instance
        }
        
        modalSwiper = new Swiper('#modalImageSwiper', {
            loop: false,
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: '#modalImageSwiperPagination', // Ganti ke id baru
                clickable: true,
            },
        });
        
        const paypalEnabled = {{ $paypalEnabled ? 'true' : 'false' }};

        // Format price (no symbol, always 2 decimals)
        const priceIdr = 'IDR ' + new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(Number(productData.price_idr || 0));

        if (paypalEnabled) {
            const priceUsd = 'USD ' + new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(Number(productData.price_usd || 0));

            document.getElementById('modalProductPrice').textContent = `${priceIdr} / ${priceUsd}`;
        } else {
            document.getElementById('modalProductPrice').textContent = priceIdr;
        }
        
        // Rating
        const ratingDiv = document.getElementById('modalProductRating');
        if (productData.rating_count > 0) {
            ratingDiv.innerHTML = generateStarRating(productData.rating_avg, productData.rating_count);
            ratingDiv.classList.remove('hidden');
        } else {
            ratingDiv.classList.add('hidden');
        }

        // Colors
        const colorSection = document.getElementById('modalColorSection');
        const colorContainer = document.getElementById('modalColorOptions');
        
        if (productData.colors && productData.colors.length > 0) {
            document.getElementById('modalSelectedColor').textContent = productData.colors[0].name;
            
            const fragment = document.createDocumentFragment();
            productData.colors.forEach((color, index) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `modal-color-option cursor-pointer w-8 h-8 rounded-full border transition-all ${index === 0 ? 'ring-1 ring-offset-2 ring-gray-900 border-transparent' : 'border-gray-200 hover:border-gray-400'}`;
                btn.style.backgroundColor = color.hex_code;
                btn.title = color.name;
                btn.addEventListener('click', function() { 
                    selectModalColor(color.name, this); 
                });
                fragment.appendChild(btn);
            });
            
            colorContainer.innerHTML = '';
            colorContainer.appendChild(fragment);
            colorSection.classList.remove('hidden');
        } else {
            document.getElementById('modalSelectedColor').textContent = '-';
            colorContainer.innerHTML = '';
            colorSection.classList.add('hidden');
        }

        // Sizes
        const sizeSection = document.getElementById('modalSizeSection');
        const sizeSelect = document.getElementById('modalSizeSelect');

        if (productData.sizes && productData.sizes.length > 0) {
            sizeSelect.innerHTML = ''; // ⬅️ HAPUS option "Select size"
            
            productData.sizes.forEach(size => {
                const option = document.createElement('option');
                option.value = size;
                option.textContent = size;
                sizeSelect.appendChild(option);
            });
            
            // ⬇️ Auto select first size (langsung selected)
            sizeSelect.value = productData.sizes[0];
            document.getElementById('modalSelectedSizeText').textContent = productData.sizes[0];
            
            sizeSection.classList.remove('hidden');
        } else {
            sizeSelect.value = '';
            document.getElementById('modalSelectedSizeText').textContent = '-';
            sizeSection.classList.add('hidden');
        }

        updateModalInquiryLink();
        
        // Show modal & prevent scroll
        document.body.style.overflow = 'hidden';
    }

    function closeQuickView() {
        document.getElementById('quickViewModal').classList.add('hidden');
        document.body.style.overflow = '';
        currentModalProduct = null;
        currentModalImages = [];
        
        // ✅ Destroy Swiper instance
        if (modalSwiper) {
            modalSwiper.destroy(true, true);
            modalSwiper = null;
        }
    }

    function formatModalInquiryCurrency(value, locale) {
        return new Intl.NumberFormat(locale, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(Number(value || 0));
    }

    function buildModalInquiryMessage() {
        if (!currentModalProduct) return '';

        const colorSection = document.getElementById('modalColorSection');
        const sizeSection = document.getElementById('modalSizeSection');
        const selectedColor = !colorSection?.classList.contains('hidden')
            ? (document.getElementById('modalSelectedColor')?.textContent || null)
            : null;
        const selectedSize = !sizeSection?.classList.contains('hidden')
            ? (document.getElementById('modalSizeSelect')?.value || null)
            : null;

        const priceLines = {{ $paypalEnabled ? 'true' : 'false' }}
            ? [
                `Price: IDR ${formatModalInquiryCurrency(currentModalProduct.price_idr, 'id-ID')}`,
                `Price: USD ${formatModalInquiryCurrency(currentModalProduct.price_usd, 'en-US')}`
            ]
            : [`Price: IDR ${formatModalInquiryCurrency(currentModalProduct.price_idr, 'id-ID')}`];

        const messageParts = [
            'Hello, I would like to inquire about this product:',
            '',
            `Product: ${currentModalProduct.name}`,
            ...priceLines,
            selectedColor ? `Color: ${selectedColor}` : null,
            selectedSize ? `Size: ${selectedSize}` : null,
            `Product Link: ${currentModalProduct.url}`,
            '',
            'Please let me know the availability and next steps.'
        ].filter(Boolean);

        return messageParts.join('\n');
    }

    function updateModalInquiryLink() {
        const btn = document.getElementById('modalWhatsAppInquiryBtn');
        if (!btn || !currentModalProduct) return;

        const inquiryMessage = buildModalInquiryMessage();
        btn.href = `https://wa.me/6287766999564?text=${encodeURIComponent(inquiryMessage)}`;
    }

    function selectModalColor(colorName, buttonElement) {
        document.getElementById('modalSelectedColor').textContent = colorName;
        
        document.querySelectorAll('.modal-color-option').forEach(btn => {
            btn.classList.remove('ring-1', 'ring-offset-2', 'ring-gray-900', 'border-transparent');
            btn.classList.add('border-gray-200', 'hover:border-gray-400');
        });
        
        buttonElement.classList.remove('border-gray-200', 'hover:border-gray-400');
        buttonElement.classList.add('ring-1', 'ring-offset-2', 'ring-gray-900', 'border-transparent');

        updateModalInquiryLink();
    }

    function generateStarRating(avgRating, ratingCount) {
        let starsHtml = '<div class="flex items-center space-x-0.5 mb-1">';
        
        for (let i = 1; i <= 5; i++) {
            let fillPercentage = 0;
            if (avgRating >= i) {
                fillPercentage = 100;
            } else if (avgRating > (i - 1)) {
                fillPercentage = (avgRating - (i - 1)) * 100;
            }
            
            starsHtml += `
                <div class="relative w-4 h-4">
                    <svg class="absolute inset-0 w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <div class="absolute inset-0 overflow-hidden" style="width: ${fillPercentage}%;">
                        <svg class="w-4 h-4 text-[#E3CFCF]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
            `;
        }
        
        starsHtml += `</div><p class="text-xs text-gray-500">${ratingCount} ${ratingCount === 1 ? 'review' : 'reviews'}</p>`;
        
        return starsHtml;
    }

})();
</script>