@extends('layout.checkout')

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- Main Container --}}
    <div class="max-w-screen-xl mx-auto px-4 md:px-6 py-12">
        
        {{-- Flash Messages (Minimalist) --}}
        @if(session('success'))
        <div class="mb-8 p-4 border border-green-100 bg-green-50/30 flex items-center justify-center text-sm text-green-800 uppercase tracking-wide">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-8 p-4 border border-red-100 bg-red-50/30 flex items-center justify-center text-sm text-red-800 uppercase tracking-wide">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Header: Order Number & Status --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 border-b border-gray-100 pb-8">
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] mb-2">Order Tracking</p>
                <h1 class="text-3xl md:text-4xl text-gray-900">Order #{{ $order->order_number }}</h1>
                <p class="mt-2 text-xs text-gray-500 uppercase tracking-widest font-light">
                    Placed on {{ $order->created_at->format('F d, Y') }}
                </p>
            </div>
            
            <div class="mt-6 md:mt-0 text-left md:text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] mb-2">Current Status</p>
                @php
                    $statusConfig = [
                        'PENDING_REVIEW' => ['color' => 'bg-yellow-500 text-yellow-600', 'label' => 'Pending Review'],
                        'INVOICE_SENT' => ['color' => 'bg-gray-900 text-gray-900', 'label' => 'Payment Required'],
                        'WAITING_VERIFICATION' => ['color' => 'bg-purple-500 text-purple-600', 'label' => 'Verifying Payment'],
                        'PAID' => ['color' => 'bg-emerald-600 text-emerald-700', 'label' => 'Paid'],
                        'PRODUCTION' => ['color' => 'bg-teal-500 text-teal-600', 'label' => 'In Production'],
                        'SHIPPED' => ['color' => 'bg-blue-500 text-blue-600', 'label' => 'Shipped'],
                        'COMPLETED' => ['color' => 'bg-green-600 text-green-700', 'label' => 'Completed'],
                        'CANCELLED' => ['color' => 'bg-red-500 text-red-600', 'label' => 'Cancelled'],
                        'REFUNDED' => ['color' => 'bg-orange-500 text-orange-600', 'label' => 'Refunded'],
                    ];
                    
                    $currentStatus = $statusConfig[$order->status] ?? ['color' => 'bg-gray-400 text-gray-500', 'label' => 'Unknown'];
                    $colorClasses = explode(' ', $currentStatus['color']);
                    $dotColor = $colorClasses[0] ?? 'bg-gray-400';
                    $textColor = $colorClasses[1] ?? 'text-gray-500';
                @endphp
                <div class="flex items-center md:justify-end">
                    <span class="w-2 h-2 rounded-full {{ $dotColor }} mr-3 animate-pulse"></span>
                    <span class="text-sm font-medium uppercase tracking-[0.15em] {{ $textColor }}">
                        {{ $currentStatus['label'] }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16">
            
            {{-- LEFT COLUMN: Details --}}
            <div class="lg:col-span-2 space-y-12">
                
                {{-- REFUND NOTICE --}}
                @if($order->status === 'REFUNDED')
                <div class="bg-orange-50/50 border border-orange-200 p-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-orange-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-xs font-bold uppercase tracking-[0.15em] text-orange-900 mb-2">Order Refunded</h3>
                            <p class="text-sm text-orange-800">This order has been refunded by the admin.</p>
                            @if($order->refund_amount && $order->payment_gateway === 'PAYPAL')
                            <div class="mt-4 pt-4 border-t border-orange-200 flex justify-between items-center">
                                <span class="text-xs uppercase tracking-widest text-orange-700">Refund Amount</span>
                                <span class="text-lg font-serif text-orange-900">${{ number_format($order->refund_amount, 2) }}</span>
                            </div>
                            <p class="text-[10px] text-orange-600 mt-2 uppercase tracking-wide">
                                Refunded on {{ $order->refunded_at->format('M d, Y') }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                
                {{-- 1. Order Items (Clean List) --}}
                <div class="space-y-8">
                    <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 pb-4 border-b border-gray-100">
                        Items Ordered
                    </h2>
                    
                    <div class="space-y-6">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-start space-x-6">
                            {{-- Image --}}
                            <div class="w-24 h-24 bg-[#F7F7F7] flex-shrink-0 border border-gray-100">
                                @if($item->product && $item->product->images->first())
                                    <img src="{{ Storage::url($item->product->images->first()->image) }}" 
                                        alt="{{ $item->item_name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <span class="text-[9px]">NO IMG</span>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Details --}}
                            <div class="flex-1 min-w-0 pt-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-sm font-serif text-gray-900">{{ $item->item_name }}</h3>
                                        
                                        @if($item->color || $item->size)
                                            <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">
                                                {{ implode(' / ', array_filter([$item->color, $item->size])) }}
                                            </p>
                                        @endif
                                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-2">
                                            Qty: {{ $item->quantity }}
                                        </p>
                                    </div>
                                    
                                    <div class="text-right">
                                        @php
                                            $price = $order->currency === 'USD' ? $item->price_usd : $item->price_idr;
                                            $itemSubtotal = $price * $item->quantity;
                                            $decimals = $order->currency === 'USD' ? 2 : 0;
                                        @endphp
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($itemSubtotal, $decimals, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Totals Summary --}}
                    <div class="pt-6 border-t border-gray-100 flex flex-col items-end space-y-3">
                        @php
                            $decimals = $order->currency === 'USD' ? 2 : 0;
                            $subtotal = $order->currency === 'USD' ? $order->subtotal_usd : $order->subtotal_idr;
                        @endphp
                        <div class="w-full md:w-1/2 flex justify-between text-xs uppercase tracking-widest text-gray-500">
                            <span>Subtotal</span>
                            <span>{{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($subtotal, $decimals, ',', '.') }}</span>
                        </div>
                        <div class="w-full md:w-1/2 flex justify-between text-xs uppercase tracking-widest text-gray-500">
                            <span>Shipping</span>
                            <span>{{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($order->shipping_cost, $decimals, ',', '.') }}</span>
                        </div>
                        <div class="w-full md:w-1/2 flex justify-between text-base font-serif text-gray-900 pt-4 border-t border-gray-100 mt-2">
                            <span>Total</span>
                            <span>{{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($order->total_amount, $decimals, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- 2. Shipping & Contact Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-gray-100">
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-6">Shipping Details</h2>
                        @if($order->shipment)
                            <div class="space-y-4">
                                {{-- Name --}}
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Recipient Name</p>
                                    <p class="text-sm text-gray-900">{{ $order->shipment->first_name }} {{ $order->shipment->last_name }}</p>
                                </div>
                                
                                {{-- Phone --}}
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Phone Number</p>
                                    <p class="text-sm text-gray-900">{{ $order->shipment->phone }}</p>
                                </div>
                                
                                {{-- Address --}}
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Street Address</p>
                                    <p class="text-sm text-gray-900">{{ $order->shipment->address_line1 }}</p>
                                </div>
                                
                                {{-- City --}}
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">City</p>
                                    <p class="text-sm text-gray-900">{{ $order->shipment->city }}</p>
                                </div>
                                
                                {{-- Province (if exists) --}}
                                @if($order->shipment->province)
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Province / State</p>
                                    <p class="text-sm text-gray-900">{{ $order->shipment->province }}</p>
                                </div>
                                @endif
                                
                                {{-- Postal Code (if exists) --}}
                                @if($order->shipment->postal_code)
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Postal Code</p>
                                    <p class="text-sm text-gray-900">{{ $order->shipment->postal_code }}</p>
                                </div>
                                @endif
                                
                                {{-- Country --}}
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Country</p>
                                    <p class="text-sm text-gray-900">{{ $order->shipment->country }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">Information pending</p>
                        @endif
                    </div>

                    {{-- Tracking Info --}}
                    
                    @if($order->shipment && $order->shipment->tracking_number)
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-6">Tracking Info</h2>
                        <div class="bg-[#FAFAFA] p-6 border border-gray-100 space-y-3">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-gray-400">Courier</p>
                                <p class="text-sm text-gray-900">{{ $order->shipment->courier_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-gray-400">Tracking Number</p>
                                <p class="text-sm font-medium text-blue-600 tracking-wide">{{ $order->shipment->tracking_number }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-gray-400">Shipped Date</p>
                                <p class="text-sm text-gray-900">{{ $order->shipment->shipped_at ? $order->shipment->shipped_at->format('M d, Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- 3. Payment / Action Area --}}
                @if(in_array($order->status, ['INVOICE_SENT', 'WAITING_VERIFICATION', 'PAID', 'PRODUCTION', 'SHIPPED']))
                <div class="pt-8 border-t border-gray-100">
                    <h2 class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-6">Action Required</h2>
                    
                    {{-- CASE 1: BCA MANUAL --}}
                    @if($order->payment_gateway === 'MANUAL' && in_array($order->status, ['INVOICE_SENT', 'WAITING_VERIFICATION']))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                            
                            {{-- LEFT: Bank Details Card --}}
                            <div class="bg-[#FAFAFA] border border-gray-100 p-8 h-full flex flex-col justify-between">
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-6">Transfer To</p>
                                    <div class="mb-1">
                                        <p class="text-xs font-bold text-gray-900 uppercase tracking-wide">Bank Central Asia</p>
                                        <p class="text-xs text-gray-500">Virtual Account / Transfer</p>
                                    </div>
                                    <div class="py-6">
                                        <p class="text-2xl md:text-3xl font-serif text-gray-900 tracking-wide text-copy cursor-pointer" title="Click to copy">
                                            {{ config('services.bca.account_number', '8830-1234-5678') }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-2">{{ config('services.bca.account_name', 'WEBSITE PERHIASAN') }}</p>
                                    </div>
                                </div>
                                <div class="pt-6 border-t border-gray-200 mt-auto">
                                    <div class="flex justify-between items-end">
                                        <span class="text-xs text-gray-500 uppercase tracking-widest">Amount to Pay</span>
                                        <span class="text-lg font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT: Upload Form or Verification Status --}}
                            <div class="h-full flex flex-col">
                                @if($order->status === 'INVOICE_SENT')
                                    {{-- Rejection Warning --}}
                                    @if($order->rejection_notes)
                                    <div class="mb-6 p-4 bg-red-50 border border-red-100 flex items-start">
                                        <svg class="w-4 h-4 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wide text-red-900 mb-1">Upload Rejected</p>
                                            <p class="text-xs text-red-800 leading-relaxed">{{ $order->rejection_notes }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <form action="{{ route('order.track.upload', $order->guest_token) }}" method="POST" enctype="multipart/form-data" id="payment-form" class="flex-1 flex flex-col">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Upload Proof</label>
                                            <input type="file" 
                                                   id="proof_image_input"
                                                   name="proof_image" 
                                                   required 
                                                   accept="image/jpeg,image/png,image/jpg"
                                                   onchange="previewImage(event)" 
                                                   class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-xs file:font-semibold file:bg-[#E8B49E] file:text-white hover:file:bg-[#dcaea1] cursor-pointer bg-gray-50 border border-gray-200 p-2">
                                        </div>
                                        
                                        <div id="image-preview" class="hidden mb-4">
                                            <img id="preview-img" src="" class="max-h-40 border border-gray-200">
                                        </div>

                                        <button type="button" onclick="openConfirmModal()" class="cursor-pointer w-full bg-gray-900 hover:bg-gray-800 text-white text-xs uppercase tracking-[0.2em] py-4 transition-all">
                                            Confirm Payment
                                        </button>
                                    </form>

                                    {{-- Admin Note --}}

                                    @if($order->admin_notes)
                                    <div class="mt-4 text-xs text-purple-700 bg-purple-50 p-3 border border-purple-100">
                                        <span class="font-bold uppercase tracking-wider">Note:</span> {{ $order->admin_notes }}
                                    </div>
                                    @endif

                                @elseif($order->status === 'WAITING_VERIFICATION')
                                    <div class="h-full flex flex-col">
                                        {{-- Verification Status --}}
                                        <div class="bg-blue-50/50 border border-blue-100 p-6 text-center mb-6">
                                            <div class="animate-pulse inline-flex p-2 rounded-full bg-blue-100 text-blue-600 mb-3">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-widest mb-1">Verifying Payment</h3>
                                            <p class="text-[10px] text-gray-500">We are checking your submission.</p>
                                        </div>

                                        {{-- Uploaded Image Preview Card --}}
                                        @if($order->proof_of_payment)
                                        <div class="flex-1 relative group cursor-pointer border border-gray-200 overflow-hidden bg-gray-50" 
                                             onclick="openLightbox('{{ Storage::url($order->proof_of_payment) }}')">
                                            <img src="{{ Storage::url($order->proof_of_payment) }}" 
                                                 alt="Payment Proof" 
                                                 class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                                            
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center">
                                                <span class="opacity-0 group-hover:opacity-100 bg-white/90 backdrop-blur text-gray-900 text-[10px] uppercase tracking-widest px-4 py-2 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                                    View Proof
                                                </span>
                                            </div>
                                            
                                            {{-- Label Badge --}}
                                            <div class="absolute top-3 left-3 bg-gray-900 text-white text-[9px] uppercase tracking-widest px-2 py-1">
                                                Your Submission
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    
                    {{-- CASE 2: PAYPAL --}}
                    @elseif($order->payment_gateway === 'PAYPAL' && $order->status === 'INVOICE_SENT')
                        <div class="bg-[#FAFAFA] border border-gray-100 p-8 text-center max-w-lg mx-auto">
                            <p class="text-xs uppercase tracking-widest text-gray-500 mb-6">Total Payment</p>
                            <p class="text-3xl font-serif text-gray-900 mb-8">${{ number_format($order->total_amount, 2) }}</p>
                            
                            <a href="{{ route('order.track.paypal.checkout', $order->guest_token) }}" 
                               class="inline-block w-full bg-[#E8B49E] hover:bg-[#dcaea1] text-white text-xs uppercase tracking-[0.2em] py-4 px-8 transition-all">
                                Pay with PayPal
                            </a>
                            <p class="text-[10px] text-gray-400 mt-4 italic">Secure redirected to PayPal Checkout</p>

                            @if($order->admin_notes)
                            <div class="mt-6 p-4 bg-purple-50/50 border border-purple-100 text-left">
                                <p class="text-[10px] font-bold uppercase tracking-wide mb-2 text-purple-900">Note from Admin</p>
                                <p class="text-xs text-purple-800 whitespace-pre-wrap">{{ $order->admin_notes }}</p>
                            </div>
                            @endif
                        </div>

                    {{-- CASE 2B: PAYPAL PAID (NEW) --}}
                    @elseif($order->payment_gateway === 'PAYPAL' && $order->status === 'PAID')
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 p-10 text-center max-w-2xl mx-auto">
                            {{-- Success Icon --}}
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 mb-6">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            {{-- Heading --}}
                            <h3 class="text-xl font-serif text-gray-900 mb-3">Payment Confirmed</h3>
                            <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 mb-6">Transaction Successful</p>

                            {{-- Divider --}}
                            <div class="w-16 h-[1px] bg-emerald-200 mx-auto mb-6"></div>

                            {{-- Message --}}
                            <div class="max-w-md mx-auto space-y-3 mb-8">
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    Thank you for your payment via PayPal. Your transaction has been successfully completed.
                                </p>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    Our team will process your order shortly and begin preparing your jewellery. You will receive a notification once your order is being crafted.
                                </p>
                            </div>

                            {{-- Payment Details Card --}}
                            <div class="bg-white/60 backdrop-blur border border-emerald-100 p-6 max-w-sm mx-auto">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-[10px] uppercase tracking-widest text-gray-500">Amount Paid</span>
                                    <span class="text-lg font-serif text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-emerald-100">
                                    <span class="text-[10px] uppercase tracking-widest text-gray-500">Paid On</span>
                                    <span class="text-xs text-gray-900">{{ $order->paid_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        @if($order->admin_notes)
                        <div class="mt-6 p-4 bg-purple-50/50 border border-purple-100 text-center max-w-lg mx-auto">
                            <p class="text-[10px] font-bold uppercase tracking-wide mb-2 text-purple-900">Note from our Team</p>
                            <p class="text-xs text-purple-800 whitespace-pre-wrap">{{ $order->admin_notes }}</p>
                        </div>
                        @endif
                    
                    {{-- CASE 2C: PRODUCTION STATUS (BCA Manual that was approved) --}}
                    @elseif($order->status === 'PRODUCTION')
                        <div class="bg-gradient-to-br from-teal-50 to-cyan-50 border border-teal-100 p-10 text-center max-w-2xl mx-auto">
                            {{-- Production Icon --}}
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-teal-100 mb-6">
                                <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </div>

                            {{-- Heading --}}
                            <h3 class="text-xl font-serif text-gray-900 mb-3">In Production</h3>
                            <p class="text-xs uppercase tracking-[0.2em] text-teal-700 mb-6">Payment Verified - Order Being Prepared</p>

                            {{-- Divider --}}
                            <div class="w-16 h-[1px] bg-teal-200 mx-auto mb-6"></div>

                            {{-- Message --}}
                            <div class="max-w-md mx-auto space-y-3 mb-8">
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    Thank you for your payment. Your order has been verified and is now in production.
                                </p>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    Our artisans are carefully crafting your jewellery with meticulous attention to detail. You will receive a shipping notification once your order is ready and dispatched.
                                </p>
                            </div>

                            {{-- Payment Details Card --}}
                            <div class="bg-white/60 backdrop-blur border border-teal-100 p-6 max-w-sm mx-auto">
                                @php
                                    $decimals = $order->currency === 'USD' ? 2 : 0;
                                @endphp
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-[10px] uppercase tracking-widest text-gray-500">Amount Paid</span>
                                    <span class="text-lg font-serif text-gray-900">{{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($order->total_amount, $decimals, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-teal-100">
                                    <span class="text-[10px] uppercase tracking-widest text-gray-500">Verified On</span>
                                    <span class="text-xs text-gray-900">{{ $order->paid_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        @if($order->admin_notes)
                        <div class="mt-6 p-4 bg-purple-50/50 border border-purple-100 text-center max-w-lg mx-auto">
                            <p class="text-[10px] font-bold uppercase tracking-wide mb-2 text-purple-900">Note from our Team</p>
                            <p class="text-xs text-purple-800 whitespace-pre-wrap">{{ $order->admin_notes }}</p>
                        </div>
                        @endif
                    
                    {{-- CASE 3: SHIPPED (CONFIRM Order) --}}
                    @elseif($order->status === 'SHIPPED')
                        <div class="flex flex-col md:flex-row items-center justify-between bg-[#FAFAFA] border border-gray-100 p-8">
                            <div class="mb-6 md:mb-0">
                                <h3 class="text-sm font-serif text-gray-900 mb-2">Order Delivered?</h3>
                                <p class="text-xs text-gray-500 max-w-md">If you have received your package, please confirm delivery to complete this order.</p>
                            </div>
                            <form id="complete-order-form" action="{{ route('order.track.complete', $order->guest_token) }}" method="POST">
                                @csrf
                                <button type="button" onclick="confirmOrderCompletion()" class="cursor-pointer bg-gray-900 hover:bg-gray-800 text-white text-xs uppercase tracking-[0.2em] px-8 py-4 transition-all whitespace-nowrap">
                                    Confirm Order
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                @endif
                
                {{-- 4. Review Section (Only Completed) --}}
                @if($order->status === 'COMPLETED')
                <div class="pt-8 border-t border-gray-100">
                     <h2 class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-6">Review</h2>
                     
                     <div class="bg-[#FAFAFA] p-8 border border-gray-100 text-center">
                        @if($order->can_review && !$order->is_reviewed)
                            <h3 class="text-lg font-serif text-gray-900 mb-2">How was your jewellery?</h3>
                            <p class="text-xs text-gray-500 mb-6 uppercase tracking-widest">Share your experience with us</p>
                            <a href="{{ route('order.track.review', $order->guest_token) }}" class="inline-block border-b border-gray-900 pb-1 text-xs uppercase tracking-[0.2em] hover:text-gray-600 transition-colors">
                                Write a Review
                            </a>
                            <p class="text-[10px] text-yellow-600 mt-4">Deadline: {{ $order->completed_at->addDays(30)->format('M d, Y') }}</p>
                        @elseif($order->is_reviewed)
                            <div class="text-gray-400">
                                <svg class="w-8 h-8 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                <p class="text-xs uppercase tracking-widest">Thank you for your review</p>
                                @if($order->can_review)
                                <a href="{{ route('order.track.review.edit', $order->guest_token) }}" class="text-[10px] text-gray-500 underline mt-2 inline-block">Edit Review</a>
                                @endif
                            </div>
                        @else
                            <p class="text-xs text-gray-400 uppercase tracking-widest">Review period closed</p>
                        @endif
                     </div>
                </div>
                @endif

            </div>

            {{-- RIGHT COLUMN: Sticky Sidebar Timeline --}}
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-24">
                    <h2 class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-8 pb-4 border-b border-gray-100">
                        Order Timeline
                    </h2>

                    <div class="relative pl-2">
                        {{-- Vertical Line --}}
                        <div class="absolute left-[11px] top-2 bottom-0 w-[1px] bg-gray-200"></div>

                        <div class="space-y-10">
                            {{-- Step 1: Placed --}}
                            <div class="relative pl-8">
                                <div class="absolute left-[-4px] top-1.5 w-[15px] h-[15px] rounded-full border-2 border-white bg-gray-900 z-10"></div>
                                <p class="text-xs uppercase tracking-widest text-gray-900 font-bold mb-1">Placed</p>
                                <p class="text-[10px] text-gray-400">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>

                            {{-- Step 2: Payment --}}
                            <div class="relative pl-8">
                                <div class="absolute left-[-4px] top-1.5 w-[15px] h-[15px] rounded-full border-2 border-white {{ in_array($order->status, ['WAITING_VERIFICATION','PAID','PRODUCTION','SHIPPED','COMPLETED']) ? 'bg-gray-900' : 'bg-gray-200' }} z-10"></div>
                                <p class="text-xs uppercase tracking-widest {{ in_array($order->status, ['WAITING_VERIFICATION','PAID','PRODUCTION','SHIPPED','COMPLETED']) ? 'text-gray-900' : 'text-gray-400' }} font-medium mb-1">Payment</p>
                                @if($order->paid_at)
                                    <p class="text-[10px] text-gray-400">{{ $order->paid_at->format('M d, Y') }}</p>
                                @else
                                    <p class="text-[10px] text-gray-300 italic">Pending</p>
                                @endif
                            </div>

                            {{-- Step 3: Production --}}
                            <div class="relative pl-8">
                                <div class="absolute left-[-4px] top-1.5 w-[15px] h-[15px] rounded-full border-2 border-white {{ in_array($order->status, ['PRODUCTION','SHIPPED','COMPLETED']) ? 'bg-gray-900' : 'bg-gray-200' }} z-10"></div>
                                <p class="text-xs uppercase tracking-widest {{ in_array($order->status, ['PRODUCTION','SHIPPED','COMPLETED']) ? 'text-gray-900' : 'text-gray-400' }} font-medium mb-1">Production</p>
                                @if(in_array($order->status, ['PRODUCTION','SHIPPED','COMPLETED']))
                                    <p class="text-[10px] text-teal-600">Being Prepared</p>
                                @else
                                    <p class="text-[10px] text-gray-300 italic">Pending</p>
                                @endif
                            </div>

                            {{-- Step 4: Shipped --}}
                            <div class="relative pl-8">
                                <div class="absolute left-[-4px] top-1.5 w-[15px] h-[15px] rounded-full border-2 border-white {{ in_array($order->status, ['SHIPPED','COMPLETED']) ? 'bg-gray-900' : 'bg-gray-200' }} z-10"></div>
                                <p class="text-xs uppercase tracking-widest {{ in_array($order->status, ['SHIPPED','COMPLETED']) ? 'text-gray-900' : 'text-gray-400' }} font-medium mb-1">Shipped</p>
                                @if($order->shipment && $order->shipment->shipped_at)
                                    <p class="text-[10px] text-gray-400">{{ $order->shipment->shipped_at->format('M d, Y') }}</p>
                                @else
                                    <p class="text-[10px] text-gray-300 italic">Pending</p>
                                @endif
                            </div>

                            {{-- Step 5: Completed --}}
                            <div class="relative pl-8">
                                <div class="absolute left-[-4px] top-1.5 w-[15px] h-[15px] rounded-full border-2 border-white {{ $order->status === 'COMPLETED' ? 'bg-gray-900' : 'bg-gray-200' }} z-10"></div>
                                <p class="text-xs uppercase tracking-widest {{ $order->status === 'COMPLETED' ? 'text-gray-900' : 'text-gray-400' }} font-medium mb-1">Completed</p>
                                @if($order->status === 'COMPLETED')
                                    <p class="text-[10px] text-green-600">Delivered</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<x-image-lightbox modalId="image-modal" alt="Payment Proof" />


{{-- CONFIRMATION MODAL --}}
<div id="confirm-modal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white max-w-md w-full shadow-2xl transform transition-all duration-300 scale-95" id="modal-content">
        
        {{-- Modal Header --}}
        <div class="border-b border-gray-100 p-6 pb-4">
            <div class="flex items-center justify-center mb-3">
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-serif text-gray-900 text-center">Confirm Submission</h3>
            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 text-center mt-1">Payment Proof Verification</p>
        </div>

        {{-- Modal Body --}}
        <div class="p-6 space-y-4">
            <div class="bg-gray-50 border border-gray-100 p-4 space-y-3">
                <div class="flex items-start">
                    <svg class="w-4 h-4 text-gray-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-bold text-gray-900 uppercase tracking-wide mb-1">Please Verify</p>
                        <p class="text-xs text-gray-600 leading-relaxed">Ensure your payment proof image is clear and shows the complete transaction details.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <svg class="w-4 h-4 text-gray-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-bold text-gray-900 uppercase tracking-wide mb-1">Review Process</p>
                        <p class="text-xs text-gray-600 leading-relaxed">Your payment proof will be reviewed by our team within 24 hours. Once verified, your order will proceed to shipment preparation.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <svg class="w-4 h-4 text-gray-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-bold text-gray-900 uppercase tracking-wide mb-1">Important Note</p>
                        <p class="text-xs text-gray-600 leading-relaxed">If the proof is unclear or invalid, you will be notified to resubmit.</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <p class="text-[10px] text-gray-500 text-center uppercase tracking-widest">
                    Amount: <span class="font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </p>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="border-t border-gray-100 p-6 pt-4 flex gap-3">
            <button type="button" onclick="closeConfirmModal()" class="cursor-pointer flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs uppercase tracking-[0.2em] py-3 px-4 transition-all">
                Cancel
            </button>
            <button type="button" onclick="submitPaymentForm()" class="cursor-pointer flex-1 bg-gray-900 hover:bg-gray-800 text-white text-xs uppercase tracking-[0.2em] py-3 px-4 transition-all">
                Submit Proof
            </button>
        </div>

    </div>
</div>


<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        
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

    function openConfirmModal() {
        const form = document.getElementById('payment-form');
        const fileInput = document.getElementById('proof_image_input');
        
        // Trigger HTML5 validation
        if (!form.checkValidity()) {
            form.reportValidity(); // This will show browser's native validation message
            return;
        }
        
        // Double check file exists
        if (!fileInput.files || fileInput.files.length === 0) {
            fileInput.setCustomValidity('Please select a payment proof image.');
            fileInput.reportValidity();
            return;
        }
        
        // Reset custom validity if file exists
        fileInput.setCustomValidity('');

        const modal = document.getElementById('confirm-modal');
        const modalContent = document.getElementById('modal-content');
        
        modal.classList.remove('hidden');
        
        // Trigger animation
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirm-modal');
        const modalContent = document.getElementById('modal-content');
        
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function submitPaymentForm() {
        const form = document.getElementById('payment-form');
        
        // Final check before submit
        if (form.checkValidity()) {
            form.submit();
        } else {
            closeConfirmModal();
            form.reportValidity();
        }
    }

    // Close modal when clicking outside
    document.getElementById('confirm-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });

    // Confirm Order Completion
    function confirmOrderCompletion() {
        Swal.fire({
            title: 'Confirm Order Receipt?',
            text: "This will mark your order as completed. You'll be able to write a review within 30 days.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#9CA3AF',
            confirmButtonText: 'Yes, I received it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('complete-order-form').submit();
            }
        });
    }

    // Reset custom validity when file changes
    document.getElementById('proof_image_input')?.addEventListener('change', function() {
        this.setCustomValidity('');
    });
</script>
@endsection