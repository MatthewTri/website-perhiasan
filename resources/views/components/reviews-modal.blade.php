{{-- TRIGGER BUTTON: Adjusted position for mobile to avoid overlap --}}
<button onclick="openReviewsModal()" 
        class="fixed right-4 bottom-4 md:right-6 md:bottom-6 z-40 bg-white border border-gray-200 shadow-[0_4px_20px_rgb(0,0,0,0.08)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:border-[#E8B49E] rounded-full p-3 md:p-4 transition-all duration-300 group flex items-center justify-center active:scale-95 cursor-pointer"
        aria-label="Open Reviews">
    <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-900 group-hover:text-[#E8B49E] transition-colors" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
    </svg>
    <span class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-[10px] md:text-xs uppercase tracking-widest px-2 py-1 md:px-3 md:py-1.5 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap pointer-events-none hidden md:block">
        Reviews
    </span>
</button>


{{-- MODAL CONTAINER --}}
<div id="reviews-modal" class="hidden fixed inset-0 bg-stone-900/60 z-50 flex items-center justify-center p-0 md:p-4 transition-opacity duration-300 opacity-0" role="dialog" aria-modal="true">
    
    {{-- MODAL BOX: Responsive Width, Height, and Rounded Corners --}}
    <div class="bg-white w-[90%] md:w-full max-w-2xl h-auto max-h-[50vh] md:max-h-[70vh] flex flex-col shadow-2xl relative transform transition-all duration-300 scale-95 rounded-xl md:rounded-2xl overflow-hidden">
        
        {{-- Close Button --}}
        <button onclick="closeReviewsModal()" class="absolute top-3 right-3 md:top-5 md:right-5 text-gray-400 hover:text-gray-900 bg-white/80 hover:bg-gray-100 rounded-full p-2 transition-all z-10">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- HEADER: Adjusted Padding --}}
        <div class="px-5 py-6 md:p-8 text-center border-b border-gray-100 bg-white z-0">
            <h2 class="text-lg md:text-2xl font-serif text-gray-900 tracking-tight mb-2 md:mb-3">
                Customer Stories
            </h2>
            <div class="flex flex-col md:flex-row items-center justify-center gap-2 md:gap-3">
                {{-- Bintang Statis --}}
                <div class="flex text-[#E8B49E] space-x-0.5">
                    @for($i=0; $i<5; $i++)
                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-[10px] md:text-xs text-gray-400 tracking-widest uppercase font-medium">
                    Based on <span id="total-reviews" class="text-gray-900">0</span> reviews
                </span>
            </div>
        </div>

        {{-- BODY: Responsive Padding & Scrollbar --}}
        <div class="flex-1 overflow-y-auto px-5 py-6 md:px-8 md:py-6 custom-scrollbar bg-white">
            <div id="reviews-list" class="space-y-8 md:space-y-10">
                {{-- Loading Spinner --}}
                <div class="h-full flex flex-col items-center justify-center min-h-[200px]">
                    <div class="animate-spin rounded-full h-8 w-8 border-2 border-gray-100 border-t-gray-900"></div>
                </div>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div id="reviews-pagination" class="px-4 py-4 md:p-6 border-t border-gray-50 hidden bg-gray-50/50">
        </div>
    </div>
</div>

<style>
    /* Scrollbar halus */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #9CA3AF; }
</style>

<script>
// --- LOGIC MODAL (UNCHANGED) ---
function openReviewsModal() {
    const modal = document.getElementById('reviews-modal');
    const modalContent = modal.querySelector('div');
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
    });
    document.body.style.overflow = 'hidden';
    loadReviews(1);
}

function closeReviewsModal() {
    const modal = document.getElementById('reviews-modal');
    const modalContent = modal.querySelector('div');
    modal.classList.add('opacity-0');
    modalContent.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
}

document.getElementById('reviews-modal').addEventListener('click', function(e) {
    if (e.target === this) closeReviewsModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeReviewsModal();
});

// --- LOAD REVIEWS (UNCHANGED) ---
async function loadReviews(page = 1) {
    const reviewsList = document.getElementById('reviews-list');
    const pagination = document.getElementById('reviews-pagination');
    
    // Skeleton Loading yang lebih elegan
    reviewsList.innerHTML = `
        <div class="space-y-6 animate-pulse">
            ${[1,2,3].map(() => `
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-3 bg-gray-100 rounded w-1/4"></div>
                        <div class="h-3 bg-gray-100 rounded w-3/4"></div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
    
    try {
        const response = await fetch(`/api/reviews?page=${page}`);
        const data = await response.json();
        
        document.getElementById('total-reviews').textContent = data.total;
        
        if (data.data.length > 0) {
            reviewsList.innerHTML = data.data.map((review, index) => renderReview(review, index)).join('');            
            if (data.last_page > 1) {
                pagination.innerHTML = renderPagination(data);
                pagination.classList.remove('hidden');
            } else {
                pagination.classList.add('hidden');
            }
        } else {
            reviewsList.innerHTML = `
                <div class="text-center py-12 flex flex-col items-center">
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-medium">No reviews yet</p>
                </div>
            `;
            pagination.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading reviews:', error);
        reviewsList.innerHTML = '<p class="text-center text-red-500 py-8 text-sm">Unable to load reviews.</p>';
    }
}

// --- RENDER REVIEW (UPDATED FOR RESPONSIVENESS) ---
function renderReview(review, index) {
    const stars = Array(5).fill(0).map((_, i) => {
        const filled = i < review.rating;
        // Bintang sedikit lebih kecil di mobile (w-3)
        return `<svg class="w-3 h-3 md:w-3.5 md:h-3.5 ${filled ? 'text-[#E8B49E]' : 'text-gray-200'} fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`;
    }).join('');
    
    let userName = 'Anonymous';
    let initials = 'A';
    if (!review.anonymous && review.order && review.order.shipment) {
        userName = `${review.order.shipment.first_name} ${review.order.shipment.last_name}`;
        initials = review.order.shipment.first_name.charAt(0).toUpperCase();
    }
    
    // Badge lebih compact di mobile
    const verifiedBadge = review.order_id 
        ? '<svg class="w-3 h-3 text-emerald-500 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg><span class="ml-1 text-[9px] md:text-[10px] uppercase tracking-wider text-gray-400 font-medium">Verified</span>' 
        : '';
    
    const imageHtml = review.image 
        ? `<div class="mt-3 md:mt-4"><img src="/storage/${review.image}" alt="Review photo" class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity border border-gray-100" onclick="openLightbox('/storage/${review.image}')"></div>`
        : '';
    
    // Perubahan Layout: Gap diperkecil (space-x-4) dan Avatar diperkecil di mobile
    return `
        <div class="flex items-start gap-3 md:gap-5">
            <div class="flex-shrink-0">
                <div class="w-9 h-9 md:w-11 md:h-11 bg-stone-50 border border-stone-100 rounded-full flex items-center justify-center text-stone-800 font-serif text-sm md:text-lg">
                    ${initials}
                </div>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-1.5 gap-1 md:gap-0">
                    <div class="flex items-center">
                        <h4 class="text-xs md:text-sm font-bold text-gray-900 uppercase tracking-wider truncate max-w-[150px] md:max-w-none">${userName}</h4>
                        ${verifiedBadge}
                    </div>
                    <div class="flex space-x-0.5">${stars}</div>
                </div>
                
                <p class="text-[10px] md:text-xs text-gray-400 mb-2 md:mb-3 font-medium">
                    Purchased: <a href="/products/${review.product.slug}" class="text-gray-900 hover:underline decoration-gray-300 underline-offset-2 transition-colors">${review.product.name}</a>
                </p>
                
                ${review.comment ? `<p class="text-xs md:text-sm text-gray-600 leading-relaxed font-light">${review.comment}</p>` : ''}
                ${imageHtml}
            </div>
        </div>
    `;
}

// --- RENDER PAGINATION (UNCHANGED) ---
function renderPagination(data) {
    const pages = [];
    // Ukuran tombol pagination diperkecil sedikit agar muat di mobile
    const btnClass = "w-7 h-7 md:w-8 md:h-8 flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors focus:outline-none";
    const activeBtnClass = "w-7 h-7 md:w-8 md:h-8 flex items-center justify-center text-xs md:text-sm font-bold text-gray-900 border-b-2 border-gray-900 focus:outline-none";
    const textBtnClass = "w-7 h-7 md:w-8 md:h-8 flex items-center justify-center text-xs md:text-sm font-light text-gray-500 hover:text-gray-900 transition-colors focus:outline-none";

    const leftArrow = `<svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>`;
    const rightArrow = `<svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>`;
    const doubleLeftArrow = `<svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/></svg>`;
    const doubleRightArrow = `<svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>`;

    if (data.current_page > 1) {
        pages.push(`<button onclick="loadReviews(1)" class="${btnClass}">${doubleLeftArrow}</button>`);
        pages.push(`<button onclick="loadReviews(${data.current_page - 1})" class="${btnClass}">${leftArrow}</button>`);
    }
    
    const maxVisible = 4; // Kurangi jumlah halaman terlihat di mobile
    let startPage = Math.max(1, data.current_page - Math.floor(maxVisible / 2));
    let endPage = Math.min(data.last_page, startPage + maxVisible - 1);
    if (endPage - startPage < maxVisible - 1) startPage = Math.max(1, endPage - maxVisible + 1);
    
    for (let i = startPage; i <= endPage; i++) {
        if (i === data.current_page) {
            pages.push(`<button class="${activeBtnClass}">${i}</button>`);
        } else {
            pages.push(`<button onclick="loadReviews(${i})" class="${textBtnClass}">${i}</button>`);
        }
    }
    
    if (data.current_page < data.last_page) {
        pages.push(`<button onclick="loadReviews(${data.current_page + 1})" class="${btnClass}">${rightArrow}</button>`);
        pages.push(`<button onclick="loadReviews(${data.last_page})" class="${btnClass}">${doubleRightArrow}</button>`);
    }
    
    return `<div class="flex items-center justify-center space-x-1 md:space-x-2">${pages.join('')}</div>`;
}
</script>

@include('components.image-lightbox')