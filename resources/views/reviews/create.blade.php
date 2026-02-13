@extends('layout.main')

@section('content')
<div class="bg-white min-h-screen">
    <div class="container mx-auto px-4 lg:px-8 max-w-5xl">
        
        <!-- Header -->
        <div class="py-12 lg:py-16 border-b border-gray-100">

            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-sm p-4 flex items-start">
                <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-sm p-4 flex items-start">
                <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-sm p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800 mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="text-center">
                <a href="{{ route('order.track', $order->guest_token) }}" class="inline-flex items-center text-[10px] uppercase tracking-[0.2em] text-gray-400 hover:text-gray-900 transition-colors font-medium mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Order
                </a>
                
                <h1 class="text-3xl lg:text-4xl font-serif text-gray-900 mb-3 tracking-wide">Review Your Purchase</h1>
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-medium mb-2">Order #{{ $order->order_number }}</p>
                <p class="text-[10px] text-gray-500 mt-3 flex items-center justify-center">
                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Review deadline: {{ $order->completed_at->addDays(30)->format('M d, Y') }}
                </p>
            </div>
        </div>

        <!-- Review Form -->
        <form action="{{ route('order.track.review.store', $order->guest_token) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="py-12 space-y-12">
                {{-- ✅ LOOP GROUPED ITEMS (1x per product) --}}
                @foreach($groupedItems as $index => $item)
                @php
                    // Cek apakah produk ini sudah direview di order ini
                    $productReviewed = $order->reviews()->where('product_id', $item->product_id)->exists();
                @endphp

                <div class="border border-gray-100 bg-white {{ $productReviewed ? 'opacity-50' : '' }}">
                    <div class="p-8 lg:p-12">
                        <!-- Product Header -->
                        <div class="flex items-start space-x-6 pb-8 mb-8 border-b border-gray-100">
                            <!-- Product Image -->
                            <div class="flex-shrink-0 w-20 h-20 lg:w-24 lg:h-24">
                            @if($item->product && $item->product->featuredImage)
                                <img src="{{ asset('storage/' . $item->product->featuredImage->image) }}" 
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover border border-gray-100">
                            @else
                                <div class="w-full h-full bg-gray-50 border border-gray-100 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1">
                            <h3 class="text-lg font-serif text-gray-900 mb-2">{{ $item->product->name }}</h3>
                            
                            {{-- ✅ DISPLAY VARIANTS (Info Only) --}}
                            @if(count($item->variants) > 0)
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($item->variants as $variant)
                                        <span class="inline-flex items-center px-2 py-1 bg-gray-50 text-[10px] text-gray-600 border border-gray-100">
                                            @if($variant['color'])
                                                {{ $variant['color'] }}
                                            @endif
                                            
                                            @if($variant['color'] && $variant['size'])
                                                <span class="mx-1 text-gray-300">•</span>
                                            @endif
                                            
                                            @if($variant['size'])
                                                {{ $variant['size'] }}
                                            @endif
                                            
                                            <span class="ml-1 text-gray-400">×{{ $variant['quantity'] }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <p class="text-[10px] uppercase tracking-[0.15em] text-gray-400">Total Quantity: {{ $item->total_quantity }}</p>
                        </div>
                    </div>

                        @if($productReviewed)
                            <!-- JIKA PRODUK INI SUDAH DIREVIEW -->
                            <div class="bg-green-50 border border-green-200 p-4 mt-8">
                                <div class="flex items-center text-green-800">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-xs uppercase tracking-wide font-medium">Already Reviewed</span>
                                </div>
                            </div>
                        @else
                            <!-- ✅ FORM REVIEW (1x PER PRODUCT) -->
                            <input type="hidden" name="reviews[{{ $index }}][product_id]" value="{{ $item->product_id }}">

                            <!-- Star Rating -->
                            <div class="mb-10">
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">
                                    Rating <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center space-x-2" data-rating-group="{{ $index }}">
                                    @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" 
                                           id="rating_{{ $index }}_{{ $i }}" 
                                           name="reviews[{{ $index }}][rating]" 
                                           value="{{ $i }}" 
                                           class="hidden rating-input"
                                           data-index="{{ $index }}">
                                    <label for="rating_{{ $index }}_{{ $i }}" 
                                           class="cursor-pointer rating-star transition-all duration-200"
                                           data-value="{{ $i }}"
                                           data-index="{{ $index }}">
                                        <svg class="w-7 h-7 text-gray-200 hover:text-[#E8B49E]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </label>
                                    @endfor
                                </div>
                                {{-- Error message placeholder for this product --}}
                                <p class="text-xs text-red-600 mt-2 hidden" id="rating_error_{{ $index }}">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Please rate this product
                                </p>
                            </div>

                            <!-- Comment -->
                            <div class="mb-10">
                                <label for="comment_{{ $index }}" class="block text-[10px] font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">
                                    Your Review (Optional)
                                </label>
                                <textarea id="comment_{{ $index }}" 
                                          name="reviews[{{ $index }}][comment]" 
                                          rows="4"
                                          maxlength="1000"
                                          placeholder="Share your thoughts about this product..."
                                          class="w-full px-4 py-3 border border-gray-200 focus:border-gray-900 focus:ring-0 text-sm text-gray-900 placeholder-gray-400 resize-none transition-colors"></textarea>
                                <p class="text-[10px] text-gray-400 mt-2 tracking-wide">Maximum 1000 characters</p>
                            </div>

                            <!-- Image Upload (Max 3) -->
                            <div class="mb-10">
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">
                                    Photos (Optional)
                                </label>
                                
                                <label for="images_{{ $index }}" 
                                       class="block border-2 border-dashed border-gray-200 rounded-sm p-6 text-center cursor-pointer hover:border-gray-900 transition-all duration-300 bg-gray-50/30 hover:bg-gray-50"
                                       data-upload-area="{{ $index }}">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-700">Upload or Drag & Drop</p>
                                        <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 5MB each (Max 3 photos)</p>
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
                            <div class="pt-8 border-t border-gray-100">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" 
                                           name="reviews[{{ $index }}][anonymous]" 
                                           value="1"
                                           class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-0 focus:ring-offset-0">
                                    <span class="ml-3 text-xs text-gray-600 group-hover:text-gray-900 transition-colors">
                                        Post as Anonymous
                                        <span class="text-gray-400 ml-1">(Hide my name)</span>
                                    </span>
                                </label>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="py-12 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    <a href="{{ route('order.track', $order->guest_token) }}" 
                       class="text-[10px] uppercase tracking-[0.2em] text-gray-400 hover:text-gray-900 transition-colors font-medium">
                        Skip for now
                    </a>
                    
                    <button type="submit" 
                            class="w-full sm:w-auto px-12 py-4 bg-gray-900 text-white text-[10px] uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-lg font-medium flex items-center justify-center space-x-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Submit Reviews</span>
                    </button>
                </div>
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
                svg.classList.remove('text-gray-200');
                svg.classList.add('text-[#E8B49E]');
            } else {
                svg.classList.remove('text-[#E8B49E]');
                svg.classList.add('text-gray-200');
            }
        });
        
        // Set input value
        document.getElementById(`rating_${index}_${value}`).checked = true;
    });
    
    // Hover effect - FIXED
    star.addEventListener('mouseenter', function() {
        const value = parseInt(this.dataset.value);
        const index = this.dataset.index;
        const stars = document.querySelectorAll(`.rating-star[data-index="${index}"]`);
        
        stars.forEach((s, i) => {
            const svg = s.querySelector('svg');
            // Tambahkan warna hanya sampai bintang yang di-hover
            if (i < value) {
                svg.classList.remove('text-gray-200');
                svg.classList.add('text-[#E8B49E]');
            } else {
                svg.classList.add('text-gray-200');
                svg.classList.remove('text-[#E8B49E]');
            }
        });
    });
    
    star.addEventListener('mouseleave', function() {
        const index = this.dataset.index;
        const checkedInput = document.querySelector(`input[name="reviews[${index}][rating]"]:checked`);
        const checkedValue = checkedInput ? parseInt(checkedInput.value) : 0;
        const stars = document.querySelectorAll(`.rating-star[data-index="${index}"]`);
        
        // Kembalikan ke state yang ter-check
        stars.forEach((s, i) => {
            const svg = s.querySelector('svg');
            if (i < checkedValue) {
                svg.classList.add('text-[#E8B49E]');
                svg.classList.remove('text-gray-200');
            } else {
                svg.classList.remove('text-[#E8B49E]');
                svg.classList.add('text-gray-200');
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
            
            // "New" badge
            const badge = document.createElement('div');
            badge.className = 'absolute top-1 left-1 bg-[#E8B49E] text-white text-xs px-2 py-0.5 rounded font-medium';
            badge.textContent = 'New';
            wrapper.appendChild(badge);
            
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
    
    // Mobile: Show remove buttons always on touch devices
    if ('ontouchstart' in window) {
        const style = document.createElement('style');
        style.textContent = '.group button { opacity: 1 !important; }';
        document.head.appendChild(style);
    }
});

// ✅ FORM VALIDATION BEFORE SUBMIT
document.querySelector('form').addEventListener('submit', function(e) {
    let hasError = false;
    let firstErrorIndex = null;
    
    // Get all rating groups (excluding already reviewed products)
    const ratingGroups = document.querySelectorAll('[data-rating-group]');
    
    ratingGroups.forEach((group, index) => {
        const groupIndex = group.dataset.ratingGroup;
        const checkedRating = document.querySelector(`input[name="reviews[${groupIndex}][rating]"]:checked`);
        const errorMsg = document.getElementById(`rating_error_${groupIndex}`);
        const ratingLabel = group.closest('.mb-10')?.querySelector('label');
        
        // Clear previous errors
        if (errorMsg) errorMsg.classList.add('hidden');
        if (ratingLabel) ratingLabel.classList.remove('text-red-600');
        
        // Check if rating is selected
        if (!checkedRating) {
            hasError = true;
            if (firstErrorIndex === null) firstErrorIndex = groupIndex;
            
            // Show error message
            if (errorMsg) {
                errorMsg.classList.remove('hidden');
            }
            
            // Highlight label
            if (ratingLabel) {
                ratingLabel.classList.add('text-red-600');
            }
            
            // Add shake animation to stars
            const stars = document.querySelectorAll(`.rating-star[data-index="${groupIndex}"]`);
            stars.forEach(star => {
                star.classList.add('animate-shake');
                setTimeout(() => star.classList.remove('animate-shake'), 500);
            });
        }
    });
    
    if (hasError) {
        e.preventDefault();
        
        // Scroll to first error
        const firstError = document.getElementById(`rating_error_${firstErrorIndex}`);
        if (firstError) {
            firstError.closest('.border').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }
        
        // Show alert
        alert('Please provide a rating for all products before submitting.');
        return false;
    }
});
</script>

<style>
    @media (max-width: 1024px) {
        /* Always show delete/remove buttons on mobile */
        .group > button {
            opacity: 1 !important;
        }
    }
    
    /* Shake animation for validation error */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .animate-shake {
        animation: shake 0.3s ease-in-out;
    }
</style>

@include('components.image-lightbox')

@endsection