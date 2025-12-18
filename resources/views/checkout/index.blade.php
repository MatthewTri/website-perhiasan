@extends('layout.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="text-gray-600 mt-2">Complete your order information</p>
        </div>

        <!-- Loading State (Guest Only) -->
        @guest
        <div id="checkout-loading" class="text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading your cart...</p>
        </div>

        <div id="checkout-empty" class="hidden text-center py-12 bg-white rounded-lg shadow-md">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15M17 13v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6m8-4V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m4 0V5"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
            <p class="mt-2 text-sm text-gray-500">Please add items to your cart before checkout.</p>
            <div class="mt-6">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Continue Shopping
                </a>
            </div>
        </div>
        @endguest

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="{{ isset($isGuest) && $isGuest ? 'hidden' : '' }}">
            @csrf
            
            <!-- Hidden input untuk guest cart data -->
            @guest
            <input type="hidden" name="cart_data" id="cart_data" value="">
            @endguest
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Side - Form (2/3 width) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Contact Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Contact Information
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="contact_email" 
                                       id="contact_email" 
                                       value="{{ Auth::check() ? Auth::user()->email : old('contact_email') }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_email') border-red-500 @enderror"
                                       placeholder="your.email@example.com">
                                @error('contact_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">We'll send order confirmation to this email</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Shipping Address
                        </h2>
                        
                        <div class="space-y-4">
                            <!-- Full Name -->
                            <div>
                                <label for="shipping_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="shipping_name" 
                                       id="shipping_name" 
                                       value="{{ old('shipping_name') }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_name') border-red-500 @enderror"
                                       placeholder="John Doe">
                                @error('shipping_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                
                                <input 
                                    type="tel" 
                                    id="shipping_phone"
                                    value="{{ old('shipping_phone') }}" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_phone') border-red-500 @enderror"
                                    placeholder="Enter phone number">
                                
                                <!-- Hidden input untuk full international number -->
                                <input type="hidden" name="shipping_phone" id="hidden_shipping_phone" value="{{ old('shipping_phone') }}">
                                
                                <div id="phone-error" class="hidden"></div>
                                
                                @error('shipping_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="shipping_address_line1" class="block text-sm font-medium text-gray-700 mb-1">
                                    Street Address <span class="text-red-500">*</span>
                                </label>
                                <textarea name="shipping_address_line1" 
                                          id="shipping_address_line1" 
                                          rows="3"
                                          required
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address_line1') border-red-500 @enderror"
                                          placeholder="Street address, apartment, suite, etc.">{{ old('shipping_address_line1') }}</textarea>
                                @error('shipping_address_line1')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-1">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <select name="shipping_country" 
                                        id="shipping_country" 
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_country') border-red-500 @enderror">
                                    <option value="">Select Country</option>
                                </select>
                                @error('shipping_country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Province, City, Postal Code (Hidden by default) -->
                            <div id="address-details" class="hidden space-y-4">
                                <!-- Province -->
                                <div>
                                    <label for="shipping_province" class="block text-sm font-medium text-gray-700 mb-1">
                                        Province / State
                                    </label>
                                    <select name="shipping_province" 
                                            id="shipping_province" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_province') border-red-500 @enderror">
                                        <option value="">Select Province/State</option>
                                    </select>
                                    @error('shipping_province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City & Postal Code -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">
                                            City <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            name="shipping_city" 
                                            id="shipping_city" 
                                            value="{{ old('shipping_city') }}"
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_city') border-red-500 @enderror"
                                            placeholder="Jakarta">
                                        @error('shipping_city')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                                            Postal Code
                                        </label>
                                        <input type="text" 
                                            name="shipping_postal_code" 
                                            id="shipping_postal_code" 
                                            value="{{ old('shipping_postal_code') }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_postal_code') border-red-500 @enderror"
                                            placeholder="12345">
                                        @error('shipping_postal_code')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Section -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Payment Method <span class="text-red-500 ml-1">*</span>
                        </h2>
                        
                        <p class="text-sm text-gray-600 mb-4">
                            Select your preferred payment method. Admin will send payment details via email.
                        </p>
                        
                        <div class="space-y-3">
                            <!-- BCA Transfer Option (Indonesia) -->
                            <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-all duration-200 payment-option">
                                <input type="radio" 
                                    name="payment_gateway" 
                                    value="manual" 
                                    required
                                    class="w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500"
                                    {{ old('payment_gateway') == 'manual' ? 'checked' : '' }}>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-base font-semibold text-gray-900">BCA Bank Transfer</p>
                                            <p class="text-sm text-gray-600">Manual bank transfer to BCA account</p>
                                            <p class="text-xs text-gray-500 mt-1">Currency: <span class="font-medium">IDR</span></p>
                                        </div>
                                        <div class="flex items-center">
                                            <img src="{{ asset('asset/bca-logo.png') }}" alt="BCA" class="h-10">
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- PayPal Option (International) -->
                            <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-all duration-200 payment-option">
                                <input type="radio" 
                                    name="payment_gateway" 
                                    value="paypal" 
                                    required
                                    class="w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500"
                                    {{ old('payment_gateway') == 'paypal' ? 'checked' : '' }}>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-base font-semibold text-gray-900">PayPal</p>
                                            <p class="text-sm text-gray-600">International payment via PayPal</p>
                                            <p class="text-xs text-gray-500 mt-1">Currency: <span class="font-medium">USD</span></p>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="h-8 w-16" viewBox="0 0 124 33" fill="none">
                                                <path d="M46.211 6.749h-6.839a.95.95 0 0 0-.939.802l-2.766 17.537a.57.57 0 0 0 .564.658h3.265a.95.95 0 0 0 .939-.803l.746-4.73a.95.95 0 0 1 .938-.803h2.165c4.505 0 7.105-2.18 7.784-6.5.306-1.89.013-3.375-.872-4.415-.972-1.142-2.696-1.746-4.985-1.746z" fill="#253B80"/>
                                                <path d="M47 13.154c-.374 2.454-2.249 2.454-4.062 2.454h-1.032l.724-4.583a.57.57 0 0 1 .563-.481h.473c1.235 0 2.4 0 3.002.704.359.42.469 1.044.332 1.906z" fill="#253B80"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        @error('payment_gateway')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>BCA Transfer:</strong> Admin will send BCA account details and payment instructions to your email within 24 hours.<br>
                                        <strong>PayPal:</strong> Admin will send PayPal invoice link to your email.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes (Optional) -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Order Notes (Optional)
                        </h2>
                        
                        <textarea name="order_notes" 
                                  id="order_notes" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Any special requests or notes about your order...">{{ old('order_notes') }}</textarea>
                    </div>

                </div>

                <!-- Right Side - Order Summary (1/3 width) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                        
                        <!-- Cart Items -->
                        <div id="summary-items" class="space-y-4 mb-6 max-h-60 overflow-y-auto">
                            @auth
                            @foreach($cartItems as $item)
                            <div class="flex items-start space-x-4 pt-2 pb-4 border-b border-gray-200">
                                <div class="flex-shrink-0 w-16 h-16 relative">
                                    @if($item->product->featuredImage)
                                        <img src="{{ asset('storage/' . $item->product->featuredImage->image) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">Rp {{ number_format($item->product->price, 2, ',', '.') }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                            @endauth
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900" id="summary-subtotal">
                                    Rp {{ number_format($subtotal, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium text-gray-900">
                                    <span class="text-blue-600">Calculated at next step</span>
                                </span>
                            </div>
                            {{-- Tax --}}
                            {{-- <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium text-gray-900">Rp 0</span>
                            </div> --}}
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <span class="text-xl font-bold text-gray-900" id="summary-total">
                                        Rp {{ number_format($total, 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Place Pre-Order
                        </button>

                        <!-- Security Note -->
                        <div class="mt-4 flex items-start space-x-2 text-xs text-gray-500">
                            <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p>Your information is secure and encrypted</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@guest
<script>
// Load guest cart and populate checkout
document.addEventListener('DOMContentLoaded', async function() {
    const guestCart = localStorage.getItem('guest_cart');
    
    if (!guestCart || Object.keys(JSON.parse(guestCart)).length === 0) {
        document.getElementById('checkout-loading').classList.add('hidden');
        document.getElementById('checkout-empty').classList.remove('hidden');
        return;
    }

    const cart = JSON.parse(guestCart);
    const productIds = Object.keys(cart).map(id => parseInt(id));
    
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
    let subtotal = 0;
    
    const itemsHtml = items.map(item => {
        const itemTotal = item.product.price * item.quantity;
        subtotal += itemTotal;
        
        const imageHtml = item.product.featured_image 
            ? `<img src="/storage/${item.product.featured_image.image}" alt="${item.product.name}" class="w-full h-full object-cover rounded-lg">`
            : `<div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                </svg>
            </div>`;
        
        return `
            <div class="flex items-start space-x-4 pt-2 pb-4 border-b border-gray-200">
                <div class="flex-shrink-0 w-16 h-16 relative">
                    ${imageHtml}
                    <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                        ${item.quantity}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${item.product.name}</p>
                    <p class="text-sm text-gray-500">Rp ${new Intl.NumberFormat('id-ID').format(item.product.price)}</p>
                </div>
                <div class="text-sm font-medium text-gray-900">
                    Rp ${new Intl.NumberFormat('id-ID').format(itemTotal)}
                </div>
            </div>
        `;
    }).join('');
    
    document.getElementById('summary-items').innerHTML = itemsHtml;
    document.getElementById('summary-subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
    document.getElementById('summary-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
}
</script>
@endguest

<script>
// Form validation script
document.addEventListener('DOMContentLoaded', function() {
    // ===== PHONE INPUT SETUP =====
    const phoneInputField = document.querySelector("#shipping_phone");
    const hiddenPhoneInput = document.querySelector("#hidden_shipping_phone");
    
    if (phoneInputField) {
        // Initialize intl-tel-input
        const phoneInput = window.intlTelInput(phoneInputField, {
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                fetch('https://ipapi.co/json/')
                    .then(res => res.json())
                    .then(data => callback(data.country_code))
                    .catch(() => callback("id")); // Default Indonesia
            },
            preferredCountries: ["id", "us", "sg", "my", "th", "ph", "vn", "au", "jp", "kr"],
            separateDialCode: true,
            formatOnDisplay: true,
            nationalMode: false,
            placeholderNumberType: "MOBILE",
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
        });

        // ===== POPULATE COUNTRIES FROM COUNTRIESNOW API =====
        const countrySelect = document.getElementById('shipping_country');
        const provinceSelect = document.getElementById('shipping_province');

        // Fetch countries
        fetch('https://countriesnow.space/api/v0.1/countries/positions')
            .then(res => res.json())
            .then(data => {
                if (data.error === false && data.data) {
                    countrySelect.innerHTML = '<option value="">Select Country</option>';
                    
                    // Sort countries alphabetically
                    const sortedCountries = data.data.sort((a, b) => 
                        a.name.localeCompare(b.name)
                    );
                    
                    sortedCountries.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country.name;
                        option.textContent = country.name;
                        if ('{{ old("shipping_country") }}' === country.name) {
                            option.selected = true;
                        }
                        countrySelect.appendChild(option);
                    });

                    // Load provinces jika ada old value
                    @if(old('shipping_country'))
                        addressDetails.classList.remove('hidden');
                        loadProvinces('{{ old("shipping_country") }}', '{{ old("shipping_province") }}');
                    @endif
                }
            })
            .catch(error => {
                console.error('Error loading countries:', error);
                countrySelect.innerHTML = `
                    <option value="">Select Country</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="United States">United States</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="United Kingdom">United Kingdom</option>
                `;
            });

        // ===== LOAD PROVINCES WHEN COUNTRY CHANGES =====
        const addressDetails = document.getElementById('address-details');

        countrySelect.addEventListener('change', function() {
            const selectedCountry = this.value;
            if (selectedCountry) {
                // Show address details
                addressDetails.classList.remove('hidden');
                
                // Load provinces
                loadProvinces(selectedCountry);
            } else {
                // Hide address details
                addressDetails.classList.add('hidden');
                
                // Reset province
                provinceSelect.innerHTML = '<option value="">Select Province/State</option>';
                provinceSelect.disabled = true;
            }
        });

        // Function to load provinces
        function loadProvinces(countryName, selectedProvince = '') {
            provinceSelect.innerHTML = '<option value="">Loading provinces...</option>';
            provinceSelect.disabled = true;

            fetch('https://countriesnow.space/api/v0.1/countries/states', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ country: countryName })
            })
            .then(res => res.json())
            .then(data => {
                provinceSelect.innerHTML = '<option value="">Select Province/State</option>';
                
                if (data.error === false && data.data && data.data.states && data.data.states.length > 0) {
                    provinceSelect.disabled = false;
                    
                    // Sort provinces alphabetically
                    const sortedStates = data.data.states.sort((a, b) => 
                        a.name.localeCompare(b.name)
                    );
                    
                    sortedStates.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.name;
                        option.textContent = state.name;
                        if (selectedProvince === state.name) {
                            option.selected = true;
                        }
                        provinceSelect.appendChild(option);
                    });
                } else {
                    // Jika tidak ada data province, biarkan input manual
                    provinceSelect.innerHTML = '<option value="">No provinces available</option>';
                    provinceSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error loading provinces:', error);
                provinceSelect.innerHTML = '<option value="">Failed to load provinces</option>';
                provinceSelect.disabled = true;
            });
        }

        // Input restriction - hanya angka
        phoneInputField.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = value;
            updateHiddenPhone();
        });

        phoneInputField.addEventListener('paste', function(e) {
            e.preventDefault();
            let paste = (e.clipboardData || window.clipboardData).getData('text');
            let numericPaste = paste.replace(/[^0-9]/g, '');
            e.target.value = numericPaste;
            setTimeout(() => updateHiddenPhone(), 100);
        });

        phoneInputField.addEventListener('keypress', function(e) {
            // Allow control keys
            if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            // Only allow numbers
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        // Update hidden input saat country berubah
        phoneInputField.addEventListener("countrychange", function() {
            updateHiddenPhone();
        });

        // Function untuk update hidden input
        function updateHiddenPhone() {
            if (phoneInputField.value.trim() && phoneInput.isValidNumber()) {
                const fullNumber = phoneInput.getNumber();
                hiddenPhoneInput.value = fullNumber;
                hidePhoneError();
            } else if (phoneInputField.value.trim()) {
                const selectedCountryData = phoneInput.getSelectedCountryData();
                const dialCode = selectedCountryData.dialCode;
                const nationalNumber = phoneInputField.value;
                const fullNumber = `+${dialCode}${nationalNumber}`;
                hiddenPhoneInput.value = fullNumber;
            }
        }

        // Show phone error function
        function showPhoneError(message) {
            hidePhoneError();
            
            const errorDiv = document.getElementById('phone-error');
            errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-2 text-sm';
            errorDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    ${message}
                </div>
            `;
            
            phoneInputField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            phoneInputField.focus();
        }

        // Hide phone error function
        function hidePhoneError() {
            const errorDiv = document.getElementById('phone-error');
            errorDiv.className = 'hidden';
            errorDiv.innerHTML = '';
        }

        // ===== FORM VALIDATION ON SUBMIT =====
        const checkoutForm = document.getElementById('checkout-form');
        
        checkoutForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate phone number
            if (phoneInputField.value.trim() === '') {
                e.preventDefault();
                showPhoneError('Please enter your phone number.');
                isValid = false;
            } else {
                updateHiddenPhone();
                
                if (!phoneInput.isValidNumber()) {
                    e.preventDefault();
                    showPhoneError('Please enter a valid phone number for the selected country.');
                    isValid = false;
                }
            }
            
            // Validate required fields
            const requiredFields = checkoutForm.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (field.type === 'radio') {
                    const radioGroup = document.querySelectorAll(`input[name="${field.name}"]`);
                    const isChecked = Array.from(radioGroup).some(radio => radio.checked);
                    if (!isChecked) {
                        isValid = false;
                    }
                } else if (field.id !== 'shipping_phone') { // Skip karena sudah di-check di atas
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly');
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return false;
            }
            
            // If valid, show loading state
            if (isValid) {
                const submitBtn = checkoutForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing Order...
                `;
            }
        });
    }
});

// Payment method selection visual feedback
document.querySelectorAll('input[name="payment_gateway"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Remove selected state from all options
        document.querySelectorAll('.payment-option').forEach(option => {
            option.classList.remove('border-blue-500', 'bg-blue-50');
        });
        
        // Add selected state to chosen option
        if (this.checked) {
            const label = this.closest('.payment-option');
            label.classList.add('border-blue-500', 'bg-blue-50');
        }
    });
});

// Trigger change on page load if there's a selected option
document.addEventListener('DOMContentLoaded', function() {
    const selectedRadio = document.querySelector('input[name="payment_gateway"]:checked');
    if (selectedRadio) {
        selectedRadio.dispatchEvent(new Event('change'));
    }
});

</script>
@endsection