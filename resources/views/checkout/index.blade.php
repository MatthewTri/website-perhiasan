@extends('layout.checkout')

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- Main Container (Split Layout) --}}
    <div class="flex flex-col lg:flex-row">
        
        {{-- LEFT COLUMN: Forms (Contact, Shipping, Payment) --}}
        <div class="w-full lg:w-[58%] px-4 lg:px-12 py-8 lg:py-16 order-2 lg:order-1">
            
            {{-- Header (Mobile Only) --}}
            <div class="lg:hidden mb-8 text-center">
                <h1 class="text-xl sm:text-2xl font-serif text-gray-900 tracking-[0.2em] sm:tracking-widest uppercase">Pre-Order Checkout</h1>
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
                                    Already have an account? <a href="{{ route('login') }}" class="text-[#E3CFCF] hover:text-[#ddc8c8] underline">Log in</a>
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
                                    <select name="shipping_province" id="shipping_province" disabled class="block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors transition-opacity appearance-none disabled:opacity-60 disabled:cursor-default">
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
                                    {{-- Input Visible untuk intlTelInput --}}
                                    <input type="tel" 
                                        id="shipping_phone" 
                                        class="block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent @error('shipping_phone') border-red-500 @enderror">
                                    
                                    {{-- Hidden Input yang akan di-submit (dengan attribute required) --}}
                                    <input type="hidden" 
                                        name="shipping_phone" 
                                        id="hidden_shipping_phone" 
                                        value="{{ old('shipping_phone') }}" 
                                        required>
                                </div>
                                <div id="phone-error" class="hidden mt-1 text-xs text-red-500">Please enter a valid phone number.</div>
                                @error('shipping_phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 3. Payment Method --}}
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">Payment</h2>
                        <p class="text-xs text-gray-500 mb-4 font-light">{{ $paypalEnabled ? 'Select your preferred payment method. Details will be sent via email.' : 'Bank transfer payment is available via BCA.' }}</p>
                        
                        <div class="space-y-3">
                            {{-- BCA --}}
                            <label class="relative flex items-center p-4 border border-gray-200 cursor-pointer hover:border-gray-400 transition-all duration-200 payment-option group">
                                <input type="radio" name="payment_gateway" value="manual" required class="peer sr-only" {{ old('payment_gateway') == 'manual' ? 'checked' : '' }}>
                                <div class="w-4 h-4 border border-gray-300 rounded-full mr-4 peer-checked:border-[#454545] peer-checked:bg-[#454545] group-hover:border-gray-400 transition-colors"></div>
                                <div class="flex-1">
                                    <span class="block text-sm font-medium text-gray-900">Bank Transfer (BCA)</span>
                                    <span class="block text-xs text-gray-500">Manual transfer in IDR</span>
                                </div>
                                <img src="{{ asset('asset/bca-logo.png') }}" alt="BCA" class="h-6 opacity-60 grayscale group-hover:grayscale-0 transition-all">
                                {{-- Active State Border --}}
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-[#454545] pointer-events-none transition-colors"></div>
                            </label>

                            @if($paypalEnabled)
                                {{-- PayPal --}}
                                <label class="relative flex items-center p-4 border border-gray-200 cursor-pointer hover:border-gray-400 transition-all duration-200 payment-option group">
                                    <input type="radio" name="payment_gateway" value="paypal" required class="peer sr-only" {{ old('payment_gateway') == 'paypal' ? 'checked' : '' }}>
                                    <div class="w-4 h-4 border border-gray-300 rounded-full mr-4 peer-checked:border-[#454545] peer-checked:bg-[#454545] group-hover:border-gray-400 transition-colors"></div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-medium text-gray-900">PayPal</span>
                                        <span class="block text-xs text-gray-500">International payment in USD</span>
                                    </div>
                                    <img src="{{ asset('asset/paypal.png') }}" alt="PayPal" class="h-6 opacity-60 grayscale group-hover:grayscale-0 transition-all">
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-[#454545] pointer-events-none transition-colors"></div>
                                </label>
                            @endif
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
                        <button type="button" onclick="openPreOrderModal()"
                                class="w-full cursor-pointer bg-[#E3CFCF] hover:bg-[#ddc8c8] text-white font-bold text-xs uppercase tracking-[0.2em] py-4 px-6 transition-all duration-300 shadow-sm hover:shadow-md flex items-center justify-center">
                            Pre Order Now
                        </button>
                        
                        <div class="mt-4 flex items-center justify-center gap-6 text-[10px] text-gray-400 tracking-wide">
                            <a href="{{ route('policies.refund') }}" class="underline underline-offset-2 hover:text-gray-700 transition-colors">Refund policy</a>
                            <a href="{{ route('policies.shipping') }}" class="underline underline-offset-2 hover:text-gray-700 transition-colors">Shipping policy</a>
                            <a href="{{ route('policies.privacy') }}" class="underline underline-offset-2 hover:text-gray-700 transition-colors">Privacy policy</a>
                            <a href="{{ route('policies.terms') }}" class="underline underline-offset-2 hover:text-gray-700 transition-colors">Terms of service</a>
                            <button type="button" onclick="openContactModal()" class="underline underline-offset-2 hover:text-gray-700 transition-colors">Contact</button>
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
                                    <span class="absolute -top-2 -right-2 bg-[#E3CFCF] text-black text-[10px] font-medium rounded-full w-5 h-5 flex items-center justify-center shadow-sm">
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
                                <span class="text-xl font-medium text-gray-900" id="summary-total"
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
    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-[#E3CFCF]"></div>
    <p class="mt-4 text-xs uppercase tracking-widest text-gray-500">Processing...</p>
</div>

<div id="checkout-empty" class="hidden min-h-screen flex flex-col items-center justify-center">
    <p class="text-gray-500 text-sm uppercase tracking-widest mb-4">Your cart is empty</p>
    <a href="{{ route('products.index') }}" class="text-[#E3CFCF] border-b border-[#E3CFCF] text-xs uppercase tracking-widest pb-1 hover:text-black hover:border-black transition-colors">Return to Shop</a>
</div>


{{-- PRE-ORDER CONFIRMATION MODAL --}}
<div id="preorder-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
    
    {{-- Fix: Tambahkan id="modal-backdrop" dan class opacity-0 untuk animasi --}}
    <div id="modal-backdrop" class="fixed inset-0 bg-gray-900/60 transition-opacity duration-300 opacity-0" onclick="closePreOrderModal()"></div>

    {{-- Modal Content --}}
    {{-- Fix: Class opacity-0 dan scale-95 sebagai initial state --}}
    <div id="preorder-modal-content" class="relative bg-white w-full max-w-lg shadow-2xl transform transition-all duration-300 scale-95 opacity-0 flex flex-col max-h-[90vh]">
        
        {{-- Header --}}
        <div class="px-8 pt-8 pb-6 text-center border-b border-gray-100 bg-white rounded-t-lg">
            <h3 class="text-2xl font-serif text-gray-900 mb-2">Pre-Order Process</h3>
            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-medium">Please review before confirming</p>
        </div>

        {{-- Scrollable Body --}}
        <div class="p-6 sm:p-8 overflow-y-auto custom-scrollbar bg-white">
            <div class="text-center mb-8">
                <p class="text-sm text-gray-600 font-light leading-relaxed max-w-sm mx-auto">
                    To ensure the highest quality, each piece is crafted with care. Here is how our pre-order system works:
                </p>
            </div>

            {{-- Timeline Steps --}}
            <div class="relative pl-2 sm:pl-4 space-y-8">

                {{-- Step 1 --}}
                <div class="relative flex items-start group">
                    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-gray-200 bg-white group-hover:border-[#E3CFCF] transition-colors flex items-center justify-center z-10">
                        <span class="text-xs sm:text-sm font-serif text-gray-500 group-hover:text-[#E3CFCF]">1</span>
                    </div>
                    <div class="ml-4 sm:ml-6 mt-1 sm:mt-2">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-1">Order Review</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">Our team will review your request within 24 hours to confirm your order.</p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative flex items-start group">
                    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-gray-200 bg-white group-hover:border-[#E3CFCF] transition-colors flex items-center justify-center z-10">
                        <span class="text-xs sm:text-sm font-serif text-gray-500 group-hover:text-[#E3CFCF]">2</span>
                    </div>
                    <div class="ml-4 sm:ml-6 mt-1 sm:mt-2">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-1">Confirmation</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">We will contact you via <strong>Email</strong> or <strong>WhatsApp</strong> to confirm details.</p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="relative flex items-start group">
                    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-gray-200 bg-white group-hover:border-[#E3CFCF] transition-colors flex items-center justify-center z-10">
                        <span class="text-xs sm:text-sm font-serif text-gray-500 group-hover:text-[#E3CFCF]">3</span>
                    </div>
                    <div class="ml-4 sm:ml-6 mt-1 sm:mt-2">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-1">Your Response Required</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">Please respond to our message to proceed. Without confirmation, the order cannot continue.</p>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="relative flex items-start group">
                    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-gray-200 bg-white group-hover:border-[#E3CFCF] transition-colors flex items-center justify-center z-10">
                        <span class="text-xs sm:text-sm font-serif text-gray-500 group-hover:text-[#E3CFCF]">4</span>
                    </div>
                    <div class="ml-4 sm:ml-6 mt-1 sm:mt-2">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-1">Payment</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">Once confirmed, you will receive a secure payment link via email.</p>
                    </div>
                </div>

                {{-- Step 5 --}}
                <div class="relative flex items-start group">
                    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-gray-200 bg-white group-hover:border-[#E3CFCF] transition-colors flex items-center justify-center z-10">
                        <span class="text-xs sm:text-sm font-serif text-gray-500 group-hover:text-[#E3CFCF]">5</span>
                    </div>
                    <div class="ml-4 sm:ml-6 mt-1 sm:mt-2">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-1">Production Time</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">Pre-orders take <strong class="text-gray-700">2-8 weeks</strong> to craft. We will notify you once your piece is ready to ship.</p>
                    </div>
                </div>
            </div>

            {{-- Important Notice Box --}}
            <div class="mt-10 bg-gray-50 p-5 border border-gray-100 text-center rounded">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-2">Important</p>
                <p class="text-xs text-gray-800 leading-relaxed font-medium">
                    Please ensure your contact details are correct. updates will be sent to the email provided in checkout.
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-6 border-t border-gray-100 bg-white rounded-b-lg">
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" onclick="closePreOrderModal()" 
                        class="cursor-pointer w-full sm:w-1/2 py-4 px-6 border border-gray-200 text-gray-600 text-[10px] sm:text-xs uppercase tracking-[0.2em] hover:bg-gray-50 hover:text-gray-900 transition-all font-medium">
                    Cancel
                </button>
                <button type="button" onclick="submitPreOrder()" 
                        class="cursor-pointer w-full sm:w-1/2 py-4 px-6 bg-gray-900 text-white text-[10px] sm:text-xs uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-lg shadow-gray-200 font-medium">
                    Confirm Order
                </button>
            </div>
        </div>
    </div>
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

    // Guest Cart Logic
    document.addEventListener('DOMContentLoaded', async function() {
        const guestCart = localStorage.getItem('guest_cart');
        
        if (!guestCart || Object.keys(JSON.parse(guestCart)).length === 0) {
            document.getElementById('checkout-loading').classList.add('hidden');
            document.getElementById('checkout-form').classList.add('hidden');
            document.getElementById('checkout-empty').classList.remove('hidden');
            return;
        }

        const cart = JSON.parse(guestCart);
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
        let currentCurrency = '{{ $globalCurrency }}';
        
        const formatPrice = (priceIdr, priceUsd, currency) => {
            if (currency === 'USD') {
                return 'USD ' + new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(priceUsd);
            } else {
                return 'IDR ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(priceIdr);
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
                : '/img/no-image.jpg';
            
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
                    <span class="absolute -top-2 -right-2 bg-[#E3CFCF] text-black text-[10px] font-medium rounded-full w-5 h-5 flex items-center justify-center shadow-sm">
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
        
        document.getElementById('summary-items').innerHTML = itemsHtml;
        
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
        
        const mobileTotalLabel = document.querySelector('.summary-total-display');
        if(mobileTotalLabel) mobileTotalLabel.textContent = formatPrice(subtotalIdr, subtotalUsd, currentCurrency);
    }
</script>
@endguest

{{-- ✅ SCRIPT GLOBAL (UNTUK SEMUA USER - AUTH & GUEST) --}}
<script>
    // ===== CURRENCY SWITCHER =====
    function updateOrderSummaryPrices(currency) {
        const formatPrice = (priceIdr, priceUsd, currency) => {
            if (currency === 'USD') {
                return 'USD ' + new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(priceUsd);
            } else {
                return 'IDR ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(priceIdr);
            }
        };

        document.querySelectorAll('.item-total').forEach(el => {
            const priceIdr = parseFloat(el.dataset.priceIdr);
            const priceUsd = parseFloat(el.dataset.priceUsd);
            const qty = parseInt(el.dataset.qty || 1);
            el.textContent = formatPrice(priceIdr * qty, priceUsd * qty, currency);
        });

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
            
            const mobileLabel = document.querySelector('.summary-total-display');
            if(mobileLabel) mobileLabel.textContent = formattedTotal;
        }
    }

    // ===== PHONE & ADDRESS LOGIC =====
    function initPhoneAndAddressLogic() {
        const phoneInputField = document.querySelector("#shipping_phone");
        const hiddenPhoneInput = document.querySelector("#hidden_shipping_phone");
        const phoneError = document.getElementById('phone-error');
        
        if (phoneInputField && hiddenPhoneInput) {
            // ✅ Flag untuk track apakah user sudah interact
            let hasInteracted = false;
            
            const phoneInput = window.intlTelInput(phoneInputField, {
                initialCountry: "auto",
                geoIpLookup: callback => {
                    fetch('https://ipapi.co/json/')
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("id"));
                },
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                separateDialCode: true,
            });
            
            const updateHiddenPhone = () => {
                const fullNumber = phoneInput.getNumber();
                const isValid = phoneInput.isValidNumber();
                
                if (isValid) {
                    hiddenPhoneInput.value = fullNumber;
                    hiddenPhoneInput.setCustomValidity('');
                    phoneError.classList.add('hidden');
                    phoneInputField.classList.remove('border-red-500');
                    phoneInputField.classList.add('border-gray-300');
                } else {
                    // ✅ Hanya set error jika user sudah interact
                    if (hasInteracted) {
                        hiddenPhoneInput.value = '';
                        hiddenPhoneInput.setCustomValidity('Invalid phone number');
                        phoneError.classList.remove('hidden');
                        phoneInputField.classList.add('border-red-500');
                        phoneInputField.classList.remove('border-gray-300');
                    }
                }
            };
            
            // ✅ Set flag saat user mulai mengetik
            phoneInputField.addEventListener('input', () => {
                hasInteracted = true;
                updateHiddenPhone();
            });
            
            // ✅ Validasi saat blur (user keluar dari field)
            phoneInputField.addEventListener('blur', () => {
                if (phoneInputField.value) { // Hanya validasi jika ada input
                    hasInteracted = true;
                    updateHiddenPhone();
                }
            });
            
            phoneInputField.addEventListener('countrychange', () => {
                phoneInputField.value = '';
                hiddenPhoneInput.value = '';
                hiddenPhoneInput.setCustomValidity('');
                phoneError.classList.add('hidden');
                phoneInputField.classList.remove('border-red-500');
                phoneInputField.classList.add('border-gray-300');

                hasInteracted = false;
            });
            
            // ✅ Untuk old value (saat form error & kembali)
            if (phoneInputField.value) {
                hasInteracted = true;
                updateHiddenPhone();
            }
        }

        // ===== COUNTRY & ADDRESS LOGIC (TETAP SAMA) =====
        const countrySelect = document.getElementById('shipping_country');
        const provinceSelect = document.getElementById('shipping_province');
        const addressDetails = document.getElementById('address-details');

        // Cache provinces per country (session-scoped) to avoid refetching
        const PROVINCE_CACHE_KEY = 'checkout_provinces_cache_v1';
        let provinceCache = {};
        try {
            provinceCache = JSON.parse(sessionStorage.getItem(PROVINCE_CACHE_KEY) || '{}') || {};
        } catch (_) {
            provinceCache = {};
        }

        let inFlightProvinceController = null;

        const setProvinceLoading = (label) => {
            provinceSelect.disabled = true;
            provinceSelect.setAttribute('aria-busy', 'true');
            provinceSelect.innerHTML = `<option value="">${label}</option>`;
        };

        const setProvinceOptions = (states, selectedValue) => {
            provinceSelect.innerHTML = '<option value="">Select Province</option>';
            states.forEach(name => {
                const opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                if (selectedValue && name === selectedValue) opt.selected = true;
                provinceSelect.appendChild(opt);
            });
            provinceSelect.disabled = false;
            provinceSelect.removeAttribute('aria-busy');
        };

        if(countrySelect) {
            fetch('https://countriesnow.space/api/v0.1/countries/positions')
                .then(res => res.json())
                .then(data => {
                    if (!data.error) {
                        data.data.sort((a,b) => a.name.localeCompare(b.name)).forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = c.name;
                            opt.textContent = c.name;
                            if(c.name === '{{ old("shipping_country") }}') opt.selected = true;
                            countrySelect.appendChild(opt);
                        });
                        
                        if(countrySelect.value) {
                            countrySelect.dispatchEvent(new Event('change'));
                        }
                    }
                });

            countrySelect.addEventListener('change', function() {
                if(this.value) {
                    addressDetails.classList.remove('hidden');

                    const selectedCountry = this.value;
                    const oldProvince = '{{ old("shipping_province") }}';

                    // Abort previous request if user changes country quickly
                    if (inFlightProvinceController) {
                        inFlightProvinceController.abort();
                        inFlightProvinceController = null;
                    }

                    // Serve from cache if available
                    if (provinceCache[selectedCountry] && Array.isArray(provinceCache[selectedCountry]) && provinceCache[selectedCountry].length > 0) {
                        setProvinceOptions(provinceCache[selectedCountry], oldProvince);
                        return;
                    }

                    setProvinceLoading('Loading provinces...');

                    inFlightProvinceController = new AbortController();
                    
                    fetch('https://countriesnow.space/api/v0.1/countries/states', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({ country: selectedCountry }),
                        signal: inFlightProvinceController.signal,
                    }).then(res => res.json()).then(data => {
                        if(!data.error && data.data.states && data.data.states.length > 0) {
                            const states = data.data.states
                                .map(s => s && s.name)
                                .filter(Boolean);

                            // Update cache
                            provinceCache[selectedCountry] = states;
                            try {
                                sessionStorage.setItem(PROVINCE_CACHE_KEY, JSON.stringify(provinceCache));
                            } catch (_) {
                                // ignore storage quota / private mode
                            }

                            setProvinceOptions(states, oldProvince);
                        } else {
                            provinceSelect.innerHTML = '<option value="">N/A</option>';
                            provinceSelect.removeAttribute('required');
                            provinceSelect.disabled = true;
                            provinceSelect.removeAttribute('aria-busy');
                        }
                    }).catch((err) => {
                        if (err && err.name === 'AbortError') return;
                        provinceSelect.innerHTML = '<option value="">Failed to load</option>';
                        provinceSelect.disabled = true;
                        provinceSelect.removeAttribute('aria-busy');
                    }).finally(() => {
                        inFlightProvinceController = null;
                    });
                } else {
                    addressDetails.classList.add('hidden');

                    if (inFlightProvinceController) {
                        inFlightProvinceController.abort();
                        inFlightProvinceController = null;
                    }

                    provinceSelect.innerHTML = '<option value="">Province</option>';
                    provinceSelect.disabled = true;
                    provinceSelect.removeAttribute('aria-busy');
                }
            });
        }
    }

    // ===== PRE-ORDER MODAL FUNCTIONS (GLOBAL) =====
    function openPreOrderModal() {
        const form = document.getElementById('checkout-form');
        const hiddenPhone = document.getElementById('hidden_shipping_phone');
        const phoneError = document.getElementById('phone-error');
        
        // Step 1: HTML5 Validation
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Step 2: Phone Validation
        if (!hiddenPhone.value || hiddenPhone.value.trim() === '') {
            phoneError.classList.remove('hidden');
            document.getElementById('shipping_phone').focus();
            document.getElementById('shipping_phone').classList.add('border-red-500');
            return;
        }
        
        // Step 3: Payment Method Check
        const paymentMethod = document.querySelector('input[name="payment_gateway"]:checked');
        if (!paymentMethod) {
            alert('Please select a payment method.'); 
            return;
        }
        
        // Step 4: Show Modal
        const modal = document.getElementById('preorder-modal');
        const backdrop = document.getElementById('modal-backdrop');
        const modalContent = document.getElementById('preorder-modal-content');
        
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            modalContent.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closePreOrderModal() {
        const modal = document.getElementById('preorder-modal');
        const backdrop = document.getElementById('modal-backdrop');
        const modalContent = document.getElementById('preorder-modal-content');
        
        backdrop.classList.add('opacity-0');
        modalContent.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function submitPreOrder() {
        document.getElementById('checkout-loading').classList.remove('hidden');
        document.getElementById('checkout-form').submit();
        closePreOrderModal();
    }

    // ===== DOM READY =====
    document.addEventListener('DOMContentLoaded', function() {
        // Payment Method Listener
        document.querySelectorAll('input[name="payment_gateway"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const selectedCurrency = this.value === 'paypal' ? 'USD' : 'IDR';
                updateOrderSummaryPrices(selectedCurrency);
            });
        });

        // Default Currency
        const checkedPayment = document.querySelector('input[name="payment_gateway"]:checked');
        if(checkedPayment) {
            const currency = checkedPayment.value === 'paypal' ? 'USD' : 'IDR';
            updateOrderSummaryPrices(currency);
        } else {
            updateOrderSummaryPrices('IDR');
        }

        // Init Phone & Address
        initPhoneAndAddressLogic();
    });
</script>
@endsection