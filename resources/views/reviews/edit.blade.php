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

                            <!-- Current Images -->
                            @if($existingReview->images->count() > 0)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Current Photos ({{ $existingReview->images->count() }}/3)
                                </label>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach($existingReview->images as $reviewImage)
                                    <div class="relative group aspect-square bg-gray-100 rounded-sm overflow-hidden border border-gray-200" 
                                         id="existing_image_{{ $reviewImage->id }}">
                                        <img src="{{ asset('storage/' . $reviewImage->image) }}" 
                                             alt="Review" 
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 cursor-zoom-in"
                                             onclick="openLightbox('{{ asset('storage/' . $reviewImage->image) }}')">
                                        
                                        <label class="absolute top-1 right-1 bg-white/90 text-gray-900 p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white hover:text-rose-600 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="reviews[{{ $index }}][delete_images][]" 
                                                   value="{{ $reviewImage->id }}" 
                                                   class="hidden" 
                                                   onchange="toggleImageDelete(this, '{{ $reviewImage->id }}')">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Click images to view larger, X to remove</p>
                            </div>
                            @endif

                            <!-- Upload New Images -->
                            <div>
                                @php
                                    $currentCount = $existingReview->images->count();
                                    $remainingSlots = 3 - $currentCount;
                                @endphp
                                
                                @if($remainingSlots > 0)
                                <label for="images_{{ $index }}" 
                                       class="block border-2 border-dashed border-gray-200 rounded-sm p-8 text-center cursor-pointer hover:border-gray-900 transition-all duration-300 bg-gray-50/30 hover:bg-gray-50"
                                       data-edit-upload-area="{{ $index }}">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-700 mb-1 uppercase tracking-wider">
                                            Add More Photos
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $remainingSlots }} slot{{ $remainingSlots > 1 ? 's' : '' }} available (Max 5MB each)</p>
                                    </div>
                                    <input type="file" 
                                           id="images_{{ $index }}" 
                                           name="reviews[{{ $index }}][images][]"
                                           accept="image/jpeg,image/png,image/jpg"
                                           onchange="handleEditReviewImages(this, {{ $index }}, {{ $remainingSlots }})"
                                           multiple
                                           class="hidden"
                                           data-max-slots="{{ $remainingSlots }}">
                                </label>
                                
                                <!-- New Images Preview -->
                                <div id="new_previews_{{ $index }}" class="mt-4 grid grid-cols-3 gap-3 hidden"></div>
                                @else
                                <div class="border-2 border-dashed border-gray-200 rounded-sm p-8 text-center bg-gray-50/30">
                                    <svg class="w-10 h-10 text-gray-300 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Maximum Photos Reached (3/3)</p>
                                    <p class="text-xs text-gray-400 mt-1">Delete existing photos to upload new ones</p>
                                </div>
                                @endif
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

// Edit Review Images Management
const editReviewImages = {};

// Handle Edit Review Images Upload
function handleEditReviewImages(input, index, maxSlots) {
    if (!editReviewImages[index]) editReviewImages[index] = [];
    
    const newFiles = Array.from(input.files);
    
    for (let newFile of newFiles) {
        // Validate file size (5MB)
        if (newFile.size > 5120 * 1024) {
            alert(`${newFile.name} exceeds 5MB limit`);
            continue;
        }
        
        // Check duplicates
        const isDuplicate = editReviewImages[index].some(f => f.name === newFile.name && f.size === newFile.size);
        if (!isDuplicate && editReviewImages[index].length < maxSlots) {
            editReviewImages[index].push(newFile);
        }
    }
    
    // Limit to available slots
    if (editReviewImages[index].length > maxSlots) {
        alert(`You can only add ${maxSlots} more image(s)`);
        editReviewImages[index] = editReviewImages[index].slice(0, maxSlots);
    }
    
    updateEditReviewPreview(index);
    updateEditFileInput(index);
}

// Update Edit Review Preview
function updateEditReviewPreview(index) {
    const container = document.getElementById(`new_previews_${index}`);
    container.innerHTML = '';
    
    if (editReviewImages[index] && editReviewImages[index].length > 0) {
        container.classList.remove('hidden');
        
        editReviewImages[index].forEach((file, fileIndex) => {
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
            
            // "New" badge
            const badge = document.createElement('div');
            badge.className = 'absolute top-1 left-1 bg-green-600 text-white text-xs px-2 py-0.5 rounded font-medium';
            badge.textContent = 'New';
            wrapper.appendChild(badge);
            
            // Remove button
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'absolute top-1 right-1 bg-white/90 text-gray-900 p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white hover:text-rose-600';
            btn.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
            btn.onclick = () => removeEditReviewImage(index, fileIndex);
            
            wrapper.appendChild(btn);
            container.appendChild(wrapper);
        });
    } else {
        container.classList.add('hidden');
    }
}

// Remove Edit Review Image
function removeEditReviewImage(index, fileIndex) {
    editReviewImages[index].splice(fileIndex, 1);
    updateEditReviewPreview(index);
    updateEditFileInput(index);
}

// Update Edit File Input
function updateEditFileInput(index) {
    const input = document.getElementById(`images_${index}`);
    if (!input) return;
    
    const dt = new DataTransfer();
    if (editReviewImages[index]) {
        editReviewImages[index].forEach(file => dt.items.add(file));
    }
    input.files = dt.files;
}

// Toggle Delete Existing Image
function toggleImageDelete(checkbox, imageId) {
    const imgContainer = document.getElementById(`existing_image_${imageId}`);
    const img = imgContainer.querySelector('img');
    
    if (checkbox.checked) {
        img.style.opacity = '0.3';
        img.style.filter = 'grayscale(100%)';
        imgContainer.classList.add('ring-2', 'ring-rose-500');
    } else {
        img.style.opacity = '1';
        img.style.filter = 'none';
        imgContainer.classList.remove('ring-2', 'ring-rose-500');
    }
}

// Drag & Drop for Edit Upload Areas
document.addEventListener('DOMContentLoaded', function() {
    const editUploadAreas = document.querySelectorAll('[data-edit-upload-area]');
    
    editUploadAreas.forEach(area => {
        const index = area.dataset.editUploadArea;
        const input = document.getElementById(`images_${index}`);
        const maxSlots = input ? parseInt(input.dataset.maxSlots) : 3;
        
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
            
            if (files.length > 0 && input) {
                // Create FileList from dropped files
                const dt = new DataTransfer();
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        dt.items.add(file);
                    }
                });
                input.files = dt.files;
                
                // Trigger onchange
                handleEditReviewImages(input, index, maxSlots);
            }
        });
    });
    
    // Mobile: Show remove buttons always on touch devices
    if ('ontouchstart' in window) {
        const style = document.createElement('style');
        style.textContent = '.group button, .group label { opacity: 1 !important; }';
        document.head.appendChild(style);
    }
});
</script>

@include('components.image-lightbox')

@endsection