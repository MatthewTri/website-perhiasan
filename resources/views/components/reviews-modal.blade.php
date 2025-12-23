<div id="reviews-modal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-xl w-full max-h-[80vh] flex flex-col shadow-2xl">
        <!-- Modal Header -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Let customers speak for us</h2>
                    <div class="flex items-center mt-1">
                        <div class="flex text-yellow-400">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        </div>
                        <span class="ml-2 text-xs text-gray-600">Based on <span id="total-reviews">0</span> reviews</span>
                    </div>
                </div>
                <button onclick="closeReviewsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="flex-1 overflow-y-auto pl-4">
            <div id="reviews-list" class="space-y-4 overflow-y-auto max-h-96">
                <!-- Reviews akan dimuat via AJAX -->
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-orange-500 mx-auto"></div>
                </div>
            </div>
        </div>

        <!-- Modal Footer (Pagination) -->
        <div id="reviews-pagination" class="p-4 border-t border-gray-200 hidden">
            <!-- Pagination akan dimuat via AJAX -->
        </div>
    </div>
</div>

<!-- Floating Reviews Button -->
<button onclick="openReviewsModal()" 
        class="fixed right-0 top-1/2 -translate-y-1/2 bg-gradient-to-b from-orange-400 to-orange-500 text-white px-3 py-5 rounded-l-lg shadow-lg hover:shadow-xl transition-all duration-300 z-40 group hover:px-4">
    <div class="flex flex-col items-center space-y-1.5">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
        </svg>
        <span class="text-xs font-semibold tracking-wider" style="writing-mode: vertical-rl; text-orientation: mixed;">
            REVIEWS
        </span>
    </div>
</button>

<script>
function openReviewsModal() {
    document.getElementById('reviews-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    loadReviews(1);
}

function closeReviewsModal() {
    document.getElementById('reviews-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('reviews-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReviewsModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReviewsModal();
    }
});

async function loadReviews(page = 1) {
    const reviewsList = document.getElementById('reviews-list');
    const pagination = document.getElementById('reviews-pagination');
    
    // TAMPILKAN LOADING DULU (sebelum fetch)
    reviewsList.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-orange-500 mx-auto"></div>
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
            reviewsList.innerHTML = '<p class="text-center text-gray-500 py-8 text-sm">No reviews yet.</p>';
            pagination.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading reviews:', error);
        reviewsList.innerHTML = '<p class="text-center text-red-500 py-8 text-sm">Failed to load reviews.</p>';
    }
}

function renderReview(review, index) {
    const stars = Array(5).fill(0).map((_, i) => {
        const filled = i < review.rating;
        return `<svg class="w-4 h-4 ${filled ? 'text-yellow-400' : 'text-gray-300'} fill-current" viewBox="0 0 20 20">
            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
        </svg>`;
    }).join('');
    
    const userName = review.anonymous ? 'Anonymous' : (review.order ? review.order.shipping_name : 'Customer');
    const verifiedBadge = review.order_id ? '<span class="ml-2 text-xs bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded">Verified</span>' : '';
    
    const imageHtml = review.image 
        ? `<img src="/storage/${review.image}" alt="Review photo" class="mt-2 max-w-32 h-auto rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open('/storage/${review.image}', '_blank')">`
        : '';
    
    const paddingTop = index === 0 ? 'pt-4' : '';

    return `
        <div class="border-b border-gray-200 ${paddingTop} pb-4 pr-4 last:border-0">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <p class="font-semibold text-sm text-gray-900">${userName}</p>
                            ${verifiedBadge}
                        </div>
                        <div class="flex">${stars}</div>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">about <a href="/product/${review.product.slug}" class="text-orange-500 hover:underline">${review.product.name}</a></p>
                    ${review.comment ? `<p class="mt-2 text-sm text-gray-700 leading-relaxed">${review.comment}</p>` : ''}
                    ${imageHtml}
                </div>
            </div>
        </div>
    `;
}

function renderPagination(data) {
    const pages = [];
    
    // ⬅️ TAMBAHAN: Double Left Arrow (Jump to First Page)
    if (data.current_page > 1) {
        pages.push(`
            <button onclick="loadReviews(1)" 
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-orange-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/>
                </svg>
            </button>
        `);
    }
    
    // Previous button (Left Arrow) - ⬅️ HANYA TAMPIL JIKA BUKAN PAGE 1
    if (data.current_page > 1) {
        pages.push(`
            <button onclick="loadReviews(${data.current_page - 1})" 
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-orange-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
        `);
    }
    
    // Page numbers
    const maxVisible = 5;
    let startPage = Math.max(1, data.current_page - Math.floor(maxVisible / 2));
    let endPage = Math.min(data.last_page, startPage + maxVisible - 1);
    
    if (endPage - startPage < maxVisible - 1) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        if (i === data.current_page) {
            pages.push(`
                <button class="w-8 h-8 flex items-center justify-center text-sm font-semibold text-gray-900 bg-transparent">
                    ${i}
                </button>
            `);
        } else {
            pages.push(`
                <button onclick="loadReviews(${i})" 
                        class="w-8 h-8 flex items-center justify-center text-sm font-medium text-gray-400 hover:text-orange-500 transition-colors">
                    ${i}
                </button>
            `);
        }
    }
    
    // Next button (Right Arrow) - ⬅️ HANYA TAMPIL JIKA BUKAN PAGE TERAKHIR
    if (data.current_page < data.last_page) {
        pages.push(`
            <button onclick="loadReviews(${data.current_page + 1})" 
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-orange-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        `);
    }
    
    // Last page button (Double Right Arrow) - ⬅️ HANYA TAMPIL JIKA BUKAN PAGE TERAKHIR
    if (data.current_page < data.last_page) {
        pages.push(`
            <button onclick="loadReviews(${data.last_page})" 
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-orange-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                </svg>
            </button>
        `);
    }
    
    return `<div class="flex items-center justify-center space-x-0.5">${pages.join('')}</div>`;
}
</script>