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
                               required
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                               placeholder="Enter product name">
                        
                        <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">
                        
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
                                    value="{{ old('price_idr') }}"
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
                                    value="{{ old('price_usd') }}"
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
                                    <option value="{{ $parent->id }}" {{ old('parent_category_id') == $parent->id ? 'selected' : '' }}>
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
                                    disabled
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 @error('category_id') border-red-500 @enderror">
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

                    <!-- COLOR SECTION Row -->
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
                            <!-- Color options will be added here dynamically -->
                            <p id="noColorsText" class="text-sm text-gray-500 text-center py-4 bg-gray-50 rounded-lg">
                                No colors added yet. Click "Add Color" to start.
                            </p>
                        </div>

                        @error('colors')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SPECIFICATIONS SECTION Row -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                Product Specifications (Optional)
                            </label>
                            <button type="button" 
                                    onclick="addSpecificationSection()"
                                    class="text-sm bg-purple-50 hover:bg-purple-100 text-purple-600 px-3 py-1.5 rounded-lg transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Section
                            </button>
                        </div>

                        <!-- Specifications Container -->
                        <div id="specificationsContainer" class="space-y-4">
                            <p id="noSpecsText" class="text-sm text-gray-500 text-center py-4 bg-gray-50 rounded-lg">
                                No specifications added yet. Click "Add Section" to start.
                            </p>
                        </div>

                        @error('specifications')
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

    // COLOR LOGIC MANAGEMENT
    let colorIndex = 0;

    @if(old('colors'))
        const oldColors = @json(old('colors'));
        oldColors.forEach((color, index) => {
            addColorOptionWithData(color.name, color.hex_code);
        });
    @endif

    function addColorOption() {
        addColorOptionWithData('', '#D4AF37');
    }

    function addColorOptionWithData(colorName = '', hexCode = '#D4AF37') {
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
                           value="${hexCode}"
                            class="color-picker-input w-8 h-8 rounded-full border-2 border-gray-300 cursor-pointer"
                           title="Choose color">
                </div>

                <!-- Color Name Input -->
                <div class="flex-1">
                    <input type="text" 
                           name="colors[${colorIndex}][name]" 
                           value="${colorName}"
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
            colorDiv.remove();
        }

        // Show "no colors" text if no colors left
        const container = document.getElementById('colorsContainer');
        const noColorsText = document.getElementById('noColorsText');
        const remainingColors = container.querySelectorAll('.color-option');
        
        if (remainingColors.length === 0 && noColorsText) {
            noColorsText.style.display = 'block';
        }
    }

    // SPECIFICATIONS MANAGEMENT
    let specIndex = 0;

    @if(old('specifications'))
        const oldSpecs = @json(old('specifications'));
        oldSpecs.forEach((spec, index) => {
            addSpecificationSectionWithData(spec.section_title, spec.items || []);
        });
    @endif

    function addSpecificationSection() {
        addSpecificationSectionWithData('', ['']);
    }

    function addSpecificationSectionWithData(sectionTitle = '', items = ['']) {
        const container = document.getElementById('specificationsContainer');
        const noSpecsText = document.getElementById('noSpecsText');
        
        if (noSpecsText) {
            noSpecsText.style.display = 'none';
        }

        const specDiv = document.createElement('div');
        specDiv.className = 'specification-section bg-purple-50 p-4 rounded-lg border border-purple-200';
        specDiv.dataset.index = specIndex;
        
        // ⬅️ GENERATE ITEMS HTML
        const itemsHtml = items.map((item, itemIndex) => `
            <div class="flex items-center gap-2">
                <input type="text" 
                       name="specifications[${specIndex}][items][]" 
                       value="${item || ''}"
                       placeholder="e.g., 18k gold vermeil"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg 
                       focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm bg-white">
                <button type="button" 
                        onclick="removeSpecItem(this)"
                        class="text-gray-400 hover:text-red-500 transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `).join('');
        
        specDiv.innerHTML = `
            <div class="space-y-3">
                <!-- Section Title -->
                <div class="flex items-center gap-2">
                    <input type="text" 
                           name="specifications[${specIndex}][section_title]" 
                           value="${sectionTitle || ''}"
                           placeholder="Section header"
                           class="flex-1 px-3 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white"
                           required>
                    
                    <button type="button" 
                            onclick="removeSpecificationSection(${specIndex})"
                            class="text-red-500 hover:text-red-700 transition-colors p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>

                <!-- Items List -->
                <div class="spec-items-container space-y-2" id="spec-items-${specIndex}">
                    ${itemsHtml}
                </div>

                <!-- Add Item Button -->
                <button type="button" 
                        onclick="addSpecItem(${specIndex})"
                        class="text-xs text-purple-600 hover:text-purple-700 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add item
                </button>
            </div>
        `;

        container.appendChild(specDiv);
        specIndex++;
    }

    function addSpecItem(sectionIndex) {
        const itemsContainer = document.getElementById(`spec-items-${sectionIndex}`);
        
        const itemDiv = document.createElement('div');
        itemDiv.className = 'flex items-center gap-2';
        itemDiv.innerHTML = `
            <input type="text" 
                   name="specifications[${sectionIndex}][items][]" 
                   placeholder="e.g., 925 sterling silver"
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm bg-white">
            <button type="button" 
                    onclick="removeSpecItem(this)"
                    class="text-gray-400 hover:text-red-500 transition-colors p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        
        itemsContainer.appendChild(itemDiv);
    }

    function removeSpecItem(button) {
        const itemsContainer = button.closest('.spec-items-container');
        const items = itemsContainer.querySelectorAll('.flex.items-center');
        
        // Minimal 1 item per section
        if (items.length > 1) {
            button.closest('.flex.items-center').remove();
        } else {
            alert('Each section must have at least one item');
        }
    }

    function removeSpecificationSection(index) {
        const specDiv = document.querySelector(`.specification-section[data-index="${index}"]`);
        if (specDiv) {
            specDiv.remove();
        }

        const container = document.getElementById('specificationsContainer');
        const noSpecsText = document.getElementById('noSpecsText');
        const remainingSpecs = container.querySelectorAll('.specification-section');
        
        if (remainingSpecs.length === 0 && noSpecsText) {
            noSpecsText.style.display = 'block';
        }
    }

    // CATEGORIES MANAGEMENT
    const allCategories = @json($allCategories);

    const parentSelect = document.getElementById('parent_category_id');
    const subSelect = document.getElementById('category_id');

    parentSelect.addEventListener('change', function() {
    const parentId = parseInt(this.value);
    
    // Reset sub-category
    subSelect.innerHTML = '<option value="">None</option>';
    subSelect.disabled = true;
    subSelect.classList.add('bg-gray-50');
    
    if (parentId) {
            // Filter children dari allCategories
            const children = allCategories.filter(cat => cat.parent_id === parentId);
            
            if (children.length > 0) {
                children.forEach(child => {
                    const option = document.createElement('option');
                    option.value = child.id;
                    option.textContent = child.name;
                    subSelect.appendChild(option);
                });
                
                subSelect.disabled = false;
                subSelect.classList.remove('bg-gray-50');
            } else {
                subSelect.innerHTML = '<option value="">No sub-categories</option>';
            }
        }
    });

        form.addEventListener('submit', function(e) {
            if (!subSelect.value && parentSelect.value) {
                // Input hidden untuk category_id (jika belum ada)
                let categoryInput = document.querySelector('input[name="category_id"]');
                if (!categoryInput) {
                    categoryInput = document.createElement('input');
                    categoryInput.type = 'hidden';
                    categoryInput.name = 'category_id';
                    productForm.appendChild(categoryInput);
                }
                categoryInput.value = parentSelect.value;
            }
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


    // IMAGE UPLOAD MANAGEMENT
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