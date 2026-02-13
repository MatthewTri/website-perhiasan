@extends('layout.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <!-- Header (UNCHANGED) -->
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
                {{-- ✅ LOOP GROUPED ITEMS (1x per product) --}}
                @foreach($groupedItems as $index => $item)
                @php
                    // Cek apakah produk ini sudah direview di order ini
                    $productReviewed = $order->reviews()->where('product_id', $item->product_id)->exists();
                @endphp

                <div class="bg-white rounded-lg shadow-md p-6 {{ $productReviewed ? 'opacity-60' : '' }}">
                    <div class="flex items-start space-x-4">
                        <!-- Product Image -->
                        <div class="flex-shrink-0 w-24 h-24">
                            @if($item->product && $item->product->featuredImage)
                                <img src="{{ asset('storage/' . $item->product->featuredImage->image) }}" 
                                     alt="{{ $item->product->name }}"
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $item->product->name }}</h3>
                            
                            {{-- ✅ DISPLAY VARIANTS (Info Only) --}}
                            @if(count($item->variants) > 0)
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @foreach($item->variants as $variant)
                                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-xs text-gray-700 rounded-md">
                                            @if($variant['color'])
                                                <svg class="w-3 h-3 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>
                                                </svg>
                                                {{ $variant['color'] }}
                                            @endif
                                            
                                            @if($variant['color'] && $variant['size'])
                                                <span class="mx-1 text-gray-400">•</span>
                                            @endif
                                            
                                            @if($variant['size'])
                                                <svg class="w-3 h-3 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                {{ $variant['size'] }}
                                            @endif
                                            
                                            <span class="ml-1 text-gray-500">×{{ $variant['quantity'] }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <p class="text-sm text-gray-500 mb-4">Total Quantity: {{ $item->total_quantity }}</p>

                            @if($productReviewed)
                                <!-- JIKA PRODUK INI SUDAH DIREVIEW -->
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-center text-green-800">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm font-medium">Already reviewed</span>
                                    </div>
                                </div>
                            @else
                                <!-- ✅ FORM REVIEW (1x PER PRODUCT) -->
                                <input type="hidden" name="reviews[{{ $index }}][product_id]" value="{{ $item->product_id }}">

                                <!-- Star Rating (UNCHANGED) -->
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

                                <!-- Comment (UNCHANGED) -->
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

                                <!-- Image Upload (Max 3) -->
                                <div>
                                    <label for="images_{{ $index }}" 
                                           class="block border-2 border-dashed border-gray-200 rounded-sm p-8 text-center cursor-pointer hover:border-gray-900 transition-all duration-300 bg-gray-50/30 hover:bg-gray-50"
                                           data-upload-area="{{ $index }}">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="text-sm font-medium text-gray-700 mb-1 uppercase tracking-wider">
                                                Upload Or Drag & Drop Images
                                            </p>
                                            <p class="text-xs text-gray-400">JPG, JPEG, PNG up to 5MB each (Max 3 images)</p>
                                        </div>
                                        <input type="file" 
                                               id="images_{{ $index }}" 
                                               name="reviews[{{ $index }}][images][]"
                                               accept="image/jpeg,image/png,image/jpg"
                                               onchange="handleReviewImages(this, {{ $index }})"
                                               multiple
                                               class="hidden">
                                    </label>
                                    
                                    <!-- Image Preview Grid -->
                                    <div id="previews_{{ $index }}" class="mt-4 grid grid-cols-3 gap-3 hidden"></div>
                                </div>

                                @if($order->user_id)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="reviews[{{ $index }}][anonymous]" 
                                               value="1"
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">
                                            Post as Anonymous
                                            <span class="text-gray-500">(Hide my name)</span>
                                        </span>
                                    </label>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Submit Button (UNCHANGED) -->
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

// Review Images Management
const reviewImages = {};

// Handle Review Images Upload
function handleReviewImages(input, index) {
    if (!reviewImages[index]) reviewImages[index] = [];
    
    const newFiles = Array.from(input.files);
    
    for (let newFile of newFiles) {
        // Validate file size (5MB)
        if (newFile.size > 5120 * 1024) {
            alert(`${newFile.name} exceeds 5MB limit`);
            continue;
        }
        
        // Check duplicates
        const isDuplicate = reviewImages[index].some(f => f.name === newFile.name && f.size === newFile.size);
        if (!isDuplicate && reviewImages[index].length < 3) {
            reviewImages[index].push(newFile);
        }
    }
    
    // Limit to 3 images
    if (reviewImages[index].length > 3) {
        alert('You can only upload up to 3 images');
        reviewImages[index] = reviewImages[index].slice(0, 3);
    }
    
    updateReviewPreview(index);
    updateFileInput(index);
}

// Update Preview Display
function updateReviewPreview(index) {
    const container = document.getElementById(`previews_${index}`);
    container.innerHTML = '';
    
    if (reviewImages[index] && reviewImages[index].length > 0) {
        container.classList.remove('hidden');
        
        reviewImages[index].forEach((file, fileIndex) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative group aspect-square bg-gray-100 rounded-sm overflow-hidden border border-gray-200';
            
            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 cursor-zoom-in';
            
            // Read and display image
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                // Add lightbox onclick
                img.onclick = function() {
                    if (typeof openLightbox === 'function') {
                        openLightbox(e.target.result);
                    }
                };
            };
            reader.readAsDataURL(file);
            
            wrapper.appendChild(img);
            
            // Remove button
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'absolute top-1 right-1 bg-white/90 text-gray-900 p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white hover:text-rose-600';
            btn.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
            btn.onclick = () => removeReviewImage(index, fileIndex);
            
            wrapper.appendChild(btn);
            container.appendChild(wrapper);
        });
    } else {
        container.classList.add('hidden');
    }
}

// Remove Image
function removeReviewImage(index, fileIndex) {
    reviewImages[index].splice(fileIndex, 1);
    updateReviewPreview(index);
    updateFileInput(index);
}

// Update File Input with Selected Files
function updateFileInput(index) {
    const input = document.getElementById(`images_${index}`);
    const dt = new DataTransfer();
    
    if (reviewImages[index]) {
        reviewImages[index].forEach(file => dt.items.add(file));
    }
    
    input.files = dt.files;
}

// Drag & Drop Handlers
document.addEventListener('DOMContentLoaded', function() {
    const uploadAreas = document.querySelectorAll('[data-upload-area]');
    
    uploadAreas.forEach(area => {
        const index = area.dataset.uploadArea;
        
        // Prevent default behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            area.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
            });
        });
        
        // Highlight on drag
        ['dragenter', 'dragover'].forEach(eventName => {
            area.addEventListener(eventName, function() {
                this.classList.add('border-gray-900', 'bg-gray-100');
            });
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            area.addEventListener(eventName, function() {
                this.classList.remove('border-gray-900', 'bg-gray-100');
            });
        });
        
        // Handle drop
        area.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            const input = document.getElementById(`images_${index}`);
            
            if (files.length > 0) {
                // Create FileList from dropped files
                const dt = new DataTransfer();
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        dt.items.add(file);
                    }
                });
                input.files = dt.files;
                
                // Trigger onchange
                handleReviewImages(input, index);
            }
        });
    });
});

// Mobile: Show remove buttons always on touch devices
if ('ontouchstart' in window) {
    const style = document.createElement('style');
    style.textContent = '.group button { opacity: 1 !important; }';
    document.head.appendChild(style);
}
</script>

@include('components.image-lightbox')

@endsection