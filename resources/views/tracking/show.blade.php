@extends('layout.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <!-- Success/Error Messages -->
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

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                
                <!-- Status Badge -->
                <div>
                    @php
                        $statusConfig = [
                            'PENDING_REVIEW' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending Review'],
                            'INVOICE_SENT' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Payment Details Sent'],
                            'WAITING_VERIFICATION' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Waiting Verification'],
                            'PAID' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Paid'],
                            'SHIPPED' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'label' => 'Shipped'],
                            'COMPLETED' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Completed'],
                            'CANCELLED' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Cancelled'],
                        ];
                        $status = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $order->status];
                    @endphp
                    <span class="px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} text-sm font-semibold rounded-full">
                        {{ $status['label'] }}
                    </span>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="relative">
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                
                <div class="space-y-6">
                    <!-- Step 1: Order Placed -->
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $order->created_at ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center z-10">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Order Placed</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <!-- Step 2: Payment Details Sent -->
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ in_array($order->status, ['INVOICE_SENT', 'WAITING_VERIFICATION', 'PAID', 'SHIPPED', 'COMPLETED']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center z-10">
                            @if(in_array($order->status, ['INVOICE_SENT', 'WAITING_VERIFICATION', 'PAID', 'SHIPPED', 'COMPLETED']))
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Payment Details Sent</p>
                            @if(in_array($order->status, ['INVOICE_SENT', 'WAITING_VERIFICATION', 'PAID', 'SHIPPED', 'COMPLETED']))
                            <p class="text-xs text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                            @else
                            <p class="text-xs text-gray-500">Waiting for admin review</p>
                            @endif
                        </div>
                    </div>

                    <!-- Step 3: Payment Confirmed -->
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ in_array($order->status, ['PAID', 'SHIPPED', 'COMPLETED']) ? 'bg-green-500' : ($order->status === 'WAITING_VERIFICATION' ? 'bg-yellow-400' : 'bg-gray-300') }} flex items-center justify-center z-10">
                            @if(in_array($order->status, ['PAID', 'SHIPPED', 'COMPLETED']))
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <div class="w-2 h-2 {{ $order->status === 'WAITING_VERIFICATION' ? 'bg-yellow-600' : 'bg-gray-400' }} rounded-full"></div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Payment Confirmed</p>
                            @if($order->paid_at)
                            <p class="text-xs text-gray-500">{{ $order->paid_at->format('d M Y, H:i') }}</p>
                            @elseif($order->status === 'WAITING_VERIFICATION')
                            <p class="text-xs text-yellow-600">Waiting admin verification</p>
                            @else
                            <p class="text-xs text-gray-500">Waiting for payment</p>
                            @endif
                        </div>
                    </div>

                    <!-- Step 4: Shipped -->
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ in_array($order->status, ['SHIPPED', 'COMPLETED']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center z-10">
                            @if(in_array($order->status, ['SHIPPED', 'COMPLETED']))
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Shipped</p>
                            @if($order->tracking_number)
                            <p class="text-xs text-gray-500">{{ $order->courier_name }}: {{ $order->tracking_number }}</p>
                            @else
                            <p class="text-xs text-gray-500">Waiting for shipment</p>
                            @endif
                        </div>
                    </div>

                    <!-- Step 5: Completed -->
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $order->status === 'COMPLETED' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center z-10">
                            @if($order->status === 'COMPLETED')
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Order Completed</p>
                            <p class="text-xs text-gray-500">Order delivered successfully</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>
            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                <div class="flex items-center space-x-4 pb-4 border-b last:border-b-0">
                    @if($item->product && $item->product->images->first())
                    <img src="{{ Storage::url($item->product->images->first()->image) }}" 
                         alt="{{ $item->item_name }}"
                         class="w-20 h-20 object-cover rounded-lg">
                    @else
                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ $item->item_name }}</h3>
                        <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $price = $order->currency === 'USD' ? $item->price_usd : $item->price_idr;
                            $subtotal = $price * $item->quantity;
                            $decimals = $order->currency === 'USD' ? 2 : 0;
                        @endphp
                        <p class="font-semibold text-gray-900">
                            {{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($price, $decimals, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Subtotal: {{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($subtotal, $decimals, ',', '.') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="mt-6 pt-4 border-t space-y-2">
                @php
                    $decimals = $order->currency === 'USD' ? 2 : 0;
                    $subtotal = $order->currency === 'USD' ? $order->subtotal_usd : $order->subtotal_idr;
                @endphp
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="text-gray-900">{{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($subtotal, $decimals, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Shipping Cost:</span>
                    <span class="text-gray-900">{{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($order->shipping_cost, $decimals, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold pt-2 border-t">
                    <span class="text-gray-900">Total:</span>
                    <span class="text-blue-600">{{ $order->currency === 'USD' ? '$' : 'Rp ' }}{{ number_format($order->total_amount, $decimals, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Shipping Information</h2>
            <div class="space-y-2 text-sm">
                <div class="flex">
                    <span class="text-gray-600 w-32">Name:</span>
                    <span class="text-gray-900 font-medium">{{ $order->shipping_name }}</span>
                </div>
                <div class="flex">
                    <span class="text-gray-600 w-32">Phone:</span>
                    <span class="text-gray-900">{{ $order->shipping_phone }}</span>
                </div>
                <div class="flex">
                    <span class="text-gray-600 w-32">Address:</span>
                    <span class="text-gray-900">{{ $order->shipping_address_line1 }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}, {{ $order->shipping_country }}</span>
                </div>
                @if($order->tracking_number)
                <div class="flex pt-2 border-t mt-2">
                    <span class="text-gray-600 w-32">Courier:</span>
                    <span class="text-gray-900 font-medium">{{ $order->courier_name }}</span>
                </div>
                <div class="flex">
                    <span class="text-gray-600 w-32">Tracking:</span>
                    <span class="text-blue-600 font-medium">{{ $order->tracking_number }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Section -->
        @if($order->status === 'INVOICE_SENT' || $order->status === 'WAITING_VERIFICATION')
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Information</h2>

            @if($order->payment_gateway === 'MANUAL')
                <!-- BCA Transfer Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h3 class="font-semibold text-blue-900 mb-3">BCA Bank Transfer</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Bank Name:</span>
                            <span class="font-medium text-gray-900">BCA (Bank Central Asia)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Account Number:</span>
                            <span class="font-medium text-gray-900">{{ config('services.bca.account_number', 'XXX-XXXX-XXXX') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Account Name:</span>
                            <span class="font-medium text-gray-900">{{ config('services.bca.account_name', 'Your Company') }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-2 mt-2">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-bold text-lg text-blue-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($order->admin_notes)
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                    <h3 class="font-semibold text-purple-900 mb-2">Note from Admin:</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $order->admin_notes }}</p>
                </div>
                @endif

                <!-- Upload Proof Form -->
                @if($order->status === 'INVOICE_SENT')
                <form action="{{ route('order.track.upload', $order->guest_token) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Proof of Payment
                        </label>
                        <input type="file" 
                               name="proof_image" 
                               accept="image/jpeg,image/png,image/jpg"
                               required
                               onchange="previewImage(event)"
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">JPG, JPEG, or PNG (Max 5MB)</p>
                        @error('proof_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-4 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <img id="preview-img" src="" alt="Payment Proof Preview" class="max-w-md rounded-lg border border-gray-300">
                    </div>
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        Submit Payment Proof
                    </button>
                </form>
                @elseif($order->status === 'WAITING_VERIFICATION')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-green-900">Payment proof submitted successfully!</p>
                            <p class="text-xs text-green-700 mt-1">Admin will verify your payment within 24 hours.</p>
                        </div>
                    </div>
                    
                    @if($order->proof_of_payment)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Your uploaded proof:</p>
                        <img src="{{ Storage::url($order->proof_of_payment) }}" alt="Payment Proof" class="max-w-md rounded-lg border">
                    </div>
                    @endif
                </div>
                @endif

            @else
            <!-- PAYPAL: ON-DEMAND CHECKOUT BUTTON -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <h3 class="font-semibold text-blue-900 mb-3">PayPal Payment</h3>
                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-bold text-lg text-blue-900">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
                
                <a href="{{ route('order.track.paypal.checkout', $order->guest_token) }}" 
                class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 text-center">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.93 4.778-4.005 7.201-9.138 7.201h-2.19a.563.563 0 0 0-.556.479l-1.187 7.527h-.506l-.24 1.516a.56.56 0 0 0 .554.647h3.882c.46 0 .85-.334.922-.788.06-.26.76-4.852.76-4.852a.932.932 0 0 1 .922-.788h.58c3.76 0 6.705-1.528 7.565-5.946.36-1.847.174-3.388-.777-4.471z"/>
                    </svg>
                    Pay with PayPal
                </a>
                
                <div class="mt-3 bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                    <p class="text-xs text-yellow-800">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <strong>Note:</strong> PayPal checkout link will be created when you click the button above and is valid for 3 hours.
                    </p>
                </div>
            </div>

            @if($order->admin_notes)
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <h3 class="font-semibold text-purple-900 mb-2">Note from Admin:</h3>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $order->admin_notes }}</p>
            </div>
            @endif
        @endif
        </div>
        @endif
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
</script>
@endsection