{{-- partials/reviews-modal.blade.php --}}

<button onclick="openReviewsModal()" 
        class="fixed right-6 bottom-6 z-40 bg-white border border-gray-200 shadow-lg hover:shadow-xl hover:border-[#E8B49E] rounded-full p-4 transition-all duration-300 group flex items-center justify-center"
        aria-label="Open Reviews">
    <svg class="w-6 h-6 text-gray-900 group-hover:text-[#E8B49E] transition-colors" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
    </svg>
    <span class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs uppercase tracking-widest px-3 py-1.5 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap pointer-events-none">
        Read Reviews
    </span>
</button>


<div id="reviews-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 transition-opacity duration-300 opacity-0" role="dialog" aria-modal="true">
    <div class="bg-white w-full max-w-2xl max-h-[85vh] flex flex-col shadow-2xl relative transform transition-all duration-300 scale-95">
        
        <button onclick="closeReviewsModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900 transition-colors z-10 p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="p-8 pb-4 text-center border-b border-gray-100">
            <h2 class="text-xl md:text-2xl font-normal text-gray-900 uppercase tracking-[0.15em] mb-3">
                Customer Reviews
            </h2>
            <div class="flex items-center justify-center space-x-3">
                {{-- Bintang Statis (Sebagai Hiasan Header) --}}
                <div class="flex text-[#E8B49E] space-x-0.5">
                    @for($i=0; $i<5; $i++)
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-xs text-gray-500 tracking-wide font-light uppercase">
                    Based on <span id="total-reviews" class="font-medium text-gray-900">0</span> reviews
                </span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-8 py-4 custom-scrollbar">
            <div id="reviews-list" class="space-y-10">
                <div class="text-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
                </div>
            </div>
        </div>

        <div id="reviews-pagination" class="p-6 border-t border-gray-100 hidden bg-white">
            </div>
    </div>
</div>

<style>
    /* Custom Scrollbar untuk Modal Body agar lebih rapi */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #D1D5DB; }
</style>

<script>
// --- FUNGSI MEMBUKA MODAL (Dengan Animasi) ---
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

// --- FUNGSI MENUTUP MODAL (Dengan Animasi) ---
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

// Close on outside click
document.getElementById('reviews-modal').addEventListener('click', function(e) {
    if (e.target === this) closeReviewsModal();
});
// Close on Esc key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeReviewsModal();
});

// --- FUNGSI AJAX LOAD REVIEWS ---
async function loadReviews(page = 1) {
    const reviewsList = document.getElementById('reviews-list');
    const pagination = document.getElementById('reviews-pagination');
    
    reviewsList.innerHTML = `
        <div class="text-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
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
                <div class="text-center py-12">
                    <p class="text-sm text-gray-500 uppercase tracking-widest font-light">No reviews yet.</p>
                </div>
            `;
            pagination.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading reviews:', error);
        reviewsList.innerHTML = '<p class="text-center text-red-500 py-8 text-sm">Failed to load reviews.</p>';
    }
}

// --- FUNGSI RENDER SATU REVIEW (Desain Baru) ---
function renderReview(review, index) {
    const stars = Array(5).fill(0).map((_, i) => {
        const filled = i < review.rating;
        // Pakai SVG Bintang yang lebih tajam
        return `<svg class="w-3.5 h-3.5 ${filled ? 'text-[#E8B49E]' : 'text-gray-200'} fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`;
    }).join('');
    
    let userName = 'Anonymous';
    let initials = 'A';
    if (!review.anonymous && review.order && review.order.shipment) {
        userName = `${review.order.shipment.first_name} ${review.order.shipment.last_name}`;
        initials = review.order.shipment.first_name.charAt(0).toUpperCase();
    }
    
    const verifiedBadge = review.order_id 
        ? '<span class="ml-3 text-[10px] uppercase tracking-wider text-gray-400 border border-gray-200 px-2 py-0.5">Verified Buyer</span>' 
        : '';
    
    const imageHtml = review.image 
        ? `<div class="mt-4"><img src="/storage/${review.image}" alt="Review photo" class="w-24 h-24 object-cover cursor-pointer hover:opacity-90 transition-opacity shadow-sm" onclick="openLightbox('/storage/${review.image}')"></div>`
        : '';
    
    return `
        <div class="flex items-start space-x-6">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-900 font-serif text-lg">
                    ${initials}
                </div>
            </div>
            <div class="flex-1 min-w-0 pt-1">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider">${userName}</h4>
                        ${verifiedBadge}
                    </div>
                    <div class="flex space-x-0.5">${stars}</div>
                </div>
                
                <p class="text-xs text-gray-400 tracking-wide mb-3 font-light">
                    About: <a href="/products/${review.product.slug}" class="text-gray-900 hover:underline underline-offset-4 transition-colors">${review.product.name}</a>
                </p>
                
                ${review.comment ? `<p class="text-sm text-gray-600 leading-relaxed font-light">${review.comment}</p>` : ''}
                ${imageHtml}
            </div>
        </div>
    `;
}

// --- FUNGSI RENDER PAGINATION (Desain Baru: Minimalis) ---
function renderPagination(data) {
    const pages = [];
    const btnClass = "w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors focus:outline-none";
    const activeBtnClass = "w-8 h-8 flex items-center justify-center text-sm font-bold text-gray-900 border-b-2 border-gray-900 focus:outline-none";
    const textBtnClass = "w-8 h-8 flex items-center justify-center text-sm font-light text-gray-500 hover:text-gray-900 transition-colors focus:outline-none";

    // Arrows SVG
    const leftArrow = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>`;
    const rightArrow = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>`;
    const doubleLeftArrow = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/></svg>`;
    const doubleRightArrow = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>`;

    if (data.current_page > 1) {
        pages.push(`<button onclick="loadReviews(1)" class="${btnClass}">${doubleLeftArrow}</button>`);
        pages.push(`<button onclick="loadReviews(${data.current_page - 1})" class="${btnClass}">${leftArrow}</button>`);
    }
    
    const maxVisible = 5;
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
    
    return `<div class="flex items-center justify-center space-x-2">${pages.join('')}</div>`;
}
</script>

@include('components.image-lightbox')