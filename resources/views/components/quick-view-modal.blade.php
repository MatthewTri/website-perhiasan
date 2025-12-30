<div id="quickViewModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white max-w-4xl w-full max-h-[85vh] overflow-y-auto relative shadow-2xl">
        
        {{-- Close Button --}}
        <button type="button" id="closeModalBtn" class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-900 transition-colors">
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
            <div class="flex flex-col p-8 md:p-10 bg-white">
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

                {{-- Quantity --}}
                <div class="mb-6">
                    <div class="inline-flex items-center border border-gray-200 h-11 w-32 bg-white">
                        <button type="button" id="decreaseQty"
                                class="w-10 h-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors focus:outline-none cursor-pointer">
                            <svg fill="none" width="10" height="10" viewBox="0 0 12 12">
                                <path d="M0 6h12" stroke="currentColor" stroke-width="1.5"></path>
                            </svg>
                        </button>
                        <input type="number" id="modalQuantity" value="1" min="1" readonly 
                            class="flex-1 w-full h-full border-none text-center text-sm font-medium text-gray-900 focus:ring-0 p-0 bg-transparent appearance-none">
                        <button type="button" id="increaseQty"
                                class="w-10 h-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors focus:outline-none cursor-pointer">
                            <svg fill="none" width="10" height="10" viewBox="0 0 12 12">
                                <path d="M6 0v12M0 6h12" stroke="currentColor" stroke-width="1.5"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Add to Cart Button --}}
                <button type="button" id="modalAddToCartBtn"
                        class="w-full bg-[#E8B49E] hover:bg-[#dcaea1] text-white py-4 px-8 text-xs uppercase tracking-[0.2em] font-bold transition-all duration-300 cursor-pointer mb-4">
                    ADD TO CART
                </button>

                {{-- View Details Link --}}
                <a id="modalViewDetailsLink" href="#" class="block text-center text-[10px] uppercase tracking-[0.2em] text-gray-900 underline hover:no-underline">
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
                colors: JSON.parse(btn.dataset.colors || '[]')
            };
            
            openQuickView(productData);
        });

        // Close button
        document.getElementById('closeModalBtn')?.addEventListener('click', closeQuickView);

        // Quantity buttons
        document.getElementById('decreaseQty')?.addEventListener('click', () => updateModalQuantity(-1));
        document.getElementById('increaseQty')?.addEventListener('click', () => updateModalQuantity(1));

        // Add to cart button
        document.getElementById('modalAddToCartBtn')?.addEventListener('click', addToCartFromModal);

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
        
        // Format price
        const priceIdr = new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(productData.price_idr);
        
        const priceUsd = new Intl.NumberFormat('en-US', { 
            style: 'currency', 
            currency: 'USD'
        }).format(productData.price_usd);
        
        document.getElementById('modalProductPrice').textContent = `${priceIdr} / ${priceUsd}`;
        
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
            colorSection.classList.add('hidden');
        }

        // Reset quantity
        document.getElementById('modalQuantity').value = 1;
        
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

    function updateModalQuantity(change) {
        const input = document.getElementById('modalQuantity');
        let newValue = parseInt(input.value) + change;
        if (newValue < 1) newValue = 1;
        input.value = newValue;
    }

    function selectModalColor(colorName, buttonElement) {
        document.getElementById('modalSelectedColor').textContent = colorName;
        
        document.querySelectorAll('.modal-color-option').forEach(btn => {
            btn.classList.remove('ring-1', 'ring-offset-2', 'ring-gray-900', 'border-transparent');
            btn.classList.add('border-gray-200', 'hover:border-gray-400');
        });
        
        buttonElement.classList.remove('border-gray-200', 'hover:border-gray-400');
        buttonElement.classList.add('ring-1', 'ring-offset-2', 'ring-gray-900', 'border-transparent');
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
                        <svg class="w-4 h-4 text-[#E8B49E]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
            `;
        }
        
        starsHtml += `</div><p class="text-xs text-gray-500">${ratingCount} ${ratingCount === 1 ? 'review' : 'reviews'}</p>`;
        
        return starsHtml;
    }

    function addToCartFromModal() {
        if (!currentModalProduct) return;
        
        const quantity = parseInt(document.getElementById('modalQuantity').value);
        const selectedColor = document.getElementById('modalSelectedColor')?.textContent || null;
        const btn = document.getElementById('modalAddToCartBtn');
        const originalText = btn.innerText;
        
        btn.innerText = 'ADDING...';
        btn.disabled = true;

        fetch(`/cart/add/${currentModalProduct.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                color: selectedColor,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                @guest
                if (data.product) {
                    const cart = JSON.parse(localStorage.getItem('guest_cart') || '{}');
                    cart[currentModalProduct.id] = (cart[currentModalProduct.id] || 0) + data.quantity;
                    localStorage.setItem('guest_cart', JSON.stringify(cart));
                }
                @endguest
                
                closeQuickView();
                
                Swal.fire({
                    title: 'ADDED TO CART',
                    html: `<p class="text-sm text-gray-600">${quantity} item(s) added to your cart</p>`,
                    icon: 'success',
                    confirmButtonColor: '#111827',
                    confirmButtonText: 'VIEW CART',
                    showCancelButton: true,
                    cancelButtonText: 'CONTINUE SHOPPING',
                    cancelButtonColor: '#6B7280',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-none',
                        confirmButton: 'rounded-none px-6 py-3 text-xs uppercase tracking-widest',
                        cancelButton: 'rounded-none px-6 py-3 text-xs uppercase tracking-widest'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/cart';
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.error || 'Something went wrong',
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to add item to cart',
                icon: 'error',
                confirmButtonColor: '#EF4444'
            });
        })
        .finally(() => {
            btn.innerText = originalText;
            btn.disabled = false;
        });
    }
})();
</script>