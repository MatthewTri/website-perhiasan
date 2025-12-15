@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Add New Product</h1>
        
        <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Side - Image Upload -->
                <div class="space-y-4">
                    <!-- Main Image Preview -->
                    <div class="aspect-square bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden relative">
                        <div id="mainImagePreview" class="w-full h-full flex items-center justify-center">
                            <!-- Default state - no image -->
                            <div id="noImageState" class="text-center">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="text-gray-500 text-lg font-medium mb-2">Add an image</p>
                                <p class="text-gray-400 text-sm">Click to upload your first product image</p>
                            </div>
                            
                            <!-- Main image will be shown here -->
                            <img id="mainImage" class="w-full h-full object-cover hidden" alt="Main product image">
                        </div>
                        
                        <!-- Click overlay for main image -->
                        <input type="file" 
                               id="mainImageInput" 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                               accept="image/*" 
                               onchange="handleMainImageUpload(this)">
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    <div class="flex space-x-2 overflow-x-auto pb-2">
                        <div id="thumbnailContainer" class="flex space-x-2 pb-3">
                            <!-- Thumbnails will be added here dynamically -->
                        </div>
                        
                        <!-- Add More Images Button -->
                        <div id="addMoreButton" class="hidden">
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
                    
                    <!-- Hidden input for form submission -->
                    <input type="file" name="images[]" id="formImages" class="hidden" multiple accept="image/*">
                    
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
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                               placeholder="Enter product name">
                        
                        <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">
                        
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
                                   value="{{ old('price') }}"
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
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                   value="{{ old('stock', 0) }}"
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
                                  placeholder="Describe your product...">{{ old('description') }}</textarea>
                        
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex space-x-4 pt-1">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-200 text-lg">
                            Save Product
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
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');
    const form = document.querySelector('#productForm');

    name.addEventListener('change' , function() {
        fetch('/products/checkSlug?name=' + name.value)
        .then(response => response.json())
        .then(data => slug.value = data.slug)
    });

    form.addEventListener('submit', function(e) {
        if (name.value && !slug.value) {
            e.preventDefault(); // Stop submit
            fetch('/products/checkSlug?name=' + name.value)
            .then(response => response.json())
            .then(data => {
                slug.value = data.slug;
                form.submit(); // Submit ulang setelah slug ready
            });
        }
    });


    let selectedFiles = [];
    let currentMainIndex = 0;

    // Handle main image upload (first image ONLY)
    function handleMainImageUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            selectedFiles = [file]; // Reset with first image
            currentMainIndex = 0;
            updateImageDisplay();
            updateFormFiles();
            
            // DISABLE main image input setelah ada gambar
            input.disabled = true;
            input.style.pointerEvents = 'none';
        }
    }

    // Handle additional images
    function handleAdditionalImages(input) {
        if (input.files) {
            Array.from(input.files).forEach(file => {
                selectedFiles.push(file);
            });
            updateImageDisplay();
            updateFormFiles();
        }
        input.value = ''; // Reset input
    }

    // Update image display
    function updateImageDisplay() {
        const mainImagePreview = document.getElementById('mainImagePreview');
        const noImageState = document.getElementById('noImageState');
        const mainImage = document.getElementById('mainImage');
        const thumbnailContainer = document.getElementById('thumbnailContainer');
        const addMoreButton = document.getElementById('addMoreButton');

        if (selectedFiles.length > 0) {
            // Show main image
            noImageState.classList.add('hidden');
            mainImage.classList.remove('hidden');
            
            const reader = new FileReader();
            reader.onload = function(e) {
                mainImage.src = e.target.result;
            };
            reader.readAsDataURL(selectedFiles[currentMainIndex]);

            // Show thumbnails
            thumbnailContainer.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const thumbnailDiv = document.createElement('div');
                thumbnailDiv.className = 'relative flex-shrink-0';
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbnailDiv.innerHTML = `
                        <div class="w-16 h-16 rounded-lg overflow-hidden border-2 ${index === currentMainIndex ? 'border-blue-500' : 'border-gray-200'} cursor-pointer hover:border-blue-300 transition-colors" 
                             onclick="setMainImage(${index})">
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                        </div>
                        <button type="button" 
                                onclick="removeImage(${index})"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-tr-lg
                                rounded-bl-xs font-bold w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                            ×
                        </button>
                        ${index === 0 ? '<span class="absolute -bottom-5 left-1/2 transform -translate-x-1/2 text-xs text-blue-500 font-medium">Featured</span>' : ''}

                    `;
                };
                reader.readAsDataURL(file);
                
                thumbnailContainer.appendChild(thumbnailDiv);
            });

            // Show add more button
            addMoreButton.classList.remove('hidden');
        } else {
            // Show no image state & re-enable main image input
            noImageState.classList.remove('hidden');
            mainImage.classList.add('hidden');
            thumbnailContainer.innerHTML = '';
            addMoreButton.classList.add('hidden');
            
            // RE-ENABLE main image input saat tidak ada gambar
            const mainImageInput = document.getElementById('mainImageInput');
            mainImageInput.disabled = false;
            mainImageInput.style.pointerEvents = 'auto';
        }
    }

    // Set main image
    function setMainImage(index) {
        if (index < selectedFiles.length) {
            currentMainIndex = index;
            updateImageDisplay();
        }
    }

    // Remove image
    function removeImage(index) {
        selectedFiles.splice(index, 1);
        
        // Adjust current main index if needed
        if (currentMainIndex >= selectedFiles.length) {
            currentMainIndex = Math.max(0, selectedFiles.length - 1);
        }
        
        updateImageDisplay();
        updateFormFiles();
    }

    // Update form files for submission
    function updateFormFiles() {
        const dt = new DataTransfer();
        
        selectedFiles.forEach(file => {
            dt.items.add(file);
        });
        
        document.getElementById('formImages').files = dt.files;
    }
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