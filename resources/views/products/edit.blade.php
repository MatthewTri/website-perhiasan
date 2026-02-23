@extends('layout.checkout')

@section('content')
<div class="min-h-screen bg-white pb-20">
    
    {{-- 1. ELEGANT HEADER --}}
    <div class="max-w-screen-2xl mx-auto px-4 lg:px-6 pt-10 pb-12">
        <div class="flex flex-col items-center justify-center text-center">
            <span class="text-[10px] font-bold tracking-[0.2em] text-gray-400 uppercase mb-2">Inventory Management</span>
            <h1 class="text-3xl md:text-4xl font-serif text-gray-900 tracking-widest uppercase">
                Edit Masterpiece
            </h1>
            <div class="h-px w-16 bg-gray-900 mt-6"></div>
        </div>
    </div>

    {{-- Error Summary --}}
    @if ($errors->any())
        <div class="max-w-screen-2xl mx-auto px-4 lg:px-6 mb-8">
            <div class="bg-red-50 border-l-2 border-red-500 p-4">
                <p class="text-xs font-bold text-red-500 uppercase tracking-widest">Please correct the following errors:</p>
                <ul class="mt-2 text-xs text-red-600 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form id="editProductForm" action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="max-w-screen-2xl mx-auto px-4 lg:px-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-16">
            
            {{-- LEFT COLUMN: VISUALS (Sticky) --}}
            <div class="lg:col-span-6 xl:col-span-5">
                <div class="sticky top-24">
                    <div class="text-xs font-bold tracking-[0.15em] text-gray-900 uppercase mb-6 border-b border-gray-100 pb-2 text-center">
                        Visual Assets
                    </div>

                    <div class="w-full max-w-[480px] mx-auto space-y-4">
                        
                        {{-- 1. MAIN IMAGE --}}
                        <div class="group relative aspect-square w-full bg-[#F9F9F9] border border-gray-100 transition-all hover:border-gray-300 overflow-hidden cursor-pointer shadow-sm hover:shadow-md rounded-sm">
                            
                            <div id="mainImagePreview" class="w-full h-full">
                                @if($product->images->count() > 0)
                                    <div id="noImageState" class="w-full h-full flex flex-col items-center justify-center text-gray-300 transition-colors group-hover:text-gray-400 hidden">
                                        <span class="text-4xl font-thin mb-3">+</span>
                                        <span class="text-[10px] uppercase tracking-widest">Cover Image</span>
                                    </div>
                                    <img id="mainImage" src="{{ asset('storage/' . $product->featuredImage->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Main product image">
                                @else
                                    <div id="noImageState" class="w-full h-full flex flex-col items-center justify-center text-gray-300 transition-colors group-hover:text-gray-400">
                                        <span class="text-4xl font-thin mb-3">+</span>
                                        <span class="text-[10px] uppercase tracking-widest">Cover Image</span>
                                    </div>
                                    <img id="mainImage" class="w-full h-full object-cover hidden transition-transform duration-700 group-hover:scale-105" alt="Main product image">
                                @endif
                            </div>

                            <input type="file" id="mainImageInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 {{ $product->images->count() > 0 ? 'pointer-events-none' : '' }}" accept="image/*" onchange="handleMainImageUpload(this)" {{ $product->images->count() > 0 ? 'disabled' : '' }}>
                            
                            <div class="absolute bottom-0 left-0 w-full bg-white/90 backdrop-blur py-3 text-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-900">Change Cover</span>
                            </div>
                        </div>
                        @error('images') <p class="text-red-500 text-[10px] tracking-wide uppercase text-center mt-1">{{ $message }}</p> @enderror

                        {{-- 2. THUMBNAILS --}}
                        <div class="grid grid-cols-5 gap-y-4 gap-x-2">
                            <div id="thumbnailContainer" class="contents">
                                {{-- Existing Images --}}
                                @foreach($product->images as $index => $image)
                                    <div class="relative existing-image-thumb" data-existing-id="{{ $image->id }}">
                                        <div class="thumbnail-item aspect-square cursor-pointer overflow-hidden border-2 {{ $index === 0 ? 'border-gray-900' : 'border-gray-100' }} hover:border-gray-400 transition-colors rounded-sm" 
                                             data-index="{{ $index }}"
                                             onclick="setMainImageFromExisting('{{ asset('storage/' . $image->image) }}', {{ $index }})">
                                            <img src="{{ asset('storage/' . $image->image) }}" class="w-full h-full object-cover">
                                        </div>
                                        
                                        @if($index === 0)
                                        <span class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 text-[9px] font-bold text-gray-900 tracking-widest uppercase">COVER</span>
                                        @endif
                                        
                                        <button type="button" onclick="event.stopPropagation(); removeExistingImage({{ $image->id }}, this)"
                                                class="absolute -top-1.5 -right-1.5 bg-white text-gray-900 border border-gray-200 shadow-sm rounded-full w-4 h-4 flex items-center justify-center hover:bg-red-500 hover:text-white hover:border-red-500 transition-all z-10">
                                            <span class="text-[10px] leading-none mb-0.5">&times;</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            
                            {{-- Add More Button --}}
                            <div id="addMoreButton" class="{{ $product->images->count() > 0 ? '' : 'hidden' }} aspect-square border border-dashed border-gray-300 flex items-center justify-center cursor-pointer hover:bg-gray-50 hover:border-gray-400 transition-all">
                                <label for="additionalImages" class="w-full h-full flex items-center justify-center cursor-pointer">
                                    <span class="text-xl text-gray-400 font-light">+</span>
                                </label>
                                <input type="file" id="additionalImages" class="hidden" accept="image/*" multiple onchange="handleAdditionalImages(this)">
                            </div>
                        </div>

                        {{-- Hidden Form Inputs --}}
                        <input type="file" name="images[]" id="formImages" class="hidden" multiple accept="image/*">
                        <input type="hidden" name="deleted_images" id="deletedImages" value="">
                        
                        <p class="text-[10px] text-gray-400 text-center uppercase tracking-wider pt-2">
                            Recommended: 1080 x 1080px (Square)
                        </p>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: DETAILS --}}
            <div class="lg:col-span-6 xl:col-span-7 space-y-12">
                
                {{-- SECTION 1: ESSENTIALS --}}
                <div class="space-y-8">
                    <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">01. The Essentials</h2>
                    </div>

                    <div class="group">
                        <label for="name" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-gray-900 transition-colors">Product Name</label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $product->name) }}"
                               class="w-full bg-transparent border-b border-gray-200 py-3 text-xl md:text-2xl text-gray-900 placeholder-gray-200 focus:border-gray-900 focus:outline-none transition-all"
                               placeholder="e.g. The Sapphire Eternity Ring">
                        <input type="hidden" name="slug" id="slug" value="{{ old('slug', $product->slug) }}">
                        @error('name') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group">
                            <label for="price_idr" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-gray-900">Price (IDR)</label>
                            <div class="flex items-center border-b border-gray-200 group-focus-within:border-gray-900 transition-colors">
                                <span class="text-sm text-gray-400 mr-2">Rp</span>
                                <input type="number" id="price_idr" name="price_idr" value="{{ old('price_idr', $product->price_idr) }}" step="0.01" required
                                       class="w-full bg-transparent border-none py-3 text-lg font-light text-gray-900 focus:ring-0 placeholder-gray-200"
                                       placeholder="0">
                            </div>
                            @error('price_idr') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="group">
                            <label for="price_usd" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-gray-900">Price (USD)</label>
                            <div class="flex items-center border-b border-gray-200 group-focus-within:border-gray-900 transition-colors">
                                <span class="text-sm text-gray-400 mr-2">$</span>
                                <input type="number" id="price_usd" name="price_usd" value="{{ old('price_usd', $product->price_usd) }}" step="0.01" required
                                       class="w-full bg-transparent border-none py-3 text-lg font-light text-gray-900 focus:ring-0 placeholder-gray-200"
                                       placeholder="0.00">
                            </div>
                            @error('price_usd') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: CATEGORIZATION --}}
                <div class="space-y-8">
                    <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">02. Categorization</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group relative">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Collection (Parent)</label>
                            <select name="parent_category_id" id="parent_category_id"
                                    class="w-full bg-transparent border-b border-gray-200 py-3 text-sm text-gray-900 focus:border-gray-900 focus:ring-0 focus:outline-none appearance-none cursor-pointer" required>
                                <option value="">Select Collection</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_category_id', $selectedParentId) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_category_id') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="group relative">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Category (Sub)</label>
                            <select name="category_id" id="category_id"
                                    class="w-full bg-transparent border-b border-gray-200 py-3 text-sm text-gray-900 focus:border-gray-900 focus:ring-0 focus:outline-none appearance-none cursor-pointer">
                                <option value="">Select Parent First</option>
                            </select>
                            @error('category_id') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: VARIANTS --}}
                <div class="space-y-6">
                    <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">03. Colour</h2>
                        <button type="button" onclick="addColorOption()" class="text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-2">
                            <span>+ Add Color</span>
                        </button>
                    </div>

                    <div id="colorsContainer" class="space-y-0 divide-y divide-gray-50">
                        @forelse($product->colors as $index => $color)
                        <div class="color-option py-4 flex items-center gap-4 group" data-index="{{ $index }}" data-existing-id="{{ $color->id }}">
                            <div class="relative w-10 h-10 flex-shrink-0">
                                <input type="color" name="colors[{{ $index }}][hex_code]" value="{{ $color->hex_code }}" 
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="updateColorPreview(this, {{ $index }})">
                                <div class="w-full h-full rounded-full border border-gray-200 shadow-sm color-preview-{{ $index }}" style="background-color: {{ $color->hex_code }}"></div>
                            </div>
                            <div class="flex-1">
                                <input type="text" name="colors[{{ $index }}][name]" value="{{ $color->name }}" 
                                       placeholder="Color Name (e.g. Midnight Blue)" 
                                       class="w-full bg-transparent border-b border-transparent group-hover:border-gray-200 focus:border-gray-900 py-2 text-sm text-gray-900 focus:outline-none transition-colors" required>
                                <input type="hidden" name="colors[{{ $index }}][id]" value="{{ $color->id }}">
                            </div>
                            <button type="button" onclick="removeColorOption({{ $index }})" class="text-gray-300 hover:text-red-500 transition-colors text-xs uppercase tracking-widest">Remove</button>
                        </div>
                        @empty
                        <div id="noColorsText" class="py-6 text-center">
                            <p class="text-sm font-light text-gray-400 italic">No colour variants defined.</p>
                        </div>
                        @endforelse
                    </div>
                    <input type="hidden" name="deleted_colors" id="deletedColors" value="">
                    @error('colors') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- SECTION 4: SIZE OPTIONS --}}
                <div class="space-y-6">
                    <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">04. Size Options</h2>
                        <button type="button" onclick="addSizeOption()" class="text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-2">
                            <span>+ Add Size</span>
                        </button>
                    </div>

                    <div id="sizesContainer" class="space-y-0 divide-y divide-gray-50">
                        @forelse($product->sizes as $index => $size)
                        <div class="size-option py-4 flex items-center gap-4 group" data-index="{{ $index }}" data-existing-id="{{ $size->id }}">
                            <div class="flex-1">
                                <input type="text" name="sizes[{{ $index }}][size_name]" value="{{ $size->size_name }}" 
                                    placeholder="Size Name (e.g. M, Ring 7)" 
                                    class="w-full bg-transparent border-b border-transparent group-hover:border-gray-200 focus:border-gray-900 py-2 text-sm text-gray-900 focus:outline-none transition-colors" required>
                                <input type="hidden" name="sizes[{{ $index }}][id]" value="{{ $size->id }}">
                            </div>
                            <button type="button" onclick="removeSizeOption({{ $index }})" class="text-gray-300 hover:text-red-500 transition-colors text-xs uppercase tracking-widest">Remove</button>
                        </div>
                        @empty
                        <div id="noSizesText" class="py-6 text-center">
                            <p class="text-sm font-light text-gray-400 italic">No size options defined.</p>
                        </div>
                        @endforelse
                    </div>
                    <input type="hidden" name="deleted_sizes" id="deletedSizes" value="">
                    @error('sizes') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- SECTION 5: SPECIFICATIONS --}}
                <div class="space-y-6">
                    <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">05. Additional Information</h2>
                        <button type="button" onclick="addSpecificationSection()" class="text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-2">
                            <span>+ Add Section</span>
                        </button>
                    </div>

                    <div id="specificationsContainer" class="space-y-6">
                        @forelse($product->specifications as $index => $spec)
                        <div class="specification-section border border-gray-100 p-6 bg-[#FAFAFA] relative group transition-all hover:border-gray-200" data-index="{{ $index }}" data-existing-id="{{ $spec->id }}">
                            <div class="mb-4 pr-10">
                                <input type="text" name="specifications[{{ $index }}][section_title]" value="{{ $spec->section_title }}" 
                                       placeholder="SECTION TITLE (e.g. MATERIALS)" 
                                       class="w-full bg-transparent text-xs font-bold tracking-[0.2em] uppercase text-gray-900 placeholder-gray-300 border-none p-0 focus:ring-0"
                                       required>
                                <input type="hidden" name="specifications[{{ $index }}][id]" value="{{ $spec->id }}">
                            </div>
                            <button type="button" onclick="removeSpecificationSection({{ $index }})" class="absolute top-6 right-6 text-[10px] uppercase tracking-widest text-gray-300 hover:text-red-500">Remove Section</button>
                            
                            <div class="spec-items-container pl-1 border-l border-gray-200" id="spec-items-{{ $index }}">
                                @foreach($spec->items as $itemIndex => $item)
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                                    <input type="text" name="specifications[{{ $index }}][items][]" value="{{ $item }}" 
                                           placeholder="Detail item" 
                                           class="flex-1 bg-transparent border-b border-transparent hover:border-gray-200 focus:border-gray-900 text-sm py-1 focus:outline-none font-light"
                                           required>
                                    <button type="button" onclick="removeSpecItem(this)" class="text-gray-300 hover:text-red-500">&times;</button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addSpecItem({{ $index }})" class="mt-3 text-[10px] uppercase tracking-widest text-gray-400 hover:text-gray-900 ml-4">+ Add Item</button>
                        </div>
                        @empty
                        <div id="noSpecsText" class="py-6 text-center">
                            <p class="text-sm font-light text-gray-400 italic">No detailed specifications added.</p>
                        </div>
                        @endforelse
                    </div>
                    <input type="hidden" name="deleted_specifications" id="deletedSpecifications" value="">
                    @error('specifications') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- SECTION 6: STORYTELLING --}}
                <div class="space-y-4">
                    <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">06. The Story</h2>
                    </div>
                    
                    <div class="group">
                        <textarea name="description" id="description" rows="8"
                                  class="w-full bg-[#FAFAFA] border-0 p-6 text-sm text-gray-600 font-light leading-relaxed focus:ring-1 focus:ring-gray-200 resize-none placeholder-gray-400"
                                  placeholder="Describe the craftsmanship, materials, and inspiration behind this piece...">{{ old('description', $product->description) }}</textarea>
                    </div>
                    @error('description') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="pt-10 flex flex-col md:flex-row gap-4 items-center justify-end">
                    <a href="{{ route('products.index') }}" class="px-8 py-4 text-xs font-bold tracking-[0.2em] uppercase text-gray-500 hover:text-gray-900 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="w-full md:w-auto px-12 py-4 bg-gray-900 text-white text-xs font-bold tracking-[0.2em] uppercase hover:bg-black transition-all hover:shadow-lg transform hover:-translate-y-0.5">
                        Update Product
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    // ==========================================
    // GLOBAL VARIABLES
    // ==========================================
    let selectedFiles = [];
    let currentPreviewIndex = 0;
    let deletedImageIds = [];
    let hasExistingImages = {{ $product->images->count() > 0 ? 'true' : 'false' }};
    const form = document.querySelector('#editProductForm');
    let isSubmitting = false;
    
    // Color & Spec Management
    let colorIndex = {{ $product->colors->count() }};
    let deletedColorIds = [];
    let specIndex = {{ $product->specifications->count() }};
    let deletedSpecificationIds = [];

    // ==========================================
    // 1. SLUG GENERATOR
    // ==========================================
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');
    const productId = {{ $product->id }};
    const originalName = "{{ $product->name }}";
    let timeout = null;

    name.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            if (name.value.length > 0 && name.value !== originalName) {
                fetch('/products/checkSlug?name=' + encodeURIComponent(name.value) + '&id=' + productId)
                .then(response => response.json())
                .then(data => slug.value = data.slug)
                .catch(error => console.log('Error:', error));
            }
        }, 300);
    });

    // ==========================================
    // 2. IMAGE MANAGEMENT (EDIT MODE)
    // ==========================================
    
    function handleMainImageUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            selectedFiles = [file];
            currentPreviewIndex = 0;
            updateImageDisplay();
            updateFormFiles();
            input.disabled = true;
            input.style.pointerEvents = 'none';
        }
    }

    function handleAdditionalImages(input) {
        if (input.files && input.files.length > 0) {
            Array.from(input.files).forEach(file => {
                selectedFiles.push(file);
            });
            updateImageDisplay();
            updateFormFiles();
        }
        input.value = '';
    }

    function setMainImageFromExisting(imageSrc, index) {
        document.getElementById('mainImage').src = imageSrc;
        document.getElementById('mainImage').classList.remove('hidden');
        document.getElementById('noImageState').classList.add('hidden');
        
        // Update borders
        document.querySelectorAll('.existing-image-thumb .thumbnail-item').forEach(el => {
            el.classList.remove('border-gray-900');
            el.classList.add('border-gray-100');
        });
        
        document.querySelectorAll('.new-image-thumb .border-gray-900').forEach(el => {
            el.classList.remove('border-gray-900');
            el.classList.add('border-gray-100');
        });
        
        const existingThumbnails = document.querySelectorAll('.existing-image-thumb');
        if (existingThumbnails[index] && existingThumbnails[index].style.opacity !== '0.5') {
            const thumbnail = existingThumbnails[index].querySelector('.thumbnail-item');
            thumbnail.classList.remove('border-gray-100');
            thumbnail.classList.add('border-gray-900');
        }
    }

    function removeExistingImage(imageId, button) {
        if (!deletedImageIds.includes(imageId.toString())) {
            deletedImageIds.push(imageId.toString());
        }
        
        const thumbnail = button.closest('.existing-image-thumb');
        thumbnail.style.opacity = '0.5';
        thumbnail.style.pointerEvents = 'none';
        button.style.display = 'none';
        
        if (!thumbnail.querySelector('.deleted-image-label')) {
            const deletedLabel = document.createElement('div');
            deletedLabel.className = 'absolute -bottom-4 left-1/2 transform -translate-x-1/2 text-[9px] text-red-500 font-medium whitespace-nowrap deleted-image-label tracking-widest uppercase';
            deletedLabel.textContent = 'Deleted';
            thumbnail.appendChild(deletedLabel);
        }
        
        document.getElementById('deletedImages').value = deletedImageIds.join(',');
        
        const wasMainImage = thumbnail.querySelector('.border-gray-900');
        if (wasMainImage) {
            const remainingExisting = Array.from(document.querySelectorAll('.existing-image-thumb'))
                .find(thumb => thumb.style.opacity !== '0.5');
            
            if (remainingExisting) {
                const img = remainingExisting.querySelector('img');
                const index = Array.from(document.querySelectorAll('.existing-image-thumb')).indexOf(remainingExisting);
                setMainImageFromExisting(img.src, index);
            } else if (selectedFiles.length > 0) {
                setMainImage(0);
            } else {
                document.getElementById('noImageState').classList.remove('hidden');
                document.getElementById('mainImage').classList.add('hidden');
            }
        }
        
        checkMainImageInputState();
    }

    function updateImageDisplay() {
        const thumbnailContainer = document.getElementById('thumbnailContainer');
        const addMoreButton = document.getElementById('addMoreButton');

        const existingNewThumbs = document.querySelectorAll('.new-image-thumb');
        existingNewThumbs.forEach(thumb => thumb.remove());

        if (selectedFiles.length > 0) {
            selectedFiles.forEach((file, index) => {
                const thumbnailDiv = document.createElement('div');
                thumbnailDiv.className = 'relative new-image-thumb';
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const isCover = (index === 0 && !hasActiveExistingImage());
                    const isActive = (index === currentPreviewIndex && !hasActiveExistingImage());
                    
                    thumbnailDiv.innerHTML = `
                        <div class="thumbnail-item aspect-square cursor-pointer overflow-hidden border-2 ${isActive ? 'border-gray-900' : 'border-gray-100'} hover:border-gray-400 transition-colors rounded-sm" 
                             data-index="${index}"
                             onclick="setMainImage(${index})">
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                        </div>
                        
                        ${isCover ? '<span class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 text-[9px] font-bold text-green-600 tracking-widest uppercase">NEW</span>' : ''}
                        
                        <button type="button" onclick="event.stopPropagation(); removeNewImage(${index})"
                                class="absolute -top-1.5 -right-1.5 bg-white text-gray-900 border border-gray-200 shadow-sm rounded-full w-4 h-4 flex items-center justify-center hover:bg-red-500 hover:text-white hover:border-red-500 transition-all z-10">
                            <span class="text-[10px] leading-none mb-0.5">&times;</span>
                        </button>
                    `;
                    
                    if (index === currentPreviewIndex && !hasActiveExistingImage()) {
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

    function hasActiveExistingImage() {
        const existingThumbs = document.querySelectorAll('.existing-image-thumb');
        return Array.from(existingThumbs).some(thumb => thumb.style.opacity !== '0.5');
    }

    function setMainImage(index) {
        if (index < selectedFiles.length) {
            currentPreviewIndex = index;
            
            document.querySelectorAll('.border-gray-900').forEach(el => {
                el.classList.remove('border-gray-900');
                el.classList.add('border-gray-100');
            });
            
            const newThumbs = document.querySelectorAll('.new-image-thumb');
            if (newThumbs[index]) {
                const thumbnail = newThumbs[index].querySelector('.thumbnail-item');
                thumbnail.classList.remove('border-gray-100');
                thumbnail.classList.add('border-gray-900');
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('mainImage').src = e.target.result;
                document.getElementById('mainImage').classList.remove('hidden');
                document.getElementById('noImageState').classList.add('hidden');
            };
            reader.readAsDataURL(selectedFiles[index]);
        }
    }

    function removeNewImage(index) {
        selectedFiles.splice(index, 1);
        
        if (currentPreviewIndex >= selectedFiles.length) {
            currentPreviewIndex = Math.max(0, selectedFiles.length - 1);
        }
        
        updateImageDisplay();
        updateFormFiles();
    }

    function checkMainImageInputState() {
        const hasActiveExisting = hasActiveExistingImage();
        const hasNewImages = selectedFiles.length > 0;
        
        const mainImageInput = document.getElementById('mainImageInput');
        
        if (!hasActiveExisting && !hasNewImages) {
            mainImageInput.disabled = false;
            mainImageInput.style.pointerEvents = 'auto';
            document.getElementById('addMoreButton').classList.add('hidden');
        } else {
            mainImageInput.disabled = true;
            mainImageInput.style.pointerEvents = 'none';
            document.getElementById('addMoreButton').classList.remove('hidden');
        }
    }

    function updateFormFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => {
            dt.items.add(file);
        });
        document.getElementById('formImages').files = dt.files;
        document.getElementById('deletedImages').value = deletedImageIds.join(',');
    }

    // ==========================================
    // 3. COLOR MANAGEMENT
    // ==========================================
    
    function addColorOptionWithData(colorName = '', hexCode = '#000000') {
        const container = document.getElementById('colorsContainer');
        const noColorsText = document.getElementById('noColorsText');
        if(noColorsText) noColorsText.style.display = 'none';

        const div = document.createElement('div');
        div.className = 'color-option py-4 flex items-center gap-4 group';
        div.dataset.index = colorIndex;
        div.innerHTML = `
            <div class="relative w-10 h-10 flex-shrink-0">
                <input type="color" name="colors[${colorIndex}][hex_code]" value="${hexCode}" 
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="updateColorPreview(this, ${colorIndex})">
                <div class="w-full h-full rounded-full border border-gray-200 shadow-sm color-preview-${colorIndex}" style="background-color: ${hexCode}"></div>
            </div>
            <div class="flex-1">
                <input type="text" name="colors[${colorIndex}][name]" value="${colorName}" 
                       placeholder="Color Name (e.g. Midnight Blue)" 
                       class="w-full bg-transparent border-b border-transparent group-hover:border-gray-200 focus:border-gray-900 py-2 text-sm text-gray-900 focus:outline-none transition-colors" required>
            </div>
            <button type="button" onclick="removeColorOption(${colorIndex})" class="text-gray-300 hover:text-red-500 transition-colors text-xs uppercase tracking-widest">Remove</button>
        `;
        container.appendChild(div);
        colorIndex++;
    }

    function addColorOption() { addColorOptionWithData(); }
    
    function updateColorPreview(input, index) {
        const preview = document.querySelector(`.color-preview-${index}`);
        if(preview) preview.style.backgroundColor = input.value;
    }
    
    function removeColorOption(index) {
        const colorDiv = document.querySelector(`.color-option[data-index="${index}"]`);
        
        if (colorDiv) {
            const existingId = colorDiv.dataset.existingId;
            
            if (existingId) {
                if (!deletedColorIds.includes(existingId)) {
                    deletedColorIds.push(existingId);
                }
                document.getElementById('deletedColors').value = deletedColorIds.join(',');
                
                colorDiv.style.opacity = '0.5';
                colorDiv.style.pointerEvents = 'none';
                
                const removeBtn = colorDiv.querySelector('button');
                if (removeBtn) removeBtn.style.display = 'none';
                
                if (!colorDiv.querySelector('.deleted-label')) {
                    const deletedLabel = document.createElement('span');
                    deletedLabel.className = 'text-xs text-red-600 font-medium deleted-label uppercase tracking-widest';
                    deletedLabel.textContent = 'Will be deleted';
                    colorDiv.querySelector('.flex-1').appendChild(deletedLabel);
                }
            } else {
                colorDiv.remove();
            }
        }

        const container = document.getElementById('colorsContainer');
        const noColorsText = document.getElementById('noColorsText');
        const remainingColors = container.querySelectorAll('.color-option:not([style*="opacity: 0.5"])');
        
        if (remainingColors.length === 0 && noColorsText) {
            noColorsText.style.display = 'block';
        }
    }

    // ==========================================
    // 4. SIZE MANAGEMENT
    // ==========================================

    let sizeIndex = {{ $product->sizes->count() }};
    let deletedSizeIds = [];

    function addSizeOptionWithData(sizeName = '') {
        const container = document.getElementById('sizesContainer');
        const noSizesText = document.getElementById('noSizesText');
        if(noSizesText) noSizesText.style.display = 'none';

        const div = document.createElement('div');
        div.className = 'size-option py-4 flex items-center gap-4 group';
        div.dataset.index = sizeIndex;
        div.innerHTML = `
            <div class="flex-1">
                <input type="text" name="sizes[${sizeIndex}][size_name]" value="${sizeName}" 
                    placeholder="Size Name (e.g. M, Ring 7, Bracelet 18cm)" 
                    class="w-full bg-transparent border-b border-transparent group-hover:border-gray-200 focus:border-gray-900 py-2 text-sm text-gray-900 focus:outline-none transition-colors" required>
            </div>
            <button type="button" onclick="removeSizeOption(${sizeIndex})" class="text-gray-300 hover:text-red-500 transition-colors text-xs uppercase tracking-widest">Remove</button>
        `;
        container.appendChild(div);
        sizeIndex++;
    }

    function addSizeOption() { 
        addSizeOptionWithData(); 
    }

    function removeSizeOption(index) {
        const sizeDiv = document.querySelector(`.size-option[data-index="${index}"]`);
        
        if (sizeDiv) {
            const existingId = sizeDiv.dataset.existingId;
            
            if (existingId) {
                // Existing size - mark for deletion
                if (!deletedSizeIds.includes(existingId)) {
                    deletedSizeIds.push(existingId);
                }
                document.getElementById('deletedSizes').value = deletedSizeIds.join(',');
                
                sizeDiv.style.opacity = '0.5';
                sizeDiv.style.pointerEvents = 'none';
                
                const removeBtn = sizeDiv.querySelector('button');
                if (removeBtn) removeBtn.style.display = 'none';
                
                if (!sizeDiv.querySelector('.deleted-label')) {
                    const deletedLabel = document.createElement('span');
                    deletedLabel.className = 'text-xs text-red-600 font-medium deleted-label uppercase tracking-widest ml-2';
                    deletedLabel.textContent = 'Will be deleted';
                    sizeDiv.querySelector('.flex-1').appendChild(deletedLabel);
                }
            } else {
                // New size - just remove from DOM
                sizeDiv.remove();
            }
        }

        const container = document.getElementById('sizesContainer');
        const noSizesText = document.getElementById('noSizesText');
        const remainingSizes = container.querySelectorAll('.size-option:not([style*="opacity: 0.5"])');
        
        if (remainingSizes.length === 0 && noSizesText) {
            noSizesText.style.display = 'block';
        }
    }

    // ==========================================
    // 5. SPECIFICATIONS MANAGEMENT
    // ==========================================
    
    function addSpecificationSectionWithData(title = '', items = ['']) {
        const container = document.getElementById('specificationsContainer');
        const noSpecsText = document.getElementById('noSpecsText');
        if(noSpecsText) noSpecsText.style.display = 'none';

        const div = document.createElement('div');
        div.className = 'specification-section border border-gray-100 p-6 bg-[#FAFAFA] relative group transition-all hover:border-gray-200';
        div.dataset.index = specIndex;
        
        let itemsHtml = '';
        if(Array.isArray(items)) {
            itemsHtml = items.map(item => `
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                    <input type="text" name="specifications[${specIndex}][items][]" value="${item}" 
                           placeholder="Detail item" 
                           class="flex-1 bg-transparent border-b border-transparent hover:border-gray-200 focus:border-gray-900 text-sm py-1 focus:outline-none font-light"
                           required>
                    <button type="button" onclick="removeSpecItem(this)" class="text-gray-300 hover:text-red-500">&times;</button>
                </div>
            `).join('');
        }

        div.innerHTML = `
            <div class="mb-4 pr-10">
                <input type="text" name="specifications[${specIndex}][section_title]" value="${title}" 
                       placeholder="SECTION TITLE (e.g. MATERIALS)" 
                       class="w-full bg-transparent text-xs font-bold tracking-[0.2em] uppercase text-gray-900 placeholder-gray-300 border-none p-0 focus:ring-0"
                       required>
            </div>
            <button type="button" onclick="removeSpecificationSection(${specIndex})" class="absolute top-6 right-6 text-[10px] uppercase tracking-widest text-gray-300 hover:text-red-500">Remove Section</button>
            
            <div class="spec-items-container pl-1 border-l border-gray-200" id="spec-items-${specIndex}">
                ${itemsHtml}
            </div>
            <button type="button" onclick="addSpecItem(${specIndex})" class="mt-3 text-[10px] uppercase tracking-widest text-gray-400 hover:text-gray-900 ml-4">+ Add Item</button>
        `;
        container.appendChild(div);
        specIndex++;
    }

    function addSpecificationSection() { addSpecificationSectionWithData(); }
    
    function addSpecItem(idx) {
        const container = document.getElementById(`spec-items-${idx}`);
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 mb-2';
        div.innerHTML = `
            <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
            <input type="text" name="specifications[${idx}][items][]" 
                   placeholder="New Detail" 
                   class="flex-1 bg-transparent border-b border-transparent hover:border-gray-200 focus:border-gray-900 text-sm py-1 focus:outline-none font-light"
                   required>
            <button type="button" onclick="removeSpecItem(this)" class="text-gray-300 hover:text-red-500">&times;</button>
        `;
        container.appendChild(div);
    }

    function removeSpecItem(btn) {
        const container = btn.closest('.spec-items-container');
        if(container.children.length > 1) btn.parentElement.remove();
    }

    function removeSpecificationSection(index) {
        const specDiv = document.querySelector(`.specification-section[data-index="${index}"]`);
        
        if (specDiv) {
            const existingId = specDiv.dataset.existingId;
            
            if (existingId) {
                if (!deletedSpecificationIds.includes(existingId)) {
                    deletedSpecificationIds.push(existingId);
                }
                document.getElementById('deletedSpecifications').value = deletedSpecificationIds.join(',');
                
                specDiv.style.opacity = '0.5';
                specDiv.style.pointerEvents = 'none';
                
                const removeBtn = specDiv.querySelector('button[onclick*="removeSpecificationSection"]');
                if (removeBtn) removeBtn.style.display = 'none';
                
                if (!specDiv.querySelector('.deleted-spec-label')) {
                    const deletedLabel = document.createElement('span');
                    deletedLabel.className = 'text-xs text-red-600 font-medium deleted-spec-label ml-2 uppercase tracking-widest';
                    deletedLabel.textContent = 'Will be deleted';
                    specDiv.querySelector('.mb-4').appendChild(deletedLabel);
                }
            } else {
                specDiv.remove();
            }
        }

        const container = document.getElementById('specificationsContainer');
        const noSpecsText = document.getElementById('noSpecsText');
        const remainingSpecs = container.querySelectorAll('.specification-section:not([style*="opacity: 0.5"])');
        
        if (remainingSpecs.length === 0 && noSpecsText) {
            noSpecsText.style.display = 'block';
        }
    }

    // ==========================================
    // 6. CATEGORY MANAGEMENT
    // ==========================================
    
    const allCategories = @json($allCategories);
    const initialParentId = {{ $selectedParentId ?? 'null' }};
    const initialSubId = {{ $selectedSubCategoryId ?? 'null' }};

    const parentSelect = document.getElementById('parent_category_id');
    const subSelect = document.getElementById('category_id');

    function loadSubCategories(parentId, selectedSubId = null) {
        subSelect.innerHTML = '<option value="">None</option>';
        subSelect.disabled = true;
        
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
            } else {
                subSelect.innerHTML = '<option value="">-- No sub-categories --</option>';
            }
        }
    }

    if (initialParentId) {
        loadSubCategories(initialParentId, initialSubId);
    }

    parentSelect.addEventListener('change', function() {
        loadSubCategories(this.value);
    });

    // ==========================================
    // 7. FORM SUBMISSION
    // ==========================================
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (isSubmitting) {
            return false;
        }
        
        isSubmitting = true;
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Updating...';

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

        if (name.value.length > 0) {
            if (name.value !== originalName) {
                try {
                    const response = await fetch('/products/checkSlug?name=' + encodeURIComponent(name.value) + '&id=' + productId);
                    const data = await response.json();
                    slug.value = data.slug;
                } catch (error) {
                    alert('Failed to generate slug. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    isSubmitting = false;
                    return false;
                }
            }
        } else {
            slug.value = '';
        }

        form.submit();
    });
</script>
@endsection