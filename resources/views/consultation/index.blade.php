@extends('layout.main')

@section('content')
<div class="min-h-screen bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Section --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-light tracking-wide text-gray-900 mb-4">Book Your Consultation</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Choose the consultation type that best suits your needs</p>
        </div>

        {{-- Contact Info --}}
        <div class="text-center mb-12 text-sm text-gray-600">
            <p class="mb-2">
                <a href="tel:+6139663119" class="hover:text-[#E8B49E] transition-colors">+61 3 9663 6119</a>
                <span class="mx-2">•</span>
                <a href="mailto:info@cecileprivate.com" class="hover:text-[#E8B49E] transition-colors">info@cecileprivate.com</a>
            </p>
            <p class="text-gray-500">Cecile Private Collection • Suite 904, 125 Swanston St Melbourne VIC 3000</p>
        </div>

        {{-- Services Section --}}
        <div class="mb-8">
            <h2 class="text-2xl font-light text-gray-800 mb-6 border-b border-gray-200 pb-3">Services</h2>
        </div>

        {{-- Consultation Cards --}}
        <div class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto">
            
            {{-- Short Consultation --}}
            <div class="border border-gray-200 rounded-lg p-8 hover:shadow-lg transition-shadow duration-300">
                <h3 class="text-xl font-medium text-gray-900 mb-6">Short Consultation or Collection</h3>
                
                <div class="flex items-center justify-between mb-6">
                    <span class="text-gray-600">Duration</span>
                    <span class="text-gray-900 font-medium">30 mins</span>
                </div>

                <a href="{{ route('consultation.create', ['type' => 'short']) }}" 
                   class="block w-full text-center bg-gray-900 text-white py-3 px-6 rounded hover:bg-gray-800 transition-colors duration-300 uppercase tracking-wider text-sm font-medium">
                    Book Now
                </a>
            </div>

            {{-- Design Consultation --}}
            <div class="border border-gray-200 rounded-lg p-8 hover:shadow-lg transition-shadow duration-300">
                <h3 class="text-xl font-medium text-gray-900 mb-6">Design Consultation</h3>
                
                <div class="flex items-center justify-between mb-6">
                    <span class="text-gray-600">Duration</span>
                    <span class="text-gray-900 font-medium">1 hr</span>
                </div>

                <a href="{{ route('consultation.create', ['type' => 'design']) }}" 
                   class="block w-full text-center bg-gray-900 text-white py-3 px-6 rounded hover:bg-gray-800 transition-colors duration-300 uppercase tracking-wider text-sm font-medium">
                    Book Now
                </a>
            </div>

        </div>

        {{-- Additional Info --}}
        <div class="mt-12 text-center text-sm text-gray-500 max-w-3xl mx-auto">
            <p>Our expert consultants are ready to help you create your perfect piece of jewelry. Choose a consultation type above to get started.</p>
        </div>

    </div>
</div>
@endsection