@extends('layout.checkout')

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- Main Container (Split Layout) --}}
    <div class="flex flex-col lg:flex-row">
        
        {{-- LEFT COLUMN: Forms (Contact, Shipping, Payment) --}}
        <div class="w-full lg:w-[58%] px-4 lg:px-12 py-8 lg:py-16 order-2 lg:order-1">
            
            {{-- Header (Mobile Only) --}}
            <div class="lg:hidden mb-8 text-center">
                <h1 class="text-2xl font-serif text-gray-900 tracking-widest uppercase">Pre-Order Checkout</h1>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="{{ isset($isGuest) && $isGuest ? 'hidden' : '' }} max-w-2xl mx-auto lg:mx-0 lg:ml-auto">
                @csrf
                
                @guest
                <input type="hidden" name="cart_data" id="cart_data" value="">
                @endguest
                
                <div class="space-y-10">
                    
                    {{-- 1. Contact Information --}}
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">Contact</h2>
                            @guest
                                <div class="text-xs text-gray-500">
                                    Already have an account? <a href="{{ route('login') }}" class="text-[#E8B49E] hover:text-[#dcaea1] underline">Log in</a>
                                </div>
                            @endguest
                        </div>
                        
                        <div class="space-y-4">
                            <div class="relative">
                                <input type="email" name="contact_email" id="contact_email" 
                                       value="{{ Auth::check() ? Auth::user()->email : old('contact_email') }}" required
                                       class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors @error('contact_email') border-red-500 @enderror" 
                                       placeholder="Email">
                                <label for="contact_email" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">
                                    Email Address
                                </label>
                                @error('contact_email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. Shipping Address --}}
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">Shipping Address</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- First Name --}}
                            <div class="relative">
                                <input type="text" name="shipping_first_name" id="shipping_first_name" value="{{ old('shipping_first_name') }}" required
                                       class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors @error('shipping_first_name') border-red-500 @enderror" placeholder="First Name">
                                <label for="shipping_first_name" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">First Name</label>
                                @error('shipping_first_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Last Name --}}
                            <div class="relative">
                                <input type="text" name="shipping_last_name" id="shipping_last_name" value="{{ old('shipping_last_name') }}" required
                                       class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors @error('shipping_last_name') border-red-500 @enderror" placeholder="Last Name">
                                <label for="shipping_last_name" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">Last Name</label>
                                @error('shipping_last_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Address (Full Width) --}}
                            <div class="relative md:col-span-2">
                                <input type="text" name="shipping_address_line1" id="shipping_address_line1" value="{{ old('shipping_address_line1') }}" required
                                       class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors @error('shipping_address_line1') border-red-500 @enderror" placeholder="Address">
                                <label for="shipping_address_line1" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">Address</label>
                                @error('shipping_address_line1') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Country (Full Width) --}}
                            <div class="relative md:col-span-2">
                                <select name="shipping_country" id="shipping_country" required
                                        class="block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors appearance-none @error('shipping_country') border-red-500 @enderror">
                                    <option value="">Select Country</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                </div>
                                <label for="shipping_country" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">Country/Region</label>
                                @error('shipping_country') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Dynamic Fields Wrapper --}}
                            <div id="address-details" class="hidden md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Province --}}
                                <div class="relative">
                                    <select name="shipping_province" id="shipping_province" class="block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors appearance-none">
                                        <option value="">Province</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    </div>
                                    <label class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">Province</label>
                                    @error('shipping_province') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                {{-- City --}}
                                <div class="relative">
                                    <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city') }}" required
                                           class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent" placeholder="City">
                                    <label for="shipping_city" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">City</label>
                                    @error('shipping_city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                {{-- Postal Code --}}
                                <div class="relative">
                                    <input type="text" name="shipping_postal_code" id="shipping_postal_code" value="{{ old('shipping_postal_code') }}" oninput="this.value = this.value.toUpperCase()"
                                           class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent" placeholder="Postal Code (Optional)">
                                    <label for="shipping_postal_code" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">Postal Code (Optional)</label>
                                    @error('shipping_postal_code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Phone (Full Width) --}}
                            <div class="md:col-span-2">
                                <div class="relative">
                                    <input type="tel" id="shipping_phone" value="{{ old('shipping_phone') }}" required
                                           class="block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent @error('shipping_phone') border-red-500 @enderror">
                                    <input type="hidden" name="shipping_phone" id="hidden_shipping_phone" value="{{ old('shipping_phone') }}">
                                </div>
                                <div id="phone-error" class="hidden mt-1 text-xs text-red-500"></div>
                                @error('shipping_phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 3. Payment Method --}}
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">Payment</h2>
                        <p class="text-xs text-gray-500 mb-4 font-light">Select your preferred payment method. Details will be sent via email.</p>
                        
                        <div class="space-y-3">
                            {{-- BCA --}}
                            <label class="relative flex items-center p-4 border border-gray-200 cursor-pointer hover:border-gray-400 transition-all duration-200 payment-option group">
                                <input type="radio" name="payment_gateway" value="manual" required class="peer sr-only" {{ old('payment_gateway') == 'manual' ? 'checked' : '' }}>
                                <div class="w-4 h-4 border border-gray-300 rounded-full mr-4 peer-checked:border-[#E8B49E] peer-checked:bg-[#E8B49E] group-hover:border-gray-400 transition-colors"></div>
                                <div class="flex-1">
                                    <span class="block text-sm font-medium text-gray-900">Bank Transfer (BCA)</span>
                                    <span class="block text-xs text-gray-500">Manual transfer in IDR</span>
                                </div>
                                <img src="{{ asset('asset/bca-logo.png') }}" alt="BCA" class="h-6 opacity-60 grayscale group-hover:grayscale-0 transition-all">
                                {{-- Active State Border --}}
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-[#E8B49E] pointer-events-none transition-colors"></div>
                            </label>

                            {{-- PayPal --}}
                            <label class="relative flex items-center p-4 border border-gray-200 cursor-pointer hover:border-gray-400 transition-all duration-200 payment-option group">
                                <input type="radio" name="payment_gateway" value="paypal" required class="peer sr-only" {{ old('payment_gateway') == 'paypal' ? 'checked' : '' }}>
                                <div class="w-4 h-4 border border-gray-300 rounded-full mr-4 peer-checked:border-[#E8B49E] peer-checked:bg-[#E8B49E] group-hover:border-gray-400 transition-colors"></div>
                                <div class="flex-1">
                                    <span class="block text-sm font-medium text-gray-900">PayPal</span>
                                    <span class="block text-xs text-gray-500">International payment in USD</span>
                                </div>
                                <img src="{{ asset('asset/paypal.png') }}" alt="PayPal" class="h-6 opacity-60 grayscale group-hover:grayscale-0 transition-all">
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-[#E8B49E] pointer-events-none transition-colors"></div>
                            </label>
                        </div>
                        @error('payment_gateway') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- 4. Order Notes --}}
                    <div>
                        <div class="relative">
                            <textarea name="order_notes" id="order_notes" rows="3"
                                      class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent" placeholder="Notes">{{ old('order_notes') }}</textarea>
                            <label for="order_notes" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">Order Notes (Optional)</label>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-6">
                        <button type="submit" 
                                class="w-full bg-[#E8B49E] hover:bg-[#dcaea1] text-white font-bold text-xs uppercase tracking-[0.2em] py-4 px-6 transition-all duration-300 shadow-sm hover:shadow-md flex items-center justify-center">
                            Pre Order Now
                        </button>
                        
                        <div class="mt-4 flex items-center justify-center space-x-2 text-[10px] text-gray-400 uppercase tracking-wide">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Secure Checkout</span>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        {{-- RIGHT COLUMN: Order Summary (Sticky Sidebar) --}}
        <div class="w-full lg:w-[42%] bg-[#FAFAFA] border-l border-gray-100 order-1 lg:order-2">
            
            {{-- Wrapper Sticky --}}
            <div class="lg:sticky lg:top-0">
                
                {{-- Container Content --}}
                <div class="p-6 lg:p-16 h-full flex flex-col">
                    
                    {{-- Toggle Summary (Mobile Only) --}}
                    <div class="lg:hidden flex justify-between items-center py-4 border-b border-gray-200 cursor-pointer" onclick="document.getElementById('mobile-summary').classList.toggle('hidden')">
                        <span class="text-sm font-medium text-gray-900 flex items-center">
                            Show Order Summary 
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                        <span class="text-base font-bold text-gray-900 summary-total-display">
                            {{-- Total via JS --}}
                        </span>
                    </div>

                    {{-- Content --}}
                    <div id="mobile-summary" class="hidden lg:block pt-6 lg:pt-0">
                        <h2 class="hidden lg:block text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-8 pb-4 border-b border-gray-200">
                            Your Order
                        </h2>

                        {{-- Items List (Scrollable if too long) --}}
                        <div id="summary-items" class="space-y-6 mb-8 max-h-[calc(100vh-350px)] overflow-y-auto pr-2 pt-4 px-2 custom-scrollbar">
                            {{-- Auth Cart Items --}}
                            @auth
                            @foreach($cartItems as $item)
                            <div class="flex items-center space-x-4">
                                <div class="relative w-16 h-16 bg-white border border-gray-100 rounded-none flex-shrink-0">
                                    @if($item->product->featuredImage)
                                        <img src="{{ asset('storage/' . $item->product->featuredImage->image) }}" class="w-full h-full object-cover p-1">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50"><span class="text-[9px]">NO IMG</span></div>
                                    @endif
                                    <span class="absolute -top-2 -right-2 bg-[#E8B49E] text-black text-[10px] font-medium rounded-full w-5 h-5 flex items-center justify-center shadow-sm">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</p>
                                    
                                    {{-- ✅ TAMBAHKAN: Display Color & Size --}}
                                    @if($item->color || $item->size)
                                        <p class="text-[10px] text-gray-500 mt-1 space-x-2">
                                            @if($item->color)
                                                <span>{{ $item->color }}</span>
                                            @endif
                                            @if($item->color && $item->size)
                                                <span>•</span>
                                            @endif
                                            @if($item->size)
                                                <span>{{ $item->size }}</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                                <div class="text-sm font-medium text-gray-900 item-total"
                                    data-price-idr="{{ $item->product->price_idr }}"
                                    data-price-usd="{{ $item->product->price_usd }}"
                                    data-qty="{{ $item->quantity }}">
                                    @currencyTotal(['idr' => $item->product->price_idr, 'usd' => $item->product->price_usd, 'qty' => $item->quantity])
                                </div>
                            </div>
                            @endforeach
                            @endauth
                        </div>

                        {{-- Totals --}}
                        <div class="border-t border-gray-200 pt-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Subtotal</span>
                                <span class="text-sm font-medium text-gray-900" id="summary-subtotal" 
                                      data-idr="{{ $totalIdr ?? 0 }}" data-usd="{{ $totalUsd ?? 0 }}">
                                    @currency(['idr' => $totalIdr ?? 0, 'usd' => $totalUsd ?? 0])
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Shipping</span>
                                <span class="text-xs text-gray-400 uppercase tracking-wider">Calculated Next</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-gray-200 pt-6 mt-6">
                                <span class="text-base font-bold text-gray-900 uppercase tracking-widest">Total</span>
                                <span class="text-xl font-serif font-medium text-gray-900" id="summary-total"
                                      data-idr="{{ $totalIdr ?? 0 }}" data-usd="{{ $totalUsd ?? 0 }}">
                                    @currency(['idr' => $totalIdr ?? 0, 'usd' => $totalUsd ?? 0])
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Loader Overlay (Jika perlu) --}}
<div id="checkout-loading" class="hidden fixed inset-0 bg-white/80 z-50 flex flex-col items-center justify-center">
    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-[#E8B49E]"></div>
    <p class="mt-4 text-xs uppercase tracking-widest text-gray-500">Processing...</p>
</div>

<div id="checkout-empty" class="hidden min-h-screen flex flex-col items-center justify-center">
    <p class="text-gray-500 text-sm uppercase tracking-widest mb-4">Your cart is empty</p>
    <a href="{{ route('products.index') }}" class="text-[#E8B49E] border-b border-[#E8B49E] text-xs uppercase tracking-widest pb-1 hover:text-black hover:border-black transition-colors">Return to Shop</a>
</div>

{{-- Styles khusus Floating Label --}}
<style>
    /* Styling Scrollbar Summary */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 2px; }
    
    /* Input & Select Reset */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:hover,
    textarea:-webkit-autofill:focus,
    select:-webkit-autofill,
    select:-webkit-autofill:hover,
    select:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0px 1000px white inset;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>

@guest
<script>
    const checkoutUrl = "{{ route('checkout.index') }}";
    const currentCurrency = "{{ $globalCurrency }}";

    // Guest Cart Logic (Dipertahankan 100%)
    document.addEventListener('DOMContentLoaded', async function() {
        const guestCart = localStorage.getItem('guest_cart');
        
        if (!guestCart || Object.keys(JSON.parse(guestCart)).length === 0) {
            document.getElementById('checkout-loading').classList.add('hidden');
            document.getElementById('checkout-form').classList.add('hidden');
            document.getElementById('checkout-empty').classList.remove('hidden');
            return;
        }

        const cart = JSON.parse(guestCart);
        const productIds = Object.keys(cart).map(id => parseInt(id));
        
        // Show loader while fetching
        document.getElementById('checkout-loading').classList.remove('hidden');

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
            
            if (data.items && data.items.length > 0) {
                displayCheckoutSummary(data.items);
                document.getElementById('cart_data').value = JSON.stringify(cart);
                document.getElementById('checkout-loading').classList.add('hidden');
                document.getElementById('checkout-form').classList.remove('hidden');
            } else {
                // If API returns empty valid items
                document.getElementById('checkout-loading').classList.add('hidden');
                document.getElementById('checkout-empty').classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error loading cart:', error);
            document.getElementById('checkout-loading').classList.add('hidden');
            document.getElementById('checkout-empty').classList.remove('hidden');
        }
    });

    function displayCheckoutSummary(items) {
        // Render ulang HTML Summary untuk Guest agar sesuai desain baru
        let currentCurrency = '{{ $globalCurrency }}';
        
        const formatPrice = (priceIdr, priceUsd, currency) => {
            if (currency === 'USD') {
                return '$' + new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(priceUsd);
            } else {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(priceIdr);
            }
        };
        
        let subtotalIdr = 0;
        let subtotalUsd = 0;
        let itemsHtml = '';
        
        items.forEach(item => {
            const qty = item.quantity;
            subtotalIdr += item.product.price_idr * qty;
            subtotalUsd += item.product.price_usd * qty;
            
            const imageSrc = item.product.featured_image 
                ? `/storage/${item.product.featured_image.image}` 
                : '/img/no-image.jpg'; // Path placeholder
            
            let variantInfo = '';
            if (item.color || item.size) {
                const parts = [];
                if (item.color) parts.push(item.color);
                if (item.size) parts.push(item.size);
                variantInfo = `<p class="text-[10px] text-gray-500 mt-1">${parts.join(' • ')}</p>`;
            }
            
            itemsHtml += `
            <div class="flex items-center space-x-4">
                <div class="relative w-16 h-16 bg-white border border-gray-100 rounded-none flex-shrink-0">
                    <img src="${imageSrc}" alt="${item.product.name}" class="w-full h-full object-cover p-1">
                    <span class="absolute -top-2 -right-2 bg-[#E8B49E] text-black text-[10px] font-medium rounded-full w-5 h-5 flex items-center justify-center shadow-sm">
                        ${qty}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${item.product.name}</p>
                    ${variantInfo}
                </div>
                <div class="text-sm font-medium text-gray-900">
                    ${formatPrice(item.product.price_idr * qty, item.product.price_usd * qty, currentCurrency)}
                </div>
            </div>`;
        });
        
        // Inject HTML
        document.getElementById('summary-items').innerHTML = itemsHtml;
        
        // Update Totals
        const subtotalEl = document.getElementById('summary-subtotal');
        const totalEl = document.getElementById('summary-total');
        
        if(subtotalEl) {
            subtotalEl.dataset.idr = subtotalIdr;
            subtotalEl.dataset.usd = subtotalUsd;
            subtotalEl.textContent = formatPrice(subtotalIdr, subtotalUsd, currentCurrency);
        }
        if(totalEl) {
            totalEl.dataset.idr = subtotalIdr;
            totalEl.dataset.usd = subtotalUsd;
            totalEl.textContent = formatPrice(subtotalIdr, subtotalUsd, currentCurrency);
        }
        
        // Update Mobile Summary Total Label
        const mobileTotalLabel = document.querySelector('.summary-total-display');
        if(mobileTotalLabel) mobileTotalLabel.textContent = formatPrice(subtotalIdr, subtotalUsd, currentCurrency);
    }
</script>
@endguest

<script>
    // ===== LOGIC SWITCH CURRENCY & PAYMENT METHOD =====
    // Logic ini tetap sama, hanya menyesuaikan selector ID baru jika perlu
    
    function updateOrderSummaryPrices(currency) {
        const formatPrice = (priceIdr, priceUsd, currency) => {
            if (currency === 'USD') {
                return '$' + new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(priceUsd);
            } else {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(priceIdr);
            }
        };

        // Update item totals
        document.querySelectorAll('.item-total').forEach(el => {
            const priceIdr = parseFloat(el.dataset.priceIdr);
            const priceUsd = parseFloat(el.dataset.priceUsd);
            const qty = parseInt(el.dataset.qty || 1); // Fallback qty 1 if not set
            el.textContent = formatPrice(priceIdr * qty, priceUsd * qty, currency);
        });

        // Update Summary Totals
        const subtotalEl = document.getElementById('summary-subtotal');
        const totalEl = document.getElementById('summary-total');
        
        if (subtotalEl) {
            const idr = parseFloat(subtotalEl.dataset.idr);
            const usd = parseFloat(subtotalEl.dataset.usd);
            subtotalEl.textContent = formatPrice(idr, usd, currency);
        }
        if (totalEl) {
            const idr = parseFloat(totalEl.dataset.idr);
            const usd = parseFloat(totalEl.dataset.usd);
            const formattedTotal = formatPrice(idr, usd, currency);
            totalEl.textContent = formattedTotal;
            
            // Update mobile header total
            const mobileLabel = document.querySelector('.summary-total-display');
            if(mobileLabel) mobileLabel.textContent = formattedTotal;
        }
    }

    document.querySelectorAll('input[name="payment_gateway"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const selectedCurrency = this.value === 'paypal' ? 'USD' : 'IDR';
            updateOrderSummaryPrices(selectedCurrency);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Default currency handling
        const checkedPayment = document.querySelector('input[name="payment_gateway"]:checked');
        if(checkedPayment) {
            const currency = checkedPayment.value === 'paypal' ? 'USD' : 'IDR';
            updateOrderSummaryPrices(currency);
        } else {
            // Default IDR if nothing selected
            updateOrderSummaryPrices('IDR');
        }

        // Phone Input & Countries Logic (Copy-Paste logika IntTelInput dan Fetch Countries Anda sebelumnya kesini)
        // ... (Include your phone/country logic here exactly as before) ...
        // Agar code tidak terlalu panjang di chat, saya asumsikan script phone/country logic
        // Anda letakkan di bawah sini atau di file JS terpisah. 
        // Struktur HTML ID-nya (shipping_phone, shipping_country) sudah saya samakan.
        
        initPhoneAndAddressLogic(); // Panggil fungsi wrapper (lihat bawah)
    });
    
    function initPhoneAndAddressLogic() {
        // COPY PASTE LOGIKA PHONE INPUT & COUNTRY API ANDA DISINI
        // Pastikan selector ID-nya sesuai dengan HTML di atas:
        // #shipping_phone, #shipping_country, #shipping_province, #shipping_city
        
        const phoneInputField = document.querySelector("#shipping_phone");
        const hiddenPhoneInput = document.querySelector("#hidden_shipping_phone");
        
        if (phoneInputField) {
            const phoneInput = window.intlTelInput(phoneInputField, {
                initialCountry: "auto",
                geoIpLookup: callback => {
                    fetch('https://ipapi.co/json/').then(res => res.json()).then(data => callback(data.country_code)).catch(() => callback("id"));
                },
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                preferredCountries: [],
            });
            
            // Update hidden input on change
            const updateHidden = () => {
                if(phoneInput.isValidNumber()) hiddenPhoneInput.value = phoneInput.getNumber();
            };
            phoneInputField.addEventListener('input', updateHidden);
            phoneInputField.addEventListener('countrychange', updateHidden);
        }

        // Country API
        const countrySelect = document.getElementById('shipping_country');
        const provinceSelect = document.getElementById('shipping_province');
        const addressDetails = document.getElementById('address-details');

        if(countrySelect) {
            fetch('https://countriesnow.space/api/v0.1/countries/positions')
                .then(res => res.json())
                .then(data => {
                    if (!data.error) {
                        data.data.sort((a,b) => a.name.localeCompare(b.name)).forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = c.name;
                            opt.textContent = c.name;
                            countrySelect.appendChild(opt);
                        });
                    }
                });

            countrySelect.addEventListener('change', function() {
                if(this.value) {
                    addressDetails.classList.remove('hidden');
                    // Load provinces logic...
                    fetch('https://countriesnow.space/api/v0.1/countries/states', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({ country: this.value })
                    }).then(res => res.json()).then(data => {
                        provinceSelect.innerHTML = '<option value="">Select Province</option>';
                        if(!data.error && data.data.states.length > 0) {
                            data.data.states.forEach(s => {
                                const opt = document.createElement('option');
                                opt.value = s.name;
                                opt.textContent = s.name;
                                provinceSelect.appendChild(opt);
                            });
                        } else {
                            provinceSelect.innerHTML = '<option value="-">N/A</option>';
                        }
                    });
                } else {
                    addressDetails.classList.add('hidden');
                }
            });
        }
    }
</script>
@endsection