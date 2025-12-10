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
                            <div class="flex items-start space-x-4 pb-4 border-b border-gray-200">
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
            <div class="flex items-start space-x-4 pb-4 border-b border-gray-200">
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
// Form validation
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
        } else {
            field.classList.remove('border-red-500');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});
</script>
@endsection