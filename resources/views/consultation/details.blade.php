@extends('layout.main')

@section('content')
<div class="min-h-screen bg-stone-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        {{-- Header Navigation --}}
        <div class="mb-12 text-center">
            <a href="{{ route('consultation.create', ['type' => $type]) }}" class="group inline-flex items-center text-xs font-bold tracking-[0.15em] text-stone-400 uppercase hover:text-stone-900 transition-colors mb-6">
                <svg class="w-3 h-3 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
                </svg>
                Reschedule Appointment
            </a>
            <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight mb-2">Finalize Your Request</h1>
            <p class="text-stone-500 font-light text-sm">Please provide your details so we can prepare for your session.</p>
        </div>

        <div class="bg-white rounded-sm shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] border border-stone-100 p-8 md:p-12">
            
            {{-- Appointment Summary Card --}}
            <div class="bg-stone-50 border border-stone-100 p-6 mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-stone-200 text-stone-500 flex items-center justify-center rounded-sm flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-1">Appointment</p>
                        <p class="text-lg text-stone-900">{{ $appointmentDisplay }}</p>
                        <p class="text-sm text-stone-500 font-light mt-0.5">
                            {{ $type === 'design' ? 'Bespoke Design Consultation (1 Hour)' : 'General Consultation (30 Mins)' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-2 border-emerald-500 text-emerald-800 text-sm tracking-wide flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-8 p-4 bg-rose-50 border-l-2 border-rose-500 text-rose-800 text-sm tracking-wide">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form id="consultation-form" action="{{ route('consultation.store') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
                @csrf
                <input type="hidden" name="consultation_type" value="{{ $type }}">
                <input type="hidden" name="appointment_date" value="{{ $date }}">
                <input type="hidden" name="appointment_time" value="{{ $time }}">

                {{-- 1. Personal Information --}}
                <div>
                    <h3 class="text-xs font-bold text-stone-900 uppercase tracking-[0.2em] border-b border-stone-100 pb-4 mb-8">
                        Personal Information
                    </h3>
                    
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        {{-- First Name --}}
                        <div class="relative">
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors @error('first_name') border-red-500 @enderror" 
                                placeholder="First Name">
                            <label for="first_name" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">
                                First Name
                            </label>
                            @error('first_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class="relative">
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors @error('last_name') border-red-500 @enderror" 
                                placeholder="Last Name">
                            <label for="last_name" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">
                                Last Name
                            </label>
                            @error('last_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Email --}}
                        <div class="relative">
                            <input type="email" id="user_email" name="user_email" value="{{ old('user_email', Auth::user()->email ?? '') }}" required
                                class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors @error('user_email') border-red-500 @enderror" 
                                placeholder="Email Address">
                            <label for="user_email" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">
                                Email Address
                            </label>
                            @error('user_email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="relative">
                            <input type="tel" id="user_phone" value="{{ old('user_phone', Auth::user()->phone ?? '') }}" required
                                class="block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent @error('user_phone') border-red-500 @enderror">
                            <input type="hidden" name="user_phone" id="hidden_phone_input" value="{{ old('user_phone', Auth::user()->phone ?? '') }}">
                            <div id="phone-error" class="hidden mt-1 text-xs text-red-500"></div>
                            @error('user_phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- 2. Consultation Details --}}
                <div>
                    <h3 class="text-xs font-bold text-stone-900 uppercase tracking-[0.2em] border-b border-stone-100 pb-4 mb-8">
                        Consultation Details
                    </h3>

                    {{-- Notes --}}
                    <div class="mb-8 relative">
                        <textarea id="appointment_notes" name="appointment_notes" rows="4"
                                class="peer block w-full border border-gray-300 rounded-none px-4 py-3 text-sm text-gray-900 placeholder-transparent focus:outline-none focus:border-gray-900 focus:ring-0 bg-transparent transition-colors resize-none" 
                                placeholder="Topic of Discussion">{{ old('appointment_notes') }}</textarea>
                        <label for="appointment_notes" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-gray-900">
                            Topic of Discussion (Optional)
                        </label>
                    </div>

                    {{-- Reference Images --}}
                    <div class="space-y-6">
                        <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-2">
                            Visual References (Optional)
                        </label>

                        {{-- AI Dropdown --}}
                        @auth
                            @if(Auth::user()->chatHistory->count() > 0)
                            <div class="relative mb-6">
                                <button type="button" id="ai-dropdown-toggle" 
                                        class="flex items-center justify-between w-full p-4 bg-stone-50 hover:bg-stone-100 border border-stone-200 rounded-sm text-left transition-all group">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-stone-900 text-white rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-bold text-stone-900 uppercase tracking-wide">Select from Cecile AI</span>
                                            <span class="block text-[10px] text-stone-500">Attach designs generated from your chat history</span>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-stone-400 group-hover:text-stone-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                
                                {{-- Dropdown Content --}}
                                <div id="ai-dropdown-menu" class="hidden absolute z-20 w-full mt-2 bg-white border border-stone-100 shadow-xl rounded-sm max-h-64 overflow-y-auto custom-scrollbar">
                                    @foreach(Auth::user()->chatHistory->sortByDesc('created_at') as $index => $chat)
                                        <div class="ai-option flex items-center p-3 hover:bg-stone-50 cursor-pointer border-b border-stone-50 last:border-0 transition-colors" 
                                             data-image="{{ $chat->image_url }}" data-prompt="{{ $chat->prompt }}" data-index="{{ $index + 1 }}">
                                            <div class="w-10 h-10 rounded-sm overflow-hidden bg-stone-100 mr-3 border border-stone-200">
                                                <img src="{{ $chat->image_url }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold text-stone-800 uppercase tracking-wide">Design #{{ $index + 1 }}</p>
                                                <p class="text-[10px] text-stone-400 truncate">{{ $chat->prompt }}</p>
                                            </div>
                                            <div class="text-[10px] text-stone-300">{{ $chat->created_at->format('M d') }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endauth

                        {{-- Manual Upload Area --}}
                        <div class="relative group">
                            <input id="reference_images" name="reference_images[]" type="file" class="hidden" multiple accept="image/*" onchange="previewImages(this)">
                            <label for="reference_images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-stone-200 rounded-sm cursor-pointer hover:border-stone-400 hover:bg-stone-50/50 transition-all duration-300">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 text-stone-300 mb-3 group-hover:text-stone-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-xs text-stone-500 uppercase tracking-wide"><span class="font-bold text-stone-900">Click to upload</span> or drag and drop</p>
                                    <p class="text-[10px] text-stone-400 mt-1">PNG, JPG (Max 5MB)</p>
                                    <p class="text-[10px] text-stone-400 mt-1">Max 6 Images</p>
                                </div>
                            </label>
                        </div>

                        {{-- Preview Grid --}}
                        <div id="image-preview-container" class="grid grid-cols-3 md:grid-cols-4 gap-4 hidden pt-2">
                            {{-- JS Injected --}}
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="pt-8 border-t border-stone-100">
                    <button type="submit" id="submit-btn" class="w-full bg-stone-900 text-white py-4 px-8 text-xs font-bold uppercase tracking-[0.2em] hover:bg-stone-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 rounded-sm cursor-pointer">
                        <span id="submit-text">Confirm Appointment</span>
                    </button>
                    <p class="text-center text-[10px] text-stone-400 mt-4 uppercase tracking-widest">
                        You will receive a confirmation email shortly
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
let selectedFiles = [];

// Drag & Drop Handler
document.addEventListener('DOMContentLoaded', function() {
    const uploadLabel = document.querySelector('label[for="reference_images"]');
    
    if (uploadLabel) {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadLabel.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadLabel.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadLabel.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        uploadLabel.addEventListener('drop', handleDrop, false);
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        uploadLabel.classList.add('border-stone-900', 'bg-stone-100');
    }

    function unhighlight(e) {
        uploadLabel.classList.remove('border-stone-900', 'bg-stone-100');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        const fileArray = Array.from(files);
        const imageFiles = fileArray.filter(file => file.type.startsWith('image/'));
        
        if (imageFiles.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Files',
                text: 'Please upload only image files (PNG, JPG, GIF)',
                confirmButtonColor: '#292524'
            });
            return;
        }

        for (let file of imageFiles) {
            if (file.size > 5120 * 1024) { // 5MB
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: `${file.name} exceeds 5MB limit`,
                    confirmButtonColor: '#292524'
                });
                continue;
            }

            const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
            if (!isDuplicate && selectedFiles.length < 6) {
                selectedFiles.push(file);
            }
        }

        if (selectedFiles.length > 6) {
            Swal.fire({
                icon: 'warning',
                title: 'Maximum Images Reached',
                text: 'You can only upload up to 6 images',
                confirmButtonColor: '#292524'
            });
            selectedFiles = selectedFiles.slice(0, 6);
        }

        updateImagePreview();
        updateFileInput();
    }
});

async function addAiImageToPreview(imageUrl, prompt, displayName) {
    try {
        if (selectedFiles.length >= 6) {
            Swal.fire({
                icon: 'warning',
                title: 'Maximum Images Reached',
                text: 'You can only select up to 6 images',
                confirmButtonColor: '#292524'
            });
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
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Failed to Add Image',
            text: 'Could not fetch the AI-generated image',
            confirmButtonColor: '#292524'
        });
    }
}

function updateDropdownOptions() {
    const options = document.querySelectorAll('.ai-option');
    options.forEach(option => {
        const imageUrl = option.dataset.image;
        const isAdded = selectedFiles.some(file => file.aiImageUrl === imageUrl);
        option.style.display = isAdded ? 'none' : 'flex';
    });
}

function previewImages(input) {
    const newFiles = Array.from(input.files);
    for (let newFile of newFiles) {
        if (newFile.size > 5120 * 1024) { // 5MB
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: `${newFile.name} exceeds 5MB limit`,
                confirmButtonColor: '#292524'
            });
            continue;
        }

        const isDuplicate = selectedFiles.some(f => f.name === newFile.name && f.size === newFile.size);
        if (!isDuplicate && selectedFiles.length < 6) selectedFiles.push(newFile);
    }
    
    if (selectedFiles.length > 6) {
        Swal.fire({
            icon: 'warning',
            title: 'Maximum Images Reached',
            text: 'You can only upload up to 6 images',
            confirmButtonColor: '#292524'
        });
        selectedFiles = selectedFiles.slice(0, 6);
    }
    
    updateImagePreview();
    updateFileInput();
}

function updateImagePreview() {
    const container = document.getElementById('image-preview-container');
    container.innerHTML = '';
    
    if (selectedFiles.length > 0) {
        container.classList.remove('hidden');
        selectedFiles.forEach((file, index) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative group aspect-square bg-stone-100 rounded-sm overflow-hidden border border-stone-200';
            
            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 cursor-zoom-in';

            // Tambahkan event openLightbox
            img.onclick = function() {
                if (file.aiImageUrl) {
                    openLightbox(file.aiImageUrl);
                } else {
                    // Untuk file upload manual, perlu FileReader untuk dapatkan dataURL
                    const reader = new FileReader();
                    reader.onload = (e) => openLightbox(e.target.result);
                    reader.readAsDataURL(file);
                }
            };

            if (file.aiImageUrl) {
                img.src = file.aiImageUrl;
            } else {
                const reader = new FileReader();
                reader.onload = (e) => img.src = e.target.result;
                reader.readAsDataURL(file);
            }
            wrapper.appendChild(img);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'absolute top-1 right-1 bg-white/90 text-stone-900 p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white hover:text-rose-600';
            btn.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
            btn.onclick = () => removeImage(index);
            wrapper.appendChild(btn);

            container.appendChild(wrapper);
        });
    } else {
        container.classList.add('hidden');
    }
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    updateDropdownOptions();
    updateImagePreview();
    updateFileInput();
}

function updateFileInput() {
    const input = document.getElementById('reference_images');
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    input.files = dt.files;
}

document.addEventListener('DOMContentLoaded', function() {
    // Dropdown Logic
    const toggle = document.getElementById('ai-dropdown-toggle');
    const menu = document.getElementById('ai-dropdown-menu');
    if (toggle && menu) {
        toggle.addEventListener('click', () => menu.classList.toggle('hidden'));
        document.addEventListener('click', (e) => {
            if (!toggle.contains(e.target) && !menu.contains(e.target)) menu.classList.add('hidden');
        });
        document.querySelectorAll('.ai-option').forEach(opt => {
            opt.addEventListener('click', async function() {
                menu.classList.add('hidden');
                await addAiImageToPreview(this.dataset.image, this.dataset.prompt, `Design ${this.dataset.index}`);
            });
        });
    }

    // Phone Input
    const phoneInput = document.querySelector("#user_phone");
    const hiddenPhone = document.querySelector("#hidden_phone_input");
    if(phoneInput) {
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: "auto",
            geoIpLookup: callback => fetch('https://ipapi.co/json/').then(res => res.json()).then(data => callback(data.country_code)).catch(() => callback("id")),
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
            separateDialCode: true,
        });

        const updateHidden = () => {
            if (phoneInput.value.trim()) hiddenPhone.value = iti.getNumber();
        };
        phoneInput.addEventListener('input', updateHidden);
        phoneInput.addEventListener('countrychange', updateHidden);
        
        document.getElementById('consultation-form').addEventListener('submit', function(e) {
            updateHidden();
            if (!iti.isValidNumber()) {
                e.preventDefault();
                document.getElementById('phone-error').textContent = "Invalid phone number";
                document.getElementById('phone-error').classList.remove('hidden');
            }
        });
    }
});
</script>

<style>
@media (max-width: 1024px) {
    #image-preview-container .group > button {
        opacity: 1 !important;
    }
}
</style>
@endsection