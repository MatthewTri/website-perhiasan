@extends('layout.checkout')

@section('content')
<div class="min-h-screen bg-white pb-20">
    
    {{-- 1. ELEGANT HEADER --}}
    <div class="max-w-screen-2xl mx-auto px-4 lg:px-6 pt-10 pb-12">
        <div class="flex flex-col items-center justify-center text-center">
            <span class="text-[10px] font-bold tracking-[0.2em] text-gray-400 uppercase mb-2">Inventory Management</span>
            <h1 class="text-3xl md:text-4xl font-serif text-gray-900 tracking-widest uppercase">
                New Masterpiece
            </h1>
            <div class="h-px w-16 bg-gray-900 mt-6"></div>
        </div>
    </div>

    {{-- Error Summary (Optional - Good for UX) --}}
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

    <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="max-w-screen-2xl mx-auto px-4 lg:px-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-16">
            
            {{-- LEFT COLUMN: VISUALS (Sticky) --}}
            <div class="lg:col-span-6 xl:col-span-5">
                <div class="sticky top-24">
                    <div class="text-xs font-bold tracking-[0.15em] text-gray-900 uppercase mb-6 border-b border-gray-100 pb-2 text-center">
                        Visual Assets
                    </div>

                    {{-- 
                        PERUBAHAN DISINI: 
                        max-w-[320px] -> max-w-[480px] 
                        Agar kotaknya lebih besar/lebar tapi tetap square.
                    --}}
                    <div class="w-full max-w-[480px] mx-auto space-y-4">
                        
                        {{-- 1. MAIN IMAGE (Bigger Square) --}}
                        <div class="group relative aspect-square w-full bg-[#F9F9F9] border border-gray-100 transition-all hover:border-gray-300 overflow-hidden cursor-pointer shadow-sm hover:shadow-md rounded-sm">
                            
                            <div id="mainImagePreview" class="w-full h-full">
                                <div id="noImageState" class="w-full h-full flex flex-col items-center justify-center text-gray-300 transition-colors group-hover:text-gray-400">
                                    <span class="text-4xl font-thin mb-3">+</span>
                                    <span class="text-[10px] uppercase tracking-widest">Cover Image</span>
                                </div>
                                <img id="mainImage" class="w-full h-full object-cover hidden transition-transform duration-700 group-hover:scale-105" alt="Main product image">
                            </div>

                            <input type="file" id="mainImageInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" onchange="handleMainImageUpload(this)">
                            
                            <div class="absolute bottom-0 left-0 w-full bg-white/90 backdrop-blur py-3 text-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-900">Change Cover</span>
                            </div>
                        </div>
                        @error('images') <p class="text-red-500 text-[10px] tracking-wide uppercase text-center mt-1">{{ $message }}</p> @enderror

                        {{-- 2. THUMBNAILS (Tiny Grid) --}}
                        {{-- 
                            PERUBAHAN DISINI:
                            grid-cols-4 -> grid-cols-5
                            Karena container utamanya membesar, kita bagi 5 supaya thumbnailnya tetap kecil dan imut.
                        --}}
                        <div class="grid grid-cols-5 gap-y-4 gap-x-2">
                            <div id="thumbnailContainer" class="contents"></div>
                            
                            {{-- Add More Button --}}
                            <div id="addMoreButton" class="hidden aspect-square border border-dashed border-gray-300 flex items-center justify-center cursor-pointer hover:bg-gray-50 hover:border-gray-400 transition-all">
                                <label for="additionalImages" class="w-full h-full flex items-center justify-center cursor-pointer">
                                    <span class="text-xl text-gray-400 font-light">+</span>
                                </label>
                                <input type="file" id="additionalImages" class="hidden" accept="image/*" multiple onchange="handleAdditionalImages(this)">
                            </div>
                        </div>

                        {{-- Hidden Form Input --}}
                        <input type="file" name="images[]" id="formImages" class="hidden" multiple accept="image/*">
                        
                        <p class="text-[10px] text-gray-400 text-center uppercase tracking-wider pt-2">
                            Recommended: 1080 x 1080px (Square)
                        </p>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: DETAILS (Scrollable) --}}
            <div class="lg:col-span-6 xl:col-span-7 space-y-12">
                
                {{-- SECTION 1: ESSENTIALS --}}
                <div class="space-y-8">
                    <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">01. The Essentials</h2>
                    </div>

                    <div class="group">
                        <label for="name" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-gray-900 transition-colors">Product Name</label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}"
                               class="w-full bg-transparent border-b border-gray-200 py-3 text-xl md:text-2xl text-gray-900 placeholder-gray-200 focus:border-gray-900 focus:outline-none transition-all"
                               placeholder="e.g. The Sapphire Eternity Ring">
                        <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">
                        @error('name') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group">
                            <label for="price_idr" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-gray-900">Price (IDR)</label>
                            <div class="flex items-center border-b border-gray-200 group-focus-within:border-gray-900 transition-colors">
                                <span class="text-sm text-gray-400 mr-2">Rp</span>
                                <input type="number" id="price_idr" name="price_idr" value="{{ old('price_idr') }}" step="0.01" required
                                       class="w-full bg-transparent border-none py-3 text-lg font-light text-gray-900 focus:ring-0 placeholder-gray-200"
                                       placeholder="0">
                            </div>
                            @error('price_idr') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="group">
                            <label for="price_usd" class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-gray-900">Price (USD)</label>
                            <div class="flex items-center border-b border-gray-200 group-focus-within:border-gray-900 transition-colors">
                                <span class="text-sm text-gray-400 mr-2">$</span>
                                <input type="number" id="price_usd" name="price_usd" value="{{ old('price_usd') }}" step="0.01" required
                                       class="w-full bg-transparent border-none py-3 text-lg font-light text-gray-900 focus:ring-0 placeholder-gray-200"
                                       placeholder="0.00">
                            </div>
                             @error('price_usd') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: CATEGORIZATION (Fixed Logic) --}}
                <div class="space-y-8">
                    <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">02. Categorization</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group relative">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Collection (Parent)</label>
                            <select name="parent_category_id" id="parent_category_id" required
                                    class="w-full bg-transparent border-b border-gray-200 py-3 text-sm text-gray-900 focus:border-gray-900 focus:ring-0 focus:outline-none appearance-none cursor-pointer">
                                <option value="" class="text-gray-400">Select Collection</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_category_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_category_id') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="group relative">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Category (Sub)</label>
                            <select name="category_id" id="category_id" required disabled
                                    class="w-full bg-transparent border-b border-gray-200 py-3 text-sm text-gray-400 focus:border-gray-900 focus:ring-0 focus:outline-none appearance-none cursor-pointer disabled:opacity-50">
                                <option value="">Select Parent First</option>
                            </select>
                            @error('category_id') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: VARIANTS (With OLD Value Persistence) --}}
                <div class="space-y-6">
                    <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">03. Variations</h2>
                        <button type="button" onclick="addColorOption()" class="text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-2">
                            <span>+ Add Color</span>
                        </button>
                    </div>

                    <div id="colorsContainer" class="space-y-0 divide-y divide-gray-50">
                        <div id="noColorsText" class="py-6 text-center">
                            <p class="text-sm font-light text-gray-400 italic">No color variants defined.</p>
                        </div>
                    </div>
                    @error('colors') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- SECTION 4: SPECIFICATIONS (With OLD Value Persistence) --}}
                <div class="space-y-6">
                     <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">04. Specifications</h2>
                        <button type="button" onclick="addSpecificationSection()" class="text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-2">
                            <span>+ Add Section</span>
                        </button>
                    </div>

                    <div id="specificationsContainer" class="space-y-6">
                        <div id="noSpecsText" class="py-6 text-center">
                            <p class="text-sm font-light text-gray-400 italic">No detailed specifications added.</p>
                        </div>
                    </div>
                    @error('specifications') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- SECTION 5: STORYTELLING --}}
                <div class="space-y-4">
                     <div class="flex justify-between items-end border-b border-gray-900 pb-2 mb-6">
                        <h2 class="text-lg text-gray-900 tracking-wider italic">05. The Story</h2>
                    </div>
                    
                    <div class="group">
                        <textarea name="description" id="description" rows="8"
                                  class="w-full bg-[#FAFAFA] border-0 p-6 text-sm text-gray-600 font-light leading-relaxed focus:ring-1 focus:ring-gray-200 resize-none placeholder-gray-400"
                                  placeholder="Describe the craftsmanship, materials, and inspiration behind this piece...">{{ old('description') }}</textarea>
                    </div>
                    @error('description') <p class="text-red-500 text-[10px] uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="pt-10 flex flex-col md:flex-row gap-4 items-center justify-end">
                    <a href="{{ route('products.index') }}" class="px-8 py-4 text-xs font-bold tracking-[0.2em] uppercase text-gray-500 hover:text-gray-900 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="w-full md:w-auto px-12 py-4 bg-gray-900 text-white text-xs font-bold tracking-[0.2em] uppercase hover:bg-black transition-all hover:shadow-lg transform hover:-translate-y-0.5">
                        Publish Product
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- SCRIPT: Logic Lengkap dengan Repopulate Old Values --}}
<script>
    // 1. SLUG GENERATOR
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');
    
    if(name){
        name.addEventListener('change' , function() {
            fetch('/products/checkSlug?name=' + name.value)
            .then(response => response.json())
            .then(data => slug.value = data.slug)
        });
    }

    // ==========================================
    // 2. STABLE IMAGE MANAGER (REVISED)
    // ==========================================
    let selectedFiles = [];     // Array utama penyimpanan file
    let currentPreviewIndex = 0; // Index yang sedang dilihat (bukan mengubah urutan)

    // A. HANDLE COVER IMAGE (Index 0)
    function handleMainImageUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            if (selectedFiles.length === 0) {
                // Jika belum ada gambar, isi sebagai yang pertama
                selectedFiles.push(file);
            } else {
                // Jika sudah ada galeri, GANTI cuma yang pertama (Cover), sisanya jangan dihapus
                selectedFiles[0] = file;
            }
            
            // Set preview ke cover
            currentPreviewIndex = 0;
            refreshGalleryUI(); // Re-render struktur karena file berubah
            updateFormFiles();  // Update input hidden
        }
        // Reset input agar bisa select file yang sama jika perlu
        input.value = ''; 
    }

    // B. HANDLE ADDITIONAL IMAGES (Index 1+)
    function handleAdditionalImages(input) {
        if (input.files) {
            // Masukkan semua file baru ke belakang antrian
            Array.from(input.files).forEach(file => selectedFiles.push(file));
            
            // Jika ini gambar pertama (tadi kosong), jadikan preview
            if (selectedFiles.length === input.files.length) {
                currentPreviewIndex = 0;
            }
            
            refreshGalleryUI();
            updateFormFiles();
        }
        input.value = '';
    }

    // C. SET PREVIEW (Hanya ganti tampilan, JANGAN ubah urutan array)
    function setPreviewImage(index) {
        if (index >= selectedFiles.length) return;

        currentPreviewIndex = index;
        
        // 1. Ganti Gambar Besar
        const mainImage = document.getElementById('mainImage');
        const reader = new FileReader();
        reader.onload = (e) => mainImage.src = e.target.result;
        reader.readAsDataURL(selectedFiles[index]);

        // 2. Update Border Thumbnail (Tanpa Re-render HTML)
        document.querySelectorAll('.thumbnail-item').forEach(el => {
            el.classList.remove('border-gray-900');
            el.classList.add('border-gray-100');
        });
        
        const activeThumb = document.querySelector(`.thumbnail-item[data-index="${index}"]`);
        if(activeThumb) {
            activeThumb.classList.remove('border-gray-100');
            activeThumb.classList.add('border-gray-900');
        }
    }

    // D. REMOVE IMAGE (FIXED LOGIC)
    function removeImage(index) {
        selectedFiles.splice(index, 1);
        
        // Logic penyesuaian preview setelah hapus
        if (selectedFiles.length === 0) {
            // Jika habis, reset UI ke kosong
            resetToEmptyState();
        } else {
            // PERBAIKAN: Selalu set preview ke index 0 setelah delete
            // Karena yang index 1 sekarang menjadi index 0 (new cover)
            currentPreviewIndex = 0;
            refreshGalleryUI();
        }
        updateFormFiles();
    }

    // E. CORE UI BUILDER (FIXED - Always show index 0 as main)
    function refreshGalleryUI() {
        const noImageState = document.getElementById('noImageState');
        const mainImage = document.getElementById('mainImage');
        const thumbnailContainer = document.getElementById('thumbnailContainer');
        const addMoreButton = document.getElementById('addMoreButton');

        // 1. Tampilkan Gambar Besar (ALWAYS INDEX 0 = COVER)
        noImageState.classList.add('hidden');
        mainImage.classList.remove('hidden');
        
        // PERBAIKAN: Selalu render index 0 sebagai cover
        const readerMain = new FileReader();
        readerMain.onload = (e) => {
            mainImage.src = e.target.result;
            // Reset currentPreviewIndex ke 0 untuk konsistensi
            currentPreviewIndex = 0;
        };
        readerMain.readAsDataURL(selectedFiles[0]); // ALWAYS first file

        // 2. Bangun Thumbnail
        thumbnailContainer.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const readerThumb = new FileReader();
            readerThumb.onload = (e) => {
                const isCover = (index === 0);
                const isActive = (index === currentPreviewIndex);
                
                const div = document.createElement('div');
                div.className = 'relative';
                
                div.innerHTML = `
                    <div class="thumbnail-item relative aspect-square cursor-pointer overflow-hidden border-2 ${isActive ? 'border-gray-900' : 'border-gray-100'} hover:border-gray-400 transition-colors rounded-sm" 
                        data-index="${index}"
                        onclick="setPreviewImage(${index})">
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                    </div>
                    
                    ${isCover ? '<span class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 text-[9px] font-bold text-gray-900 tracking-widest uppercase">COVER</span>' : ''}
                    
                    <button type="button" onclick="event.stopPropagation(); removeImage(${index})" 
                            class="absolute -top-1.5 -right-1.5 bg-white text-gray-900 border border-gray-200 shadow-sm rounded-full w-4 h-4 flex items-center justify-center hover:bg-red-500 hover:text-white hover:border-red-500 transition-all z-10">
                        <span class="text-[10px] leading-none mb-0.5">&times;</span>
                    </button>
                `;
                thumbnailContainer.appendChild(div);
            };
            readerThumb.readAsDataURL(file);
        });

        // 3. Tampilkan tombol Add More
        addMoreButton.classList.remove('hidden');
    }

    function resetToEmptyState() {
        document.getElementById('noImageState').classList.remove('hidden');
        document.getElementById('mainImage').classList.add('hidden');
        document.getElementById('thumbnailContainer').innerHTML = '';
        document.getElementById('addMoreButton').classList.add('hidden');
    }

    // F. SYNC TO LARAVEL FORM
    function updateFormFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        document.getElementById('formImages').files = dt.files;
    }

    // 3. COLOR LOGIC (Updated Style + Persistence)
    let colorIndex = 0;
    
    function addColorOptionWithData(colorName = '', hexCode = '#000000') {
        const container = document.getElementById('colorsContainer');
        document.getElementById('noColorsText').style.display = 'none';

        const div = document.createElement('div');
        div.className = 'color-option py-4 flex items-center gap-4 group';
        div.dataset.index = colorIndex;
        div.innerHTML = `
            <div class="relative w-10 h-10 flex-shrink-0">
                <input type="color" name="colors[${colorIndex}][hex_code]" value="${hexCode}" 
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="w-full h-full rounded-full border border-gray-200 shadow-sm" style="background-color: ${hexCode}"></div>
            </div>
            <div class="flex-1">
                <input type="text" name="colors[${colorIndex}][name]" value="${colorName}" 
                       placeholder="Color Name (e.g. Midnight Blue)" 
                       class="w-full bg-transparent border-b border-transparent group-hover:border-gray-200 focus:border-gray-900 py-2 text-sm text-gray-900 focus:outline-none transition-colors" required>
            </div>
            <button type="button" onclick="removeColorOption(${colorIndex})" class="text-gray-300 hover:text-red-500 transition-colors text-xs uppercase tracking-widest">Remove</button>
        `;
        container.appendChild(div);
        
        // Live preview color change
        const colorInput = div.querySelector('input[type="color"]');
        const colorPreview = div.querySelector('div[style]');
        colorInput.addEventListener('input', (e) => colorPreview.style.backgroundColor = e.target.value);
        
        colorIndex++;
    }

    function addColorOption() { addColorOptionWithData(); }
    
    function removeColorOption(index) {
        const el = document.querySelector(`.color-option[data-index="${index}"]`);
        if(el) el.remove();
        if(document.querySelectorAll('.color-option').length === 0) document.getElementById('noColorsText').style.display = 'block';
    }

    // 4. SPECIFICATIONS LOGIC (Updated: Required Fields)
    let specIndex = 0;

    function addSpecificationSectionWithData(title = '', items = ['']) {
        const container = document.getElementById('specificationsContainer');
        document.getElementById('noSpecsText').style.display = 'none';

        const div = document.createElement('div');
        div.className = 'specification-section border border-gray-100 p-6 bg-[#FAFAFA] relative group transition-all hover:border-gray-200';
        div.dataset.index = specIndex;
        
        // Generate Items HTML
        let itemsHtml = '';
        if(Array.isArray(items)) {
            itemsHtml = items.map(item => `
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                    <input type="text" name="specifications[${specIndex}][items][]" value="${item}" 
                           placeholder="Detail item" 
                           class="flex-1 bg-transparent border-b border-transparent hover:border-gray-200 focus:border-gray-900 text-sm py-1 focus:outline-none font-light"
                           required> {{-- ADDED REQUIRED --}}
                    <button type="button" onclick="removeSpecItem(this)" class="text-gray-300 hover:text-red-500">&times;</button>
                </div>
            `).join('');
        }

        div.innerHTML = `
            <div class="mb-4 pr-10">
                <input type="text" name="specifications[${specIndex}][section_title]" value="${title}" 
                       placeholder="SECTION TITLE (e.g. MATERIALS)" 
                       class="w-full bg-transparent text-xs font-bold tracking-[0.2em] uppercase text-gray-900 placeholder-gray-300 border-none p-0 focus:ring-0"
                       required> {{-- ADDED REQUIRED --}}
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
                   required> {{-- ADDED REQUIRED --}}
            <button type="button" onclick="removeSpecItem(this)" class="text-gray-300 hover:text-red-500">&times;</button>
        `;
        container.appendChild(div);
    }

    function removeSpecItem(btn) {
        const container = btn.closest('.spec-items-container');
        if(container.children.length > 1) btn.parentElement.remove();
    }

    // 5. CATEGORY LOGIC (Fix: Handle Disabled & Old Values)
    const allCategories = @json($allCategories);
    const parentSelect = document.getElementById('parent_category_id');
    const subSelect = document.getElementById('category_id');

    parentSelect.addEventListener('change', function() {
        const parentId = parseInt(this.value);
        subSelect.innerHTML = '<option value="">Select Category</option>';
        subSelect.disabled = true;
        subSelect.classList.add('text-gray-400');
        
        if (parentId) {
            const children = allCategories.filter(cat => cat.parent_id === parentId);
            if (children.length > 0) {
                children.forEach(child => {
                    const option = document.createElement('option');
                    option.value = child.id;
                    option.textContent = child.name;
                    subSelect.appendChild(option);
                });
                subSelect.disabled = false;
                subSelect.classList.remove('text-gray-400');
                subSelect.classList.add('text-gray-900');
            } else {
                subSelect.innerHTML = '<option value="">No sub-categories (Uses Parent)</option>';
            }
        }
    });

    // ============================================
    // CRITICAL: RESTORE OLD VALUES (PERSISTENCE)
    // ============================================

    // Restore Colors
    const oldColors = @json(old('colors', []));
    if (oldColors.length > 0) {
        // Clear empty state immediately if we have old data
        document.getElementById('noColorsText').style.display = 'none';
        oldColors.forEach(color => {
            addColorOptionWithData(color.name, color.hex_code);
        });
    }

    // Restore Specifications
    const oldSpecs = @json(old('specifications', []));
    if (oldSpecs.length > 0) {
        document.getElementById('noSpecsText').style.display = 'none';
        oldSpecs.forEach(spec => {
            // Note: old() might return object structure, adjust if needed based on Controller validation structure
            // Assuming old('specifications') structure matches input array structure
            addSpecificationSectionWithData(spec.section_title, spec.items);
        });
    }

    // Restore Category
    const oldParentId = "{{ old('parent_category_id') }}";
    const oldCategoryId = "{{ old('category_id') }}";
    
    if (oldParentId) {
        // Trigger change manual untuk populate sub-category
        parentSelect.value = oldParentId;
        parentSelect.dispatchEvent(new Event('change'));
        
        // Tunggu sebentar agar opsi ter-render lalu set value
        setTimeout(() => {
            if(oldCategoryId) {
                subSelect.value = oldCategoryId;
            }
        }, 50);
    }

</script>
@endsection