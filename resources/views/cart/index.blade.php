@extends('layout.main')

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- Header Section --}}
    <div class="pt-16 pb-12 text-center border-b border-gray-100">
        <h1 class="text-2xl md:text-3xl font-serif text-gray-900 uppercase tracking-[0.2em]">Shopping cart</h1>
        <p class="mt-3 text-xs text-gray-500 uppercase tracking-widest font-light">
            You are eligible for free shipping
        </p>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="max-w-screen-xl mx-auto px-6 mt-6">
            <div class="bg-gray-50 border-l-2 border-gray-900 p-4 flex items-center justify-between">
                <p class="text-xs uppercase tracking-wide text-gray-600">{{ session('success') }}</p>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-900">&times;</button>
            </div>
        </div>
    @endif

    <div class="max-w-screen-xl mx-auto px-4 md:px-6 py-12 md:py-16">
        
        {{-- AUTHENTICATED USER CART --}}
        @auth
            @if($cartItems->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
                    
                    {{-- Left: Cart Items List --}}
                    <div class="lg:col-span-8 space-y-8">
                        {{-- Table Header (Hidden on Mobile) --}}
                        <div class="hidden md:grid grid-cols-12 gap-4 pb-4 border-b border-gray-200 text-[10px] uppercase tracking-widest text-gray-500 font-medium">
                            <div class="col-span-6">Product</div>
                            <div class="col-span-2 text-center">Quantity</div>
                            <div class="col-span-4 text-right">Total</div>
                        </div>

                        @foreach($cartItems as $item)
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-4 items-center pb-8 border-b border-gray-100 last:border-0 relative">
                                
                                {{-- Product Image & Details --}}
                                <div class="col-span-1 md:col-span-6 flex items-start space-x-6">
                                    <div class="w-24 h-24 bg-[#F7F7F7] flex-shrink-0">
                                        @if($item->product->featuredImage)
                                            <img src="{{ asset('storage/' . $item->product->featuredImage->image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-full h-full object-cover object-center">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0 pt-1">
                                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.15em] leading-relaxed mb-1">
                                            <a href="{{ route('products.show', $item->product) }}" class="hover:text-gray-600 transition-colors">
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>
                                        <p class="text-[11px] text-gray-500 tracking-wide mb-3">
                                            @currency(['idr' => $item->product->price_idr, 'usd' => $item->product->price_usd])
                                        </p>
                                        
                                        @if($item->color)
                                            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">
                                                Color: <span class="text-gray-600">{{ $item->color }}</span>
                                            </p>
                                        @endif

                                        @if($item->size)
                                            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">
                                                Size: <span class="text-gray-600">{{ $item->size }}</span>
                                            </p>
                                        @endif

                                        {{-- Remove Button (Mobile Position) --}}
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-3 md:hidden">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[10px] uppercase tracking-widest text-gray-400 hover:text-red-600 transition-colors border-b border-gray-200 hover:border-red-600 pb-0.5">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Quantity Selector --}}
                                <div class="col-span-1 md:col-span-2 flex justify-start md:justify-center">
                                    <div class="inline-flex items-center border border-gray-200 h-9 w-24">
                                        {{-- Decrement --}}
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="decrement">
                                            <button type="submit" class="w-8 h-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">
                                                <span class="text-lg leading-none">&minus;</span>
                                            </button>
                                        </form>

                                        <span class="flex-1 text-center text-xs font-medium text-gray-900">{{ $item->quantity }}</span>

                                        {{-- Increment --}}
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="increment">
                                            <button type="submit" class="w-8 h-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">
                                                <span class="text-lg leading-none">&plus;</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Total Price & Desktop Remove --}}
                                <div class="col-span-1 md:col-span-4 flex flex-col items-end justify-between h-full py-1">
                                    <span class="text-sm font-medium text-gray-900 tracking-wide">
                                        @currencyTotal([
                                            'idr' => $item->product->price_idr, 
                                            'usd' => $item->product->price_usd, 
                                            'qty' => $item->quantity
                                        ])
                                    </span>
                                    
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="hidden md:block mt-auto">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[10px] uppercase tracking-widest text-gray-400 hover:text-red-600 transition-colors underline decoration-gray-200 hover:decoration-red-600 underline-offset-4">
                                            Remove
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    {{-- Right: Order Summary --}}
                    <div class="lg:col-span-4">
                        <div class="bg-[#F9F9F9] p-8 lg:p-10 sticky top-28">
                            <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-6 pb-4 border-b border-gray-200">
                                Order Summary
                            </h2>
                            
                            <div class="space-y-4 mb-8">
                                <div class="flex justify-between items-start">
                                    <span class="text-xs uppercase tracking-wide text-gray-500">Subtotal</span>
                                    <span class="text-sm font-medium text-gray-900">
                                        @currency(['idr' => $totalIdr, 'usd' => $totalUsd])
                                    </span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-xs uppercase tracking-wide text-gray-500">Shipping</span>
                                    <span class="text-xs text-gray-900">Calculated at checkout</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center py-6 border-t border-gray-200 mb-8">
                                <span class="text-sm font-bold uppercase tracking-widest text-gray-900">Total</span>
                                <span class="text-lg font-medium text-gray-900">
                                    @currency(['idr' => $totalIdr, 'usd' => $totalUsd])
                                </span>
                            </div>

                            {{-- Checkout Button --}}
                            <a href="{{ route('checkout.index') }}" 
                               class="block w-full bg-[#E3CFCF] hover:bg-[#ddc8c8] text-white text-center py-4 px-6 text-xs uppercase tracking-[0.2em] font-bold transition-all duration-300 shadow-sm hover:shadow-md mb-4">
                                Proceed to Checkout
                            </a>
                            
                            <a href="{{ route('products.index') }}" class="block w-full text-center text-[10px] uppercase tracking-[0.2em] text-gray-500 hover:text-gray-900 transition-colors underline underline-offset-4">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            @else
                {{-- Empty State Auth --}}
                <div class="text-center py-20">
                    <h3 class="text-lg font-serif text-gray-900 mb-2">Your Bag is Empty</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-8">Looks like you haven't added any pieces yet.</p>
                    <a href="{{ route('products.index') }}" class="inline-block border-b border-gray-900 pb-1 text-xs uppercase tracking-[0.2em] font-medium hover:text-gray-600 transition-colors">
                        Start Shopping
                    </a>
                </div>
            @endif
        @endauth

        {{-- GUEST USER CART (Rendered by JS) --}}
        @guest
            <div id="guest-cart-container" class="min-h-[400px]">
                {{-- Content injected via JS --}}
                <div id="guest-cart-content" class="hidden"></div>

                {{-- Empty State Guest --}}
                <div id="empty-cart" class="hidden text-center py-20">
                    <h3 class="text-lg font-serif text-gray-900 mb-2">Your Bag is Empty</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-8">Looks like you haven't added any pieces yet.</p>
                    <a href="{{ route('products.index') }}" class="inline-block border-b border-gray-900 pb-1 text-xs uppercase tracking-[0.2em] font-medium hover:text-gray-600 transition-colors">
                        Start Shopping
                    </a>
                </div>
            </div>
        @endguest

    </div>
</div>

@guest
<script>
    const checkoutUrl = "{{ route('checkout.index') }}";
    const currentCurrency = "{{ $globalCurrency }}";

    // Guest Cart Management
    class GuestCart {
        constructor() {
            this.storageKey = 'guest_cart';
            this.init();
        }

        init() {
            this.loadGuestCart();
        }

        getCart() {
            const cart = localStorage.getItem(this.storageKey);
            return cart ? JSON.parse(cart) : {};
        }

        saveCart(cart) {
            localStorage.setItem(this.storageKey, JSON.stringify(cart));
        }

        addItem(productId, quantity = 1) {
            const cart = this.getCart();
            if (cart[productId]) {
                cart[productId] += quantity;
            } else {
                cart[productId] = quantity;
            }
            this.saveCart(cart);
        }

        removeItem(cartKey) {
            const cart = this.getCart();
            delete cart[cartKey];
            this.saveCart(cart);
            this.loadGuestCart();
        }

        async loadGuestCart() {
            const cart = this.getCart();
            const productIds = Object.keys(cart);

            document.getElementById('guest-cart-content').classList.add('hidden');
            document.getElementById('empty-cart').classList.add('hidden');

            if (productIds.length === 0) {
                document.getElementById('empty-cart').classList.remove('hidden');
                return;
            }

            try {
                const response = await fetch('/api/cart/guest-items', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_ids: cart })
                });

                const data = await response.json();

                this.displayCart(data.items);
            } catch (error) {
                console.error('Error loading cart:', error);
                document.getElementById('empty-cart').classList.remove('hidden');
            }
        }

        // ⬅️ FORMAT PRICE BERDASARKAN CURRENCY
        formatPrice(priceIdr, priceUsd) {
            if (currentCurrency === 'USD') {
                return 'USD ' + new Intl.NumberFormat('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(priceUsd);
            } else {
                return 'IDR ' + new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(priceIdr);
            }
        }

        updateQuantity(cartKey, action) {
            const cart = this.getCart();
            
            if (!cart[cartKey]) {
                console.error('Item not found in cart');
                return;
            }

            if (action === 'increment') {
                cart[cartKey] += 1;
            } else if (action === 'decrement') {
                if (cart[cartKey] > 1) {
                    cart[cartKey] -= 1;
                } else {
                    // Jika quantity = 1 dan decrement, hapus item
                    delete cart[cartKey];
                }
            }

            this.saveCart(cart);
            this.loadGuestCart(); // Reload cart untuk update UI
        }

        displayCart(items) {
            let total = 0;
            let totalItems = 0;
            let itemsHtml = '';

            items.forEach(item => {
                const price = currentCurrency === 'USD' ? item.product.price_usd : item.product.price_idr;
                const quantity = item.quantity; 
                const itemTotal = price * quantity;
                
                total += itemTotal;
                totalItems += quantity;

                const cartKey = `${item.product.id}_${item.color || 'default'}_${item.size || 'default'}`; 

                const imageSrc = item.product.featured_image 
                    ? `/storage/${item.product.featured_image.image}` 
                    : '/img/no-image.jpg';

                itemsHtml += `
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-4 items-center pb-8 border-b border-gray-100 last:border-0 relative">
                    
                    <div class="col-span-1 md:col-span-6 flex items-start space-x-6">
                        <div class="w-24 h-24 bg-[#F7F7F7] flex-shrink-0">
                            <img src="${imageSrc}" alt="${item.product.name}" class="w-full h-full object-cover object-center">
                        </div>
                        <div class="flex-1 min-w-0 pt-1">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.15em] leading-relaxed mb-1">
                                <a href="/products/${item.product.slug}" class="hover:text-gray-600 transition-colors">
                                    ${item.product.name}
                                </a>
                            </h3>
                            <p class="text-[11px] text-gray-500 tracking-wide mb-3">
                                ${this.formatPrice(item.product.price_idr, item.product.price_usd)}
                            </p>
                            ${item.color ? `<p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Color: <span class="text-gray-600">${item.color}</span></p>` : ''}
                            ${item.size ? `<p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Size: <span class="text-gray-600">${item.size}</span></p>` : ''}
                            <button onclick="guestCart.removeItem('${cartKey}')" class="mt-3 md:hidden text-[10px] uppercase tracking-widest text-gray-400 hover:text-red-600 border-b border-gray-200 hover:border-red-600 pb-0.5 transition-colors">
                                Remove
                            </button>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 flex justify-start md:justify-center">
                        <div class="inline-flex items-center border border-gray-200 h-9 w-24 bg-white">
                            <button onclick="guestCart.updateQuantity('${cartKey}', 'decrement')" 
                                    class="w-8 h-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">
                                <span class="text-lg leading-none">&minus;</span>
                            </button>
                            <span class="flex-1 text-center text-xs font-medium text-gray-900">${quantity}</span>
                            <button onclick="guestCart.updateQuantity('${cartKey}', 'increment')" 
                                    class="w-8 h-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">
                                <span class="text-lg leading-none">&plus;</span>
                            </button>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-4 flex flex-col items-end justify-between h-full py-1">
                        <span class="text-sm font-medium text-gray-900 tracking-wide">
                            ${this.formatPrice(item.product.price_idr * quantity, item.product.price_usd * quantity)}
                        </span>
                        
                        <button onclick="guestCart.removeItem('${cartKey}')" class="hidden md:block mt-auto text-[10px] uppercase tracking-widest text-gray-400 hover:text-red-600 underline decoration-gray-200 hover:decoration-red-600 underline-offset-4 transition-all">
                            Remove
                        </button>
                    </div>
                </div>`;
            });

            const layoutHtml = `
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
                <div class="lg:col-span-8 space-y-8">
                    <div class="hidden md:grid grid-cols-12 gap-4 pb-4 border-b border-gray-200 text-[10px] uppercase tracking-widest text-gray-500 font-medium">
                        <div class="col-span-6">Product</div>
                        <div class="col-span-2 text-center">Quantity</div>
                        <div class="col-span-4 text-right">Total</div>
                    </div>
                    
                    ${itemsHtml}
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-[#F9F9F9] p-8 lg:p-10 sticky top-28">
                        <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-6 pb-4 border-b border-gray-200">
                            Order Summary
                        </h2>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-start">
                                <span class="text-xs uppercase tracking-wide text-gray-500">Total Items</span>
                                <span class="text-sm font-medium text-gray-900">${totalItems}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-xs uppercase tracking-wide text-gray-500">Shipping</span>
                                <span class="text-xs text-gray-900">Calculated at checkout</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center py-6 border-t border-gray-200 mb-8">
                            <span class="text-sm font-bold uppercase tracking-widest text-gray-900">Total</span>
                            <span class="text-lg font-medium text-gray-900">
                                ${this.formatPrice(total, total)}
                            </span>
                        </div>

                        <a href="${checkoutUrl}" 
                        class="block w-full bg-[#E3CFCF] hover:bg-[#ddc8c8] text-white text-center py-4 px-6 text-xs uppercase tracking-[0.2em] font-bold transition-all duration-300 shadow-sm hover:shadow-md mb-4">
                            Proceed to Checkout
                        </a>
                        
                        <a href="/products" class="block w-full text-center text-[10px] uppercase tracking-[0.2em] text-gray-500 hover:text-gray-900 transition-colors underline underline-offset-4">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>`;

            document.getElementById('guest-cart-content').innerHTML = layoutHtml;
            document.getElementById('guest-cart-content').classList.remove('hidden');
        }
    }

    // Initialize guest cart
    const guestCart = new GuestCart();

    async function migrateGuestCartToUser() {
        const guestCart = localStorage.getItem('guest_cart');
        
        if (!guestCart) {
            return;
        }
        
        const cartData = JSON.parse(guestCart);
        
        if (Object.keys(cartData).length === 0) {
            return;
        }
        
        try {
            const response = await fetch('/cart/migrate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(cartData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                localStorage.removeItem('guest_cart');
                console.log('Cart migration successful:', result.message);
            } else {
                console.error('Cart migration failed:', result.error);
            }
        } catch (error) {
            console.error('Error during cart migration:', error);
        }
    }

    window.migrateGuestCartToUser = migrateGuestCartToUser;
</script>
@endguest
@endsection