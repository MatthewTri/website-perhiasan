@extends('layout.main')

@section('content')
@php
    $sortOptions = [
        'featured'     => 'Featured',
        'best_selling' => 'Best Selling',
        'alpha_asc'    => 'Alphabetically, A-Z',
        'alpha_desc'   => 'Alphabetically, Z-A',
        'price_asc'    => 'Price, Low to High',
        'price_desc'   => 'Price, High to Low',
        'date_asc'     => 'Date, Old to New',
        'date_desc'    => 'Date, New to Old',
    ];
    $currentSort = $currentSort ?? 'featured';
    $currentSortLabel = $sortOptions[$currentSort] ?? 'Featured';
@endphp
<div class="w-full bg-white border-b border-gray-100 sticky top-[top-navbar-height] z-20">
    <div class="max-w-screen-2xl mx-auto px-4 lg:px-6">
        <div class="flex flex-col md:flex-row justify-between items-center py-6 md:py-8">
            <div class="mb-4 md:mb-0 text-center md:text-left">
                <h1 class="text-xl md:text-2xl font-serif text-gray-900 tracking-widest uppercase">
                    {{ $pageTitle ?? 'Products' }}
                </h1>
                @if(isset($pageSubtitle))
                    <p class="text-xs text-gray-500 tracking-wider mt-1">{{ $pageSubtitle }}</p>
                @endif
            </div>
            
            @can('admin')
            <a href="{{ route('products.create') }}" class="group relative inline-flex items-center justify-start overflow-hidden px-6 py-2 bg-gray-900 font-medium transition-all hover:bg-white border border-gray-900">
                <span class="w-48 h-48 rounded rotate-[-40deg] bg-white absolute bottom-0 left-0 -translate-x-full ease-out duration-500 transition-all translate-y-full mb-9 ml-9 group-hover:ml-0 group-hover:mb-32 group-hover:translate-x-0"></span>
                <span class="relative w-full text-left text-white transition-colors duration-300 ease-in-out group-hover:text-gray-900 uppercase text-xs tracking-widest">
                    + Add Product
                </span>
            </a>
            @endcan
        </div>
    </div>

    <div class="border-t border-gray-100">
        <div class="max-w-screen-2xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-[1fr_200px_100px] divide-x divide-gray-100">
                <button class="flex items-center justify-center py-3 md:py-4 text-xs font-bold tracking-[0.15em] uppercase text-gray-500 hover:text-gray-900 transition-colors md:justify-start md:px-6">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    Filter
                </button>
                
                <div class="relative flex" id="sortByContainer">
                    <button id="sortByBtn" class="flex items-center justify-center py-3 md:py-4 text-xs font-bold tracking-[0.15em] uppercase text-gray-500 hover:text-gray-900 transition-colors w-full cursor-pointer">
                        <span>Sort By</span>
                        <svg id="sortChevron" class="w-3 h-3 ml-2 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    {{-- Desktop Dropdown --}}
                    <div id="sortDropdown" class="hidden absolute top-full left-0 bg-white border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.12)] min-w-[220px] z-50 py-1">
                        @foreach($sortOptions as $value => $label)
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $value]) }}"
                           class="flex items-center justify-between px-6 py-3 text-sm tracking-wide transition-colors hover:bg-gray-50 {{ $currentSort === $value ? 'text-gray-900 font-bold border-l-2 border-gray-900 pl-5' : 'text-gray-500' }}">
                            <span>{{ $label }}</span>
                            @if($currentSort === $value)
                            <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>

                <div class="hidden md:flex items-center justify-center space-x-2">
                    <div class="w-4 h-4 bg-gray-400"></div>
                    <div class="grid grid-cols-2 gap-0.5 w-4 h-4">
                        <div class="bg-gray-900 w-full h-full"></div>
                        <div class="bg-gray-900 w-full h-full"></div>
                        <div class="bg-gray-900 w-full h-full"></div>
                        <div class="bg-gray-900 w-full h-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 2. PRODUCT GRID --}}
<div class="max-w-screen-2xl mx-auto px-4 lg:px-6 py-8 md:py-12">
    
    @if(session('success'))
        <div class="bg-gray-50 border-l-4 border-gray-900 text-gray-900 px-4 py-3 mb-8 text-xs tracking-wide uppercase" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- 
       GRID SYSTEM:
       Mobile: grid-cols-2 (Sesuai Gambar 5)
       Tablet: grid-cols-3
       Desktop: grid-cols-4
       Gap: gap-x-4 gap-y-10 (Jarak vertikal lebih jauh untuk kesan elegan)
    --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 md:gap-x-8 gap-y-10 md:gap-y-16">
        @forelse($products as $product)
            <div class="group relative flex flex-col">
                
                {{-- A. IMAGE CONTAINER --}}
                <div class="relative w-full aspect-square bg-[#F9F9F9] overflow-hidden group/image">
                    <a href="{{ route('products.show', $product) }}" class="block w-full h-full">
                        @if($product->featuredImage)
                            <img src="{{ asset('storage/' . $product->featuredImage->image) }}" 
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover object-center transition-transform duration-700 ease-in-out">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <span class="text-[10px] uppercase tracking-widest">No Image</span>
                            </div>
                        @endif
                    </a>

                    {{-- ADMIN CONTROLS (Muncul saat Hover - Overlay di Kiri Atas) --}}
                    @auth
                        <div class="absolute top-2 left-2 flex flex-col space-y-2 opacity-0 group-hover/image:opacity-100 transition-opacity duration-300 z-10">
                            <a href="{{ route('products.edit', $product) }}" class="bg-white/90 backdrop-blur text-gray-900 p-2 hover:bg-gray-900 hover:text-white transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')" class="bg-white/90 backdrop-blur text-red-600 p-2 hover:bg-red-600 hover:text-white transition shadow-sm cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @endauth

                    {{-- TOMBOL PLUS (+) - Kanan Bawah (Hanya muncul saat hover image) --}}
                    <button type="button"
                        class="quick-view-btn absolute bottom-2 right-2 bg-white text-gray-900 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center hover:bg-gray-900 hover:text-white transition-all duration-300 opacity-0 group-hover/image:opacity-100 translate-y-2 group-hover/image:translate-y-0 cursor-pointer z-20 shadow-sm"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        data-product-slug="{{ $product->slug }}"
                        data-price-idr="{{ $product->price_idr }}"
                        data-price-usd="{{ $product->price_usd }}"
                        data-product-url="{{ route('products.show', $product) }}"
                        data-rating-avg="{{ $product->rating_avg ?? 0 }}"
                        data-rating-count="{{ $product->rating_count ?? 0 }}"
                        data-featured-image="{{ $product->featuredImage ? asset('storage/' . $product->featuredImage->image) : '' }}"
                        data-colors='@json($product->colors ? $product->colors->map(fn($c) => ["name" => $c->name, "hex_code" => $c->hex_code])->values()->toArray() : [])'
                        data-sizes='@json($product->sizes ? $product->sizes->map(fn($s) => $s->size_name)->values()->toArray() : [])'>
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>

                {{-- B. PRODUCT INFO (Minimalist Text) --}}
                <div class="mt-4 text-center">
                    <h3 class="text-[10px] md:text-[11px] font-bold text-gray-900 uppercase tracking-[0.15em] mb-1 leading-relaxed">
                        <a href="{{ route('products.show', $product) }}">
                            {{ $product->name }}
                        </a>
                    </h3>

                    <p class="text-[11px] md:text-xs text-gray-500 tracking-widest font-medium mb-3">
                        @currency(['idr' => $product->price_idr, 'usd' => $product->price_usd])
                    </p>

                    @if($product->colors && $product->colors->count() > 0)
                    <div class="flex justify-center items-center space-x-2 mb-2">
                        @foreach($product->colors->sortBy('sort_order') as $color)
                            <span class="w-3 h-3 rounded-full border border-transparent hover:border-gray-400 cursor-pointer transition-colors" 
                                style="background-color: {{ $color->hex_code }}" 
                                title="{{ $color->name }}"></span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Rating Stars (Dynamic with Partial Fill) --}}
                    @if($product->rating_count > 0)
                    <div class="flex justify-center items-center space-x-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            @php
                                $fillPercentage = 0;
                                if ($product->rating_avg >= $i) {
                                    $fillPercentage = 100; // Full star
                                } elseif ($product->rating_avg > ($i - 1)) {
                                    $fillPercentage = ($product->rating_avg - ($i - 1)) * 100; // Partial star
                                }
                            @endphp
                            
                            <div class="relative w-3 h-3">
                                {{-- Background Star (Empty/Gray) --}}
                                <svg class="absolute inset-0 w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                
                                {{-- Foreground Star (Filled with gradient clip) --}}
                                <div class="absolute inset-0 overflow-hidden" style="width: {{ $fillPercentage }}%;">
                                    <svg class="w-3 h-3 text-[#E8B49E]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            </div>
                        @endfor
                        
                        <span class="text-[10px] text-gray-400 ml-1">({{ number_format($product->rating_avg, 1) }})</span>
                        {{-- <span class="text-[9px] text-gray-300">•</span>
                        <span class="text-[10px] text-gray-400">{{ $product->rating_count }} {{ Str::plural('review', $product->rating_count) }}</span> --}}
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-xs uppercase tracking-widest text-gray-400">No products found in this collection.</p>
                @can('admin')
                    <a href="{{ route('products.create') }}" class="mt-4 inline-block border-b border-gray-900 text-xs uppercase tracking-widest pb-1 hover:text-gray-600">Create Product</a>
                @endcan
            </div>
        @endforelse
    </div>
</div>

{{-- Mobile Sort Bottom Sheet --}}
<div id="sortOverlay" class="fixed inset-0 bg-black/40 z-40 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"></div>
<div id="sortBottomSheet" class="fixed bottom-0 left-0 right-0 bg-white z-50 translate-y-full transition-transform duration-300 ease-in-out md:hidden shadow-2xl rounded-t-xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-gray-900">Sort By</h3>
        <button id="closeSortSheet" class="p-1 text-gray-400 hover:text-gray-900 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <div class="py-2">
        @foreach($sortOptions as $value => $label)
        <a href="{{ request()->fullUrlWithQuery(['sort' => $value]) }}"
           class="flex items-center justify-between px-6 py-4 text-sm tracking-wide transition-colors hover:bg-gray-50 {{ $currentSort === $value ? 'text-gray-900 font-semibold' : 'text-gray-500' }}">
            <span>{{ $label }}</span>
            @if($currentSort === $value)
            <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            @endif
        </a>
        @endforeach
    </div>
</div>

<script>
(function () {
    const sortByBtn     = document.getElementById('sortByBtn');
    const sortDropdown  = document.getElementById('sortDropdown');
    const sortChevron   = document.getElementById('sortChevron');
    const sortOverlay   = document.getElementById('sortOverlay');
    const sortSheet     = document.getElementById('sortBottomSheet');
    const closeSheetBtn = document.getElementById('closeSortSheet');
    const container     = document.getElementById('sortByContainer');

    function isMobile() { return window.innerWidth < 768; }

    function openSheet() {
        sortOverlay.classList.remove('opacity-0', 'pointer-events-none');
        sortSheet.classList.remove('translate-y-full');
        document.body.style.overflow = 'hidden';
    }

    function closeSheet() {
        sortOverlay.classList.add('opacity-0', 'pointer-events-none');
        sortSheet.classList.add('translate-y-full');
        document.body.style.overflow = '';
    }

    function openDropdown() {
        sortDropdown.classList.remove('hidden');
        sortChevron.classList.add('rotate-180');
    }

    function closeDropdown() {
        sortDropdown.classList.add('hidden');
        sortChevron.classList.remove('rotate-180');
    }

    sortByBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        if (isMobile()) {
            openSheet();
        } else {
            sortDropdown.classList.contains('hidden') ? openDropdown() : closeDropdown();
        }
    });

    document.addEventListener('click', function (e) {
        if (container && !container.contains(e.target)) {
            closeDropdown();
        }
    });

    closeSheetBtn.addEventListener('click', closeSheet);
    sortOverlay.addEventListener('click', closeSheet);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeDropdown(); closeSheet(); }
    });
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(productId, productName) {
        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete <strong>"${productName}"</strong><br><br>This action cannot be undone and will also delete all associated images.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#9CA3AF',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                confirmButton: 'px-6 py-2 text-xs uppercase tracking-widest',
                cancelButton: 'px-6 py-2 text-xs uppercase tracking-widest'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${productId}`).submit();
            }
        });
    }

    // function addToCartLater(productId) {
    //     // Kirim AJAX request untuk menambah ke cart
    //     fetch(`/cart/add/${productId}`, {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    //         }
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.success) {
    //             // Jika guest user dan ada product data, simpan ke localStorage
    //             @guest
    //             if (data.product) {
    //                 const cart = JSON.parse(localStorage.getItem('guest_cart') || '{}');
    //                 if (cart[productId]) {
    //                     cart[productId]++;
    //                 } else {
    //                     cart[productId] = 1;
    //                 }
    //                 localStorage.setItem('guest_cart', JSON.stringify(cart));
    //             }
    //             @endguest

    //             // success alert
    //             Swal.fire({
    //                 title: 'Success!',
    //                 text: 'Product added to cart successfully!',
    //                 icon: 'success',
    //                 confirmButtonColor: '#111827',
    //                 confirmButtonText: 'OK',
    //                 timer: 2000,
    //                 timerProgressBar: true,
    //                 customClass: {
    //                     confirmButton: 'px-6 py-2 text-xs uppercase tracking-widest'
    //                 }
    //             });
    //         } else {
    //             // error alert
    //             Swal.fire({
    //                 title: 'Error!',
    //                 text: data.error || 'Something went wrong!',
    //                 icon: 'error',
    //                 confirmButtonColor: '#EF4444',
    //                 confirmButtonText: 'OK'
    //             });
    //         }
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //         Swal.fire({
    //             title: 'Error!',
    //             text: 'Something went wrong!',
    //             icon: 'error',
    //             confirmButtonColor: '#EF4444'
    //         });
    //     });
    // }
</script>
@endsection