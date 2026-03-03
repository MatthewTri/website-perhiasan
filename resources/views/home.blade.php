@extends('layout.main')

@section('content')

{{-- HERO SECTION --}}
<section class="relative w-full h-[60vh] md:h-[75vh] lg:h-[85vh] overflow-hidden bg-gradient-to-br from-[#F5E6E0] to-[#E8B49E]/20">
    <div class="absolute inset-0">
        <img src="" 
             alt="Luxury Jewellery Collection" 
             class="w-full h-full object-cover object-center opacity-90"
             onerror="this.style.display='none'">
    </div>
    
    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent"></div>
    
    <div class="relative h-full flex items-center justify-center text-center px-4">
        <div class="max-w-4xl mx-auto space-y-6 md:space-y-8 animate-fade-in">
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-light text-white tracking-[0.3em] uppercase leading-tight">
                Timeless Elegance
            </h1>
            <p class="text-sm md:text-base text-white/90 font-light tracking-[0.2em] uppercase max-w-2xl mx-auto">
                Discover exquisite jewellery crafted for life's precious moments
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4">
                <a href="{{ route('products.index') }}" 
                   class="group relative inline-flex items-center justify-center px-8 md:px-12 py-3 md:py-4 bg-white text-gray-900 overflow-hidden transition-all duration-300 hover:bg-[#E8B49E] hover:text-white border-2 border-white">
                    <span class="relative text-xs md:text-sm font-bold tracking-[0.2em] uppercase">Shop Collection</span>
                </a>
                <a href="{{ route('consultation.index') }}" 
                   class="group relative inline-flex items-center justify-center px-8 md:px-12 py-3 md:py-4 bg-transparent text-white border-2 border-white overflow-hidden transition-all duration-300 hover:bg-white hover:text-gray-900">
                    <span class="relative text-xs md:text-sm font-bold tracking-[0.2em] uppercase">Book Consultation</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- FEATURED CATEGORIES --}}
<section class="max-w-screen-2xl mx-auto px-4 lg:px-6 py-16 md:py-24">
    <div class="text-center mb-12 md:mb-16">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-light text-gray-900 tracking-[0.3em] uppercase mb-4">
            Shop by Category
        </h2>
        <div class="w-24 h-px bg-[#E8B49E] mx-auto"></div>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        {{-- Necklaces --}}
        <a href="{{ route('products.index') }}" class="group relative overflow-hidden aspect-square bg-gray-50">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <img src="" 
                 alt="Necklaces" 
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                 >
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <h3 class="text-white text-sm md:text-lg font-bold tracking-[0.2em] uppercase transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    Necklaces
                </h3>
            </div>
        </a>
        
        {{-- Earrings --}}
        <a href="{{ route('products.index') }}" class="group relative overflow-hidden aspect-square bg-gray-50">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <img src="" 
                 alt="Earrings" 
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                 >
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <h3 class="text-white text-sm md:text-lg font-bold tracking-[0.2em] uppercase transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    Earrings
                </h3>
            </div>
        </a>
        
        {{-- Bracelets --}}
        <a href="{{ route('products.index') }}" class="group relative overflow-hidden aspect-square bg-gray-50">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <img src="" 
                 alt="Bracelets" 
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                 >
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <h3 class="text-white text-sm md:text-lg font-bold tracking-[0.2em] uppercase transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    Bracelets
                </h3>
            </div>
        </a>
        
        {{-- Rings --}}
        <a href="{{ route('products.index') }}" class="group relative overflow-hidden aspect-square bg-gray-50">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <img src="" 
                 alt="Rings" 
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                 >
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <h3 class="text-white text-sm md:text-lg font-bold tracking-[0.2em] uppercase transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    Rings
                </h3>
            </div>
        </a>
    </div>
</section>

{{-- BEST SELLERS SECTION --}}
<section class="bg-gradient-to-b from-white to-[#F5E6E0]/30 py-16 md:py-24">
    <div class="max-w-screen-2xl mx-auto px-4 lg:px-6">
        <div class="text-center mb-12 md:mb-16">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-light text-gray-900 tracking-[0.3em] uppercase mb-4">
                Best Sellers
            </h2>
            <div class="w-24 h-px bg-[#E8B49E] mx-auto mb-6"></div>
            <p class="text-sm md:text-base text-gray-600 font-light tracking-wide max-w-2xl mx-auto">
                Our most coveted pieces, cherished by jewellery lovers worldwide
            </p>
        </div>
        
        {{-- Product Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 md:gap-x-8 gap-y-10 md:gap-y-16">
            @php
                $bestSellers = \App\Models\Product::with(['images', 'colors'])
                    ->orWhereHas('orderItems')
                    ->withCount('orderItems')
                    ->orderBy('order_items_count', 'desc')
                    ->take(8)
                    ->get();
            @endphp
            
            @forelse($bestSellers as $product)
            <div class="group relative flex flex-col">
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
                    
                    {{-- Quick View Button --}}
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
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
                
                {{-- Product Info (Minimalist Text) --}}
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
                    </div>
                    @endif
                </div>
            </div>
            @empty
            {{-- Placeholder if no products --}}
            @for($i = 1; $i <= 4; $i++)
            <div class="group relative flex flex-col">
                <div class="relative w-full aspect-square bg-gray-100"></div>
                <div class="mt-4 flex flex-col space-y-2 text-center">
                    <div class="h-3 bg-gray-200 rounded w-3/4 mx-auto"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2 mx-auto"></div>
                </div>
            </div>
            @endfor
            @endforelse
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center justify-center px-10 py-4 bg-gray-900 text-white hover:bg-[#E8B49E] transition-all duration-300 border-2 border-gray-900 hover:border-[#E8B49E]">
                <span class="text-xs md:text-sm font-bold tracking-[0.2em] uppercase">View All Products</span>
            </a>
        </div>
    </div>
</section>

{{-- UNIQUE FEATURES --}}
<section class="max-w-screen-2xl mx-auto px-4 lg:px-6 py-16 md:py-24">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
        {{-- Feature 1 --}}
        <div class="flex flex-col items-center text-center space-y-4 group">
            <div class="w-16 h-16 md:w-20 md:h-20 flex items-center justify-center bg-[#F5E6E0] group-hover:bg-[#E8B49E] transition-colors duration-300">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-[#E8B49E] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-sm md:text-base font-bold tracking-[0.15em] uppercase text-gray-900">
                Authentic Quality
            </h3>
            <p class="text-xs md:text-sm text-gray-600 font-light leading-relaxed max-w-xs">
                Every piece is crafted with genuine materials and comes with a certificate of authenticity
            </p>
        </div>
        
        {{-- Feature 2 --}}
        <div class="flex flex-col items-center text-center space-y-4 group">
            <div class="w-16 h-16 md:w-20 md:h-20 flex items-center justify-center bg-[#F5E6E0] group-hover:bg-[#E8B49E] transition-colors duration-300">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-[#E8B49E] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-sm md:text-base font-bold tracking-[0.15em] uppercase text-gray-900">
                Lifetime Support
            </h3>
            <p class="text-xs md:text-sm text-gray-600 font-light leading-relaxed max-w-xs">
                Our expert team provides complimentary cleaning and maintenance for all your jewellery
            </p>
        </div>
        
        {{-- Feature 3 --}}
        <div class="flex flex-col items-center text-center space-y-4 group">
            <div class="w-16 h-16 md:w-20 md:h-20 flex items-center justify-center bg-[#F5E6E0] group-hover:bg-[#E8B49E] transition-colors duration-300">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-[#E8B49E] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h3 class="text-sm md:text-base font-bold tracking-[0.15em] uppercase text-gray-900">
                Free Shipping
            </h3>
            <p class="text-xs md:text-sm text-gray-600 font-light leading-relaxed max-w-xs">
                Complimentary express shipping on all orders across Indonesia with secure packaging
            </p>
        </div>
    </div>
</section>

{{-- INSTAGRAM FEED SECTION --}}
<section class="bg-[#F5E6E0]/30 py-16 md:py-24">
    <div class="max-w-screen-2xl mx-auto px-4 lg:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-light text-gray-900 tracking-[0.3em] uppercase mb-4">
                Follow Us
            </h2>
            <div class="w-24 h-px bg-[#E8B49E] mx-auto mb-6"></div>
            <a href="#" class="inline-flex items-center space-x-2 text-sm text-gray-700 hover:text-[#E8B49E] transition-colors tracking-widest">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                <span class="font-semibold uppercase">@cecileprivate</span>
            </a>
        </div>
        
        <div class="grid grid-cols-3 md:grid-cols-6 gap-2 md:gap-4">
            @for($i = 1; $i <= 6; $i++)
            <a href="#" class="group relative aspect-square overflow-hidden bg-gray-200">
                <img src="" 
                     alt="Instagram Post {{ $i }}" 
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                     >
                <div class="absolute inset-0 bg-[#E8B49E]/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </div>
            </a>
            @endfor
        </div>
    </div>
</section>

{{-- NEWSLETTER SECTION --}}
<section class="bg-gradient-to-br from-[#E8B49E] to-[#dcaea1] py-16 md:py-20">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-2xl md:text-3xl font-light text-white tracking-[0.3em] uppercase mb-4">
            Join Our Collection
        </h2>
        <p class="text-sm md:text-base text-white/90 font-light tracking-wide mb-8">
            Subscribe to receive exclusive offers, early access to new collections, and styling inspiration
        </p>
        
        <form action="{{ route('subscription.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
            @csrf
            <input type="email" 
                   name="email"
                   placeholder="Enter your email address" 
                   value="{{ old('email') }}"
                   required
                   class="flex-1 px-6 py-4 bg-white/95 text-gray-900 placeholder-gray-500 focus:outline-none text-sm tracking-wide">
            <button type="submit" 
                    class="px-8 py-4 bg-gray-900 text-white hover:bg-white hover:text-gray-900 transition-all duration-300 text-xs font-bold tracking-[0.2em] uppercase whitespace-nowrap">
                Subscribe
            </button>
        </form>
        
        <p class="text-xs text-white/70 mt-4 font-light">
            By subscribing, you agree to our Privacy Policy and consent to receive updates
        </p>
    </div>
</section>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 1s ease-out;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    @if(session('subscription_success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('subscription_success') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#E8B49E',
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'rounded-sm px-8 py-3 text-xs uppercase tracking-widest font-bold'
            }
        });
    @endif

    @if(session('subscription_error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('subscription_error') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#E8B49E',
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'rounded-sm px-8 py-3 text-xs uppercase tracking-widest font-bold'
            }
        });
    @endif

    @if($errors->has('email'))
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: '{{ $errors->first('email') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#E8B49E',
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'rounded-sm px-8 py-3 text-xs uppercase tracking-widest font-bold'
            }
        });
    @endif
</script>

@endsection
