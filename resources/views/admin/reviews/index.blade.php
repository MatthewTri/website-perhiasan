@extends('layout.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="bg-white min-h-screen py-8 md:py-12">
    <div class="container mx-auto px-4 max-w-7xl">
        
        <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-10 gap-4">
            <div>
                <h6 class="text-xs font-bold tracking-[0.2em] text-stone-500 uppercase mb-2">Community & Feedback</h6>
                <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight">Review Moderation</h1>
            </div>
            <div class="hidden md:flex gap-6 text-sm text-stone-500">
                <div class="flex flex-col items-end">
                    <span class="font-bold text-stone-900 text-lg" id="totalReviews">{{ $reviews->total() }}</span>
                    <span>Total Reviews</span>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-2 border-emerald-500 text-emerald-800 text-sm tracking-wide shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-stone-50 rounded-sm border border-stone-100 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                <div class="md:col-span-5">
                    <label for="search" class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Search</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Product, customer, order ID or comment..."
                               class="w-full bg-white border-b-2 border-stone-200 px-3 py-2 text-stone-800 placeholder-stone-300 focus:outline-none focus:border-stone-800 transition-colors font-serif">
                        <svg id="searchLoading" class="hidden w-4 h-4 text-stone-400 absolute right-3 top-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <svg id="searchIcon" class="w-4 h-4 text-stone-400 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label for="visibility" class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Status</label>
                    <select id="visibility" name="visibility" class="w-full bg-white border-b-2 border-stone-200 px-3 py-2 text-stone-800 focus:outline-none focus:border-stone-800 cursor-pointer font-serif">
                        <option value="">All Status</option>
                        <option value="visible" {{ request('visibility') === 'visible' ? 'selected' : '' }}>Visible</option>
                        <option value="hidden" {{ request('visibility') === 'hidden' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="rating" class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Rating</label>
                    <select id="rating" name="rating" class="w-full bg-white border-b-2 border-stone-200 px-3 py-2 text-stone-800 focus:outline-none focus:border-stone-800 cursor-pointer font-serif">
                        <option value="">All Stars</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <button type="button" id="clearFilters" class="w-full bg-stone-600 hover:bg-stone-700 text-white font-medium py-3 px-4 rounded-sm transition duration-300 text-xs uppercase tracking-widest shadow-md hover:shadow-lg">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>

        <div id="reviewsContainer">
            @include('admin.reviews.partials.reviews-list')
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let searchTimeout;
    const searchInput = document.getElementById('search');
    const visibilitySelect = document.getElementById('visibility');
    const ratingSelect = document.getElementById('rating');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const searchLoading = document.getElementById('searchLoading');
    const searchIcon = document.getElementById('searchIcon');
    const reviewsContainer = document.getElementById('reviewsContainer');
    const totalReviews = document.getElementById('totalReviews');

    // Debounced live search function
    function performSearch() {
        const search = searchInput.value;
        const visibility = visibilitySelect.value;
        const rating = ratingSelect.value;

        // Build query params
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (visibility) params.append('visibility', visibility);
        if (rating) params.append('rating', rating);

        const queryString = params.toString();
        const url = `{{ route('admin.reviews.index') }}${queryString ? '?' + queryString : ''}`;

        // Show loading
        searchLoading.classList.remove('hidden');
        searchIcon.classList.add('hidden');
        reviewsContainer.style.opacity = '0.5';

        // Fetch reviews via AJAX
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse response and extract partial content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('reviewsContainer');
            const newTotal = doc.getElementById('totalReviews');

            if (newContent) {
                reviewsContainer.innerHTML = newContent.innerHTML;
            }
            if (newTotal && totalReviews) {
                totalReviews.textContent = newTotal.textContent;
            }

            // Hide loading
            searchLoading.classList.add('hidden');
            searchIcon.classList.remove('hidden');
            reviewsContainer.style.opacity = '1';

            // Update URL without reload
            window.history.pushState({}, '', url);

            // Reattach pagination handlers
            attachPaginationHandlers();
        })
        .catch(error => {
            console.error('Search error:', error);
            searchLoading.classList.add('hidden');
            searchIcon.classList.remove('hidden');
            reviewsContainer.style.opacity = '1';
        });
    }

    // Debounce for search input
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });

    // Immediate trigger for select dropdowns
    visibilitySelect.addEventListener('change', performSearch);
    ratingSelect.addEventListener('change', performSearch);

    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        visibilitySelect.value = '';
        ratingSelect.value = '';
        performSearch();
    });

    // Handle pagination clicks via AJAX
    function attachPaginationHandlers() {
        const paginationLinks = reviewsContainer.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.href;
                
                searchLoading.classList.remove('hidden');
                searchIcon.classList.add('hidden');
                reviewsContainer.style.opacity = '0.5';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('reviewsContainer');
                    const newTotal = doc.getElementById('totalReviews');

                    if (newContent) {
                        reviewsContainer.innerHTML = newContent.innerHTML;
                    }
                    if (newTotal && totalReviews) {
                        totalReviews.textContent = newTotal.textContent;
                    }

                    searchLoading.classList.add('hidden');
                    searchIcon.classList.remove('hidden');
                    reviewsContainer.style.opacity = '1';
                    window.history.pushState({}, '', url);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    attachPaginationHandlers();
                })
                .catch(error => {
                    console.error('Pagination error:', error);
                    searchLoading.classList.add('hidden');
                    searchIcon.classList.remove('hidden');
                    reviewsContainer.style.opacity = '1';
                });
            });
        });
    }

    // Initial pagination handlers
    attachPaginationHandlers();

    // Handle browser back/forward
    window.addEventListener('popstate', function() {
        location.reload();
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Review?',
                    text: this.getAttribute('data-warning') || "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1c1917',
                    cancelButtonColor: '#a8a29e',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'font-sans',
                        title: 'font-serif text-stone-800',
                        confirmButton: 'px-6 py-2.5 rounded-sm font-medium tracking-wide',
                        cancelButton: 'px-6 py-2.5 rounded-sm font-medium tracking-wide'
                    }
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });
    });
</script>

@endsection
