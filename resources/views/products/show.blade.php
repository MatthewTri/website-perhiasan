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

                {{-- 4. DROPDOWN VARIANT (Example style based on image - e.g. Letter/Size) --}}
                {{-- Jika produk punya spesifikasi ukuran/huruf, tampilkan disini --}}
                {{-- <div class="mb-6">
                    <label class="block text-xs text-gray-500 mb-2">Letter:</label>
                    <div class="relative">
                        <select class="w-full appearance-none border border-gray-200 bg-white py-3 px-4 pr-8 rounded-none text-sm text-gray-900 focus:outline-none focus:border-gray-400 focus:ring-0 cursor-pointer">
                            <option>A</option>
                            <option>B</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div> --}}

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
                <div class="space-y-3 pb-8 border-b border-gray-100 mb-8">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                        <div class="text-sm">
                            <p class="text-gray-900">Pickup available at <span class="font-medium">Greenwich</span></p>
                            <p class="text-xs text-gray-500 mt-0.5">Usually ready in 1 hour</p>
                            <a href="#" class="text-xs text-gray-900 underline mt-1 inline-block hover:no-underline">Check availability at other stores</a>
                        </div>
                    </div>
                </div>

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
                    
                    <details class="group py-4 border-b border-gray-100 cursor-pointer">
                        <summary class="flex justify-between items-center list-none text-xs font-bold uppercase tracking-[0.15em] text-gray-900">
                            <span>Reviews ({{ $product->rating_count }})</span>
                            <span class="transition-transform group-open:rotate-180">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </summary>
                        <div class="text-sm text-gray-600 mt-4 font-light">
                            <p class="italic">No reviews yet.</p>
                        </div>
                    </details>
                </div>

            </div>
        </div>
    </div>

    {{-- YOU MAY ALSO LIKE (Clean Grid) --}}
    <div class="border-t border-gray-100 py-16 md:py-24">
        <div class="max-w-screen-2xl mx-auto px-4 lg:px-6">
            <h2 class="text-center text-xl font-normal uppercase tracking-[0.2em] mb-12 text-gray-900">You May Also Like</h2>
            {{-- Insert your grid component here --}}
        </div>
    </div>
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

    // 4. Add to Cart Logic
    function addToCart(productId) {
        const quantity = parseInt(document.getElementById('quantity').value);
        const selectedColor = document.getElementById('selectedColorName')?.textContent || null;
        
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
                    const cartKey = `${productId}_${colorKey}`;
                    
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
                    cancelButtonColor: '#ffffff',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-none',
                        confirmButton: 'rounded-none px-6 py-3 text-xs uppercase tracking-widest',
                        cancelButton: 'rounded-none px-6 py-3 text-xs uppercase tracking-widest text-gray-900 border border-gray-200 hover:bg-gray-50',
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
        const firstThumbnail = document.querySelector('.thumbnail-image');
        if (firstThumbnail) {
            firstThumbnail.classList.remove('border-transparent');
            firstThumbnail.classList.add('border-gray-900');
        }

        const mainImg = document.getElementById('mainProductImage');
        if (mainImg) {
            mainImg.setAttribute('data-current-image', mainImg.src);
        }
    });

    function openMainImageLightbox(img) {
        const images = JSON.parse(img.getAttribute('data-lightbox-images'));
        const currentImage = img.getAttribute('data-current-image') || img.src;
        
        // ✅ Cari index gambar yang sedang ditampilkan di main image
        const index = images.findIndex(src => src === currentImage);
        
        // Buka lightbox di index yang benar
        openLightbox(images, index >= 0 ? index : 0);
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