<!-- filepath: c:\laragon\www\website-perhiasan\resources\views\admin\orders\show.blade.php -->
@extends('layout.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header with Back Button -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.orders.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Orders
                </a>
            </div>
            <div class="text-center flex-1">
                <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
                <p class="text-gray-600 mt-1">Review and process order #{{ $order->order_number }}</p>
            </div>
            
            <!-- Order Status Badge -->
            <div>
                @php
                $statusColors = [
                    'PENDING_REVIEW' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'INVOICE_SENT' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'PAID' => 'bg-green-100 text-green-800 border-green-200',
                    'SHIPPED' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                    'COMPLETED' => 'bg-purple-100 text-purple-800 border-purple-200',
                    'CANCELLED' => 'bg-red-100 text-red-800 border-red-200',
                ];
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold border-2 {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side - Order Information (2/3 width) -->
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address
                            </label>
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                                {{ $order->contact_email }}
                            </div>
                        </div>
                        
                        @if($order->user)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium text-green-800">Registered Member</span>
                            </div>
                        </div>
                        @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Guest Order</span>
                            </div>
                        </div>
                        @endif
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name
                            </label>
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                                {{ $order->shipping_name }}
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number
                            </label>
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                                {{ $order->shipping_phone }}
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Street Address
                            </label>
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 whitespace-pre-wrap">{{ $order->shipping_address_line1 }}</div>
                        </div>

                        <!-- Country -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Country
                            </label>
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                                {{ $order->shipping_country }}
                            </div>
                        </div>

                        <!-- Province, City, Postal Code -->
                        <div class="space-y-4">
                            @if($order->shipping_province)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Province / State
                                </label>
                                <div class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                                    {{ $order->shipping_province }}
                                </div>
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        City
                                    </label>
                                    <div class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                                        {{ $order->shipping_city }}
                                    </div>
                                </div>
                                @if($order->shipping_postal_code)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Postal Code
                                    </label>
                                    <div class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                                        {{ $order->shipping_postal_code }}
                                    </div>
                                </div>
                                @endif
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
                        Payment Information
                    </h2>
                    
                    <div class="space-y-4">
                        <!-- Customer's Payment Preference -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Customer's Payment Preference
                            </label>
                            <div class="p-4 border-2 border-blue-200 bg-blue-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        @if($order->payment_preference === 'MANUAL')
                                        <p class="text-base font-semibold text-gray-900">BCA Bank Transfer (Indonesia)</p>
                                        <p class="text-sm text-gray-600">Manual bank transfer to BCA account</p>
                                        @else
                                        <p class="text-base font-semibold text-gray-900">PayPal (International)</p>
                                        <p class="text-sm text-gray-600">PayPal invoice payment</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">Currency: <span class="font-medium">{{ $order->currency }}</span></p>
                                    </div>
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Gateway (if invoice created) -->
                        @if($order->payment_gateway)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Payment Method Used
                            </label>
                            <div class="p-4 border border-gray-300 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        @if($order->payment_gateway === 'MANUAL')
                                        <p class="text-base font-medium text-gray-900">BCA Bank Transfer</p>
                                        <p class="text-sm text-gray-500">Account: {{ config('services.bca.account_number') ?? 'XXX-XXXX-XXXX' }}</p>
                                        <p class="text-sm text-gray-500">Name: {{ config('services.bca.account_name') ?? 'Your Company' }}</p>
                                        @else
                                        <p class="text-base font-medium text-gray-900">PayPal</p>
                                        @if($order->payment_url)
                                        <a href="{{ $order->payment_url }}" target="_blank" class="text-sm text-blue-600 hover:underline mt-1 inline-block">
                                            View PayPal Invoice →
                                        </a>
                                        @endif
                                        @endif
                                    </div>
                                    @if($order->status === 'INVOICE_SENT')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                        Payment Details Sent
                                    </span>
                                    @elseif($order->status === 'PAID')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                        Paid
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Admin Notes - Display (after invoice created) -->
                        @if($order->admin_notes && $order->status !== 'PENDING_REVIEW')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Admin Notes for Customer
                            </label>
                            <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $order->admin_notes }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Change Payment Gateway (Only if PENDING_REVIEW) -->
                        @if($order->status === 'PENDING_REVIEW')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-yellow-800 mb-3">
                                        Customer prefers {{ $order->payment_preference }}. Change payment method if customer requests.
                                    </p>
                                    <form action="{{ route('admin.orders.sendPaymentDetails', $order) }}" method="POST" id="invoice-form">
                                        @csrf
                                        
                                        <!-- Payment Method Selection -->
                                        <div class="space-y-2 mb-4">
                                            <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-all">
                                                <input type="radio" 
                                                    name="payment_gateway_override" 
                                                    value="MANUAL"
                                                    {{ $order->payment_preference === 'MANUAL' ? 'checked' : '' }}
                                                    class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                                <span class="ml-3 text-sm font-medium text-gray-900">BCA Bank Transfer (Indonesia)</span>
                                            </label>
                                            
                                            <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-all">
                                                <input type="radio" 
                                                    name="payment_gateway_override" 
                                                    value="PAYPAL"
                                                    {{ $order->payment_preference === 'PAYPAL' ? 'checked' : '' }}
                                                    class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                                <span class="ml-3 text-sm font-medium text-gray-900">PayPal (International)</span>
                                            </label>
                                        </div>

                                        <!-- Admin Notes -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Notes for Customer (Optional)
                                            </label>
                                            <textarea 
                                                name="admin_notes" 
                                                rows="3"
                                                placeholder="e.g., Thank you for your order! Payment details will be sent to your email."
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                                            <p class="text-xs text-gray-500 mt-1">This note will be included in the payment email.</p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Customer's Notes -->
                @if($order->order_notes)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Customer's Notes
                    </h2>
                    
                    <div class="w-full px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-lg text-gray-900 whitespace-pre-wrap">{{ $order->order_notes }}</div>
                </div>
                @endif
            </div>

            <!-- Right Side - Order Summary (1/3 width) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                    
                    <!-- Order Info -->
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Order Number</span>
                            <span class="font-medium text-gray-900">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Order Date</span>
                            <span class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Order Time</span>
                            <span class="font-medium text-gray-900">{{ $order->created_at->format('g:i A') }}</span>
                        </div>
                    </div>
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6 max-h-60 overflow-y-auto">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-start space-x-4 pt-2 pb-4 border-b border-gray-200">
                            <div class="flex-shrink-0 w-16 h-16 relative">
                                @if($item->product && $item->product->featuredImage)
                                    <img src="{{ asset('storage/' . $item->product->featuredImage->image) }}" 
                                         alt="{{ $item->item_name }}"
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
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->item_name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->currency }} {{ number_format($item->price, 2) }}</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $order->currency }} {{ number_format($item->price * $item->quantity, 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">{{ $order->currency }} {{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-gray-900">
                                @if($order->shipping_cost > 0)
                                    {{ $order->currency }} {{ number_format($order->shipping_cost, 2) }}
                                @else
                                    <span class="text-blue-600">To be calculated</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium text-gray-900">{{ $order->currency }} {{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between">
                                <span class="text-base font-semibold text-gray-900">Total</span>
                                <span class="text-xl font-bold text-gray-900">{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($order->status === 'PENDING_REVIEW')
                    <div class="space-y-3">
                        <!-- ⬅️ HAPUS <form>, GUNAKAN button dengan form="invoice-form" -->
                        <button type="submit" 
                                form="invoice-form"
                                onclick="return confirm('Are you sure you want to create invoice for this order?')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Create Invoice
                        </button>
                        
                        <button type="button" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel Order
                        </button>
                    </div>
                    @elseif($order->status === 'PAID')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-green-800">Payment Received</span>
                        </div>
                    </div>
                    
                    <button type="button" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Mark as Shipped
                    </button>
                    @elseif($order->status === 'SHIPPED')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                            </svg>
                            <span class="text-sm font-medium text-blue-800">Order Shipped</span>
                        </div>
                        @if($order->courier_name && $order->tracking_number)
                        <p class="text-xs text-blue-700">Courier: {{ $order->courier_name }}</p>
                        <p class="text-xs text-blue-700">Tracking: {{ $order->tracking_number }}</p>
                        @endif
                    </div>
                    
                    <button type="button" 
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mark as Completed
                    </button>
                    @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600 text-center">Order status: <span class="font-medium">{{ str_replace('_', ' ', $order->status) }}</span></p>
                    </div>
                    @endif

                    <!-- Security Note -->
                    <div class="mt-4 flex items-start space-x-2 text-xs text-gray-500">
                        <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p>All customer information is confidential</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection