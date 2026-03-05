@extends('layout.checkout')

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- Header Bar --}}
    <div class="border-b border-gray-100 bg-white sticky top-0 z-10">
        <div class="container mx-auto px-4 lg:px-12 py-4 lg:py-6">
            {{-- Mobile Layout --}}
            <div class="flex lg:hidden flex-col space-y-3">
                {{-- Back Button & Status --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.orders.index') }}" 
                    class="inline-flex items-center text-xs uppercase tracking-[0.2em] text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                    
                    @php
                    $statusColors = [
                        'PENDING_REVIEW' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
                        'INVOICE_SENT' => 'bg-blue-50 text-blue-800 border-blue-200',
                        'WAITING_VERIFICATION' => 'bg-purple-50 text-purple-800 border-purple-200',
                        'PAID' => 'bg-green-50 text-green-800 border-green-200',
                        'PRODUCTION' => 'bg-teal-50 text-teal-800 border-teal-200',
                        'SHIPPED' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                        'COMPLETED' => 'bg-purple-50 text-purple-800 border-purple-200',
                        'CANCELLED' => 'bg-red-50 text-red-800 border-red-200',
                        'REFUNDED' => 'bg-orange-50 text-orange-800 border-orange-200',
                    ];
                    @endphp
                    
                    <span class="inline-flex items-center px-3 py-1.5 text-[9px] uppercase tracking-[0.15em] font-medium border {{ $statusColors[$order->status] ?? 'bg-gray-50 text-gray-800 border-gray-200' }}">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>
                
                {{-- Order Number --}}
                <div class="text-center">
                    <h1 class="text-xl text-gray-900 tracking-wide">Order #{{ $order->order_number }}</h1>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            
            {{-- Desktop Layout --}}
            <div class="hidden lg:flex items-center justify-between">
                {{-- Back Button --}}
                <a href="{{ route('admin.orders.index') }}" 
                class="inline-flex items-center text-xs uppercase tracking-[0.2em] text-gray-600 hover:text-gray-900 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
                
                {{-- Order Number (Centered) --}}
                <div class="absolute left-1/2 transform -translate-x-1/2 text-center">
                    <h1 class="text-2xl text-gray-900 tracking-wide whitespace-nowrap">Order #{{ $order->order_number }}</h1>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
                
                {{-- Status Badge --}}
                <span class="inline-flex items-center px-4 py-2 text-[10px] uppercase tracking-[0.15em] font-medium border flex-shrink-0 {{ $statusColors[$order->status] ?? 'bg-gray-50 text-gray-800 border-gray-200' }}">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="container mx-auto px-4 lg:px-12 py-4">
        <div class="bg-green-50 border-l-4 border-green-400 px-4 py-3">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- Main Split Layout --}}
    <div class="flex flex-col lg:flex-row">
        
        {{-- LEFT COLUMN: Order Details --}}
        <div class="w-full lg:w-[58%] px-4 lg:px-12 py-8 lg:py-16 order-2 lg:order-1">
            <div class="max-w-2xl mx-auto lg:mx-0 lg:ml-auto space-y-10">
                
                {{-- Contact Information --}}
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">Contact</h2>
                    <div class="relative">
                        <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-sm text-gray-900">
                            {{ $order->contact_email }}
                        </div>
                        <label class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">Email Address</label>
                    </div>
                </div>

                {{-- Shipping Address --}}
                @if($order->shipment)
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">Shipping Address</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- First & Last Name --}}
                        <div class="relative">
                            <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-sm text-gray-900">
                                {{ $order->shipment->first_name }}
                            </div>
                            <label class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">First Name</label>
                        </div>
                        
                        <div class="relative">
                            <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-sm text-gray-900">
                                {{ $order->shipment->last_name }}
                            </div>
                            <label class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">Last Name</label>
                        </div>

                        {{-- Phone --}}
                        <div class="relative md:col-span-2">
                            <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-sm text-gray-900">
                                {{ $order->shipment->phone }}
                            </div>
                            <label class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">Phone Number</label>
                        </div>

                        {{-- Address --}}
                        <div class="relative md:col-span-2">
                            <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-sm text-gray-900">
                                {{ $order->shipment->address_line1 }}
                            </div>
                            <label class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">Street Address</label>
                        </div>

                        {{-- Country --}}
                        <div class="relative md:col-span-2">
                            <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-sm text-gray-900">
                                {{ $order->shipment->country }}
                            </div>
                            <label class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">Country</label>
                        </div>

                        {{-- Province (if exists) --}}
                        @if($order->shipment->province)
                        <div class="relative md:col-span-2">
                            <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-sm text-gray-900">
                                {{ $order->shipment->province }}
                            </div>
                            <label class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">Province / State</label>
                        </div>
                        @endif

                        {{-- City & Postal Code --}}
                        <div class="relative">
                            <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-sm text-gray-900">
                                {{ $order->shipment->city }}
                            </div>
                            <label class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">City</label>
                        </div>
                        
                        @if($order->shipment->postal_code)
                        <div class="relative">
                            <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-sm text-gray-900">
                                {{ $order->shipment->postal_code }}
                            </div>
                            <label class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500">Postal Code</label>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Payment Information --}}
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">Payment</h2>
                    
                    {{-- Customer's Preference --}}
                    <div class="mb-4">
                        <label class="block text-xs uppercase tracking-wider text-gray-500 mb-2">Customer's Preference</label>
                        <div class="p-4 border-2 border-blue-100 bg-blue-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($order->payment_preference === 'MANUAL')
                                    <p class="text-sm font-medium text-gray-900">BCA Bank Transfer (Indonesia)</p>
                                    <p class="text-xs text-gray-600 mt-1">Manual bank transfer to BCA account</p>
                                    @else
                                    <p class="text-sm font-medium text-gray-900">PayPal (International)</p>
                                    <p class="text-xs text-gray-600 mt-1">PayPal invoice payment</p>
                                    @endif
                                    <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider">Currency: {{ $order->currency }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Gateway (if invoice created) --}}
                    @if($order->payment_gateway)
                    <div class="mb-4">
                        <label class="block text-xs uppercase tracking-wider text-gray-500 mb-2">Payment Method Used</label>
                        <div class="p-4 border border-gray-200 bg-gray-50">
                            @if($order->payment_gateway === 'MANUAL')
                                <p class="text-sm font-medium text-gray-900">BCA Bank Transfer</p>
                                <p class="text-xs text-gray-600 mt-1">Account: {{ config('services.bca.account_number') ?? 'XXX-XXXX-XXXX' }}</p>
                                <p class="text-xs text-gray-600">Name: {{ config('services.bca.account_name') ?? 'Your Company' }}</p>
                                
                                {{-- ADMIN UPLOAD PROOF (INVOICE_SENT Only) --}}
                                @if($order->status === 'INVOICE_SENT')
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-[10px] uppercase tracking-wider text-gray-500 mb-3">Admin: Upload Customer's Proof</p>
                                    <p class="text-xs text-gray-600 mb-3 italic">If customer sent proof via WhatsApp or other channel, you can upload it here.</p>
                                    
                                    <form action="{{ route('admin.orders.uploadProof', $order) }}" method="POST" enctype="multipart/form-data" id="admin-upload-form">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <input type="file" 
                                                name="proof_image" 
                                                id="admin_proof_input"
                                                required 
                                                accept="image/jpeg,image/png,image/jpg"
                                                onchange="previewAdminProof(event)"
                                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-gray-800 cursor-pointer bg-white border border-gray-200 p-2">
                                            @error('proof_image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        
                                        <div id="admin-preview" class="hidden mb-3">
                                            <img id="admin-preview-img" src="" class="max-h-32 border border-gray-200">
                                        </div>

                                        <button type="button" 
                                                onclick="confirmUploadProof()"
                                                class="w-full bg-gray-900 hover:bg-gray-800 text-white text-xs uppercase tracking-[0.2em] py-3 transition-all">
                                            Upload & Mark for Verification
                                        </button>
                                    </form>
                                </div>
                                @endif
                                
                            @else
                                <p class="text-sm font-medium text-gray-900">PayPal</p>
                                <p class="text-xs text-gray-600 mt-1">Transaction ID: {{ $order->capture_id }}</p>
                                
                                {{-- PayPal Status tetap sama seperti sebelumnya --}}
                                @if(isset($paypalStatus) && $paypalStatus !== null && $paypalStatus['success'])
                                <div class="mt-3 p-3 bg-blue-50 border border-blue-100">
                                    <p class="text-[10px] uppercase tracking-wider font-medium text-blue-900 mb-2">PayPal Order Status:</p>
                                    <div class="space-y-1 text-xs text-blue-800">
                                        <p><span class="font-medium">Status:</span> 
                                            <span class="px-2 py-0.5 text-[10px] uppercase tracking-wider {{ 
                                                $paypalStatus['status'] === 'COMPLETED' ? 'bg-green-100 text-green-800' : 
                                                ($paypalStatus['status'] === 'APPROVED' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') 
                                            }}">
                                                {{ $paypalStatus['status'] }}
                                            </span>
                                        </p>
                                        <p><span class="font-medium">Payer Email:</span> {{ $paypalStatus['payer_email'] }}</p>
                                        <p><span class="font-medium">Amount:</span> {{ $paypalStatus['currency'] }} {{ number_format($paypalStatus['amount'], 2, ',', '.') }}</p>
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Admin Notes Display --}}
                    @if($order->admin_notes && $order->status !== 'PENDING_REVIEW')
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-500 mb-2">Admin Notes for Customer</label>
                        <div class="p-4 bg-purple-50 border border-purple-100">
                            <p class="text-sm text-gray-900">{{ $order->admin_notes }}</p>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Payment Gateway Override (PENDING_REVIEW) --}}
                    @if($order->status === 'PENDING_REVIEW')
                    <div class="bg-gray-50 border border-gray-200 p-6 mt-4">
                        <div class="mb-5">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Customer Preference</p>
                                <span id="override-indicator" class="hidden text-[9px] uppercase tracking-wider px-2 py-1 bg-orange-100 text-orange-700 border border-orange-200">
                                    ⚠ Override Active
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900">{{ $order->payment_preference === 'MANUAL' ? 'BCA Bank Transfer' : 'PayPal' }}</span>
                                <span class="text-xs text-gray-500">•</span>
                                <span class="text-xs text-gray-600">You can override if requested</span>
                            </div>
                        </div>

                        <form action="{{ route('admin.orders.sendPaymentDetails', $order) }}" method="POST" id="invoice-form">
                            @csrf
                            
                            <input type="hidden" name="customer_preference" value="{{ $order->payment_preference }}">
                            
                            <div class="space-y-3 mb-6">
                                <label class="relative flex items-center p-4 border border-gray-200 cursor-pointer hover:border-gray-400 transition-all group">
                                    <input type="radio" name="payment_gateway_override" value="MANUAL" 
                                        {{ $order->payment_preference === 'MANUAL' ? 'checked' : '' }}
                                        class="peer sr-only payment-radio">
                                    <div class="w-4 h-4 border border-gray-300 rounded-full mr-4 peer-checked:border-[#E8B49E] peer-checked:bg-[#E8B49E] group-hover:border-gray-400"></div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-medium text-gray-900">BCA Bank Transfer (Indonesia)</span>
                                        <span class="block text-xs text-gray-500 mt-0.5">Manual transfer in IDR</span>
                                    </div>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-[#E8B49E] pointer-events-none"></div>
                                </label>
                                
                                <label class="relative flex items-center p-4 border border-gray-200 cursor-pointer hover:border-gray-400 transition-all group">
                                    <input type="radio" name="payment_gateway_override" value="PAYPAL"
                                        {{ $order->payment_preference === 'PAYPAL' ? 'checked' : '' }}
                                        class="peer sr-only payment-radio">
                                    <div class="w-4 h-4 border border-gray-300 rounded-full mr-4 peer-checked:border-[#E8B49E] peer-checked:bg-[#E8B49E] group-hover:border-gray-400"></div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-medium text-gray-900">PayPal (International)</span>
                                        <span class="block text-xs text-gray-500 mt-0.5">International payment in USD</span>
                                    </div>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-[#E8B49E] pointer-events-none"></div>
                                </label>
                            </div>

                            <div class="relative">
                                <textarea name="admin_notes" rows="3"
                                        class="peer block w-full border border-gray-200 px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 bg-white resize-none" 
                                        placeholder="Notes">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                                <label class="absolute left-4 -top-2.5 bg-gray-50 px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs">
                                    Notes for Customer (Optional)
                                </label>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>

                {{-- Proof of Payment --}}
                @if($order->proof_of_payment)
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">Payment Proof Verification</h2>
                    
                    @if($order->status === 'WAITING_VERIFICATION')
                    <div class="bg-purple-50 border-l-4 border-purple-400 p-4 mb-4">
                        <p class="text-xs text-purple-900 font-medium">Customer has uploaded payment proof. Please verify below:</p>
                    </div>
                    @endif
                    
                    <div class="border border-gray-200 p-4">
                        <p class="text-xs uppercase tracking-wider text-gray-500 mb-3">Payment Proof:</p>
                        <img src="{{ Storage::url($order->proof_of_payment) }}" 
                            alt="Payment Proof" 
                            class="w-48 h-auto border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity"
                            onclick="openLightbox('{{ Storage::url($order->proof_of_payment) }}', 'admin-image-modal')">
                        <p class="text-xs text-gray-500 mt-2">Click to view full size</p>
                    </div>
                </div>
                @endif

                {{-- Customer's Notes --}}
                @if($order->order_notes)
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">Customer's Notes</h2>
                    <div class="w-full px-4 py-3 bg-yellow-50 border border-yellow-100 text-sm text-gray-900">
                        {{ $order->order_notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- RIGHT COLUMN: Order Summary (Sticky) --}}
        <div class="w-full lg:w-[42%] bg-[#FAFAFA] border-l border-gray-100 order-1 lg:order-2">
            <div class="lg:sticky lg:top-0">
                <div class="p-6 lg:p-16">
                    <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-8 pb-4 border-b border-gray-200">
                        Order Summary
                    </h2>

                    {{-- Cart Items --}}
                    <div id="summary-items" class="space-y-6 mb-8 max-h-[calc(100vh-450px)] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center space-x-4 mt-2">
                            <div class="relative w-16 h-16 bg-white border border-gray-100 flex-shrink-0">
                                @if($item->product && $item->product->featuredImage)
                                    <img src="{{ asset('storage/' . $item->product->featuredImage->image) }}" 
                                        alt="{{ $item->item_name }}"
                                        class="w-full h-full object-cover p-1">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                        <span class="text-[9px]">NO IMG</span>
                                    </div>
                                @endif
                                <span class="absolute -top-2 -right-2 bg-[#E8B49E] text-black text-[10px] font-medium rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ $item->quantity }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->item_name }}</p>
                                
                                @if($item->color || $item->size)
                                <p class="text-[10px] text-gray-500 mt-1 space-x-2">
                                    @if($item->color)<span>{{ $item->color }}</span>@endif
                                    @if($item->color && $item->size)<span>•</span>@endif
                                    @if($item->size)<span>{{ $item->size }}</span>@endif
                                </p>
                                @endif
                            </div>
                            <div class="text-sm font-medium text-gray-900" id="item-price-{{ $item->id }}">
                                @if($order->currency === 'USD')
                                    ${{ number_format($item->price_usd * $item->quantity, 2) }}
                                @else
                                    Rp {{ number_format($item->price_idr * $item->quantity, 0, ',', '.') }}
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Price Breakdown --}}
                    <div class="border-t border-gray-200 pt-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Subtotal</span>
                            <span class="text-sm font-medium text-gray-900" id="subtotal-display">
                                @if($order->currency === 'USD')
                                    ${{ number_format($order->subtotal_usd, 2) }}
                                @else
                                    Rp {{ number_format($order->subtotal_idr, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Shipping</span>
                            
                            @if($order->status === 'PENDING_REVIEW')
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-gray-600" id="shipping-currency-label">{{ $order->currency }}</span>
                                <input type="number" name="shipping_cost" id="shipping_cost_input" form="invoice-form"
                                    value="{{ old('shipping_cost', $order->shipping_cost) }}" step="0.01" min="0" required
                                    class="w-24 px-2 py-1 border border-gray-300 text-sm text-right focus:outline-none focus:border-gray-900">
                            </div>
                            @else
                            <span class="text-sm font-medium text-gray-900">
                                @if($order->shipping_cost > 0)
                                    {{ $order->currency }} {{ number_format($order->shipping_cost, 2, ',', '.') }}
                                @else
                                    <span class="text-green-600 text-xs uppercase tracking-wider">Free</span>
                                @endif
                            </span>
                            @endif
                        </div>

                        @if($order->status === 'PENDING_REVIEW')
                        <div class="flex justify-end flex-wrap gap-2">
                            <button type="button" onclick="setShippingCost(0)"
                                    class="px-3 py-1 text-xs bg-green-50 text-green-700 hover:bg-green-100 transition uppercase tracking-wider">
                                Free Shipping
                            </button>
                        </div>
                        @error('shipping_cost')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                        @endif

                        <div class="flex justify-between items-center border-t border-gray-200 pt-6 mt-6">
                            <span class="text-base font-bold text-gray-900 uppercase tracking-widest">Total</span>
                            <span class="text-xl font-medium text-gray-900" id="total-display">
                                {{ $order->currency }} {{ number_format($order->total_amount, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-8 space-y-3">
                        @if($order->status === 'PENDING_REVIEW')
                        <button type="button" onclick="openInvoiceModal()"
                                class="w-full cursor-pointer bg-gray-900 hover:bg-gray-800 text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                            Create Invoice
                        </button>
                        
                        <button type="button" onclick="confirmCancelOrder()"
                                class="w-full cursor-pointer border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                            Cancel Order
                        </button>

                        @elseif($order->status === 'WAITING_VERIFICATION')
                        {{-- Simple Approve (tanpa shipping) --}}
                        <form action="{{ route('admin.orders.verifyPayment', $order) }}" method="POST" id="approve-payment-form">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            
                            <button type="button" onclick="confirmApprovePayment()"
                                    class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                                Approve Payment → Production
                            </button>
                        </form>

                        <button type="button" onclick="toggleForm('reject-form-container', '')"
                                class="w-full border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                            Reject Payment
                        </button>

                        {{-- Info Box --}}
                        <div class="bg-teal-50 border border-teal-200 p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-teal-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-teal-900 mb-1">Payment Verification</p>
                                    <p class="text-xs text-teal-800 leading-relaxed">
                                        After approval, order moves to <span class="font-semibold">PRODUCTION</span> status. 
                                        You can enter shipping details once the item is ready to ship.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Reject Form --}}
                        <div id="reject-form-container" class="hidden mt-4 p-6 bg-gray-50 border border-gray-200">
                            <h3 class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-4">Rejection Reason</h3>
                            
                            <form action="{{ route('admin.orders.verifyPayment', $order) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                
                                <div class="space-y-4">
                                    <div class="relative">
                                        <textarea name="rejection_notes" required rows="3"
                                                class="peer block w-full border border-gray-200 px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 bg-white resize-none"
                                                placeholder="Reason"></textarea>
                                        <label class="absolute left-4 -top-2.5 bg-gray-50 px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs">
                                            Why are you rejecting? *
                                        </label>
                                        @error('rejection_notes') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="bg-gray-100 border border-gray-200 p-3">
                                        <p class="text-xs text-gray-700">
                                            Order returns to <span class="font-medium text-gray-900">INVOICE_SENT</span>
                                        </p>
                                    </div>

                                    <button type="button" onclick="confirmRejectPayment()"
                                            class="w-full cursor-pointer bg-gray-900 hover:bg-gray-800 text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                                        Reject & Request Re-upload
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- CASE: PAID STATUS (PayPal - Ready to Process) --}}
                        @elseif($order->status === 'PAID')
                        <div class="bg-emerald-50 border border-emerald-200 p-4 mb-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-emerald-900 mb-1">Payment Confirmed (PayPal)</p>
                                    <p class="text-xs text-emerald-800 leading-relaxed">
                                        Customer has paid via PayPal. Click "Process Order" to move to production and prepare the order.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form id="process-order-form" action="{{ route('admin.orders.processOrder', $order) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmProcessOrder()"
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                                Process Order → Production
                            </button>
                        </form>

                        {{-- CASE: PRODUCTION STATUS --}}
                        @elseif($order->status === 'PRODUCTION')
                        <button type="button" onclick="document.getElementById('production-shipping-form').classList.toggle('hidden')"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                            Mark as Shipped
                        </button>

                        {{-- Status Info --}}
                        <div class="bg-teal-50 border border-teal-200 p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-teal-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-teal-900 mb-1">In Production</p>
                                    <p class="text-xs text-teal-800 leading-relaxed">
                                        Payment verified. Order is being prepared. Enter shipping details when ready to ship.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div id="production-shipping-form" class="hidden mt-4 p-6 bg-gray-50 border border-gray-200">
                            <h3 class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-4">Enter Shipping Details</h3>
                            
                            <form action="{{ route('admin.orders.updateShipping', $order) }}" method="POST">
                                @csrf
                                
                                <div class="space-y-4">
                                    <div class="relative">
                                        <select name="courier_name" required
                                                class="block w-full border border-gray-200 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-gray-900 bg-white appearance-none">
                                            <option value="">Select Courier</option>
                                            <option value="JNE">JNE</option>
                                            <option value="Paxel">Paxel</option>
                                            <option value="RPX">RPX</option>
                                        </select>
                                        <label class="absolute left-4 -top-2.5 bg-gray-50 px-1 text-xs text-gray-500">Courier / Shipping Service *</label>
                                        @error('courier_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="relative">
                                        <input type="text" 
                                            id="tracking_number_field" 
                                            name="tracking_number" 
                                            required
                                            class="block w-full border border-gray-200 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-gray-900 bg-white"
                                            placeholder="Tracking Number">
                                        
                                        <label for="tracking_number_field" 
                                            class="absolute left-4 -top-2.5 bg-gray-50 px-1 text-[10px] font-bold uppercase tracking-wider text-gray-500 cursor-text">                                            
                                            Tracking Number / Resi *
                                        </label>
                                        
                                        @error('tracking_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="bg-gray-100 border border-gray-200 p-3">
                                        <p class="text-xs text-gray-700">
                                            Status will change to <span class="font-medium text-gray-900">SHIPPED</span>
                                        </p>
                                    </div>

                                    <button type="button" onclick="confirmMarkShipped()"
                                            class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                                        Ship Order
                                    </button>
                                </div>
                            </form>
                        </div>

                        @elseif($order->status === 'SHIPPED')
                        <div class="bg-gray-50 border border-gray-200 p-6 mb-4">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3 flex-1">
                                    <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-3">Order Shipped</p>
                                        @if($order->shipment && $order->shipment->courier_name && $order->shipment->tracking_number)
                                        <div class="space-y-2 text-xs text-gray-700">
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Courier:</span>
                                                <span class="font-medium">{{ $order->shipment->courier_name }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Tracking:</span>
                                                <span class="font-medium font-mono">{{ $order->shipment->tracking_number }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Shipped:</span>
                                                <span class="font-medium">{{ $order->shipment->shipped_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Edit Button --}}
                                <button type="button" onclick="document.getElementById('edit-shipping-form').classList.toggle('hidden')"
                                        class="ml-4 px-3 py-1.5 border border-gray-300 text-gray-700 hover:bg-gray-100 text-[10px] uppercase tracking-wider font-medium transition-all flex-shrink-0">
                                    Edit
                                </button>
                            </div>
                        </div>

                        {{-- Edit Shipping Form --}}
                        <div id="edit-shipping-form" class="hidden mb-4 p-6 bg-gray-50 border border-gray-200">
                            <h3 class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-4">Update Shipping Details</h3>
                            
                            <form action="{{ route('admin.orders.updateShipping', $order) }}" method="POST">
                                @csrf
                                
                                <div class="space-y-4">
                                    <div class="relative">
                                        <select name="courier_name" required
                                                class="block w-full border border-gray-200 px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-gray-900 bg-white appearance-none">
                                            <option value="">Select Courier</option>
                                            <option value="JNE" {{ $order->shipment->courier_name === 'JNE' ? 'selected' : '' }}>JNE</option>
                                            <option value="Paxel" {{ $order->shipment->courier_name === 'Paxel' ? 'selected' : '' }}>Paxel</option>
                                            <option value="RPX" {{ $order->shipment->courier_name === 'RPX' ? 'selected' : '' }}>RPX</option>
                                        </select>
                                        <label class="absolute left-4 -top-2.5 bg-gray-50 px-1 text-xs text-gray-500 pointer-events-none">Courier / Shipping Service *</label>
                                        @error('courier_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="relative">
                                        <input type="text" name="tracking_number" required
                                            value="{{ $order->shipment->tracking_number }}"
                                            class="peer block w-full border border-gray-200 px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 bg-white"
                                            placeholder="Tracking">
                                        <label class="absolute left-4 -top-2.5 bg-gray-50 px-1 text-xs text-gray-500 pointer-events-none transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs">
                                            Tracking Number / Resi *
                                        </label>
                                        @error('tracking_number') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="button" onclick="document.getElementById('edit-shipping-form').classList.add('hidden')"
                                                class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-100 text-xs uppercase tracking-[0.15em] font-medium transition-all">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                                class="flex-1 px-4 py-3 bg-gray-900 hover:bg-gray-800 text-white text-xs uppercase tracking-[0.15em] font-medium transition-all">
                                            Update Shipping
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <button type="button" onclick="confirmCompleteOrder()"
                                class="w-full cursor-pointer bg-gray-900 hover:bg-gray-800 text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                            Mark as Completed
                        </button>

                        @elseif($order->status === 'COMPLETED' && $order->can_request_refund && $order->payment_gateway !== 'PAYPAL')
                        <button type="button" onclick="document.getElementById('manual-refund-form').classList.toggle('hidden')"
                                class="w-full border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                            Process Refund
                        </button>
                        
                        <div id="manual-refund-form" class="hidden mt-4 p-6 bg-gray-50 border border-gray-200">
                            <h3 class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-4">Refund Details</h3>
                            
                            <form action="{{ route('admin.orders.processRefund', $order) }}" method="POST">
                                @csrf
                                
                                <div class="space-y-4">
                                    <div class="relative">
                                        <input type="number" name="refund_amount" required step="0.01" min="0.01" max="{{ $order->total_amount }}" value="{{ $order->total_amount }}"
                                            class="peer block w-full border border-gray-200 px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 bg-white"
                                            placeholder="Amount">
                                        <label class="absolute left-4 -top-2.5 bg-gray-50 px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs">
                                            Refund Amount ({{ $order->currency }}) *
                                        </label>
                                        <p class="text-xs text-gray-500 mt-1">Maximum: {{ $order->currency }} {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                                    </div>

                                    <div class="bg-gray-100 border border-gray-200 p-3">
                                        <p class="text-xs text-gray-700">
                                            Status will change to <span class="font-medium text-gray-900">REFUNDED</span>
                                        </p>
                                    </div>

                                    <button type="button" onclick="confirmProcessRefund()"
                                            class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium text-xs uppercase tracking-[0.2em] py-4 transition-all">
                                        Process Refund
                                    </button>
                                </div>
                            </form>
                        </div>

                        @else
                        <div class="bg-gray-50 border border-gray-200 p-4 text-center">
                            <p class="text-xs uppercase tracking-wider text-gray-600">
                                Status: <span class="font-medium">{{ str_replace('_', ' ', $order->status) }}</span>
                            </p>
                        </div>
                        @endif
                    </div>

                    {{-- Refund Info --}}
                    @if($order->status === 'COMPLETED')
                        @if($order->can_request_refund)
                        <div class="mt-4 p-4 bg-green-50 border-l-4 border-green-400">
                            <p class="text-xs text-green-800">
                                Refundable until <strong>{{ $order->refundable_until->format('M d, Y') }}</strong>
                            </p>
                            @if ($order->payment_gateway === 'PAYPAL')
                            <p class="text-xs text-green-600 mt-2 italic">Process refund from PayPal Dashboard</p>
                            @endif
                        </div>
                        @else
                        <div class="mt-4 p-4 bg-gray-100 border-l-4 border-gray-300">
                            <p class="text-xs font-medium text-gray-700">Refund Window Closed</p>
                            <p class="text-xs text-gray-600 mt-1">Expired on {{ $order->refundable_until->format('M d, Y') }}</p>
                        </div>
                        @endif
                    @elseif($order->status === 'REFUNDED')
                    <div class="mt-4 p-4 bg-orange-50 border-l-4 border-orange-400">
                        <p class="text-xs font-medium text-orange-900">Order Refunded</p>
                        <p class="text-xs text-orange-700 mt-1">
                            Amount: <strong>{{ $order->currency }} {{ number_format($order->refund_amount, 2, ',', '.') }}</strong>
                        </p>
                        <p class="text-xs text-orange-600 mt-1">Refunded on {{ $order->refunded_at->format('M d, Y') }}</p>
                    </div>
                    @endif

                    {{-- Security Note --}}
                    <div class="mt-6 flex items-center justify-center space-x-2 text-[10px] text-gray-400 uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Confidential Information</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI CREATE INVOICE - SIMPLE VERSION --}}
<div id="invoice-confirmation-modal" class="fixed inset-0 z-50 hidden" style="background-color: rgba(0, 0, 0, 0.6);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white w-full max-w-md shadow-2xl">
            
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-serif text-gray-900">Confirm Invoice Creation</h3>
                <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Review details before sending</p>
            </div>

            {{-- Body --}}
            <div class="px-6 py-6 space-y-5">
                
                {{-- Payment Method --}}
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-2">Payment Method</p>
                    <div class="p-3 bg-gray-50 border border-gray-200">
                        <p class="text-sm font-medium text-gray-900" id="modal-payment-method">-</p>
                        <p class="text-xs text-gray-600 mt-1" id="modal-currency">-</p>
                    </div>
                    
                    {{-- Override Warning --}}
                    <div id="modal-override-warning" class="hidden mt-2 p-3 bg-orange-50 border-l-4 border-orange-400">
                        <p class="text-xs text-orange-800">
                            <span class="font-medium">⚠ Override Active:</span> You selected different method than customer's preference
                        </p>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-3">Invoice Amount</p>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900" id="modal-subtotal">-</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-gray-900" id="modal-shipping">-</span>
                        </div>
                        
                        <div class="flex justify-between items-center border-t border-gray-200 pt-3 mt-3">
                            <span class="text-base font-bold text-gray-900 uppercase tracking-wider">Total</span>
                            <span class="text-xl text-gray-900" id="modal-total">-</span>
                        </div>
                    </div>
                </div>

                {{-- Customer Email --}}
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-2">Send Invoice To</p>
                    <div class="p-3 bg-blue-50 border border-blue-100">
                        <p class="text-sm text-blue-900 font-medium">{{ $order->contact_email }}</p>
                    </div>
                </div>

                {{-- Admin Notes Preview --}}
                <div id="modal-notes-container" class="hidden">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-2">Notes for Customer</p>
                    <div class="p-3 bg-purple-50 border border-purple-100">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap" id="modal-notes-text"></p>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeInvoiceModal()"
                        class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-100 text-xs uppercase tracking-[0.15em] font-medium transition-all">
                    Cancel
                </button>
                <button type="submit" form="invoice-form"
                        class="flex-1 px-4 py-3 bg-gray-900 hover:bg-gray-800 text-white text-xs uppercase tracking-[0.15em] font-medium transition-all">
                    Create & Send Invoice
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Image Lightbox Modal --}}
<x-image-lightbox modalId="admin-image-modal" alt="Payment Proof" />

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 2px; }
</style>

<script>
    // Shipping Cost Calculator & Currency Switcher
    const subtotalIdr = {{ $order->subtotal_idr }};
    const subtotalUsd = {{ $order->subtotal_usd }};

    // Item prices data
    const itemPrices = {
        @foreach($order->orderItems as $item)
        '{{ $item->id }}': {
            idr: {{ $item->price_idr * $item->quantity }},
            usd: {{ $item->price_usd * $item->quantity }}
        },
        @endforeach
    };

    const paymentGatewayRadios = document.querySelectorAll('input[name="payment_gateway_override"]');
    const shippingInput = document.getElementById('shipping_cost_input');
    const totalDisplay = document.getElementById('total-display');

    function updateTotal() {
        if (!shippingInput) return;
        
        const selectedGateway = document.querySelector('input[name="payment_gateway_override"]:checked');
        const isPaypal = selectedGateway && selectedGateway.value === 'PAYPAL';
        const currency = isPaypal ? 'USD' : 'IDR';
        const subtotal = isPaypal ? subtotalUsd : subtotalIdr;
        
        const shipping = parseFloat(shippingInput.value) || 0;
        const total = subtotal + shipping;
        
        if (totalDisplay) {
            totalDisplay.textContent = currency + ' ' + total.toLocaleString(currency === 'USD' ? 'en-US' : 'id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }

    function updateCurrency() {
        const selectedGateway = document.querySelector('input[name="payment_gateway_override"]:checked');
        const isPaypal = selectedGateway && selectedGateway.value === 'PAYPAL';
        const currency = isPaypal ? 'USD' : 'IDR';
        
        // Update subtotal display
        const subtotalDisplay = document.getElementById('subtotal-display');
        if (subtotalDisplay) {
            const subtotal = isPaypal ? subtotalUsd : subtotalIdr;
            if (isPaypal) {
                subtotalDisplay.textContent = '$' + subtotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                subtotalDisplay.textContent = 'Rp ' + subtotal.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }
        }
        
        // Update each item price
        @foreach($order->orderItems as $item)
        const itemPrice{{ $item->id }} = document.getElementById('item-price-{{ $item->id }}');
        if (itemPrice{{ $item->id }}) {
            const price = isPaypal ? itemPrices['{{ $item->id }}'].usd : itemPrices['{{ $item->id }}'].idr;
            if (isPaypal) {
                itemPrice{{ $item->id }}.textContent = '$' + price.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                itemPrice{{ $item->id }}.textContent = 'Rp ' + price.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }
        }
        @endforeach
        
        // Update shipping currency label
        const shippingCurrencyLabel = document.getElementById('shipping-currency-label');
        if (shippingCurrencyLabel) {
            shippingCurrencyLabel.textContent = currency;
        }
        
        // Reset shipping to 0 when currency changes
        if (shippingInput) {
            shippingInput.value = '0.00';
        }
        
        updateTotal();
    }

    if (paymentGatewayRadios.length > 0) {
        paymentGatewayRadios.forEach(radio => {
            radio.addEventListener('change', updateCurrency);
        });
    }

    if (shippingInput) {
        shippingInput.addEventListener('input', updateTotal);
        updateTotal();
    }

    function setShippingCost(amount) {
        if (shippingInput) {
            shippingInput.value = amount.toFixed(2);
            updateTotal();
        }
    }

    function toggleForm(showId, hideId) {
        const showElement = document.getElementById(showId);
        const hideElement = document.getElementById(hideId);
        
        if (hideElement && !hideElement.classList.contains('hidden')) {
            hideElement.classList.add('hidden');
        }
        
        showElement.classList.toggle('hidden');
    }

    // Admin Upload Proof Preview
    function previewAdminProof(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('admin-preview');
        const previewImg = document.getElementById('admin-preview-img');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    }
</script>

<script>
    // Override Indicator Logic
    const customerPreference = '{{ $order->payment_preference }}';
    const paymentRadios = document.querySelectorAll('.payment-radio');
    const overrideIndicator = document.getElementById('override-indicator');

    if (paymentRadios.length > 0) {
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const selectedValue = this.value;
                
                // Show/hide override indicator
                if (selectedValue !== customerPreference) {
                    overrideIndicator?.classList.remove('hidden');
                } else {
                    overrideIndicator?.classList.add('hidden');
                }
                
                // Update shipping cost to 0 when payment method changes
                if (shippingInput) {
                    shippingInput.value = '0.00';
                    updateTotal();
                }
            });
        });
    }

    // MODAL FUNCTIONS - SIMPLE VERSION
    function openInvoiceModal() {
        console.log('Opening modal...'); // Debug
        
        const modal = document.getElementById('invoice-confirmation-modal');
        if (!modal) {
            console.error('Modal not found!');
            return;
        }
        
        const selectedGateway = document.querySelector('input[name="payment_gateway_override"]:checked');
        const gateway = selectedGateway ? selectedGateway.value : customerPreference;
        const isPaypal = gateway === 'PAYPAL';
        const currency = isPaypal ? 'USD' : 'IDR';
        
        // Payment Method
        document.getElementById('modal-payment-method').textContent = 
            isPaypal ? 'PayPal (International)' : 'BCA Bank Transfer (Indonesia)';
        document.getElementById('modal-currency').textContent = 
            'Currency: ' + currency;
        
        // Show override warning if method is different
        const overrideWarning = document.getElementById('modal-override-warning');
        if (gateway !== customerPreference) {
            overrideWarning.classList.remove('hidden');
        } else {
            overrideWarning.classList.add('hidden');
        }
        
        // Calculate amounts
        const subtotal = isPaypal ? {{ $order->subtotal_usd }} : {{ $order->subtotal_idr }};
        const shipping = parseFloat(shippingInput?.value || 0);
        const total = subtotal + shipping;
        
        // Format numbers
        const formatPrice = (amount) => {
            return amount.toLocaleString(isPaypal ? 'en-US' : 'id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        };
        
        // Update modal values
        document.getElementById('modal-subtotal').textContent = currency + ' ' + formatPrice(subtotal);
        document.getElementById('modal-shipping').textContent = 
            shipping > 0 ? currency + ' ' + formatPrice(shipping) : 'FREE';
        document.getElementById('modal-total').textContent = currency + ' ' + formatPrice(total);
        
        // Admin notes
        const notesTextarea = document.querySelector('textarea[name="admin_notes"]');
        const notesContainer = document.getElementById('modal-notes-container');
        const notesText = document.getElementById('modal-notes-text');
        
        if (notesTextarea && notesTextarea.value.trim()) {
            notesText.textContent = notesTextarea.value;
            notesContainer.classList.remove('hidden');
        } else {
            notesContainer.classList.add('hidden');
        }
        
        // SHOW MODAL - SIMPLE, NO ANIMATION
        modal.classList.remove('hidden');
        console.log('Modal should be visible now');
    }

    function closeInvoiceModal() {
        const modal = document.getElementById('invoice-confirmation-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Close modal on backdrop click
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('invoice-confirmation-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                // Close hanya jika click di backdrop (bukan di content)
                if (e.target === modal) {
                    closeInvoiceModal();
                }
            });
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeInvoiceModal();
        }
    });
</script>

{{-- Script untuk berbagai sweetalert atau konfirmasi --}}
<script>
    // Cancel Order with SweetAlert
    function confirmCancelOrder() {
        Swal.fire({
            title: 'Cancel Order?',
            text: 'Are you sure you want to cancel this order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Cancel Order',
            cancelButtonText: 'No, Keep Order',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit cancel form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.orders.cancel", $order) }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Complete Order with SweetAlert
    function confirmCompleteOrder() {
        Swal.fire({
            title: 'Mark as Completed?',
            text: 'Are you sure the customer has received this order?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Mark Complete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.orders.complete", $order) }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Upload Proof Confirmation (Admin)
    function confirmUploadProof() {
        const fileInput = document.getElementById('admin_proof_input');
        
        if (!fileInput.files || fileInput.files.length === 0) {
            Swal.fire({
                title: 'No File Selected',
                text: 'Please select a payment proof image first.',
                icon: 'warning',
                confirmButtonColor: '#111827',
            });
            return false;
        }

        Swal.fire({
            title: 'Upload Payment Proof?',
            html: 'This will change order status to <strong>WAITING_VERIFICATION</strong>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Upload',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('admin-upload-form').submit();
            }
        });
    }

    // Reject Payment Confirmation
    function confirmRejectPayment() {
        const rejectionNotes = document.querySelector('#reject-form-container textarea[name="rejection_notes"]').value.trim();
        
        if (!rejectionNotes) {
            Swal.fire({
                title: 'Rejection Reason Required',
                text: 'Please provide a reason for rejecting this payment proof.',
                icon: 'warning',
                confirmButtonColor: '#111827',
            });
            return false;
        }

        Swal.fire({
            title: 'Reject Payment Proof?',
            html: `Order will return to <strong>INVOICE_SENT</strong> status.<br><small class="text-gray-500">Customer will be notified to re-upload proof.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Reject',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('#reject-form-container form').submit();
            }
        });
    }

    // Approve Payment (WAITING_VERIFICATION → PRODUCTION)
    function confirmApprovePayment() {
        Swal.fire({
            title: 'Approve Payment?',
            html: `Order status will change to <strong class="text-teal-600">PRODUCTION</strong><br><small class="text-gray-500 mt-2 block">You can enter shipping details later when the order is ready.</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approve-payment-form').submit();
            }
        });
    }

    // Process Order (PAID → PRODUCTION)
    function confirmProcessOrder() {
        Swal.fire({
            title: 'Process Order?',
            html: `Order status will change to <strong class="text-teal-600">PRODUCTION</strong><br><small class="text-gray-500 mt-2 block">After this, you can enter shipping details when the order is ready.</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Process Order',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('process-order-form').submit();
            }
        });
    }

    // Process Refund Confirmation
    function confirmProcessRefund() {
        const form = document.querySelector('#manual-refund-form form');
        const refundAmount = document.querySelector('input[name="refund_amount"]').value;
        const currency = '{{ $order->currency }}';
        
        Swal.fire({
            title: 'Process Refund?',
            html: `Refund amount: <strong>${currency} ${parseFloat(refundAmount).toLocaleString()}</strong><br><small class="text-gray-500">This action cannot be undone.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Process Refund',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Confirm Approve & Ship (untuk WAITING_VERIFICATION)
    function confirmApproveShip() {
        const form = document.querySelector('#verification-shipping-form form');
        const courier = form.querySelector('select[name="courier_name"]').value;
        const tracking = form.querySelector('input[name="tracking_number"]').value.trim();
        
        // Validasi required fields
        if (!courier || courier === '') {
            Swal.fire({
                title: 'Courier Required',
                text: 'Please select a courier/shipping service.',
                icon: 'warning',
                confirmButtonColor: '#111827',
            });
            return false;
        }
        
        if (!tracking) {
            Swal.fire({
                title: 'Tracking Number Required',
                text: 'Please enter tracking number/resi.',
                icon: 'warning',
                confirmButtonColor: '#111827',
            });
            return false;
        }

        Swal.fire({
            title: 'Approve & Ship Order?',
            html: `<div class="text-left">
                <p class="mb-2"><strong>Courier:</strong> ${courier}</p>
                <p class="mb-2"><strong>Tracking:</strong> ${tracking}</p>
                <p class="text-sm text-gray-500 mt-3">Order status will change to <strong>SHIPPED</strong></p>
            </div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Approve & Ship',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Confirm Mark as Shipped (untuk PRODUCTION status)
    function confirmMarkShipped() {
        const form = document.querySelector('#production-shipping-form form');
        const courier = form.querySelector('select[name="courier_name"]').value;
        const tracking = form.querySelector('input[name="tracking_number"]').value.trim();
        
        // Validasi required fields
        if (!courier || courier === '') {
            Swal.fire({
                title: 'Courier Required',
                text: 'Please select a courier/shipping service.',
                icon: 'warning',
                confirmButtonColor: '#111827',
            });
            return false;
        }
        
        if (!tracking) {
            Swal.fire({
                title: 'Tracking Number Required',
                text: 'Please enter tracking number/resi.',
                icon: 'warning',
                confirmButtonColor: '#111827',
            });
            return false;
        }

        Swal.fire({
            title: 'Mark as Shipped?',
            html: `<div class="text-left">
                <p class="mb-2"><strong>Courier:</strong> ${courier}</p>
                <p class="mb-2"><strong>Tracking:</strong> ${tracking}</p>
                <p class="text-sm text-gray-500 mt-3">Order status will change to <strong>SHIPPED</strong></p>
            </div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Mark as Shipped',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection