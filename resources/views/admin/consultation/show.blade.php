@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header with Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.consultation.index') }}" 
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Consultation Requests
        </a>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-pink-50 to-purple-50 border-b border-gray-200 px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Consultation Request #{{ $customRequest->id }}</h1>
                    <p class="text-sm text-gray-600 mt-1">Submitted on {{ $customRequest->created_at->format('l, F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Pending Review
                    </span>
                    <form action="{{ route('admin.consultation.destroy', $customRequest->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this request?')"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Request
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="p-8">
            <!-- Customer Information Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b-2 border-pink-200">
                    Customer Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Full Name</label>
                        <p class="text-base font-semibold text-gray-900">{{ $customRequest->user_name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Email Address</label>
                        <p class="text-base font-semibold text-gray-900">
                            <a href="mailto:{{ $customRequest->user_email }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $customRequest->user_email }}
                            </a>
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Phone Number</label>
                        <p class="text-base font-semibold text-gray-900">
                            <a href="tel:{{ $customRequest->user_phone }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $customRequest->user_phone }}
                            </a>
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Request Date</label>
                        <p class="text-base font-semibold text-gray-900">{{ $customRequest->created_at->format('M d, Y - g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Additional Notes Section -->
            @if($customRequest->notes)
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b-2 border-pink-200">
                    Additional Notes
                </h2>
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg p-6 border-l-4 border-amber-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $customRequest->notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Reference Images Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b-2 border-pink-200">
                    Design Reference Images
                    <span class="ml-2 text-sm font-normal text-gray-500">({{ $customRequest->images->count() }} {{ Str::plural('image', $customRequest->images->count()) }})</span>
                </h2>
                
                @if($customRequest->images->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($customRequest->images as $image)
                    <div class="bg-white rounded-lg overflow-hidden shadow-md border border-gray-200">
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            <img src="{{ $image->image_url }}" 
                                alt="Reference Image" 
                                class="w-full h-full object-cover"
                                onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjQwMCIgdmlld0JveD0iMCAwIDQwMCA0MDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI0MDAiIGhlaWdodD0iNDAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0yMDAgMjgwQzI0NC4xODMgMjgwIDI4MCAyNDQuMTgzIDI4MCAyMDBDMjgwIDE1NS44MTcgMjQ0LjE4MyAxMjAgMjAwIDEyMEMxNTUuODE3IDEyMCAxMjAgMTU1LjgxNyAxMjAgMjAwQzEyMCAyNDQuMTgzIDE1NS44MTcgMjgwIDIwMCAyODBaIiBzdHJva2U9IiM5Q0E0QUYiIHN0cm9rZS13aWR0aD0iOCIvPgo8cGF0aCBkPSJNMjAwIDIyMEgyMDAiIHN0cm9rZT0iIzlDQTRBRiIgc3Ryb2tlLXdpZHRoPSI4IiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4KPC9zdmc+Cg=='; this.classList.add('opacity-50');">
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No Reference Images</h3>
                    <p class="text-sm text-gray-500">This customer did not attach any design references.</p>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                
                <div class="flex space-x-3">
                    <button class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Create Invoice
                    </button>
                    <a href="mailto:{{ $customRequest->user_email }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection