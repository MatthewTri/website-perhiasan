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

                    <!-- Price Row (IDR & USD) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Price IDR -->
                        <div>
                            <label for="price_idr" class="block text-sm font-medium text-gray-700 mb-2">
                                Price (IDR) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500 text-lg">Rp</span>
                                <input type="number" 
                                    id="price_idr" 
                                    name="price_idr" 
                                    value="{{ old('price_idr', $product->price_idr) }}"
                                    step="0.01"
                                    min="0"
                                    required
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                                    placeholder="0.00">
                            </div>
                            
                            @error('price_idr')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price USD -->
                        <div>
                            <label for="price_usd" class="block text-sm font-medium text-gray-700 mb-2">
                                Price (USD) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500 text-lg">$</span>
                                <input type="number" 
                                    id="price_usd" 
                                    name="price_usd" 
                                    value="{{ old('price_usd', $product->price_usd) }}"
                                    step="0.01"
                                    min="0"
                                    required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                                    placeholder="0.00">
                            </div>
                            
                            @error('price_usd')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Category Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Parent Category -->
                        <div>
                            <label for="parent_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Parent Category <span class="text-red-500">*</span>
                            </label>
                            <select name="parent_category_id" 
                                    id="parent_category_id"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('parent_category_id') border-red-500 @enderror">
                                <option value="">-- Select Parent Category --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" 
                                            {{ old('parent_category_id', $selectedParentId) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            
                            @error('parent_category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sub-Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sub-Category (Optional)
                            </label>
                            <select name="category_id" 
                                    id="category_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                                <option value="">-- Select Parent First --</option>
                            </select>
                            
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            
                            <p class="text-xs text-gray-500 mt-1">
                                Leave empty to use parent category
                            </p>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                Product Colors (Optional)
                            </label>
                            <button type="button" 
                                    onclick="addColorOption()"
                                    class="text-sm bg-blue-50 hover:bg-blue-100 text-blue-600 px-3 py-1.5 rounded-lg transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Color
                            </button>
                        </div>

                        <!-- Colors Container -->
                        <div id="colorsContainer" class="space-y-3">
                            <!-- ⬅️ EXISTING COLORS -->
                            @forelse($product->colors as $index => $color)
                            <div class="color-option bg-gray-50 p-4 rounded-lg border border-gray-200" data-index="{{ $index }}" data-existing-id="{{ $color->id }}">
                                <div class="flex items-center gap-3">
                                    <!-- Color Picker -->
                                    <div class="flex-shrink-0">
                                        <input type="color" 
                                               name="colors[{{ $index }}][hex_code]" 
                                               value="{{ $color->hex_code }}"
                                               class="color-picker-input w-8 h-8 rounded-full border-2 border-gray-300 cursor-pointer"
                                               title="Choose color">
                                    </div>

                                    <!-- Color Name Input -->
                                    <div class="flex-1">
                                        <input type="text" 
                                               name="colors[{{ $index }}][name]" 
                                               value="{{ $color->name }}"
                                               placeholder="e.g., 18k gold plated, Rose Gold, Silver"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               required>
                                        <input type="hidden" name="colors[{{ $index }}][id]" value="{{ $color->id }}">
                                    </div>

                                    <!-- Remove Button -->
                                    <button type="button" 
                                            onclick="removeColorOption({{ $index }})"
                                            class="flex-shrink-0 text-red-500 hover:text-red-700 transition-colors p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @empty
                            <p id="noColorsText" class="text-sm text-gray-500 text-center py-4 bg-gray-50 rounded-lg">
                                No colors added yet. Click "Add Color" to start.
                            </p>
                            @endforelse
                        </div>

                        <!-- Hidden input untuk deleted color IDs -->
                        <input type="hidden" name="deleted_colors" id="deletedColors" value="">

                        @error('colors')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
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
    const allCategories = @json($allCategories);
    const initialParentId = {{ $selectedParentId ?? 'null' }};
    const initialSubId = {{ $selectedSubCategoryId ?? 'null' }};

    const parentSelect = document.getElementById('parent_category_id');
    const subSelect = document.getElementById('category_id');

    // Load sub-categories function
    function loadSubCategories(parentId, selectedSubId = null) {
        subSelect.innerHTML = '<option value="">None</option>';
        subSelect.disabled = true;
        subSelect.classList.add('bg-gray-50');
        
        if (parentId) {
            const children = allCategories.filter(cat => cat.parent_id === parseInt(parentId));
            
            if (children.length > 0) {
                children.forEach(child => {
                    const option = document.createElement('option');
                    option.value = child.id;
                    option.textContent = child.name;
                    if (selectedSubId && child.id === selectedSubId) {
                        option.selected = true;
                    }
                    subSelect.appendChild(option);
                });
                
                subSelect.disabled = false;
                subSelect.classList.remove('bg-gray-50');
            } else {
                subSelect.innerHTML = '<option value="">-- No sub-categories --</option>';
            }
        }
    }

    // Load initial sub-categories
    if (initialParentId) {
        loadSubCategories(initialParentId, initialSubId);
    }

    // Handle parent change
    parentSelect.addEventListener('change', function() {
        loadSubCategories(this.value);
    });

    // ⬅️ COLOR MANAGEMENT
    let colorIndex = {{ $product->colors->count() }}; // Start from existing count
    let deletedColorIds = [];

    function addColorOption() {
        const container = document.getElementById('colorsContainer');
        const noColorsText = document.getElementById('noColorsText');
        
        // Hide "no colors" text
        if (noColorsText) {
            noColorsText.style.display = 'none';
        }

        const colorDiv = document.createElement('div');
        colorDiv.className = 'color-option bg-gray-50 p-4 rounded-lg border border-gray-200';
        colorDiv.dataset.index = colorIndex;
        
        colorDiv.innerHTML = `
            <div class="flex items-center gap-3">
                <!-- Color Picker -->
                <div class="flex-shrink-0">
                    <input type="color" 
                           name="colors[${colorIndex}][hex_code]" 
                           value="#D4AF37"
                           class="color-picker-input w-8 h-8 rounded-full border-2 border-gray-300 cursor-pointer"
                           title="Choose color">
                </div>

                <!-- Color Name Input -->
                <div class="flex-1">
                    <input type="text" 
                           name="colors[${colorIndex}][name]" 
                           placeholder="e.g., 18k gold plated, Rose Gold, Silver"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Remove Button -->
                <button type="button" 
                        onclick="removeColorOption(${colorIndex})"
                        class="flex-shrink-0 text-red-500 hover:text-red-700 transition-colors p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;

        container.appendChild(colorDiv);
        colorIndex++;
    }

    function removeColorOption(index) {
        const colorDiv = document.querySelector(`.color-option[data-index="${index}"]`);
        
        if (colorDiv) {
            // CEK APAKAH INI EXISTING COLOR (ada data-existing-id)
            const existingId = colorDiv.dataset.existingId;
            
            if (existingId) {
                // Mark untuk dihapus dari database
                if (!deletedColorIds.includes(existingId)) {
                    deletedColorIds.push(existingId);
                }
                document.getElementById('deletedColors').value = deletedColorIds.join(',');
                
                // Visual feedback
                colorDiv.style.opacity = '0.5';
                colorDiv.style.pointerEvents = 'none';
                
                // Ganti button remove dengan label "Will be deleted"
                const removeBtn = colorDiv.querySelector('button');
                if (removeBtn) {
                    removeBtn.style.display = 'none';
                }
                
                // Cek apakah sudah ada label "Will be deleted"
                if (!colorDiv.querySelector('.deleted-label')) {
                    const deletedLabel = document.createElement('span');
                    deletedLabel.className = 'text-xs text-red-600 font-medium deleted-label';
                    deletedLabel.textContent = 'Will be deleted';
                    colorDiv.querySelector('.flex-1').appendChild(deletedLabel);
                }
            } else {
                // Hapus langsung (new color yang belum disave)
                colorDiv.remove();
            }
        }

        // Show "no colors" text if no colors left
        const container = document.getElementById('colorsContainer');
        const noColorsText = document.getElementById('noColorsText');
        const remainingColors = container.querySelectorAll('.color-option:not([style*="opacity: 0.5"])');
        
        if (remainingColors.length === 0 && noColorsText) {
            noColorsText.style.display = 'block';
        }
    }

    // ⬅️ GLOBAL VARIABLES
    let selectedFiles = [];
    let currentMainIndex = 0;
    let deletedImageIds = [];
    let hasExistingImages = {{ $product->images->count() > 0 ? 'true' : 'false' }};
    const form = document.querySelector('#editProductForm');
    let isSubmitting = false; // ⬅️ PREVENT DOUBLE SUBMIT

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

    // ⬅️ HANDLE MAIN IMAGE UPLOAD (ONLY if no existing images)
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

    // ⬅️ HANDLE ADDITIONAL IMAGES (ADD to existing)
    function handleAdditionalImages(input) {
        if (input.files && input.files.length > 0) {
            Array.from(input.files).forEach(file => {
                selectedFiles.push(file);
            });
            updateImageDisplay();
            updateFormFiles();
        }
        // ⬅️ RESET INPUT VALUE UNTUK ALLOW RE-SELECT FILE YANG SAMA
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
        
        // ⬅️ CEK APAKAH SUDAH ADA LABEL
        if (!thumbnail.querySelector('.deleted-image-label')) {
            const deletedLabel = document.createElement('div');
            deletedLabel.className = 'absolute -bottom-4 left-1/2 transform -translate-x-1/2 text-xs text-red-500 font-medium whitespace-nowrap deleted-image-label';
            deletedLabel.textContent = 'Deleted';
            thumbnail.appendChild(deletedLabel);
        }
        
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

    // ⬅️ FORM SUBMISSION VALIDATION (PREVENT DOUBLE SUBMIT)
    form.addEventListener('submit', async function(e) {
        e.preventDefault(); // ⬅️ PREVENT DEFAULT SUBMIT
        
        // ⬅️ PREVENT DOUBLE SUBMIT
        if (isSubmitting) {
            return false;
        }
        
        isSubmitting = true;
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Updating...';

        // Ensure form data is updated
        updateFormFiles();
        
        if (!subSelect.value && parentSelect.value) {
            let categoryInput = document.querySelector('input[name="category_id_hidden"]');
            if (!categoryInput) {
                categoryInput = document.createElement('input');
                categoryInput.type = 'hidden';
                categoryInput.name = 'category_id';
                form.appendChild(categoryInput);
            }
            categoryInput.value = parentSelect.value;
        }

        // Always fetch slug before submit
        if (name.value.length > 0) {
            try {
                const response = await fetch('/products/checkSlug?name=' + encodeURIComponent(name.value));
                const data = await response.json();
                slug.value = data.slug;
            } catch (error) {
                alert('Failed to generate slug. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                isSubmitting = false;
                return false;
            }
        } else {
            slug.value = '';
        }

        // ⬅️ SUBMIT AFTER SLUG IS READY
        form.submit();
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

    /* ⬅️ CUSTOM COLOR PICKER BULAT SEMPURNA */
    .color-picker-input {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: transparent;
        padding: 0;
        cursor: pointer;
    }

    /* Chrome/Safari/Edge */
    .color-picker-input::-webkit-color-swatch-wrapper {
        padding: 0;
        border-radius: 9999px;
    }

    .color-picker-input::-webkit-color-swatch {
        border: none;
        border-radius: 9999px;
    }

    /* Firefox */
    .color-picker-input::-moz-color-swatch {
        border: none;
        border-radius: 9999px;
    }

    .color-picker-input::-moz-focus-inner {
        border: 0;
        padding: 0;
    }

    /* Hover & Focus States */
    .color-picker-input:hover {
        border-color: #9ca3af;
    }

    .color-picker-input:focus {
        outline: none;
        border-color: #000000;
    }
</style>
@endsection