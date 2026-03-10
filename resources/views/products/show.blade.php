@extends('layout.main')

@php
    $lightboxImages = [];
    
    foreach($product->images->sortBy('sort_order') as $img) {
        $lightboxImages[] = asset('storage/' . $img->image);
    }
    
    if(empty($lightboxImages)) {
        $lightboxImages[] = asset('img/no-image.jpg');
    }
@endphp

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- BREADCRUMB (Minimalist) --}}
    <div class="max-w-screen-2xl mx-auto px-4 lg:px-6 pt-6 pb-2">
        <nav class="flex text-[10px] uppercase tracking-widest text-gray-400 font-medium">
            <a href="/" class="hover:text-gray-900 transition-colors">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-gray-900 transition-colors">Products</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>
    </div>

    {{-- MAIN PRODUCT SECTION --}}
    <div class="max-w-screen-2xl mx-auto px-4 lg:px-6 py-8 md:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-20 items-start">
            
            {{-- LEFT: IMAGE GALLERY (Sticky & Clean) --}}
            <div class="space-y-4 lg:sticky lg:top-24">
                {{-- Main Image Wrapper --}}
                <div class="aspect-square w-full bg-[#F7F7F7] overflow-hidden relative group">
                    
                    @if($product->featuredImage)
                        <img id="mainProductImage"
                            src="{{ asset('storage/' . $product->featuredImage->image) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover object-center transition-transform duration-700 cursor-zoom-in"
                            data-lightbox-images='@json($lightboxImages)'
                            data-current-index="0"
                            onclick="openMainImageLightbox(this)">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <span class="text-xs uppercase tracking-widest">No Image</span>
                        </div>
                    @endif

                    {{-- Optional: Icon Zoom di pojok kanan bawah agar user sadar bisa diklik --}}
                    <div class="absolute bottom-4 right-4 bg-white p-2 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </div>
                </div>

                {{-- Thumbnails (LEBIH KECIL) --}}
                @if($product->images->count() > 1)
                <div class="grid grid-cols-10 gap-2">
                    @foreach($product->images->sortBy('sort_order') as $image)
                        <div class="aspect-square cursor-pointer border border-transparent hover:border-gray-900 transition-all overflow-hidden thumbnail-image"
                            onclick="changeMainImage('{{ asset('storage/' . $image->image) }}', this)">
                            <img src="{{ asset('storage/' . $image->image) }}" 
                                class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- RIGHT: PRODUCT INFO (The "Cecile Private Collection" Style) --}}
            <div class="flex flex-col h-full pt-2">
                
                {{-- 1. TITLE --}}
                <h1 class="text-2xl md:text-3xl font-normal text-gray-900 uppercase tracking-[0.15em] leading-tight mb-2">
                    {{ $product->name }}
                </h1>
                
                {{-- 2. PRICE --}}
                <p class="text-lg text-gray-500 tracking-[0.05em] font-light mb-8">
                    @currency(['idr' => $product->price_idr, 'usd' => $product->price_usd])
                </p>

                {{-- 3. COLOUR SELECTION --}}
                @if($product->colors && $product->colors->count() > 0)
                <div class="mb-8">
                    <p class="text-xs text-gray-500 mb-3">
                        Colour: <span id="selectedColorName" class="text-gray-900 capitalize">{{ $product->colors->first()->name }}</span>
                    </p>
                    <div class="flex items-center space-x-3">
                        @foreach($product->colors as $index => $color)
                            <button type="button" 
                                    onclick="selectColor('{{ $color->name }}', this)"
                                    class="color-option w-8 h-8 rounded-full border border-gray-300 focus:outline-none transition-all {{ $index === 0 ? 'ring-1 ring-offset-2 ring-gray-900 border-transparent' : 'hover:border-gray-400' }}"
                                    style="background-color: {{ $color->hex_code }}"
                                    title="{{ $color->name }}">
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- 4. SIZE SELECTION (Mirip Quick View Modal) --}}
                @if($product->sizes && $product->sizes->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs uppercase tracking-widest text-gray-500">
                            Size: <span id="selectedSizeName" class="text-gray-900 font-medium">{{ $product->sizes->first()->size_name }}</span>
                        </p>
                        <button type="button" onclick="openSizeChart()" class="text-xs text-gray-400 hover:text-gray-900 underline cursor-pointer">Size chart</button>
                    </div>
                    
                    <div class="relative">
                        <select id="sizeSelect" 
                                onchange="updateSelectedSize()"
                                class="w-full px-4 py-3 border border-gray-200 text-sm text-gray-900 focus:outline-none focus:border-gray-400 focus:ring-0 appearance-none bg-white cursor-pointer">
                            @foreach($product->sizes->sortBy('sort_order') as $size)
                                <option value="{{ $size->size_name }}">{{ $size->size_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                {{-- 5. QUANTITY & ADD TO CART --}}
                <div class="mb-8">
                    {{-- 1. Quantity Selector (Baris Atas) --}}
                    <div class="mb-6">
                        {{-- Wrapper Container (Border Box) --}}
                        <div class="inline-flex items-center border border-gray-200 h-11 w-32 bg-white">
                            
                            {{-- Decrease Button --}}
                            <button onclick="updateQuantity(-1)" type="button" 
                                    class="w-10 h-full flex items-center justify-center text-gray-500 hover:text-gray-900 transition-colors focus:outline-none">
                                <span class="sr-only">Decrease quantity</span>
                                {{-- SVG Minus dari Snippet Anda --}}
                                <svg aria-hidden="true" focusable="false" fill="none" width="10" height="10" viewBox="0 0 12 12">
                                    <path d="M0 6h12" stroke="currentColor" stroke-width="1.5"></path>
                                </svg>
                            </button>

                            {{-- Input Field --}}
                            <input type="number" id="quantity" value="1" min="1" readonly 
                                class="flex-1 w-full h-full border-none text-center text-sm font-medium text-gray-900 focus:ring-0 p-0 bg-transparent appearance-none"
                                aria-label="Change quantity">

                            {{-- Increase Button --}}
                            <button onclick="updateQuantity(1)" type="button" 
                                    class="w-10 h-full flex items-center justify-center text-gray-500 hover:text-gray-900 transition-colors focus:outline-none">
                                <span class="sr-only">Increase quantity</span>
                                {{-- SVG Plus dari Snippet Anda --}}
                                <svg aria-hidden="true" focusable="false" fill="none" width="10" height="10" viewBox="0 0 12 12">
                                    <path d="M6 0v12M0 6h12" stroke="currentColor" stroke-width="1.5"></path>
                                </svg>
                            </button>
                            
                        </div>
                    </div>

                    {{-- Add to Cart Button (Full Width) --}}
                    <button onclick="addToCart({{ $product->id }})" 
                            class="w-full bg-[#E8B49E] hover:bg-[#dcaea1] text-white py-4 px-8 text-xs uppercase tracking-[0.2em] font-bold transition-all duration-300 hover:shadow-md">
                        Add to Cart
                    </button>
                </div>

                {{-- 6. STOCK / PICKUP INFO (Persis Gambar) --}}
                {{-- <div class="space-y-3 pb-8 border-b border-gray-100 mb-8">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                        <div class="text-sm">
                            <p class="text-gray-900">Pickup available at <span class="font-medium">Greenwich</span></p>
                            <p class="text-xs text-gray-500 mt-0.5">Usually ready in 1 hour</p>
                            <a href="#" class="text-xs text-gray-900 underline mt-1 inline-block hover:no-underline">Check availability at other stores</a>
                        </div>
                    </div>
                </div> --}}

                {{-- 7. DESCRIPTION (Text Only Style) --}}
                @if($product->description)
                <div class="mb-10 text-sm text-gray-600 leading-7 font-light">
                    {!! nl2br(e($product->description)) !!}
                </div>
                @endif

                {{-- 8. SPECIFICATIONS (Clean List) --}}
                @if($product->specifications && $product->specifications->count() > 0)
                <div class="mb-10 space-y-4">
                    @foreach($product->specifications->sortBy('sort_order') as $spec)
                        <div>
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">{{ $spec->section_title }}</h3>
                            <ul class="text-sm text-gray-600 list-disc list-inside space-y-1 pl-1 marker:text-gray-300">
                                @foreach($spec->items as $item)
                                    <li class="pl-2">{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- 9. COLLAPSIBLE SECTIONS (Delivery & Returns) --}}
                <div class="border-t border-gray-100">
                    <details class="group py-4 border-b border-gray-100 cursor-pointer">
                        <summary class="flex justify-between items-center list-none text-xs font-bold uppercase tracking-[0.15em] text-gray-900">
                            <span>Delivery & Returns</span>
                            <span class="transition-transform group-open:rotate-180">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </summary>
                        <div class="text-sm text-gray-600 mt-4 leading-relaxed space-y-2 font-light">
                            <p>Free shipping all across Indonesia.</p>
                            <p>Returns accepted within 14 days of delivery.</p>
                        </div>
                    </details>
                    
                    <details id="reviews-dropdown" class="group py-4 border-b border-gray-100 cursor-pointer" ontoggle="handleReviewsToggle(this)">
                        <summary class="flex justify-between items-center list-none text-xs font-bold uppercase tracking-[0.15em] text-gray-900">
                            <span>Reviews (<span id="dropdown-review-count">{{ $product->rating_count }}</span>)</span>
                            <span class="transition-transform group-open:rotate-180">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </summary>
                        <div class="mt-6">
                            @if($product->rating_count > 0)
                                {{-- Rating Summary --}}
                                <div class="mb-6 pb-6 border-b border-gray-100">
                                    <div class="flex items-center justify-center gap-3">
                                        <div class="text-center">
                                            <div class="text-3xl font-light text-gray-900 mb-1">
                                                <span id="dropdown-rating-avg">{{ number_format($product->rating_avg, 1) }}</span>
                                                <span class="text-lg text-gray-400">/5</span>
                                            </div>
                                            <div id="dropdown-stars" class="flex justify-center text-[#E8B49E] space-x-0.5 mb-2">
                                                {{-- Stars rendered based on initial rating --}}
                                                @php
                                                    $rating = $product->rating_avg;
                                                    $fullStars = floor($rating);
                                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                                @endphp
                                                @for($i = 0; $i < 5; $i++)
                                                    @if($i < $fullStars)
                                                        {{-- Full star --}}
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @elseif($i == $fullStars && $hasHalfStar)
                                                        {{-- Half star --}}
                                                        <svg class="w-3.5 h-3.5" viewBox="0 0 20 20"><defs><linearGradient id="halfStarGrad"><stop offset="50%" stop-color="#E8B49E"/><stop offset="50%" stop-color="#E5E7EB"/></linearGradient></defs><path fill="url(#halfStarGrad)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @else
                                                        {{-- Empty star --}}
                                                        <svg class="w-3.5 h-3.5 text-gray-200 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">
                                                Based on <span id="dropdown-rating-count">{{ $product->rating_count }}</span> reviews
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Reviews List --}}
                                <div id="dropdown-reviews-list" class="space-y-6 md:space-y-8 mb-6">
                                    {{-- Loading Spinner --}}
                                    <div class="flex items-center justify-center py-8">
                                        <div class="animate-spin rounded-full h-6 w-6 border-2 border-gray-100 border-t-gray-900"></div>
                                    </div>
                                </div>
                                
                                {{-- Pagination --}}
                                <div id="dropdown-reviews-pagination" class="hidden mt-6 pt-4 border-t border-gray-100">
                                </div>
                            @else
                                {{-- No Reviews Message --}}
                                <div class="text-center py-8">
                                    <p class="text-xs text-gray-400 italic">No reviews yet. Be the first to review this product!</p>
                                </div>
                            @endif
                        </div>
                    </details>
                </div>

            </div>
        </div>
    </div>

    {{-- YOU MAY ALSO LIKE (Clean Grid) --}}
    @if($relatedProducts->count() > 0)
    <div class="border-t border-gray-100 md:pt-24">
        <div class="max-w-screen-2xl mx-auto px-4 lg:px-6">
            <h2 class="text-center text-xl font-normal uppercase tracking-[0.2em] mb-12 text-gray-900">You May Also Like</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 md:gap-x-8 gap-y-10 md:gap-y-16">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="group relative flex flex-col">
                        
                        {{-- A. IMAGE CONTAINER --}}
                        <div class="relative w-full aspect-square bg-[#F9F9F9] overflow-hidden group/image">
                            <a href="{{ route('products.show', $relatedProduct) }}" class="block w-full h-full">
                                @if($relatedProduct->featuredImage)
                                    <img src="{{ asset('storage/' . $relatedProduct->featuredImage->image) }}" 
                                         alt="{{ $relatedProduct->name }}" 
                                         class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover/image:scale-105">
                                @else
                                    <img src="{{ asset('img/no-image.jpg') }}" 
                                         alt="No Image" 
                                         class="w-full h-full object-cover opacity-30">
                                @endif
                            </a>

                            {{-- QUICK VIEW BUTTON (+) --}}
                            <button type="button"
                                class="quick-view-btn absolute bottom-2 right-2 bg-white text-gray-900 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center hover:bg-gray-900 hover:text-white transition-all duration-300 opacity-0 group-hover/image:opacity-100 translate-y-2 group-hover/image:translate-y-0 cursor-pointer z-20 shadow-sm"
                                data-product-id="{{ $relatedProduct->id }}"
                                data-product-name="{{ $relatedProduct->name }}"
                                data-product-slug="{{ $relatedProduct->slug }}"
                                data-price-idr="{{ $relatedProduct->price_idr }}"
                                data-price-usd="{{ $relatedProduct->price_usd }}"
                                data-product-url="{{ route('products.show', $relatedProduct) }}"
                                data-rating-avg="{{ $relatedProduct->rating_avg ?? 0 }}"
                                data-rating-count="{{ $relatedProduct->rating_count ?? 0 }}"
                                data-featured-image="{{ $relatedProduct->featuredImage ? asset('storage/' . $relatedProduct->featuredImage->image) : '' }}"
                                data-colors='@json($relatedProduct->colors ? $relatedProduct->colors->map(fn($c) => ["name" => $c->name, "hex_code" => $c->hex_code])->values()->toArray() : [])'
                                data-sizes='@json($relatedProduct->sizes ? $relatedProduct->sizes->pluck("size_name")->toArray() : [])'>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>

                        {{-- B. PRODUCT INFO (Minimalist Text) --}}
                        <div class="mt-4 text-center">
                            <h3 class="text-[10px] md:text-[11px] font-bold text-gray-900 uppercase tracking-[0.15em] mb-1 leading-relaxed">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="hover:text-gray-600 transition-colors">
                                    {{ $relatedProduct->name }}
                                </a>
                            </h3>

                            <p class="text-[11px] md:text-xs text-gray-500 tracking-widest font-medium mb-3">
                                Rp {{ number_format($relatedProduct->price_idr, 0, ',', '.') }}
                            </p>

                            @if($relatedProduct->colors && $relatedProduct->colors->count() > 0)
                            <div class="flex justify-center items-center space-x-2 mb-2">
                                @foreach($relatedProduct->colors->take(4) as $color)
                                <div class="w-3 h-3 md:w-4 md:h-4 rounded-full border border-gray-200" 
                                     style="background-color: {{ $color->hex_code }};" 
                                     title="{{ $color->name }}"></div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Rating Stars (Dynamic with Partial Fill) --}}
                            @if($relatedProduct->rating_count > 0)
                            <div class="flex justify-center items-center space-x-0.5">
                                @php
                                    $rating = $relatedProduct->rating_avg ?? 0;
                                    $fullStars = floor($rating);
                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                @endphp

                                {{-- Full Stars --}}
                                @for($i = 0; $i < $fullStars; $i++)
                                <svg class="w-3 h-3 md:w-3.5 md:h-3.5 text-[#E8B49E] fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor

                                {{-- Half Star --}}
                                @if($hasHalfStar)
                                <svg class="w-3 h-3 md:w-3.5 md:h-3.5 text-[#E8B49E]" viewBox="0 0 20 20">
                                    <defs>
                                        <linearGradient id="half-{{ $relatedProduct->id }}">
                                            <stop offset="50%" stop-color="currentColor"/>
                                            <stop offset="50%" stop-color="#E5E7EB" stop-opacity="1"/>
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#half-{{ $relatedProduct->id }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endif

                                {{-- Empty Stars --}}
                                @for($i = 0; $i < $emptyStars; $i++)
                                <svg class="w-3 h-3 md:w-3.5 md:h-3.5 text-gray-200 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

{{-- SweetAlert2 & Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Quantity Logic
    function updateQuantity(change) {
        const input = document.getElementById('quantity');
        let newValue = parseInt(input.value) + change;
        if (newValue < 1) newValue = 1;
        input.value = newValue;
    }

    // 2. Image Switcher
    function changeMainImage(imageSrc, thumbnailElement) {
        const mainImg = document.getElementById('mainProductImage');

        // Fade out
        mainImg.classList.add('fade-out');
        mainImg.classList.remove('fade-in');

        // Preload image dulu biar ga lag
        const newImage = new Image();
        newImage.src = imageSrc;

        newImage.onload = function() {
            setTimeout(() => {
                // Ganti src setelah fade out
                mainImg.src = imageSrc;
                // Fade in
                mainImg.classList.remove('fade-out');
                mainImg.classList.add('fade-in');
                
                // ✅ Update data-current-image agar lightbox tahu index yang benar
                mainImg.setAttribute('data-current-image', imageSrc);
            }, 200);
        };

        // Update border thumbnail
        document.querySelectorAll('.thumbnail-image').forEach(el => {
            el.classList.remove('border-gray-900');
            el.classList.add('border-transparent');
        });
        thumbnailElement.classList.remove('border-transparent');
        thumbnailElement.classList.add('border-gray-900');
    }

    // 3. Color Selection
    function selectColor(colorName, buttonElement) {
        document.getElementById('selectedColorName').textContent = colorName;
        
        document.querySelectorAll('.color-option').forEach(btn => {
            btn.classList.remove('ring-1', 'ring-offset-2', 'ring-gray-900', 'border-transparent');
            btn.classList.add('border-gray-300', 'hover:border-gray-400'); // Reset style
        });
        
        // Active style
        buttonElement.classList.remove('border-gray-300', 'hover:border-gray-400');
        buttonElement.classList.add('ring-1', 'ring-offset-2', 'ring-gray-900', 'border-transparent');
    }

    // Size Selection Update
    function updateSelectedSize() {
        const sizeSelect = document.getElementById('sizeSelect');
        const selectedSize = sizeSelect.value;
        document.getElementById('selectedSizeName').textContent = selectedSize;
    }

    // 4. Add to Cart Logic
    function addToCart(productId) {
        const quantity = parseInt(document.getElementById('quantity').value);
        const selectedColor = document.getElementById('selectedColorName')?.textContent || null;
        
        const sizeSelect = document.getElementById('sizeSelect');
        const selectedSize = sizeSelect ? sizeSelect.value : null;
        // Loading State
        const btn = event.target;
        const originalText = btn.innerText;
        btn.innerText = 'ADDING...';
        btn.disabled = true;

        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                color: selectedColor,
                size: selectedSize,
                quantity: quantity // ✅ Kirim quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                @guest
                // Guest: Update localStorage dengan quantity yang benar
                if (data.product) {
                    const cart = JSON.parse(localStorage.getItem('guest_cart') || '{}');

                    const colorKey = data.product.color || 'default';
                    const sizeKey = data.product.size || 'default';
                    const cartKey = `${productId}_${colorKey}_${sizeKey}`;
                    
                    console.log('💾 Saving to localStorage:', { cartKey, quantity: data.quantity }); // ✅ DEBUG

                    if (cart[cartKey]) {
                        // Tambahkan quantity baru ke yang sudah ada
                        cart[cartKey] = parseInt(cart[cartKey]) + data.quantity;
                    } else {
                        // Set quantity baru
                        cart[cartKey] = data.quantity;
                    }
                    
                    localStorage.setItem('guest_cart', JSON.stringify(cart));
                    console.log('✅ Updated localStorage:', cart); // ✅ DEBUG
                }
                @endguest
                
                Swal.fire({
                    title: 'ADDED TO CART',
                    html: `<p class="text-sm text-gray-600">${quantity} item(s) added to your cart</p>`,
                    icon: 'success',
                    color: '#111827',
                    confirmButtonColor: '#111827',
                    confirmButtonText: 'VIEW CART',
                    showCancelButton: true,
                    cancelButtonText: 'CONTINUE SHOPPING',
                    cancelButtonColor: '#6B7280',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-none',
                        confirmButton: 'rounded-none px-6 py-3 text-xs uppercase tracking-widest',
                        cancelButton: 'rounded-none px-6 py-3 text-xs uppercase tracking-widest hover:bg-gray-600',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/cart';
                    } else {
                        // Reset quantity ke 1 setelah berhasil add
                        document.getElementById('quantity').value = 1;
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

    // Init: Set first thumbnail active
    document.addEventListener('DOMContentLoaded', function() {
        
        const mainImg = document.getElementById('mainProductImage');
        if (mainImg) {
            mainImg.setAttribute('data-current-image', mainImg.src);
        } else {
            console.error('❌ Main image not found!');
        }
        
        // Check if lightbox function is available
        if (typeof window.openProductGallery === 'function') {
            console.log('✅ openProductGallery function available');
        } else {
            console.error('❌ openProductGallery function not found!');
        }
        
        const firstThumbnail = document.querySelector('.thumbnail-image');
        if (firstThumbnail) {
            firstThumbnail.classList.remove('border-transparent');
            firstThumbnail.classList.add('border-gray-900');
        }
    });

    function openMainImageLightbox(img) {
        console.log('🖼️ Main image clicked!');
        
        // Check if function exists
        if (typeof openProductGallery !== 'function') {
            console.error('❌ openProductGallery function not available!');
            alert('Error: Lightbox not loaded. Please refresh the page.');
            return;
        }
        
        const images = JSON.parse(img.getAttribute('data-lightbox-images'));
        const currentImage = img.getAttribute('data-current-image') || img.src;
        
        
        // ✅ Cari index gambar yang sedang ditampilkan di main image
        const index = images.findIndex(src => src === currentImage);
                
        // Buka lightbox di index yang benar
        openProductGallery(images, index >= 0 ? index : 0);
    }

    // --- HANDLE REVIEWS TOGGLE ---
    let reviewsLoaded = false;

    function handleReviewsToggle(detailsElement) {
        const reviewCount = {{ $product->rating_count }};
        if (detailsElement.open && !reviewsLoaded && reviewCount > 0) {
            loadDropdownReviews(1);
            reviewsLoaded = true;
        }
    }

    // --- RENDER STARS FOR SUMMARY ---
    function renderSummaryStars(rating) {
        const stars = [];
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        
        for (let i = 0; i < 5; i++) {
            if (i < fullStars) {
                // Full star
                stars.push(`<svg class="w-3.5 h-3.5 text-[#E8B49E] fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`);
            } else if (i === fullStars && hasHalfStar) {
                // Half star
                stars.push(`<svg class="w-3.5 h-3.5" viewBox="0 0 20 20"><defs><linearGradient id="halfStarSummary"><stop offset="50%" stop-color="#E8B49E"/><stop offset="50%" stop-color="#E5E7EB"/></linearGradient></defs><path fill="url(#halfStarSummary)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`);
            } else {
                // Empty star
                stars.push(`<svg class="w-3.5 h-3.5 text-gray-200 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`);
            }
        }
        
        return stars.join('');
    }

    // --- LOAD DROPDOWN REVIEWS ---
    async function loadDropdownReviews(page = 1) {
        const reviewsList = document.getElementById('dropdown-reviews-list');
        const pagination = document.getElementById('dropdown-reviews-pagination');
        const productId = {{ $product->id }};
        
        // Skeleton Loading
        reviewsList.innerHTML = `
            <div class="space-y-4 animate-pulse">
                ${[1,2,3].map(() => `
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex-shrink-0"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-2 bg-gray-100 rounded w-1/4"></div>
                            <div class="h-2 bg-gray-100 rounded w-3/4"></div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        
        try {
            const response = await fetch(`/api/products/${productId}/reviews?page=${page}`);
            const data = await response.json();
            
            // Update counts
            document.getElementById('dropdown-review-count').textContent = data.total;
            document.getElementById('dropdown-rating-count').textContent = data.total;
            
            // Update average rating if available
            if (data.rating_avg !== undefined) {
                const rating = parseFloat(data.rating_avg);
                document.getElementById('dropdown-rating-avg').textContent = rating.toFixed(1);
                
                // Update stars
                const starsContainer = document.getElementById('dropdown-stars');
                starsContainer.innerHTML = renderSummaryStars(rating);
            }
            
            if (data.data.length > 0) {
                reviewsList.innerHTML = data.data.map((review, index) => renderDropdownReview(review, index)).join('');            
                if (data.last_page > 1) {
                    pagination.innerHTML = renderDropdownPagination(data);
                    pagination.classList.remove('hidden');
                } else {
                    pagination.classList.add('hidden');
                }
            } else {
                reviewsList.innerHTML = `
                    <div class="text-center py-6">
                        <p class="text-xs text-gray-400 italic">No reviews yet. Be the first to review this product!</p>
                    </div>
                `;
                pagination.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error loading reviews:', error);
            reviewsList.innerHTML = '<p class="text-center text-red-500 py-4 text-xs">Unable to load reviews.</p>';
        }
    }

    // --- RENDER DROPDOWN REVIEW ---
    function renderDropdownReview(review, index) {
        const stars = Array(5).fill(0).map((_, i) => {
            const filled = i < review.rating;
            return `<svg class="w-3 h-3 md:w-3.5 md:h-3.5 ${filled ? 'text-[#E8B49E]' : 'text-gray-200'} fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`;
        }).join('');
        
        let userName = 'Anonymous';
        let initials = 'A';
        if (!review.anonymous && review.order && review.order.shipment) {
            userName = `${review.order.shipment.first_name} ${review.order.shipment.last_name}`;
            initials = review.order.shipment.first_name.charAt(0).toUpperCase();
        }
        
        const verifiedBadge = review.order_id 
            ? '<svg class="w-3 h-3 text-emerald-500 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg><span class="ml-1 text-[9px] md:text-[10px] uppercase tracking-wider text-gray-400 font-medium">Verified</span>' 
            : '';
        
        // Multiple images support
        let imagesHtml = '';
        if (review.images && review.images.length > 0) {
            const imageElements = review.images.map(img => 
                `<img src="/storage/${img.image}" alt="Review photo" class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity border border-gray-100" onclick="openLightbox('/storage/${img.image}')">`
            ).join('');
            imagesHtml = `<div class="mt-3 md:mt-4 flex gap-2">${imageElements}</div>`;
        }
        
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
                    
                    ${review.comment ? `<p class="text-xs md:text-sm text-gray-600 leading-relaxed font-light mt-2">${review.comment}</p>` : ''}
                    ${imagesHtml}
                </div>
            </div>
        `;
    }

    // --- RENDER DROPDOWN PAGINATION ---
    function renderDropdownPagination(data) {
        const pages = [];
        const btnClass = "w-7 h-7 md:w-8 md:h-8 flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors focus:outline-none";
        const activeBtnClass = "w-7 h-7 md:w-8 md:h-8 flex items-center justify-center text-xs md:text-sm font-bold text-gray-900 border-b-2 border-gray-900 focus:outline-none";
        const textBtnClass = "w-7 h-7 md:w-8 md:h-8 flex items-center justify-center text-xs md:text-sm font-light text-gray-500 hover:text-gray-900 transition-colors focus:outline-none";

        const leftArrow = `<svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>`;
        const rightArrow = `<svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>`;
        const doubleLeftArrow = `<svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/></svg>`;
        const doubleRightArrow = `<svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>`;

        if (data.current_page > 1) {
            pages.push(`<button onclick="loadDropdownReviews(1)" class="${btnClass}">${doubleLeftArrow}</button>`);
            pages.push(`<button onclick="loadDropdownReviews(${data.current_page - 1})" class="${btnClass}">${leftArrow}</button>`);
        }
        
        const maxVisible = 4;
        let startPage = Math.max(1, data.current_page - Math.floor(maxVisible / 2));
        let endPage = Math.min(data.last_page, startPage + maxVisible - 1);
        if (endPage - startPage < maxVisible - 1) startPage = Math.max(1, endPage - maxVisible + 1);
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === data.current_page) {
                pages.push(`<button class="${activeBtnClass}">${i}</button>`);
            } else {
                pages.push(`<button onclick="loadDropdownReviews(${i})" class="${textBtnClass}">${i}</button>`);
            }
        }
        
        if (data.current_page < data.last_page) {
            pages.push(`<button onclick="loadDropdownReviews(${data.current_page + 1})" class="${btnClass}">${rightArrow}</button>`);
            pages.push(`<button onclick="loadDropdownReviews(${data.last_page})" class="${btnClass}">${doubleRightArrow}</button>`);
        }
        
        return `<div class="flex items-center justify-center space-x-1 md:space-x-2">${pages.join('')}</div>`;
    }
</script>

<style>
    /* Hilangkan panah default pada number input */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    /* Hide default details marker */
    details > summary { list-style: none; }
    details > summary::-webkit-details-marker { display: none; }

    #mainProductImage {
        transition: opacity 0.4s cubic-bezier(.4,0,.2,1);
    }
    #mainProductImage.fade-out {
        opacity: 0;
    }
    #mainProductImage.fade-in {
        opacity: 1;
    }
</style>
@endsection