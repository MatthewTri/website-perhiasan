@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-md p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Book a Consultation</h1>
            <p class="text-gray-600">Tell us about your dream jewelry and we'll help bring it to life</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
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
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('consultation.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

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

            <!-- Country Field -->
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                    Country <span class="text-red-500">*</span>
                </label>
                <select 
                    id="country" 
                    name="country" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    <option value="">Select your country</option>
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
            </div>

            <!-- Reference Images Upload -->
            <div>
                <label for="reference_images" class="block text-sm font-medium text-gray-700 mb-2">
                    Reference Images (Optional)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors duration-200">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="reference_images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Upload images</span>
                                <input 
                                    id="reference_images" 
                                    name="reference_images[]" 
                                    type="file" 
                                    class="sr-only" 
                                    multiple 
                                    accept="image/*"
                                    onchange="previewImages(this)">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PNG, JPG, JPEG up to 5MB each (max 5 images)
                        </p>
                    </div>
                </div>
                
                <!-- Image Preview Container -->
                <div id="image-preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4 hidden">
                    <!-- Preview images will be inserted here -->
                </div>

                <p class="text-sm text-gray-500 mt-2">
                    💡 <strong>Tips:</strong> Upload reference images of jewelry styles you like, sketches, or inspiration photos to help us understand your vision better.
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
                    rows="6"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 resize-none"
                    placeholder="Tell us about your dream jewelry... What type of jewelry do you want? What materials? Any specific design ideas? Budget range? Special occasions? Be as detailed as possible to help us understand your vision.">{{ old('notes') }}</textarea>
                <p class="text-sm text-gray-500 mt-2">
                    💡 <strong>Tips:</strong> Include details like jewelry type (ring, necklace, earrings), materials (gold, silver, diamonds), style preferences, budget range, and any special meaning or occasion.
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800 mb-1">What happens next?</h3>
                        <p class="text-sm text-blue-700">
                            After you submit this consultation request, our jewelry experts will review your requirements and reference images, then contact you within 24-48 hours with a personalized proposal and pricing.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button 
                    type="submit" 
                    id="submit-btn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <span id="submit-text">Submit Consultation Request</span>
                </button>
            </div>

            <!-- Login Notice for Guests -->
            @guest
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-yellow-700">
                                <strong>Note:</strong> While you can submit this form as a guest, we recommend 
                                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">logging in</a> 
                                or <a href="{{ route('register') }}" class="text-blue-600 hover:underline">creating an account</a> 
                                to track your consultation status and receive updates.
                            </p>
                        </div>
                    </div>
                </div>
            @endguest
        </form>
    </div>
</div>

<script>
function previewImages(input) {
    const container = document.getElementById('image-preview-container');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    
    container.innerHTML = ''; // Clear previous previews
    
    if (input.files.length > 0) {
        container.classList.remove('hidden');
        
        // Check file count limit
        if (input.files.length > 5) {
            alert('Maximum 5 images allowed. Only the first 5 images will be uploaded.');
        }
        
        // Process up to 5 files
        const filesToProcess = Math.min(input.files.length, 5);
        
        for (let i = 0; i < filesToProcess; i++) {
            const file = input.files[i];
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                continue;
            }
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert(`${file.name} is too large. Maximum size is 5MB.`);
                continue;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative group';
                previewDiv.innerHTML = `
                    <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                        <img src="${e.target.result}" 
                             alt="Preview ${i + 1}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    </div>
                    <div class="absolute top-2 right-2">
                        <button type="button" 
                                onclick="removeImage(this, ${i})"
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
        }
    } else {
        container.classList.add('hidden');
    }
}

function removeImage(button, index) {
    const container = document.getElementById('image-preview-container');
    const input = document.getElementById('reference_images');
    
    // Remove the preview element
    button.closest('.relative').remove();
    
    // Create a new FileList without the removed file
    const dt = new DataTransfer();
    const files = input.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    input.files = dt.files;
    
    // Hide container if no images left
    if (input.files.length === 0) {
        container.classList.add('hidden');
    }
}

// Form submission with loading state
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitText.textContent = 'Uploading...';
        
        // Show upload progress for images
        const imageCount = document.getElementById('reference_images').files.length;
        if (imageCount > 0) {
            submitText.textContent = `Uploading ${imageCount} image(s)...`;
        }
    });
});
</script>
@endsection