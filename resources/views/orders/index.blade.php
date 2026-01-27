@extends('layout.main')

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- Header Section (Consistent with Cart) --}}
    <div class="pt-16 pb-12 text-center border-b border-gray-100">
        <h1 class="text-2xl md:text-3xl font-serif text-gray-900 uppercase tracking-[0.2em]">Order History</h1>
        <p class="mt-3 text-xs text-gray-500 uppercase tracking-widest font-light">
            Track and manage your purchases
        </p>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 md:px-6 py-12">
        
        @if($orders->isEmpty())
            {{-- Empty State --}}
            <div class="text-center py-20 border border-gray-100 bg-[#FAFAFA]">
                <h3 class="text-xl font-serif text-gray-900 mb-2">No Orders Found</h3>
                <p class="text-xs text-gray-500 uppercase tracking-widest mb-8">You haven't placed any orders yet.</p>
                <a href="{{ route('products.index') }}" class="inline-block border-b border-gray-900 pb-1 text-xs uppercase tracking-[0.2em] font-medium hover:text-gray-600 transition-colors">
                    Start Shopping
                </a>
            </div>
        @else
            <div class="space-y-16">
                @foreach($orders as $order)
                <div class="group">
                    {{-- Order Header Info --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-12 mb-4 md:mb-0">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Order Number</p>
                                <p class="text-sm font-medium text-gray-900 tracking-wide">#{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Date</p>
                                <p class="text-sm text-gray-900">{{ $order->created_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Status</p>
                                
                                {{-- Elegant Status Indicator --}}
                                @php
                                    $statusConfig = [
                                        'PENDING_REVIEW' => ['color' => 'text-yellow-600', 'label' => 'Pending Review'],
                                        'INVOICE_SENT' => ['color' => 'text-gray-900', 'label' => 'Payment Required'],
                                        'WAITING_VERIFICATION' => ['color' => 'text-purple-600', 'label' => 'Verifying Payment'],
                                        'PAID' => ['color' => 'text-emerald-700', 'label' => 'Paid'],
                                        'SHIPPED' => ['color' => 'text-blue-600', 'label' => 'In Transit'],
                                        'COMPLETED' => ['color' => 'text-gray-900', 'label' => 'Completed'],
                                        'CANCELLED' => ['color' => 'text-red-600', 'label' => 'Cancelled'],
                                    ];
                                    $status = $statusConfig[$order->status] ?? ['color' => 'text-gray-500', 'label' => $order->status];
                                @endphp
                                <div class="flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full {{ str_replace('text-', 'bg-', $status['color']) }} mr-2"></span>
                                    <span class="text-xs font-medium uppercase tracking-[0.1em] {{ $status['color'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Total & Detail Link --}}
                        <div class="text-left md:text-right">
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Total Amount</p>
                            <p class="text-lg font-serif text-gray-900">
                                @if($order->currency === 'USD')
                                    ${{ number_format($order->total_amount, 2) }}
                                @else
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Products Preview Grid --}}
                    <div class="py-8 grid grid-cols-1 lg:grid-cols-4 gap-8">
                        {{-- Items Section --}}
                        <div class="lg:col-span-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($order->orderItems->take(3) as $item)
                                <div class="flex items-start space-x-4">
                                    <div class="w-20 h-20 bg-[#F7F7F7] flex-shrink-0 border border-gray-100">
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
                                    <div class="flex-1 min-w-0 py-1">
                                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider truncate border-b border-transparent group-hover:border-gray-900 transition-colors inline-block mb-1">
                                            {{ $item->item_name }}
                                        </h4>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-wide">
                                            Qty: {{ $item->quantity }}
                                        </p>
                                        
                                        {{-- Variant Info --}}
                                        @if($item->color || $item->size)
                                            <p class="text-[10px] text-gray-400 mt-1">
                                                {{ implode(' / ', array_filter([$item->color, $item->size])) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            @if($order->orderItems->count() > 3)
                            <p class="mt-4 text-[10px] text-gray-400 uppercase tracking-widest pl-1">
                                + {{ $order->orderItems->count() - 3 }} more item(s)
                            </p>
                            @endif
                        </div>

                        {{-- Actions Section --}}
                        <div class="lg:col-span-1 flex flex-col justify-center space-y-4 lg:border-l lg:border-gray-100 lg:pl-8">
                            
                            <a href="{{ route('order.track', $order->guest_token) }}" 
                               class="w-full text-center bg-gray-900 hover:bg-gray-800 text-white text-[10px] uppercase tracking-[0.2em] font-medium py-3 px-4 transition-all duration-300">
                                View Details
                            </a>

                            {{-- Dynamic Action Buttons --}}
                            @if($order->status === 'INVOICE_SENT')
                                @if($order->rejection_notes)
                                    <div class="p-3 border border-red-200 bg-red-50/50 text-center">
                                        <p class="text-[10px] text-red-800 font-medium uppercase tracking-wide mb-2">Proof Rejected</p>
                                        <a href="{{ route('order.track', $order->guest_token) }}" class="text-[10px] text-red-600 underline hover:text-red-800">Re-upload</a>
                                    </div>
                                @elseif($order->payment_gateway === 'MANUAL')
                                    <a href="{{ route('order.track', $order->guest_token) }}" 
                                       class="w-full text-center border border-[#E8B49E] text-[#E8B49E] hover:bg-[#E8B49E] hover:text-white text-[10px] uppercase tracking-[0.2em] font-medium py-3 px-4 transition-all duration-300">
                                        Confirm Payment
                                    </a>
                                @elseif($order->payment_gateway === 'PAYPAL')
                                    <a href="{{ route('order.track.paypal.checkout', $order->guest_token) }}" 
                                       class="w-full text-center bg-[#E8B49E] hover:bg-[#dcaea1] text-white text-[10px] uppercase tracking-[0.2em] font-medium py-3 px-4 transition-all duration-300">
                                        Pay with PayPal
                                    </a>
                                @endif
                            @elseif($order->status === 'SHIPPED')
                                <form action="{{ route('order.track.complete', $order->guest_token) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Confirm receipt of this order?')"
                                            class="w-full text-center bg-[#E8B49E] hover:bg-[#dcaea1] text-white text-[10px] uppercase tracking-[0.2em] font-medium py-3 px-4 transition-all duration-300">
                                        Receive Order
                                    </button>
                                </form>
                            @elseif($order->status === 'COMPLETED' && $order->can_review && !$order->is_reviewed)
                                <a href="{{ route('order.track.review', $order->guest_token) }}" 
                                   class="w-full text-center border border-gray-200 hover:border-gray-900 text-gray-500 hover:text-gray-900 text-[10px] uppercase tracking-[0.2em] font-medium py-3 px-4 transition-all duration-300">
                                    Write Review
                                </a>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection