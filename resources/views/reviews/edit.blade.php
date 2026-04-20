@extends('layout.main')

@section('content')
<div class="bg-white min-h-screen">
    <div class="container mx-auto px-4 lg:px-8 max-w-5xl">
        
        <!-- Header -->
        <div class="py-12 lg:py-16 border-b border-gray-100">
            <div class="text-center">
                <h1 class="text-3xl lg:text-4xl font-serif text-gray-900 mb-3 tracking-wide">Edit Your Review</h1>
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-medium">Order #{{ $order->order_number }}</p>
            </div>
        </div>

        <!-- Review Form -->
        <form action="{{ route('order.track.review.update', $order->guest_token) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="py-12 space-y-12">
                @foreach($order->orderItems as $index => $item)
                @php
                    // Ambil review yang sudah ada untuk produk ini
                    $existingReview = $order->reviews()->where('product_id', $item->product_id)->first();
                @endphp

                @if($existingReview)
                <div class="border border-gray-100 bg-white">
                    <div class="p-8 lg:p-12">
                        <!-- Product Header -->
                        <div class="flex items-start space-x-6 pb-8 mb-8 border-b border-gray-100">
                            <!-- Product Image -->
                            <div class="flex-shrink-0 w-20 h-20 lg:w-24 lg:h-24">
                                @if($item->product && $item->product->featuredImage)
                                    <img src="{{ asset('storage/' . $item->product->featuredImage->image) }}" 
                                         alt="{{ $item->item_name }}"
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
                                <h3 class="text-lg font-serif text-gray-900 mb-2">{{ $item->item_name }}</h3>
                                <p class="text-[10px] uppercase tracking-[0.15em] text-gray-400">Quantity: {{ $item->quantity }}</p>
                            </div>
                        </div>

                        <input type="hidden" name="reviews[{{ $index }}][review_id]" value="{{ $existingReview->id }}">

                        <!-- Star Rating -->
                        <div class="mb-10">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">
                                Rating
                            </label>
                            <div class="flex items-center space-x-2" data-rating-group="{{ $index }}">
                                @for($i = 1; $i <= 5; $i++)
                                <input type="radio" 
                                       id="rating_{{ $index }}_{{ $i }}" 
                                       name="reviews[{{ $index }}][rating]" 
                                       value="{{ $i }}" 
                                       {{ $existingReview->rating == $i ? 'checked' : '' }}
                                       class="hidden rating-input"
                                       data-index="{{ $index }}">
                                <label for="rating_{{ $index }}_{{ $i }}" 
                                       class="cursor-pointer rating-star transition-all duration-200"
                                       data-value="{{ $i }}"
                                       data-index="{{ $index }}">
                                    <svg class="w-7 h-7 {{ $i <= $existingReview->rating ? 'text-[#E3CFCF]' : 'text-gray-200' }} hover:text-[#E3CFCF]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Comment -->
                        <div class="mb-10">
                            <label for="comment_{{ $index }}" class="block text-[10px] font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">
                                Your Review
                            </label>
                            <textarea id="comment_{{ $index }}" 
                                      name="reviews[{{ $index }}][comment]" 
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Share your thoughts about this product..."
                                      class="w-full px-4 py-3 border border-gray-200 focus:border-gray-900 focus:ring-0 text-sm text-gray-900 placeholder-gray-400 resize-none transition-colors">{{ $existingReview->comment }}</textarea>
                            <p class="text-[10px] text-gray-400 mt-2 tracking-wide">Maximum 1000 characters</p>
                        </div>

                            <!-- Photos Section -->
                            <div class="mb-4">
                                @php
                                    $currentCount = $existingReview->images->count();
                                    $remainingSlots = 3 - $currentCount;
                                @endphp
                                
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Photos ({{ $currentCount }}/3)
                                    @if($remainingSlots > 0)
                                        <span class="text-gray-500 font-normal">- {{ $remainingSlots }} slot(s) available</span>
                                    @endif
                                </label>
                                
                                <!-- Combined Grid for Current & New Photos -->
                                <div id="photos_grid_{{ $index }}" class="grid grid-cols-3 gap-3 mb-3">
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
                                
                                <p class="text-xs text-gray-400 mb-3">Click images to view larger, X to remove</p>
                                
                                <!-- Upload Area -->
                                @if($remainingSlots > 0)
                                <label for="images_{{ $index }}" 
                                       class="block border-2 border-dashed border-gray-200 rounded-sm p-6 text-center cursor-pointer hover:border-gray-900 transition-all duration-300 bg-gray-50/30 hover:bg-gray-50"
                                       data-edit-upload-area="{{ $index }}">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-700">Add {{ $remainingSlots }} more photo(s)</p>
                                        <p class="text-xs text-gray-500 mt-1">Click or drag & drop (Max 5MB each)</p>
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
                                @else
                                <div class="border-2 border-dashed border-gray-200 rounded-sm p-6 text-center bg-gray-50/30">
                                    <svg class="w-8 h-8 text-gray-300 mb-2 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Maximum Photos Reached (3/3)</p>
                                    <p class="text-xs text-gray-400 mt-1">Delete existing photos to upload new ones</p>
                                </div>
                                @endif
                            </div>

                        @if($order->user_id)
                        <div class="pt-8 border-t border-gray-100">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" 
                                       name="reviews[{{ $index }}][anonymous]" 
                                       value="1"
                                       {{ $existingReview->anonymous ? 'checked' : '' }}
                                       class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-0 focus:ring-offset-0">
                                <span class="ml-3 text-xs text-gray-600 group-hover:text-gray-900 transition-colors">
                                    Post as Anonymous
                                    <span class="text-gray-400 ml-1">(Hide my name)</span>
                                </span>
                            </label>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="py-12 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    <a href="{{ route('order.track', $order->guest_token) }}" 
                       class="text-[10px] uppercase tracking-[0.2em] text-gray-400 hover:text-gray-900 transition-colors font-medium">
                        Cancel
                    </a>
                    
                    <button type="submit" 
                            id="submit-btn"
                            disabled
                            class="w-full sm:w-auto px-12 py-4 bg-gray-900 text-white text-[10px] uppercase tracking-[0.2em] transition-all shadow-lg font-medium flex items-center justify-center space-x-3 disabled:opacity-30 disabled:cursor-not-allowed disabled:shadow-none hover:bg-gray-800 disabled:hover:bg-gray-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Update Review</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Track Initial Values
const initialValues = {};

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
                svg.classList.add('text-[#E3CFCF]');
            } else {
                svg.classList.remove('text-[#E3CFCF]');
                svg.classList.add('text-gray-200');
            }
        });
        
        // Set input value
        document.getElementById(`rating_${index}_${value}`).checked = true;
        
        // Check for changes
        checkFormChanges();
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
                svg.classList.add('text-[#E3CFCF]');
            } else {
                svg.classList.add('text-gray-200');
                svg.classList.remove('text-[#E3CFCF]');
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
                svg.classList.add('text-[#E3CFCF]');
                svg.classList.remove('text-gray-200');
            } else {
                svg.classList.remove('text-[#E3CFCF]');
                svg.classList.add('text-gray-200');
            }
        });
    });
});

// Edit Review Images Management
const editReviewImages = {};

// Function to check if form has changes
function checkFormChanges() {
    let hasChanges = false;
    
    // Check all review items
    Object.keys(initialValues).forEach(key => {
        const [type, index] = key.split('_');
        
        if (type === 'rating') {
            const current = document.querySelector(`input[name="reviews[${index}][rating]"]:checked`);
            const currentValue = current ? current.value : '';
            if (currentValue !== initialValues[key]) {
                hasChanges = true;
            }
        } else if (type === 'comment') {
            const current = document.getElementById(`comment_${index}`);
            if (current && current.value !== initialValues[key]) {
                hasChanges = true;
            }
        } else if (type === 'anonymous') {
            const current = document.querySelector(`input[name="reviews[${index}][anonymous]"]`);
            if (current && current.checked !== initialValues[key]) {
                hasChanges = true;
            }
        }
    });
    
    // Check if any new images added
    Object.keys(editReviewImages).forEach(index => {
        if (editReviewImages[index] && editReviewImages[index].length > 0) {
            hasChanges = true;
        }
    });
    
    // Check if any images marked for deletion
    const deleteCheckboxes = document.querySelectorAll('input[name^="reviews"][name$="[delete_images][]"]:checked');
    if (deleteCheckboxes.length > 0) {
        hasChanges = true;
    }
    
    // Enable/disable submit button
    const submitBtn = document.getElementById('submit-btn');
    if (submitBtn) {
        submitBtn.disabled = !hasChanges;
    }
}

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
    checkFormChanges();
}

// Update Edit Review Preview
function updateEditReviewPreview(index) {
    const container = document.getElementById(`photos_grid_${index}`);
    if (!container) return;
    
    // Remove all previously added new photo previews
    container.querySelectorAll('[data-new-photo]').forEach(el => el.remove());
    
    // Add new photo previews to the same grid
    if (editReviewImages[index] && editReviewImages[index].length > 0) {
        editReviewImages[index].forEach((file, fileIndex) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative group aspect-square bg-gray-100 rounded-sm overflow-hidden border border-gray-200';
            wrapper.setAttribute('data-new-photo', 'true');
            
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
            badge.className = 'absolute top-1 left-1 bg-[#E3CFCF] text-white text-xs px-2 py-0.5 rounded font-medium';
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
    }
}

// Remove Edit Review Image
function removeEditReviewImage(index, fileIndex) {
    editReviewImages[index].splice(fileIndex, 1);
    updateEditReviewPreview(index);
    updateEditFileInput(index);
    checkFormChanges();
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
    
    checkFormChanges();
}

// Drag & Drop for Edit Upload Areas
document.addEventListener('DOMContentLoaded', function() {
    // Store initial values
    document.querySelectorAll('input[name^="reviews"][name$="[rating]"]:checked').forEach(input => {
        const match = input.name.match(/reviews\[(\d+)\]\[rating\]/);
        if (match) {
            const index = match[1];
            initialValues[`rating_${index}`] = input.value;
        }
    });
    
    document.querySelectorAll('textarea[name^="reviews"][name$="[comment]"]').forEach(textarea => {
        const match = textarea.name.match(/reviews\[(\d+)\]\[comment\]/);
        if (match) {
            const index = match[1];
            initialValues[`comment_${index}`] = textarea.value;
            
            // Add event listener
            textarea.addEventListener('input', checkFormChanges);
        }
    });
    
    document.querySelectorAll('input[name^="reviews"][name$="[anonymous]"]').forEach(checkbox => {
        const match = checkbox.name.match(/reviews\[(\d+)\]\[anonymous\]/);
        if (match) {
            const index = match[1];
            initialValues[`anonymous_${index}`] = checkbox.checked;
            
            // Add event listener
            checkbox.addEventListener('change', checkFormChanges);
        }
    });
    
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

<style>
    @media (max-width: 1024px) {
        /* Always show delete/remove buttons on mobile */
        .group > label,
        .group > button {
            opacity: 1 !important;
        }
    }
</style>

@include('components.image-lightbox')

@endsection