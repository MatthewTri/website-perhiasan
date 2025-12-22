@extends('layout.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Your Reviews</h1>
            <p class="text-gray-600 mt-2">Order #{{ $order->order_number }}</p>
        </div>

        <!-- Review Form -->
        <form action="{{ route('order.track.review.update', $order->guest_token) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                @foreach($order->orderItems as $index => $item)
                @php
                    // Ambil review yang sudah ada untuk produk ini
                    $existingReview = $order->reviews()->where('product_id', $item->product_id)->first();
                @endphp

                @if($existingReview)
                <div class="bg-white rounded-lg shadow-md p-6">
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

                            <input type="hidden" name="reviews[{{ $index }}][review_id]" value="{{ $existingReview->id }}">

                            <!-- Star Rating -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Rating
                                </label>
                                <div class="flex items-center space-x-1" data-rating-group="{{ $index }}">
                                    @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" 
                                           id="rating_{{ $index }}_{{ $i }}" 
                                           name="reviews[{{ $index }}][rating]" 
                                           value="{{ $i }}" 
                                           {{ $existingReview->rating == $i ? 'checked' : '' }}
                                           class="hidden rating-input"
                                           data-index="{{ $index }}">
                                    <label for="rating_{{ $index }}_{{ $i }}" 
                                           class="cursor-pointer rating-star transition-colors duration-150"
                                           data-value="{{ $i }}"
                                           data-index="{{ $index }}">
                                        <svg class="w-8 h-8 {{ $i <= $existingReview->rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </label>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Click on stars to change rating</p>
                            </div>

                            <!-- Comment -->
                            <div class="mb-4">
                                <label for="comment_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Your Review
                                </label>
                                <textarea id="comment_{{ $index }}" 
                                          name="reviews[{{ $index }}][comment]" 
                                          rows="3"
                                          maxlength="1000"
                                          placeholder="Share your experience with this product..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ $existingReview->comment }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
                            </div>

                            <!-- Current Image -->
                            @if($existingReview->image)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Photo</label>
                                <div class="relative inline-block">
                                    <img src="{{ asset('storage/' . $existingReview->image) }}" alt="Review" class="max-w-xs rounded-lg border border-gray-300">
                                    <label class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full cursor-pointer shadow-lg">
                                        <input type="checkbox" name="reviews[{{ $index }}][delete_image]" value="1" class="hidden" onchange="toggleImageDelete(this, {{ $index }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Check the trash icon to remove this photo</p>
                            </div>
                            @endif

                            <!-- Upload New Image -->
                            <div>
                                <label for="image_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $existingReview->image ? 'Change Photo' : 'Upload Photo' }} (Optional)
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

                            @if($order->user_id)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="reviews[{{ $index }}][anonymous]" 
                                           value="1"
                                           {{ $existingReview->anonymous ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">
                                        Post as Anonymous
                                        <span class="text-gray-500">(Hide my name)</span>
                                    </span>
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('order.track', $order->guest_token) }}" class="text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
                
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Reviews
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

// Toggle Delete Image
function toggleImageDelete(checkbox, index) {
    const img = checkbox.closest('div').querySelector('img');
    if (checkbox.checked) {
        img.style.opacity = '0.3';
        img.style.filter = 'grayscale(100%)';
    } else {
        img.style.opacity = '1';
        img.style.filter = 'none';
    }
}
</script>
@endsection