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
                                <input type="tel" 
                                       name="shipping_phone" 
                                       id="shipping_phone" 
                                       value="{{ old('shipping_phone') }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_phone') border-red-500 @enderror"
                                       placeholder="+62 812 3456 7890">
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

                            <!-- City & Province -->
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
                                    <label for="shipping_province" class="block text-sm font-medium text-gray-700 mb-1">
                                        Province / State
                                    </label>
                                    <input type="text" 
                                           name="shipping_province" 
                                           id="shipping_province" 
                                           value="{{ old('shipping_province') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_province') border-red-500 @enderror"
                                           placeholder="DKI Jakarta">
                                    @error('shipping_province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Postal Code & Country -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                                <div>
                                    <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-1">
                                        Country <span class="text-red-500">*</span>
                                    </label>
                                    <select name="shipping_country" 
                                            id="shipping_country" 
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_country') border-red-500 @enderror">
                                        <option value="">Select Country</option>
                                        <option value="Indonesia" {{ old('shipping_country') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="Singapore" {{ old('shipping_country') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                        <option value="Malaysia" {{ old('shipping_country') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                        <option value="United States" {{ old('shipping_country') == 'United States' ? 'selected' : '' }}>United States</option>
                                        <option value="United Kingdom" {{ old('shipping_country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                    </select>
                                    @error('shipping_country')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Section (Tambahkan setelah Shipping Information, sebelum Additional Notes) -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Payment Method <span class="text-red-500 ml-1">*</span>
                        </h2>
                        
                        <p class="text-sm text-gray-600 mb-4">
                            Select your preferred payment method. Admin will send you a payment link via email.
                        </p>
                        
                        <div class="space-y-3">
                            <!-- Xendit Option -->
                            <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-all duration-200 payment-option">
                                <input type="radio" 
                                    name="payment_gateway" 
                                    value="xendit" 
                                    required
                                    class="w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500"
                                    {{ old('payment_gateway') == 'xendit' ? 'checked' : '' }}>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-base font-semibold text-gray-900">Xendit</p>
                                            <p class="text-sm text-gray-600">Bank Transfer, E-Wallet, Credit Card</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <img src="https://dashboard.xendit.co/favicon.ico" alt="Xendit" class="h-8 w-8 object-contain">
                                        </div>
                                    </div>
                                </div>
                                <svg class="absolute top-4 right-4 w-5 h-5 text-blue-600 hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>

                            <!-- PayPal Option -->
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
                                            <p class="text-sm text-gray-600">International Payment Gateway</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none">
                                                <path d="M20.067 8.478c.492.88.556 2.014.3 3.327-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 00-.794.68l-.04.22-.63 3.993-.028.15a.805.805 0 01-.794.679H8.088a.483.483 0 01-.476-.568l1.145-7.25.007-.045c.07-.448.458-.783.914-.783h1.873c3.896 0 6.944-1.582 7.833-6.155.023-.12.044-.237.064-.352a5.76 5.76 0 01.619.984z" fill="#139AD6"/>
                                                <path d="M18.768 3.345c-.213-.093-.433-.173-.66-.24A10.758 10.758 0 0015.938 3h-5.12a.916.916 0 00-.905.773L8.607 11.58l-.026.178a.916.916 0 01.905-.773h1.873c3.896 0 6.944-1.582 7.833-6.155.023-.12.044-.237.064-.352.293-.03.578-.068.854-.117a6.028 6.028 0 00-1.342-1.016z" fill="#263B80"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <svg class="absolute top-4 right-4 w-5 h-5 text-blue-600 hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
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
                                        After submitting your order, our admin will create a payment link and send it to your email within 24 hours.
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
                        <div id="summary-items" class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                            @auth
                            @foreach($cartItems as $item)
                            <div class="flex items-start space-x-4 pt-4 pb-4 border-b border-gray-200">
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
                                    <p class="text-sm text-gray-500">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
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
                                    Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium text-gray-900">
                                    <span class="text-blue-600">Calculated at next step</span>
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium text-gray-900">Rp 0</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <span class="text-xl font-bold text-gray-900" id="summary-total">
                                        Rp {{ number_format($total ?? 0, 0, ',', '.') }}
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
            <div class="flex items-start space-x-4 pt-4 pb-4 border-b border-gray-200">
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
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    let errors = [];

    requiredFields.forEach(field => {
        if (field.type === 'radio') {
            const radioGroup = document.querySelectorAll(`input[name="${field.name}"]`);
            const isChecked = Array.from(radioGroup).some(radio => radio.checked);
            if (!isChecked && !errors.includes(field.name)) {
                isValid = false;
                errors.push(field.name);
            }
        } else {
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
        if (errors.includes('payment_gateway')) {
            alert('Please select a payment method');
        } else {
            alert('Please fill in all required fields');
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});

// Payment method selection visual feedback
document.querySelectorAll('input[name="payment_gateway"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Remove selected state from all options
        document.querySelectorAll('.payment-option').forEach(option => {
            option.classList.remove('border-blue-500', 'bg-blue-50');
            option.querySelector('.check-icon').classList.add('hidden');
        });
        
        // Add selected state to chosen option
        if (this.checked) {
            const label = this.closest('.payment-option');
            label.classList.add('border-blue-500', 'bg-blue-50');
            label.querySelector('.check-icon').classList.remove('hidden');
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