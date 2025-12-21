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
                    @php
                        // ⬅️ HITUNG HARGA & SUBTOTAL SESUAI CURRENCY ORDER
                        $price = $order->currency === 'USD' ? $item->price_usd : $item->price_idr;
                        $subtotal = $price * $item->quantity;
                        $decimals = $order->currency === 'USD' ? 2 : 0;
                    @endphp
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
                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × {{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($price, $decimals, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">
                                {{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($subtotal, $decimals, ',', '.') }}
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
                        @php
                            $decimals = $order->currency === 'USD' ? 2 : 0;
                        @endphp
                        <p class="text-xl font-bold text-blue-600">
                            {{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($order->total_amount, $decimals, ',', '.') }}
                        </p>
                    </div>
                    <a href="{{ route('orders.show', $order->slug) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                        View Details
                    </a>
                </div>

                <!-- Quick Actions -->
                @if($order->status === 'INVOICE_SENT')
                <div class="mt-4 pt-4 border-t">
                    @if($order->payment_gateway === 'MANUAL')
                    <a href="{{ route('order.track', $order->guest_token) }}" 
                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload Payment Proof
                    </a>
                    @elseif($order->payment_gateway === 'PAYPAL')
                    <a href="{{ route('order.track.paypal.checkout', $order->guest_token) }}" 
                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.93 4.778-4.005 7.201-9.138 7.201h-2.19a.563.563 0 0 0-.556.479l-1.187 7.527h-.506l-.24 1.516a.56.56 0 0 0 .554.647h3.882c.46 0 .85-.334.922-.788.06-.26.76-4.852.76-4.852a.932.932 0 0 1 .922-.788h.58c3.76 0 6.705-1.528 7.565-5.946.36-1.847.174-3.388-.777-4.471z"/>
                        </svg>
                        Pay with PayPal
                    </a>
                    @endif
                </div>
                @elseif($order->status === 'WAITING_VERIFICATION')
                <div class="mt-4 pt-4 border-t bg-purple-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-purple-900">Payment Proof Submitted</p>
                            <p class="text-xs text-purple-700">Admin will verify within 24 hours</p>
                        </div>
                    </div>
                </div>
                @elseif($order->status === 'SHIPPED')
                <div class="mt-4 pt-4 border-t bg-blue-50 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Order Shipped!</p>
                                @if($order->tracking_number)
                                <p class="text-xs text-blue-700">{{ $order->courier_name }}: {{ $order->tracking_number }}</p>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('order.track.complete', $order->guest_token) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Have you received your order?')"
                                    class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                Order Received
                            </button>
                        </form>
                    </div>
                </div>
                @elseif($order->status === 'COMPLETED')
                <div class="mt-4 pt-4 border-t bg-green-50 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm font-medium text-green-900">Order Completed!</p>
                        </div>
                        @if($order->can_review && !$order->is_reviewed)
                        <a href="{{ route('order.track.review', $order->guest_token) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Write Review
                        </a>
                        @elseif($order->is_reviewed)
                        <span class="text-sm text-gray-600">Reviewed ✓</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection