@extends('layout.main')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <a href="{{ route('consultation.create', ['type' => $type]) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Change date & time
            </a>
            <h1 class="text-3xl md:text-4xl font-light tracking-wide text-gray-900 mb-2">Enter Your Details</h1>
            <p class="text-gray-600">Almost there! Just a few more details.</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            
            {{-- Appointment Info --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $appointmentDisplay }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $type === 'design' ? 'Design Consultation (1 hour)' : 'Short Consultation or Collection (30 mins)' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form id="consultation-form" action="{{ route('consultation.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <input type="hidden" name="consultation_type" value="{{ $type }}">
                <input type="hidden" name="appointment_date" value="{{ $date }}">
                <input type="hidden" name="appointment_time" value="{{ $time }}">

                {{-- Name Fields --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            value="{{ old('first_name', Auth::user()->name ?? '') }}" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            value="{{ old('last_name') }}" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                {{-- Email --}}
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                {{-- Phone --}}
                <div>
                    <label for="user_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="tel" 
                        id="user_phone"
                        value="{{ old('user_phone', Auth::user()->phone ?? '') }}" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <input type="hidden" name="user_phone" id="hidden_phone_input" value="{{ old('user_phone', Auth::user()->phone ?? '') }}">
                    <div id="phone-error" class="hidden"></div>
                </div>

                {{-- Appointment Notes --}}
                <div>
                    <label for="appointment_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Appointment Notes
                    </label>
                    <textarea 
                        id="appointment_notes" 
                        name="appointment_notes" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        placeholder="Tell us what you'd like to discuss during the consultation...">{{ old('appointment_notes') }}</textarea>
                </div>

                {{-- Reference Images Section --}}
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Reference Images (Optional)</h3>
                    <p class="text-sm text-gray-600 mb-4">Upload any inspiration or reference images that might help us prepare for your consultation.</p>
                    
                    {{-- AI Images Dropdown (if authenticated) --}}
                    @auth
                        @if(Auth::user()->chatHistory->count() > 0)
                        <div class="mb-4">
                            <div class="bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    <h4 class="text-sm font-semibold text-purple-800">AI Generated Designs</h4>
                                </div>
                                <p class="text-xs text-purple-600 mb-3">Click to add your AI designs to references</p>
                                
                                <div class="relative">
                                    <button type="button" id="ai-dropdown-toggle" class="w-full px-3 py-2 text-sm border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white text-left flex items-center justify-between">
                                        <span class="text-gray-500">Select an AI generated design...</span>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    
                                    <div id="ai-dropdown-menu" class="hidden absolute z-10 w-full mt-1 bg-white border border-purple-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach(Auth::user()->chatHistory->sortByDesc('created_at') as $index => $chat)
                                            <div class="ai-option flex items-center p-3 hover:bg-purple-50 cursor-pointer border-b last:border-b-0" 
                                                data-image="{{ $chat->image_url }}" 
                                                data-prompt="{{ $chat->prompt }}"
                                                data-index="{{ $index + 1 }}"
                                                data-date="{{ $chat->created_at->format('M j, Y H:i') }}">
                                                <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 mr-3 flex-shrink-0">
                                                    <img src="{{ $chat->image_url }}" alt="AI Design" class="w-full h-full object-cover">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900">Image generation {{ $index + 1 }}</p>
                                                    <p class="text-xs text-gray-500">{{ $chat->created_at->format('M j, Y H:i') }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endauth

                    {{-- File Upload --}}
                    <div class="border-2 border-gray-300 border-dashed rounded-lg p-6 hover:border-blue-400 transition-colors">
                        <label for="reference_images" class="cursor-pointer flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-600 mb-2">Drop images here or click to upload</span>
                            <span class="text-xs text-gray-500">PNG, JPG up to 5MB (max 6 images)</span>
                            <input 
                                id="reference_images" 
                                name="reference_images[]" 
                                type="file" 
                                class="hidden" 
                                multiple 
                                accept="image/*"
                                onchange="previewImages(this)">
                        </label>
                    </div>

                    <div id="image-preview-container" class="mt-4 grid grid-cols-3 gap-4 hidden"></div>
                </div>

                {{-- Submit Button --}}
                <div class="border-t border-gray-200 pt-6">
                    <button 
                        type="submit" 
                        id="submit-btn"
                        class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-4 px-6 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submit-text">Confirm Appointment</span>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

<script>
// ✅ Global variable untuk menyimpan semua files
let selectedFiles = [];

// ✅ Function untuk add AI image
async function addAiImageToPreview(imageUrl, prompt, displayName) {
    try {
        if (selectedFiles.length >= 6) {
            alert('Maximum 6 images allowed. Please remove some images first.');
            return;
        }

        const response = await fetch(imageUrl);
        if (!response.ok) throw new Error('Failed to fetch image');
        
        const blob = await response.blob();
        const fileName = `ai_design_${Date.now()}.png`;
        const file = new File([blob], fileName, { type: blob.type || 'image/png' });
        
        file.aiImageUrl = imageUrl;
        file.displayName = displayName;
        selectedFiles.push(file);
        
        updateImagePreview();
        updateFileInput();
        updateDropdownOptions();
        
    } catch (error) {
        console.error('Error adding AI image:', error);
        alert('Failed to add image. Please try again.');
    }
}

// ✅ Function untuk update dropdown options (hide yang sudah dipilih)
function updateDropdownOptions() {
    const options = document.querySelectorAll('.ai-option');
    
    options.forEach(option => {
        const imageUrl = option.dataset.image;
        const isAlreadyAdded = selectedFiles.some(file => 
            file.aiImageUrl && file.aiImageUrl === imageUrl
        );
        
        if (isAlreadyAdded) {
            option.style.display = 'none';
        } else {
            option.style.display = 'flex';
        }
    });
}

// ✅ Preview images dari file input
function previewImages(input) {
    const newFiles = Array.from(input.files);
    
    for (let newFile of newFiles) {
        const isDuplicate = selectedFiles.some(existingFile => 
            existingFile.name === newFile.name && existingFile.size === newFile.size
        );
        
        if (!isDuplicate && selectedFiles.length < 6) {
            selectedFiles.push(newFile);
        }
    }
    
    if (selectedFiles.length > 6) {
        alert('Maximum 6 images allowed. Only the first 6 images will be kept.');
        selectedFiles = selectedFiles.slice(0, 6);
    }
    
    updateImagePreview();
    updateFileInput();
}

// ✅ Update image preview
function updateImagePreview() {
    const container = document.getElementById('image-preview-container');
    container.innerHTML = '';
    
    if (selectedFiles.length > 0) {
        container.classList.remove('hidden');
        
        selectedFiles.forEach((file, index) => {
            if (!file.type.startsWith('image/')) {
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                alert(`${file.name} is too large. Maximum size is 5MB.`);
                return;
            }
            
            const previewDiv = document.createElement('div');
            previewDiv.className = 'relative group';
            previewDiv.id = `preview-${index}`;
            
            previewDiv.innerHTML = `
                <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="mt-2">
                    <p class="text-xs text-gray-600 truncate">Loading...</p>
                </div>
            `;
            
            container.appendChild(previewDiv);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const targetDiv = document.getElementById(`preview-${index}`);
                if (targetDiv) {
                    targetDiv.innerHTML = `
                        <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                            <img src="${e.target.result}" 
                                alt="Preview ${index + 1}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        </div>
                        <button type="button" 
                                onclick="removeImage(${index})"
                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <div class="mt-2">
                            <p class="text-xs text-gray-600 truncate">${file.name}</p>
                            <p class="text-xs text-gray-400">${(file.size / 1024 / 1024).toFixed(1)} MB</p>
                        </div>
                    `;
                }
            };
            reader.readAsDataURL(file);
        });
    } else {
        container.classList.add('hidden');
    }
}

// ✅ Remove image
function removeImage(index) {
    selectedFiles.splice(index, 1);
    updateDropdownOptions();
    updateImagePreview();
    updateFileInput();
}

// ✅ Update file input
function updateFileInput() {
    const input = document.getElementById('reference_images');
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    input.files = dt.files;
}

// ✅ Document ready
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggle = document.getElementById('ai-dropdown-toggle');
    const dropdownMenu = document.getElementById('ai-dropdown-menu');

    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function() {
            dropdownMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        const options = document.querySelectorAll('.ai-option');
        options.forEach(option => {
            option.addEventListener('click', async function() {
                const prompt = this.dataset.prompt;
                const imageUrl = this.dataset.image;
                const displayName = `Image generation ${this.dataset.index}`;
                
                dropdownMenu.classList.add('hidden');
                await addAiImageToPreview(imageUrl, prompt, displayName);
            });
        });
    }

    // ✅ Phone input initialization
    const phoneInputField = document.querySelector("#user_phone");
    const hiddenPhoneInput = document.querySelector("#hidden_phone_input");
    
    const phoneInput = window.intlTelInput(phoneInputField, {
        initialCountry: "auto",
        geoIpLookup: function(callback) {
            fetch('https://ipapi.co/json/')
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback("id"));
        },
        preferredCountries: ["id", "us", "sg", "my", "th", "ph", "vn", "au", "jp", "kr"],
        separateDialCode: true,
        formatOnDisplay: true,
        nationalMode: false,
        placeholderNumberType: "MOBILE",
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
    });

    phoneInputField.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        e.target.value = value;
        updateHiddenPhone();
    });

    phoneInputField.addEventListener('paste', function(e) {
        e.preventDefault();
        let paste = (e.clipboardData || window.clipboardData).getData('text');
        let numericPaste = paste.replace(/[^0-9]/g, '');
        e.target.value = numericPaste;
        setTimeout(() => updateHiddenPhone(), 100);
    });

    phoneInputField.addEventListener('keypress', function(e) {
        if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    phoneInputField.addEventListener("countrychange", function() {
        updateHiddenPhone();
    });

    function updateHiddenPhone() {
        if (phoneInputField.value.trim() && phoneInput.isValidNumber()) {
            const fullNumber = phoneInput.getNumber();
            hiddenPhoneInput.value = fullNumber;
            hidePhoneError();
        } else if (phoneInputField.value.trim()) {
            const selectedCountryData = phoneInput.getSelectedCountryData();
            const dialCode = selectedCountryData.dialCode;
            const nationalNumber = phoneInputField.value;
            const fullNumber = `+${dialCode}${nationalNumber}`;
            hiddenPhoneInput.value = fullNumber;
        }
    }

    // ✅ Form submission
    const form = document.getElementById('consultation-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    
    form.addEventListener('submit', function(e) {
        if (phoneInputField.value.trim() === '') {
            e.preventDefault();
            showPhoneError('Please enter your phone number.');
            return;
        }
        
        updateHiddenPhone();
        
        if (phoneInput.isValidNumber()) {
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

    function hidePhoneError() {
        const errorDiv = document.getElementById('phone-error');
        errorDiv.className = 'hidden';
        errorDiv.innerHTML = '';
    }
});
</script>
@endsection