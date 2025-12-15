@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Product</h1>
        
        <form id="editProductForm" action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Side - Image Upload -->
                <div class="space-y-4">
                    <!-- Main Image Preview -->
                    <div class="aspect-square bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden relative">
                        <div id="mainImagePreview" class="w-full h-full flex items-center justify-center">
                            <!-- Show existing main image or default state -->
                            @if($product->featuredImage)
                                <img id="mainImage" 
                                     src="{{ asset('storage/' . $product->featuredImage->image) }}" 
                                     class="w-full h-full object-cover" 
                                     alt="Main product image">
                                <div id="noImageState" class="text-center hidden">
                                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium mb-2">Add an image</p>
                                    <p class="text-gray-400 text-sm">Click to upload your first product image</p>
                                </div>
                            @else
                                <div id="noImageState" class="text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium mb-2">Add an image</p>
                                    <p class="text-gray-400 text-sm">Click to upload your first product image</p>
                                </div>
                                <img id="mainImage" class="w-full h-full object-cover hidden" alt="Main product image">
                            @endif
                        </div>
                        
                        <!-- Click overlay for main image -->
                        <input type="file" 
                               id="mainImageInput" 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer {{ $product->images->count() > 0 ? 'pointer-events-none' : '' }}" 
                               accept="image/*" 
                               onchange="handleMainImageUpload(this)"
                               {{ $product->images->count() > 0 ? 'disabled' : '' }}>
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    <div class="flex space-x-2 overflow-x-auto py-2">
                        <div id="thumbnailContainer" class="flex space-x-2 pb-3">
                            <!-- Show existing thumbnails -->
                            @foreach($product->images as $index => $image)
                                <div class="relative flex-shrink-0" data-existing-id="{{ $image->id }}">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-blue-500' : 'border-gray-200' }} cursor-pointer hover:border-blue-300 transition-colors" 
                                         onclick="setMainImageFromExisting('{{ asset('storage/' . $image->image) }}', {{ $index }})">
                                        <img src="{{ asset('storage/' . $image->image) }}" class="w-full h-full object-cover">
                                    </div>
                                    <button type="button" 
                                            onclick="removeExistingImage({{ $image->id }}, this)"
                                            class="absolute top-0 right-0 bg-red-500 text-white rounded-tr-lg rounded-bl-xs font-bold w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                        ×
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Add More Images Button -->
                        <div id="addMoreButton" class="{{ $product->images->count() > 0 ? '' : 'hidden' }}">
                            <label for="additionalImages" class="flex-shrink-0 w-16 h-16 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </label>
                            <input type="file" 
                                   id="additionalImages" 
                                   class="hidden" 
                                   accept="image/*" 
                                   multiple 
                                   onchange="handleAdditionalImages(this)">
                        </div>
                    </div>
                    
                    <!-- Hidden inputs -->
                    <input type="file" name="images[]" id="formImages" class="hidden" multiple accept="image/*">
                    <input type="hidden" name="deleted_images" id="deletedImages" value="">
                    
                    @error('images')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Right Side - Product Details -->
                <div class="space-y-6">
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Name *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $product->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                               placeholder="Enter product name">
                        
                        <input type="hidden" name="slug" id="slug" value="{{ old('slug', $product->slug) }}">
                        
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Price (Rp) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500 text-lg">Rp</span>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $product->price) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                                   placeholder="0.00">
                        </div>
                        
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category & Stock Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category *
                            </label>
                            <select name="category_id" 
                                    id="category_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                                Stock *
                            </label>
                            <input type="number" 
                                   id="stock" 
                                   name="stock" 
                                   value="{{ old('stock', $product->stock) }}"
                                   min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0">
                            
                            @error('stock')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description"
                                  rows="6"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                  placeholder="Describe your product...">{{ old('description', $product->description) }}</textarea>
                        
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex space-x-4 pt-1">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-200 text-lg">
                            Update Product
                        </button>
                        <a href="{{ route('products.index') }}" 
                           class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-4 px-6 rounded-lg transition duration-200 text-center text-lg">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Global variables
    let selectedFiles = [];
    let currentMainIndex = 0;
    let deletedImageIds = [];
    let hasExistingImages = {{ $product->images->count() > 0 ? 'true' : 'false' }};
    const form = document.querySelector('#editProductForm');

    // Auto-generate slug
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');
    const originalName = "{{ $product->name }}";
    let timeout = null;

    name.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            // Only update slug if name changed from original
            if (name.value.length > 0 && name.value !== originalName) {
                fetch('/products/checkSlug?name=' + encodeURIComponent(name.value))
                .then(response => response.json())
                .then(data => slug.value = data.slug)
                .catch(error => console.log('Error:', error));
            }
        }, 300);
    });

    // Handle main image upload (ONLY if no existing images)
    function handleMainImageUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            selectedFiles = [file];
            currentMainIndex = 0;
            
            updateImageDisplay();
            updateFormFiles();
            
            // Disable main image input
            input.disabled = true;
            input.style.pointerEvents = 'none';
        }
    }

    // Handle additional images (ADD to existing)
    function handleAdditionalImages(input) {
        if (input.files) {
            Array.from(input.files).forEach(file => {
                selectedFiles.push(file);
            });
            updateImageDisplay();
            updateFormFiles();
        }
        input.value = '';
    }

    // Set main image from existing image
    function setMainImageFromExisting(imageSrc, index) {
        document.getElementById('mainImage').src = imageSrc;
        document.getElementById('mainImage').classList.remove('hidden');
        document.getElementById('noImageState').classList.add('hidden');
        
        // Update border colors for existing images
        document.querySelectorAll('[data-existing-id] > div').forEach(el => {
            el.classList.remove('border-blue-500');
            el.classList.add('border-gray-200');
        });
        
        // Remove border from new images
        document.querySelectorAll('.new-image-thumb .border-blue-500').forEach(el => {
            el.classList.remove('border-blue-500');
            el.classList.add('border-gray-200');
        });
        
        // Add blue border to selected existing image
        const existingThumbnails = document.querySelectorAll('[data-existing-id]');
        if (existingThumbnails[index] && existingThumbnails[index].style.opacity !== '0.5') {
            const thumbnail = existingThumbnails[index].querySelector('div');
            thumbnail.classList.remove('border-gray-200');
            thumbnail.classList.add('border-blue-500');
        }
    }

    // Remove existing image (mark for deletion)
    function removeExistingImage(imageId, button) {
        if (!deletedImageIds.includes(imageId.toString())) {
            deletedImageIds.push(imageId.toString());
        }
        
        const thumbnail = button.closest('[data-existing-id]');
        thumbnail.style.opacity = '0.5';
        thumbnail.style.pointerEvents = 'none';
        
        // Hide delete button and show "Will be deleted" text
        button.style.display = 'none';
        const deletedLabel = document.createElement('div');
        deletedLabel.className = 'absolute -bottom-4 left-1/2 transform -translate-x-1/2 text-xs text-red-500 font-medium whitespace-nowrap';
        deletedLabel.textContent = 'Deleted';
        thumbnail.appendChild(deletedLabel);
        
        // Update deleted images input
        document.getElementById('deletedImages').value = deletedImageIds.join(',');
        
        // Check if this was the main image, switch to another
        const wasMainImage = thumbnail.querySelector('.border-blue-500');
        if (wasMainImage) {
            // Find first remaining existing image or new image
            const remainingExisting = Array.from(document.querySelectorAll('[data-existing-id]'))
                .find(thumb => thumb.style.opacity !== '0.5');
            
            if (remainingExisting) {
                const img = remainingExisting.querySelector('img');
                const index = Array.from(document.querySelectorAll('[data-existing-id]')).indexOf(remainingExisting);
                setMainImageFromExisting(img.src, index);
            } else if (selectedFiles.length > 0) {
                setMainImage(0);
            } else {
                // No images left
                document.getElementById('noImageState').classList.remove('hidden');
                document.getElementById('mainImage').classList.add('hidden');
            }
        }
        
        checkMainImageInputState();
    }

    // Update image display for new images
    function updateImageDisplay() {
        const thumbnailContainer = document.getElementById('thumbnailContainer');
        const addMoreButton = document.getElementById('addMoreButton');

        // Remove previous new image thumbnails (keep existing ones)
        const existingNewThumbs = document.querySelectorAll('.new-image-thumb');
        existingNewThumbs.forEach(thumb => thumb.remove());

        if (selectedFiles.length > 0) {
            selectedFiles.forEach((file, index) => {
                const thumbnailDiv = document.createElement('div');
                thumbnailDiv.className = 'relative flex-shrink-0 new-image-thumb';
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbnailDiv.innerHTML = `
                        <div class="w-16 h-16 rounded-lg overflow-hidden border-2 ${index === currentMainIndex && !hasActiveExistingImage() ? 'border-blue-500' : 'border-gray-200'} cursor-pointer hover:border-blue-300 transition-colors" 
                             onclick="setMainImage(${index})">
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                        </div>
                        <button type="button" 
                                onclick="removeNewImage(${index})"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-tr-lg rounded-bl-xs font-bold w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                            ×
                        </button>
                        <span class="absolute -bottom-5 left-1/2 transform -translate-x-1/2 text-xs text-green-500 font-medium">New</span>
                    `;
                    
                    // Set as main image if no existing images are active
                    if (index === currentMainIndex && !hasActiveExistingImage()) {
                        document.getElementById('mainImage').src = e.target.result;
                        document.getElementById('mainImage').classList.remove('hidden');
                        document.getElementById('noImageState').classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
                
                thumbnailContainer.appendChild(thumbnailDiv);
            });

            addMoreButton.classList.remove('hidden');
        }
        
        checkMainImageInputState();
    }

    // Check if there are active (not deleted) existing images
    function hasActiveExistingImage() {
        const existingThumbs = document.querySelectorAll('[data-existing-id]');
        return Array.from(existingThumbs).some(thumb => thumb.style.opacity !== '0.5');
    }

    // Set main image from new uploads
    function setMainImage(index) {
        if (index < selectedFiles.length) {
            currentMainIndex = index;
            
            // Remove blue border from all images
            document.querySelectorAll('.border-blue-500').forEach(el => {
                el.classList.remove('border-blue-500');
                el.classList.add('border-gray-200');
            });
            
            // Add blue border to selected new image
            const newThumbs = document.querySelectorAll('.new-image-thumb');
            if (newThumbs[index]) {
                const thumbnail = newThumbs[index].querySelector('div');
                thumbnail.classList.remove('border-gray-200');
                thumbnail.classList.add('border-blue-500');
            }
            
            // Update main image
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('mainImage').src = e.target.result;
                document.getElementById('mainImage').classList.remove('hidden');
                document.getElementById('noImageState').classList.add('hidden');
            };
            reader.readAsDataURL(selectedFiles[index]);
        }
    }

    // Remove new uploaded image
    function removeNewImage(index) {
        selectedFiles.splice(index, 1);
        
        if (currentMainIndex >= selectedFiles.length) {
            currentMainIndex = Math.max(0, selectedFiles.length - 1);
        }
        
        updateImageDisplay();
        updateFormFiles();
    }

    // Check if main image input should be enabled
    function checkMainImageInputState() {
        const hasActiveExisting = hasActiveExistingImage();
        const hasNewImages = selectedFiles.length > 0;
        
        const mainImageInput = document.getElementById('mainImageInput');
        
        if (!hasActiveExisting && !hasNewImages) {
            // No images at all - enable main upload
            mainImageInput.disabled = false;
            mainImageInput.style.pointerEvents = 'auto';
            document.getElementById('addMoreButton').classList.add('hidden');
        } else {
            // Has images - disable main upload
            mainImageInput.disabled = true;
            mainImageInput.style.pointerEvents = 'none';
            document.getElementById('addMoreButton').classList.remove('hidden');
        }
    }

    // Update form files for submission
    function updateFormFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => {
            dt.items.add(file);
        });
        document.getElementById('formImages').files = dt.files;
        document.getElementById('deletedImages').value = deletedImageIds.join(',');
    }

    // Form submission validation
    form.addEventListener('submit', async function(e) {
    const hasActiveExisting = hasActiveExistingImage();
    const hasNewImages = selectedFiles.length > 0;

    // Ensure form data is updated
    updateFormFiles();

    // Always fetch slug before submit
    if (name.value.length > 0) {
        try {
            const response = await fetch('/products/checkSlug?name=' + encodeURIComponent(name.value));
            const data = await response.json();
            slug.value = data.slug;
        } catch (error) {
            alert('Failed to generate slug. Please try again.');
            return;
        }
    } else {
        slug.value = '';
    }

    // Submit after slug is ready
    this.submit();
});
</script>

<style>
    /* Custom scrollbar for thumbnail gallery */
    #thumbnailContainer::-webkit-scrollbar {
        height: 4px;
    }
    
    #thumbnailContainer::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }
    
    #thumbnailContainer::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }
    
    #thumbnailContainer::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endsection