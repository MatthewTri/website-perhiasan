@extends('layout.main')

@section('content')

{{-- ============================================================
     GOOGLE FONTS - Cormorant Garamond (display) + Jost (body)
     ============================================================ --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@200;300;400;500&display=swap" rel="stylesheet">

{{-- ============================================================
     HERO SECTION — Full bleed, cinematic, editorial
     ============================================================ --}}
<section class="hero-section relative w-full h-screen min-h-[600px] max-h-[1000px] overflow-hidden">

    {{-- Background Image --}}
    <div class="absolute inset-0">
        <img src="{{ asset('asset/home/home.JPEG') }}"
             alt="Cecile Private Collection"
             loading="eager"
             fetchpriority="high"
             decoding="async"
             class="w-full h-full object-cover object-center gpu-img"
             onerror="this.style.display='none'">
    </div>

    {{-- Decorative corner lines --}}
    <div class="absolute top-8 left-8 w-16 h-16 border-t border-l border-white/30 hidden md:block" aria-hidden="true"></div>
    <div class="absolute top-8 right-8 w-16 h-16 border-t border-r border-white/30 hidden md:block" aria-hidden="true"></div>
    <div class="absolute bottom-8 left-8 w-16 h-16 border-b border-l border-white/30 hidden md:block" aria-hidden="true"></div>
    <div class="absolute bottom-8 right-8 w-16 h-16 border-b border-r border-white/30 hidden md:block" aria-hidden="true"></div>

    {{-- Hero Content --}}
    <div class="relative h-full flex flex-col items-center justify-center text-center px-6">

        {{-- Eyebrow --}}
        <p class="hero-eyebrow text-white/60 font-jost font-light text-[10px] tracking-[0.5em] uppercase mb-6 md:mb-8">
            Est. 2019 &nbsp;·&nbsp; Surabaya, Indonesia
        </p>

        {{-- Main Headline --}}
        <h1 class="hero-title font-cormorant font-light text-white leading-[0.9] tracking-[0.08em] uppercase mb-6 md:mb-8"
            style="font-size: clamp(3.5rem, 10vw, 8rem);">
            Cecile<br>
            <em class="italic font-extralight" style="font-size:0.85em;">Private</em><br>
            Collection
        </h1>

        <div class="hero-divider w-px h-12 bg-white/40 mx-auto mb-6 md:mb-8"></div>

        {{-- <p class="hero-sub font-jost font-light text-white/80 text-xs md:text-sm tracking-[0.3em] uppercase mb-10 md:mb-12">
            Bespoke Jewellery &nbsp;·&nbsp; Crafted With Intention
        </p> --}}

        {{-- CTAs --}}
        <div class="hero-cta flex flex-col sm:flex-row gap-4 items-center">
            <a href="{{ route('products.index') }}"
               class="cta-primary group inline-flex items-center justify-center px-10 py-4 border border-white text-white font-jost font-medium text-[10px] tracking-[0.3em] uppercase overflow-hidden relative">
                <span class="cta-fill absolute inset-0 bg-white transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500 ease-in-out"></span>
                <span class="relative group-hover:text-gray-900 transition-colors duration-500">Shop Collection</span>
            </a>
            <a href="{{ route('consultation.index') }}"
               class="cta-secondary group inline-flex items-center justify-center px-10 py-4 font-jost font-light text-white/80 text-[10px] tracking-[0.3em] uppercase hover:text-white transition-colors duration-300">
                <span class="mr-3 w-6 h-px bg-current inline-block transition-all duration-300 group-hover:w-10"></span>
                Book Consultation
            </a>
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 scroll-hint">
        <div class="w-px h-10 bg-white/30 relative overflow-hidden">
            <div class="scroll-line absolute top-0 left-0 w-full h-1/2 bg-white/70"></div>
        </div>
    </div>
</section>

{{-- ============================================================
     MARQUEE STRIP — Luxury brand signals
     ============================================================ --}}
<div class="marquee-strip bg-[#1a1a1a] py-4 overflow-hidden" aria-hidden="true">
    <div class="marquee-track flex gap-12 whitespace-nowrap">
        @foreach(range(1, 4) as $i)
        <span class="font-jost font-light text-[10px] tracking-[0.4em] text-[#C9A89A] uppercase">Bespoke Jewellery</span>
        <span class="text-[#C9A89A]/30">✦</span>
        <span class="font-jost font-light text-[10px] tracking-[0.4em] text-white/40 uppercase">GIA Certified</span>
        <span class="text-[#C9A89A]/30">✦</span>
        <span class="font-jost font-light text-[10px] tracking-[0.4em] text-[#C9A89A] uppercase">Indonesian Artisans</span>
        <span class="text-[#C9A89A]/30">✦</span>
        <span class="font-jost font-light text-[10px] tracking-[0.4em] text-white/40 uppercase">Est. 2019 Surabaya</span>
        <span class="text-[#C9A89A]/30">✦</span>
        @endforeach
    </div>
</div>

{{-- ============================================================
     SHOP BY CATEGORY — Editorial grid
     ============================================================ --}}
<section class="cv-section py-20 md:py-32 px-6 lg:px-16 max-w-screen-2xl mx-auto">

    <div class="section-header flex items-center gap-6 mb-14 md:mb-20">
        <div class="w-8 h-px bg-[#C9A89A]"></div>
        <p class="font-jost font-light text-[10px] tracking-[0.5em] uppercase text-[#C9A89A]">Discover</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">

        {{-- Large left card --}}
        <a href="{{ route('products.index') }}"
           class="group relative overflow-hidden aspect-[4/5] md:aspect-auto md:row-span-2 bg-[#f0e8e4]">
            <img src="{{ asset('asset/product/rings/London-Blue-Topaz-Classic-Victorian-Ring.jpg') }}" alt="Rings & Bands"
                 loading="lazy" decoding="async"
                 class="w-full h-full object-cover gpu-img cat-img"
                 onerror="this.parentElement.classList.add('bg-[#e8d8d0]')">
            <div class="cat-overlay absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-500"></div>
            <div class="absolute bottom-0 left-0 right-0 p-8 md:p-10 translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                <p class="font-jost font-light text-[9px] text-[#C9A89A] tracking-[0.5em] uppercase mb-2">Signature</p>
                <h3 class="font-cormorant font-light text-white text-3xl md:text-4xl tracking-[0.1em] uppercase mb-3">Rings &amp; Bands</h3>
                <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    <span class="w-6 h-px bg-[#C9A89A]"></span>
                    <span class="font-jost text-white/70 text-[10px] tracking-[0.3em] uppercase">Explore</span>
                </div>
            </div>
        </a>

        {{-- Top right --}}
        <a href="{{ route('products.index') }}"
           class="group relative overflow-hidden aspect-[4/3] bg-[#f0e8e4]">
            <img src="{{ asset('asset/product/necklaces/Green-Quartz-Pendant.jpg') }}" alt="Necklaces"
                 loading="lazy" decoding="async"
                 class="w-full h-full object-cover gpu-img cat-img"
                 onerror="this.parentElement.classList.add('bg-[#ede0db]')">
            <div class="cat-overlay absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-500"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                <p class="font-jost font-light text-[9px] text-[#C9A89A] tracking-[0.5em] uppercase mb-1">Delicate</p>
                <h3 class="font-cormorant font-light text-white text-2xl md:text-3xl tracking-[0.1em] uppercase">Necklaces</h3>
            </div>
        </a>

        {{-- Bottom right (2 cols) --}}
        <div class="grid grid-cols-2 gap-3 md:gap-4">
            <a href="{{ route('products.index') }}"
               class="group relative overflow-hidden aspect-square bg-[#f0e8e4]">
                <img src="{{ asset('asset/product/earrings/Pink-Tourmaline-with-Drop-Diamond-Earrings.jpg') }}" alt="Earrings"
                     loading="lazy" decoding="async"
                     class="w-full h-full object-cover gpu-img cat-img"
                     onerror="this.parentElement.classList.add('bg-[#e8d8d0]')">
                <div class="cat-overlay absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-500"></div>
                <div class="absolute bottom-0 left-0 p-4 md:p-5">
                    <h3 class="font-cormorant font-light text-white text-xl md:text-2xl tracking-[0.08em] uppercase">Earrings</h3>
                </div>
            </a>
            <a href="{{ route('products.index') }}"
               class="group relative overflow-hidden aspect-square bg-[#f0e8e4]">
                <img src="{{ asset('asset/product/bracelet/Red-Spinel-with-Diamonds-Chain-Bracelet.jpg') }}" alt="Bracelets"
                     loading="lazy" decoding="async"
                     class="w-full h-full object-cover gpu-img cat-img"
                     onerror="this.parentElement.classList.add('bg-[#ede0db]')">
                <div class="cat-overlay absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-500"></div>
                <div class="absolute bottom-0 left-0 p-4 md:p-5">
                    <h3 class="font-cormorant font-light text-white text-xl md:text-2xl tracking-[0.08em] uppercase">Bracelets</h3>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- ============================================================
     BEST SELLERS — Elegant minimal product grid
     ============================================================ --}}
<section class="bg-[#FAF7F5] py-20 md:py-32">
    <div class="max-w-screen-2xl mx-auto px-6 lg:px-16">

        {{-- Section Header --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-14 md:mb-20 gap-6">
            <div>
                <div class="flex items-center gap-6 mb-5">
                    <div class="w-8 h-px bg-[#C9A89A]"></div>
                    <p class="font-jost font-light text-[10px] tracking-[0.5em] uppercase text-[#C9A89A]">Curated Selection</p>
                </div>
                <h2 class="font-cormorant font-light text-gray-900 tracking-[0.1em] uppercase leading-none"
                    style="font-size: clamp(2.2rem, 5vw, 4rem);">
                    Most Loved
                </h2>
            </div>
            <a href="{{ route('products.index') }}"
               class="group inline-flex items-center gap-4 font-jost font-light text-[10px] tracking-[0.4em] uppercase text-gray-500 hover:text-[#C9A89A] transition-colors duration-300 self-start md:self-auto">
                View All
                <span class="w-8 h-px bg-current transition-all duration-300 group-hover:w-14"></span>
            </a>
        </div>

        {{-- Product Grid — style sama dengan halaman all products --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 md:gap-x-8 gap-y-10 md:gap-y-16">
            @php
                $bestSellers = \App\Models\Product::query()
                    ->with([
                        'featuredImage',
                        'colors:id,product_id,name,hex_code,sort_order',
                        'sizes:id,product_id,size_name,sort_order',
                    ])
                    ->bestSellers(null, 8)
                    ->get();

                $missing = 8 - $bestSellers->count();
                if ($missing > 0) {
                    $fallback = \App\Models\Product::query()
                        ->with([
                            'featuredImage',
                            'colors:id,product_id,name,hex_code,sort_order',
                            'sizes:id,product_id,size_name,sort_order',
                        ])
                        ->when(
                            $bestSellers->isNotEmpty(),
                            fn ($q) => $q->whereNotIn('id', $bestSellers->pluck('id'))
                        )
                        ->limit($missing)
                        ->get();

                    $bestSellers = $bestSellers->concat($fallback);
                }
            @endphp

            @forelse($bestSellers as $product)
            <div class="group relative flex flex-col">
                <div class="relative w-full aspect-square bg-[#F9F9F9] overflow-hidden group/image">
                    <a href="{{ route('products.show', $product) }}" class="block w-full h-full">
                        @if($product->featuredImage)
                            <img src="{{ asset('storage/' . $product->featuredImage->image) }}"
                                 alt="{{ $product->name }}"
                                 decoding="async"
                                 class="w-full h-full object-cover object-center gpu-img transition-transform duration-700 ease-in-out">
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

                {{-- Product Info --}}
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
                                    $fillPercentage = 100;
                                } elseif ($product->rating_avg > ($i - 1)) {
                                    $fillPercentage = ($product->rating_avg - ($i - 1)) * 100;
                                }
                            @endphp
                            <div class="relative w-3 h-3">
                                <svg class="absolute inset-0 w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
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

        {{-- View All CTA --}}
        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center justify-center px-10 py-4 bg-gray-900 text-white hover:bg-[#E3CFCF] transition-all duration-300 border-2 border-gray-900 hover:border-[#E3CFCF]">
                <span class="text-xs md:text-sm font-bold tracking-[0.2em] uppercase">View All Products</span>
            </a>
        </div>
    </div>
</section>

{{-- ============================================================
     BRAND STORY / ABOUT SECTION — Dark, editorial, immersive
     ============================================================ --}}
<section class="cv-section relative overflow-hidden bg-[#111111] py-24 md:py-36">

    {{-- Subtle texture overlay --}}
    <div class="about-texture absolute inset-0 opacity-[0.03]" aria-hidden="true"></div>

    <div class="relative max-w-screen-2xl mx-auto px-6 lg:px-16">

        {{-- Top label --}}
        <div class="flex items-center gap-6 mb-16 md:mb-24">
            <div class="w-8 h-px bg-[#C9A89A]"></div>
            <p class="font-jost font-light text-[10px] tracking-[0.5em] uppercase text-[#C9A89A]">The House of Cecile</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-0 items-start">

            {{-- Left: Large statement text --}}
            <div class="lg:col-span-5 lg:pr-12">
                <h2 class="font-cormorant font-light text-white leading-[0.95] tracking-[0.05em] uppercase mb-10"
                    style="font-size: clamp(2.8rem, 6vw, 5.5rem);">
                    More<br>Than<br>
                    <em class="italic text-[#C9A89A]">Beautiful</em>
                </h2>

                <p class="font-jost font-light text-white/50 text-xs tracking-[0.3em] uppercase mb-6">Est. 2019, Surabaya</p>

                <a href="{{ route('about-us')}}"
                   class="group inline-flex items-center gap-5 font-jost font-light text-[10px] tracking-[0.4em] uppercase text-[#C9A89A] hover:text-white transition-colors duration-300 mt-4">
                    Our Story
                    <span class="w-10 h-px bg-current transition-all duration-400 group-hover:w-16"></span>
                </a>
            </div>

            {{-- Center: Image --}}
            <div class="lg:col-span-4 relative">
                <div class="relative overflow-hidden">
                    <img src="{{ asset('asset/aboutus/DD_06014.jpg') }}"
                         alt="Cecilia Hermanto - Founder"
                         loading="lazy"
                         decoding="async"
                         class="w-full aspect-[3/4] object-cover object-center gpu-img">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#111]/60 via-transparent to-transparent pointer-events-none"></div>
                </div>

                {{-- Floating name badge --}}
                <div class="absolute -bottom-5 -right-4 md:-right-8 bg-[#C9A89A] px-5 py-4">
                    <p class="font-jost font-light text-[8px] tracking-[0.4em] uppercase text-white/70 mb-0.5">Founder</p>
                    <p class="font-cormorant font-light text-white text-lg tracking-[0.1em]">Cecilia Hermanto</p>
                </div>
            </div>

            {{-- Right: Story text + credentials --}}
            <div class="lg:col-span-3 lg:pl-12 lg:pt-4 flex flex-col gap-10">

                <p class="font-jost font-light text-white/60 text-sm leading-relaxed">
                    Founded on the belief that jewellery should hold emotion, preserve memory, and reflect the identity of its wearer. Every bespoke piece begins with a conversation.
                </p>

                <div class="space-y-6 pt-6 border-t border-white/10">
                    <div>
                        <p class="font-jost font-light text-[9px] tracking-[0.4em] uppercase text-[#C9A89A] mb-1">Education</p>
                        <p class="font-cormorant text-white text-base tracking-wide">NAFA Singapore</p>
                        <p class="font-jost font-light text-white/40 text-[10px] tracking-[0.2em]">Jewellery Design</p>
                    </div>
                    <div>
                        <p class="font-cormorant text-white text-base tracking-wide">Birmingham City University</p>
                        <p class="font-jost font-light text-white/40 text-[10px] tracking-[0.2em]">International Jewellery Business</p>
                    </div>
                    <div>
                        <p class="font-cormorant text-white text-base tracking-wide">GIA Certified</p>
                        <p class="font-jost font-light text-white/40 text-[10px] tracking-[0.2em]">Coloured Stones Grading</p>
                    </div>
                </div>

                {{-- Numbers --}}
                <div class="grid grid-cols-2 gap-0 pt-6 border-t border-white/10">
                    <div>
                        <p class="font-cormorant font-light text-[#C9A89A] text-4xl tracking-wider">5+</p>
                        <p class="font-jost font-light text-white/40 text-[9px] tracking-[0.3em] uppercase mt-1">Years</p>
                    </div>
                    <div class="border-l border-white/10 pl-6">
                        <p class="font-cormorant font-light text-[#C9A89A] text-4xl tracking-wider">3+</p>
                        <p class="font-jost font-light text-white/40 text-[9px] tracking-[0.3em] uppercase mt-1">Certifications</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Full-width quote --}}
        <div class="mt-24 md:mt-32 pt-12 md:pt-16 border-t border-white/10 text-center">
            <blockquote class="font-cormorant font-light italic text-white/70 leading-relaxed max-w-4xl mx-auto"
                        style="font-size: clamp(1.4rem, 3vw, 2.4rem);">
                "Jewellery is deeply personal — something that can hold emotion, preserve memories, and reflect the identity of its wearer."
            </blockquote>
            <p class="font-jost font-light text-[9px] tracking-[0.5em] uppercase text-[#C9A89A] mt-6">Cecilia Hermanto · Founder</p>
        </div>
    </div>
</section>


{{-- ============================================================
     FEATURES STRIP — Minimal, elegant
     ============================================================ --}}
<section class="cv-section border-t border-b border-[#E8D9D3] py-12 md:py-16">
    <div class="max-w-screen-2xl mx-auto px-6 lg:px-16">
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-[#E8D9D3]">

            <div class="flex items-center gap-6 py-8 md:py-0 md:px-12 first:pl-0 last:pr-0">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#C9A89A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-jost font-medium text-[11px] tracking-[0.25em] uppercase text-gray-900 mb-1">Authentic Quality</h3>
                    <p class="font-jost font-light text-xs text-gray-500 leading-relaxed">Genuine materials with certificate of authenticity</p>
                </div>
            </div>

            <div class="flex items-center gap-6 py-8 md:py-0 md:px-12">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#C9A89A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-jost font-medium text-[11px] tracking-[0.25em] uppercase text-gray-900 mb-1">Lifetime Support</h3>
                    <p class="font-jost font-light text-xs text-gray-500 leading-relaxed">Complimentary cleaning & maintenance for all pieces</p>
                </div>
            </div>

            <div class="flex items-center gap-6 py-8 md:py-0 md:px-12">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#C9A89A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-jost font-medium text-[11px] tracking-[0.25em] uppercase text-gray-900 mb-1">Free Shipping</h3>
                    <p class="font-jost font-light text-xs text-gray-500 leading-relaxed">Express delivery across Indonesia, secure packaging</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     INSTAGRAM FEED — Grid mosaic
     ============================================================ --}}
<section class="cv-section py-20 md:py-32 px-6 lg:px-16 max-w-screen-2xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12 md:mb-16 gap-5">
        <div>
            <div class="flex items-center gap-6 mb-5">
                <div class="w-8 h-px bg-[#C9A89A]"></div>
                <p class="font-jost font-light text-[10px] tracking-[0.5em] uppercase text-[#C9A89A]">Social</p>
            </div>
            <h2 class="font-cormorant font-light text-gray-900 tracking-[0.08em] uppercase"
                style="font-size: clamp(1.8rem, 4vw, 3.2rem);">
                Follow the Journey
            </h2>
        </div>
        <a href="https://www.instagram.com/cecileprivatecollection" target="_blank"
           class="group inline-flex items-center gap-3 font-jost font-light text-[10px] tracking-[0.4em] uppercase text-gray-500 hover:text-[#C9A89A] transition-colors duration-300 self-start md:self-auto">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            @cecileprivatecollection
        </a>
    </div>

    {{-- Asymmetric Instagram grid --}}
    <div class="grid grid-cols-3 md:grid-cols-6 gap-2">
        @for($i = 1; $i <= 6; $i++)
        <a href="https://www.instagram.com/cecileprivatecollection/" target="_blank"
           class="group relative overflow-hidden {{ $i == 1 ? 'col-span-2 row-span-2 aspect-square' : 'aspect-square' }} bg-[#F3EDEA]">
            <img src="{{ asset('asset/instagram-home/' . $i . '.jpg') }}"
                 alt="Instagram {{ $i }}"
                 loading="lazy" decoding="async"
                 class="w-full h-full object-cover gpu-img ig-img">
            <div class="absolute inset-0 bg-[#C9A89A]/0 group-hover:bg-[#C9A89A]/70 transition-all duration-400 flex items-center justify-center">
                <svg class="w-7 h-7 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-400 scale-75 group-hover:scale-100 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </div>
        </a>
        @endfor
    </div>
</section>

{{-- ============================================================
     NEWSLETTER SECTION — Dark, luxurious
     ============================================================ --}}
<section class="cv-section relative overflow-hidden bg-[#1a1a1a] py-20 md:py-28">
    <div class="newsletter-deco absolute inset-0" aria-hidden="true"></div>
    <div class="relative max-w-3xl mx-auto px-6 text-center">

        <div class="flex items-center justify-center gap-5 mb-8">
            <div class="w-12 h-px bg-[#C9A89A]/50"></div>
            <p class="font-jost font-light text-[9px] tracking-[0.6em] uppercase text-[#C9A89A]">Private Access</p>
            <div class="w-12 h-px bg-[#C9A89A]/50"></div>
        </div>

        <h2 class="font-cormorant font-light text-white tracking-[0.08em] uppercase leading-tight mb-4"
            style="font-size: clamp(2rem, 4.5vw, 3.5rem);">
            Join Our Circle
        </h2>
        <p class="font-jost font-light text-white/50 text-xs md:text-sm tracking-[0.2em] leading-relaxed mb-10 max-w-lg mx-auto">
            Receive exclusive offers, early access to new collections, and styling inspiration from Cecilia.
        </p>

        <form action="{{ route('subscription.subscribe') }}" method="POST"
              class="flex flex-col sm:flex-row gap-0 max-w-lg mx-auto border border-white/10 focus-within:border-[#C9A89A] transition-colors duration-300">
            @csrf
            <input type="email"
                   name="email"
                   placeholder="Your email address"
                   value="{{ old('email') }}"
                   required
                   class="flex-1 px-6 py-4 bg-transparent text-white placeholder-white/30 font-jost font-light text-xs tracking-[0.2em] focus:outline-none focus:ring-1 focus:ring-[#E3CFCF] focus:border-transparent border-r border-white/10">
            <button type="submit"
                    class="px-8 py-4 bg-[#C9A89A] hover:bg-[#b89488] text-white font-jost font-medium text-[10px] tracking-[0.35em] uppercase transition-colors duration-300 whitespace-nowrap cursor-pointer">
                Subscribe
            </button>
        </form>

        <p class="font-jost font-light text-white/25 text-[10px] tracking-[0.2em] mt-5">
            By subscribing you agree to our Privacy Policy
        </p>
    </div>
</section>

{{-- ============================================================
     STYLES
     ============================================================ --}}
<style>
    /* Fonts */
    .font-cormorant { font-family: 'Cormorant Garamond', Georgia, serif; }
    .font-jost      { font-family: 'Jost', sans-serif; }

    /* ─── Performance ─── */
    .gpu-img {
        transform: translateZ(0);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    .cv-section {
        content-visibility: auto;
        contain-intrinsic-size: 0 600px;
    }
    section { contain: layout style; }

    /* ─── Hero ─── */
    .hero-eyebrow  { animation: fadeUp 1s ease-out 0.2s both; }
    .hero-title    { animation: fadeUp 1.1s ease-out 0.4s both; }
    .hero-divider  { animation: fadeUp 1s ease-out 0.7s both; }
    .hero-sub      { animation: fadeUp 1s ease-out 0.85s both; }
    .hero-cta      { animation: fadeUp 1s ease-out 1s both; }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Scroll indicator animation */
    .scroll-line {
        animation: scrollDrop 2s ease-in-out infinite;
    }
    @keyframes scrollDrop {
        0%   { transform: translateY(-100%); }
        100% { transform: translateY(200%); }
    }
    .scroll-hint { animation: fadeUp 1s ease-out 1.4s both; }

    /* ─── Marquee ─── */
    .marquee-track {
        animation: marquee 28s linear infinite;
        width: max-content;
    }
    @keyframes marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    .marquee-strip:hover .marquee-track { animation-play-state: paused; }

    /* ─── Category images ─── */
    .cat-img { transition: transform 0.6s ease; }
    a:hover .cat-img { transform: scale(1.04) translateZ(0); }

    /* ─── Product images ─── */
    .product-img { transition: transform 0.5s ease; }
    .group\/image:hover .product-img { transform: scale(1.05) translateZ(0); }

    /* ─── Process images ─── */
    .process-img { transition: transform 0.6s ease; }
    .process-step:hover .process-img { transform: scale(1.04) translateZ(0); }

    /* ─── Instagram images ─── */
    .ig-img { transition: transform 0.5s ease; }
    a:hover .ig-img { transform: scale(1.06) translateZ(0); }

    /* ─── About section texture ─── */
    .about-texture {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* ─── Newsletter deco ─── */
    .newsletter-deco {
        background:
            radial-gradient(ellipse 50% 70% at 0% 50%, rgba(201,168,154,0.06) 0%, transparent 60%),
            radial-gradient(ellipse 50% 70% at 100% 50%, rgba(201,168,154,0.04) 0%, transparent 60%);
        pointer-events: none;
    }

    /* ─── Scroll reveal ─── */
    @media (prefers-reduced-motion: no-preference) {
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ─── Accessibility ─── */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

{{-- ============================================================
     SCRIPTS
     ============================================================ --}}
<script>
(function () {
    /* ── Cart clear after checkout ── */
    @if(request()->get('clear_cart') == '1')
        localStorage.removeItem('guest_cart');
    @endif

    /* ── SweetAlert2 notifications ── */
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Order Placed Successfully!',
            html: '<p class="text-sm text-gray-600">{{ session('success') }}</p>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#C9A89A',
            customClass: { popup: 'rounded-none', confirmButton: 'rounded-none px-8 py-3 text-xs uppercase tracking-widest font-bold' }
        });
    @endif

    @if(session('subscription_success'))
        Swal.fire({
            icon: 'success', title: 'Success!',
            text: '{{ session('subscription_success') }}',
            confirmButtonText: 'OK', confirmButtonColor: '#C9A89A',
            customClass: { popup: 'rounded-none', confirmButton: 'rounded-none px-8 py-3 text-xs uppercase tracking-widest' }
        });
    @endif

    @if(session('subscription_error'))
        Swal.fire({
            icon: 'error', title: 'Oops...',
            text: '{{ session('subscription_error') }}',
            confirmButtonText: 'OK', confirmButtonColor: '#C9A89A',
            customClass: { popup: 'rounded-none', confirmButton: 'rounded-none px-8 py-3 text-xs uppercase tracking-widest' }
        });
    @endif

    @if($errors->has('email'))
        Swal.fire({
            icon: 'error', title: 'Validation Error',
            text: '{{ $errors->first('email') }}',
            confirmButtonText: 'OK', confirmButtonColor: '#C9A89A',
            customClass: { popup: 'rounded-none', confirmButton: 'rounded-none px-8 py-3 text-xs uppercase tracking-widest' }
        });
    @endif

    /* ── Scroll reveal ── */
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    requestAnimationFrame(() => e.target.classList.add('visible'));
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(el => io.observe(el));
    } else {
        reveals.forEach(el => el.classList.add('visible'));
    }
})();
</script>

@endsection