@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="text-center py-8 px-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Book a Consultation</h1>
            <p class="text-gray-600">Tell us about your dream jewelry and we'll help bring it to life</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mx-8 mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mx-8 mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Content - 2 Columns -->
        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[600px]">
            
            <!-- Left Column - Image Upload -->
            <div class="bg-gray-50 p-8 border-r border-gray-200">
                <div class="h-full flex flex-col">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Reference Images
                        </h2>
                        <p class="text-gray-600 text-sm">
                            Upload images of jewelry styles you like, sketches, or inspiration photos to help us understand your vision better.
                        </p>
                    </div>

                    <!-- ✅ AI Generation Dropdown - TAMBAHAN BARU -->
                    @auth
                        @if(Auth::user()->chatHistory->count() > 0)
                        <div class="mb-6">
                            <div class="bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    <h3 class="text-sm font-semibold text-purple-800">✨ AI Generated Designs</h3>
                                </div>
                                <p class="text-xs text-purple-600 mb-3">Choose from your previously generated AI designs</p>
                                
                                <!-- Custom Dropdown -->
                                <div class="relative">
                                    <button type="button" id="ai-dropdown-toggle" class="w-full px-3 py-2 text-sm border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-left flex items-center justify-between">
                                        <span id="selected-text" class="text-gray-500">Select an AI generated design...</span>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div id="ai-dropdown-menu" class="hidden absolute z-10 w-full mt-1 bg-white border border-purple-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach(Auth::user()->chatHistory->sortByDesc('created_at') as $chat)
                                            <div class="ai-option flex items-center p-3 hover:bg-purple-50 cursor-pointer border-b border-gray-100 last:border-b-0" 
                                                data-image="{{ $chat->image_url }}" 
                                                data-prompt="{{ $chat->prompt }}" 
                                                data-date="{{ $chat->created_at->format('M j, Y H:i') }}">
                                                <!-- Thumbnail Image -->
                                                <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 mr-3 flex-shrink-0">
                                                    <img src="{{ $chat->image_url }}" 
                                                        alt="AI Design" 
                                                        class="w-full h-full object-cover"
                                                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQ4IiBoZWlnaHQ9IjQ4IiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0yNCAzMkMzMC42Mjc0IDMyIDM2IDI2LjYyNzQgMzYgMjBDMzYgMTMuMzcyNiAzMC42Mjc0IDggMjQgOEMxNy4zNzI2IDggMTIgMTMuMzcyNiAxMiAyMEMxMiAyNi42Mjc0IDE3LjM3MjYgMzIgMjQgMzJaIiBzdHJva2U9IiM5Q0E0QUYiIHN0cm9rZS13aWR0aD0iMiIvPgo8cGF0aCBkPSJNMjQgMjRIMjQiIHN0cm9rZT0iIzlDQTRBRiIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4KPC9zdmc+Cg=='; this.classList.add('opacity-50');">
                                                </div>
                                                
                                                <!-- Text Content -->
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ Str::limit($chat->prompt, 30) }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ $chat->created_at->format('M j, Y H:i') }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <button type="button" id="add-ai-image-btn" class="mt-3 w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add to References
                                </button>
                            </div>
                        </div>
                        @endif
                    @endauth

                    <!-- Upload Area -->
                    <div class="flex-1 flex flex-col">
                        <div class="border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors duration-200 flex-1 min-h-[100px]">
                            <label for="reference_images" class="cursor-pointer h-full flex flex-col items-center justify-center p-6 text-center">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="text-lg font-medium text-gray-700 mb-2">
                                    Drop images here or click to upload
                                </div>
                                <div class="text-sm text-gray-500 mb-4">
                                    PNG, JPG, JPEG up to 5MB each (max 6 images)
                                </div>
                                <div class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 text-sm font-medium">
                                    Choose Files
                                </div>
                                <input 
                                    id="reference_images" 
                                    name="reference_images[]" 
                                    type="file" 
                                    class="hidden" 
                                    multiple 
                                    accept="image/*"
                                    onchange="previewImages(this)"
                                    form="consultation-form">
                            </label>
                        </div>

                        <!-- Image Preview Container -->
                        <div id="image-preview-container" class="mt-6 grid grid-cols-3 gap-4 hidden">
                            <!-- Preview images will be inserted here -->
                        </div>

                        <!-- Upload Tips -->
                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800 mb-1">💡 Pro Tips:</h4>
                                    <ul class="text-sm text-blue-700 space-y-1">
                                        <li>• Multiple angles show details better</li>
                                        <li>• Include close-up shots of specific features</li>
                                        <li>• Pinterest/Instagram inspiration works great</li>
                                        <li>• Hand-drawn sketches are welcome too!</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Form -->
            <div class="p-8">
                <form id="consultation-form" action="{{ route('consultation.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 h-full flex flex-col">
                    @csrf

                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Your Information
                        </h2>
                    </div>

                    <!-- Form Fields Container -->
                    <div class="flex-1 space-y-5">
                        
                        <!-- Name Field -->
                        <div>
                            <label for="user_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="user_name" 
                                name="user_name" 
                                value="{{ old('user_name', Auth::user()->name ?? '') }}" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                placeholder="Enter your full name">
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="user_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="user_email" 
                                name="user_email" 
                                value="{{ old('user_email', Auth::user()->email ?? '') }}" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                placeholder="Enter your email address">
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="user_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            
                            <input 
                                type="tel" 
                                id="user_phone"
                                value="{{ old('user_phone', Auth::user()->phone ?? '') }}" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                placeholder="Enter your phone number">
                            
                            <input type="hidden" name="user_phone" id="hidden_phone_input" value="{{ old('user_phone', Auth::user()->phone ?? '') }}">
                            
                            <p class="text-sm text-gray-500 mt-1">
                                📞 Select your phone's country code and enter your number
                            </p>
                            
                            <div id="phone-error" class="hidden"></div>
                        </div>

                        <!-- Country Field -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Shipping Country <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="country" 
                                name="country" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                <option value="">Where should we ship your jewelry?</option>
                                <option value="Indonesia" {{ old('country') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                <option value="Malaysia" {{ old('country') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                <option value="Singapore" {{ old('country') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                <option value="Thailand" {{ old('country') == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                <option value="Philippines" {{ old('country') == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                <option value="Vietnam" {{ old('country') == 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                                <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                                <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                <option value="Japan" {{ old('country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                <option value="South Korea" {{ old('country') == 'South Korea' ? 'selected' : '' }}>South Korea</option>
                                <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">
                                🌍 This can be different from your phone number's country
                            </p>
                        </div>

                        <!-- Notes Field -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Describe Your Dream Jewelry
                            </label>
                            <textarea 
                                id="notes" 
                                name="notes" 
                                rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 resize-none"
                                placeholder="Tell us about your dream jewelry... What type of jewelry do you want? What materials? Any specific design ideas? Budget range? Special occasions? Be as detailed as possible to help us understand your vision.">{{ old('notes') }}</textarea>
                            <p class="text-sm text-gray-500 mt-2">
                                💡 Include: jewelry type, materials, style, budget range, special occasions
                            </p>
                        </div>

                    </div>

                    <!-- Bottom Section -->
                    <div class="space-y-4 pt-4 border-t border-gray-200">
                        
                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-blue-800 mb-1">What happens next?</h3>
                                    <p class="text-sm text-blue-700">
                                        Our jewelry experts will review your requirements and reference images, then contact you within 24-48 hours with a personalized proposal and pricing.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            id="submit-btn"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submit-text">Submit Consultation Request</span>
                        </button>

                        <!-- Login Notice for Guests -->
                        @guest
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.30 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-yellow-700">
                                            <strong>Note:</strong> We recommend 
                                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">logging in</a> 
                                            or <a href="{{ route('register') }}" class="text-blue-600 hover:underline">creating an account</a> 
                                            to track your consultation status.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endguest

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Global variable untuk menyimpan semua files
    let selectedFiles = [];

    function previewImages(input) {
        const container = document.getElementById('image-preview-container');
        
        // Tambah files baru ke array existing files (bukan replace)
        const newFiles = Array.from(input.files);
        
        // Gabungkan dengan files yang sudah ada
        for (let newFile of newFiles) {
            // Cek duplikasi berdasarkan nama dan ukuran
            const isDuplicate = selectedFiles.some(existingFile => 
                existingFile.name === newFile.name && existingFile.size === newFile.size
            );
            
            if (!isDuplicate && selectedFiles.length < 6) {
                selectedFiles.push(newFile);
            }
        }
        
        // Limit maksimal 6 files
        if (selectedFiles.length > 6) {
            alert('Maximum 6 images allowed. Only the first 6 images will be kept.');
            selectedFiles = selectedFiles.slice(0, 6);
        }
        
        // Update tampilan
        updateImagePreview();
        
        // Update file input dengan semua selected files
        updateFileInput();
    }

    function updateImagePreview() {
        const container = document.getElementById('image-preview-container');
        container.innerHTML = ''; // Clear previous previews
        
        if (selectedFiles.length > 0) {
            container.classList.remove('hidden');
            
            selectedFiles.forEach((file, index) => {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    return;
                }
                
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert(`${file.name} is too large. Maximum size is 5MB.`);
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative group';
                    previewDiv.innerHTML = `
                        <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                            <img src="${e.target.result}" 
                                alt="Preview ${index + 1}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        </div>
                        <div class="absolute top-2 right-2">
                            <button type="button" 
                                    onclick="removeImage(${index})"
                                    class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-lg transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2">
                            <p class="text-xs text-gray-600 truncate">${file.name}</p>
                            <p class="text-xs text-gray-400">${(file.size / 1024 / 1024).toFixed(1)} MB</p>
                        </div>
                    `;
                    container.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            });
        } else {
            container.classList.add('hidden');
        }
    }

    function removeImage(index) {
        // Remove file dari array
        selectedFiles.splice(index, 1);
        
        // Update preview
        updateImagePreview();
        
        // Update file input
        updateFileInput();
    }

    function updateFileInput() {
        const input = document.getElementById('reference_images');
        
        // Create new DataTransfer object
        const dt = new DataTransfer();
        
        // Add all selected files to DataTransfer
        selectedFiles.forEach(file => {
            dt.items.add(file);
        });
        
        // Set files to input
        input.files = dt.files;
    }

    // Form submission with loading state
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggle = document.getElementById('ai-dropdown-toggle');
        const dropdownMenu = document.getElementById('ai-dropdown-menu');
        const selectedText = document.getElementById('selected-text');
        const addAiImageBtn = document.getElementById('add-ai-image-btn');
        let selectedAiData = null;

        if (dropdownToggle && dropdownMenu) {
            // Toggle dropdown
            dropdownToggle.addEventListener('click', function() {
                dropdownMenu.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });

            // Handle option selection
            const options = document.querySelectorAll('.ai-option');
            options.forEach(option => {
                option.addEventListener('click', function() {
                    const prompt = this.dataset.prompt;
                    const imageUrl = this.dataset.image;
                    const date = this.dataset.date;
                    
                    // Update selected text
                    selectedText.textContent = prompt.length > 30 ? prompt.substring(0, 30) + '...' : prompt;
                    selectedText.classList.remove('text-gray-500');
                    selectedText.classList.add('text-gray-900');
                    
                    // Store selected data
                    selectedAiData = { imageUrl, prompt, date };
                    
                    // Enable add button
                    addAiImageBtn.disabled = false;
                    
                    // Close dropdown
                    dropdownMenu.classList.add('hidden');
                });
            });
        }
        // Initialize phone number input
        const phoneInputField = document.querySelector("#user_phone");
        const hiddenPhoneInput = document.querySelector("#hidden_phone_input");
        
        // ✅ Initialize intl-tel-input
        const phoneInput = window.intlTelInput(phoneInputField, {
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                fetch('https://ipapi.co/json/')
                    .then(function(res) { return res.json(); })
                    .then(function(data) { callback(data.country_code); })
                    .catch(function() { callback("id"); }); // Default to Indonesia
            },
            preferredCountries: ["id", "us", "sg", "my", "th", "ph", "vn", "au", "jp", "kr"],
            separateDialCode: true,
            formatOnDisplay: true,
            nationalMode: false,
            placeholderNumberType: "MOBILE",
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
        });

        // ✅ Input restriction - hanya angka
        phoneInputField.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = value;
            
            // Update hidden input realtime
            updateHiddenPhone();
        });

        phoneInputField.addEventListener('paste', function(e) {
            e.preventDefault();
            let paste = (e.clipboardData || window.clipboardData).getData('text');
            let numericPaste = paste.replace(/[^0-9]/g, '');
            e.target.value = numericPaste;
            
            // Update hidden input setelah paste
            setTimeout(() => updateHiddenPhone(), 100);
        });

        phoneInputField.addEventListener('keypress', function(e) {
            // Allow control keys
            if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            // Only allow numbers
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        // ✅ Update hidden input saat country berubah
        phoneInputField.addEventListener("countrychange", function() {
            updateHiddenPhone();
        });

        // ✅ Function untuk update hidden input
        function updateHiddenPhone() {
            if (phoneInputField.value.trim() && phoneInput.isValidNumber()) {
                const fullNumber = phoneInput.getNumber();
                hiddenPhoneInput.value = fullNumber;
                hidePhoneError();
            } else if (phoneInputField.value.trim()) {
                // Ada input tapi tidak valid
                const selectedCountryData = phoneInput.getSelectedCountryData();
                const dialCode = selectedCountryData.dialCode;
                const nationalNumber = phoneInputField.value;
                const fullNumber = `+${dialCode}${nationalNumber}`;
                hiddenPhoneInput.value = fullNumber;
            }
        }

        // ✅ Form submission
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        
        form.addEventListener('submit', function(e) {
            // Cek apakah phone number diisi
            if (phoneInputField.value.trim() === '') {
                e.preventDefault();
                showPhoneError('Please enter your phone number.');
                return;
            }
            
            // Update final hidden input
            updateHiddenPhone();
            
            // Validasi phone number
            if (phoneInput.isValidNumber()) {
                // Continue submission
                submitBtn.disabled = true;
                submitText.textContent = 'Submitting...';
                
                const imageCount = selectedFiles.length;
                if (imageCount > 0) {
                    submitText.textContent = `Uploading ${imageCount} image(s)...`;
                }
            } else {
                e.preventDefault();
                showPhoneError('Please enter a valid phone number for the selected country.');
            }
        });

        // ✅ Show phone error function
        function showPhoneError(message) {
            hidePhoneError();
            
            const errorDiv = document.getElementById('phone-error');
            errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-2';
            errorDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    ${message}
                </div>
            `;
            
            phoneInputField.scrollIntoView({ behavior: 'smooth' });
            phoneInputField.focus();
        }

        // ✅ Hide phone error function
        function hidePhoneError() {
            const errorDiv = document.getElementById('phone-error');
            errorDiv.className = 'hidden';
            errorDiv.innerHTML = '';
        }
    });
</script>
@endsection