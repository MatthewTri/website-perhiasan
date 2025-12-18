@extends('layout.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="text-gray-600 mt-2">View and track all your orders</p>
        </div>

        <!-- Orders List -->
        @if($orders->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No orders yet</h3>
            <p class="text-gray-600 mb-6">Start shopping to create your first order!</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-200">
                Browse Products
            </a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    
                    <!-- Status Badge -->
                    @php
                        $statusConfig = [
                            'PENDING_REVIEW' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending Review'],
                            'INVOICE_SENT' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Payment Pending'],
                            'WAITING_VERIFICATION' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Verifying Payment'],
                            'PAID' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Paid'],
                            'SHIPPED' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'label' => 'Shipped'],
                            'COMPLETED' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Completed'],
                            'CANCELLED' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Cancelled'],
                        ];
                        $status = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $order->status];
                    @endphp
                    <span class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} text-sm font-semibold rounded-full">
                        {{ $status['label'] }}
                    </span>
                </div>

                <!-- Order Items Preview (first 3 items) -->
                <div class="space-y-3 mb-4">
                    @foreach($order->orderItems->take(3) as $item)
                    <div class="flex items-center space-x-4">
                        @if($item->product && $item->product->images->first())
                        <img src="{{ Storage::url($item->product->images->first()->image) }}" 
                             alt="{{ $item->item_name }}"
                             class="w-16 h-16 object-cover rounded-lg">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $item->item_name }}</h4>
                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">
                                {{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($item->subtotal, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($order->orderItems->count() > 3)
                    <p class="text-sm text-gray-500 text-center">
                        + {{ $order->orderItems->count() - 3 }} more item(s)
                    </p>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="flex items-center justify-between pt-4 border-t">
                    <div>
                        <p class="text-sm text-gray-600">Total Amount</p>
                        <p class="text-xl font-bold text-blue-600">
                            {{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($order->total_amount, 2, ',', '.') }}
                        </p>
                    </div>
                    <a href="{{ route('orders.show', $order->slug) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                        View Details
                    </a>
                </div>

                <!-- Quick Actions -->
                @if($order->status === 'INVOICE_SENT' && $order->payment_preference === 'MANUAL')
                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('order.track', $order->guest_token) }}" 
                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload Payment Proof
                    </a>
                </div>
                @elseif($order->status === 'SHIPPED')
                <div class="mt-4 pt-4 border-t bg-blue-50 rounded-lg p-3">
                    <p class="text-sm font-medium text-blue-900">Tracking Info:</p>
                    <p class="text-sm text-blue-800">{{ $order->courier_name }}: <span class="font-semibold">{{ $order->tracking_number }}</span></p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection