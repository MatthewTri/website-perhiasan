@extends('layout.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('order.track', $order->guest_token) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Order Details
            </a>
            
            <h1 class="text-3xl font-bold text-gray-900">Review Your Purchase</h1>
            <p class="text-gray-600 mt-2">Order #{{ $order->order_number }}</p>
            <p class="text-sm text-yellow-600 mt-1">
                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                Review deadline: {{ $order->completed_at->addDays(30)->format('M d, Y') }}
            </p>
        </div>

        <!-- Review Form -->
        <form action="{{ route('order.track.review.store', $order->guest_token) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                @foreach($order->orderItems as $index => $item)
                @php
                    // ⬅️ CEK PER PRODUK: Apakah produk ini sudah direview di order ini?
                    $productReviewed = $order->reviews()->where('product_id', $item->product_id)->exists();
                @endphp

                <div class="bg-white rounded-lg shadow-md p-6 {{ $productReviewed ? 'opacity-60' : '' }}">
                    <div class="flex items-start space-x-4">
                        <!-- Product Image -->
                        <div class="flex-shrink-0 w-24 h-24">
                            @if($item->product && $item->product->featuredImage)
                                <img src="{{ asset('storage/' . $item->product->featuredImage->image) }}" 
                                     alt="{{ $item->item_name }}"
                                     class="w-full h-full object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info & Review Form -->
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $item->item_name }}</h3>
                            <p class="text-sm text-gray-500 mb-4">Quantity: {{ $item->quantity }}</p>

                            @if($productReviewed)
                                <!-- ⬅️ JIKA PRODUK INI SUDAH DIREVIEW -->
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-center text-green-800">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm font-medium">Already reviewed</span>
                                    </div>
                                </div>
                            @else
                                <!-- ⬅️ FORM REVIEW (BELUM DIREVIEW) -->
                                <input type="hidden" name="reviews[{{ $index }}][product_id]" value="{{ $item->product_id }}">

                                <!-- Star Rating -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Rating (Optional)
                                    </label>
                                    <div class="flex items-center space-x-1" data-rating-group="{{ $index }}">
                                        @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" 
                                               id="rating_{{ $index }}_{{ $i }}" 
                                               name="reviews[{{ $index }}][rating]" 
                                               value="{{ $i }}" 
                                               class="hidden rating-input"
                                               data-index="{{ $index }}">
                                        <label for="rating_{{ $index }}_{{ $i }}" 
                                               class="cursor-pointer rating-star transition-colors duration-150"
                                               data-value="{{ $i }}"
                                               data-index="{{ $index }}">
                                            <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </label>
                                        @endfor
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Click on stars to rate</p>
                                </div>

                                <!-- Comment -->
                                <div class="mb-4">
                                    <label for="comment_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        Your Review (Optional)
                                    </label>
                                    <textarea id="comment_{{ $index }}" 
                                              name="reviews[{{ $index }}][comment]" 
                                              rows="3"
                                              maxlength="1000"
                                              placeholder="Share your experience with this product..."
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
                                </div>

                                <!-- Image Upload -->
                                <div>
                                    <label for="image_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Photo (Optional)
                                    </label>
                                    <input type="file" 
                                           id="image_{{ $index }}" 
                                           name="reviews[{{ $index }}][image]"
                                           accept="image/jpeg,image/png,image/jpg"
                                           onchange="previewReviewImage(event, {{ $index }})"
                                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                    <p class="text-xs text-gray-500 mt-1">JPG, JPEG, or PNG (Max 5MB)</p>
                                    
                                    <!-- Image Preview -->
                                    <div id="preview_{{ $index }}" class="mt-3 hidden">
                                        <img id="preview_img_{{ $index }}" src="" alt="Preview" class="max-w-xs rounded-lg border border-gray-300">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('order.track', $order->guest_token) }}" class="text-gray-600 hover:text-gray-800">
                    Skip for now
                </a>
                
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Submit Reviews
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Star Rating Interactive
document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
        const value = parseInt(this.dataset.value);
        const index = this.dataset.index;
        const stars = document.querySelectorAll(`.rating-star[data-index="${index}"]`);
        
        // Update visual
        stars.forEach((s, i) => {
            const svg = s.querySelector('svg');
            if (i < value) {
                svg.classList.remove('text-gray-300');
                svg.classList.add('text-yellow-400');
            } else {
                svg.classList.remove('text-yellow-400');
                svg.classList.add('text-gray-300');
            }
        });
        
        // Set input value
        document.getElementById(`rating_${index}_${value}`).checked = true;
    });
    
    // Hover effect
    star.addEventListener('mouseenter', function() {
        const value = parseInt(this.dataset.value);
        const index = this.dataset.index;
        const stars = document.querySelectorAll(`.rating-star[data-index="${index}"]`);
        
        stars.forEach((s, i) => {
            const svg = s.querySelector('svg');
            if (i < value) {
                svg.classList.add('text-yellow-400');
            }
        });
    });
    
    star.addEventListener('mouseleave', function() {
        const index = this.dataset.index;
        const checkedInput = document.querySelector(`input[name="reviews[${index}][rating]"]:checked`);
        const checkedValue = checkedInput ? parseInt(checkedInput.value) : 0;
        const stars = document.querySelectorAll(`.rating-star[data-index="${index}"]`);
        
        stars.forEach((s, i) => {
            const svg = s.querySelector('svg');
            if (i < checkedValue) {
                svg.classList.add('text-yellow-400');
                svg.classList.remove('text-gray-300');
            } else {
                svg.classList.remove('text-yellow-400');
                svg.classList.add('text-gray-300');
            }
        });
    });
});

// Image Preview
function previewReviewImage(event, index) {
    const file = event.target.files[0];
    const preview = document.getElementById(`preview_${index}`);
    const previewImg = document.getElementById(`preview_img_${index}`);
    
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