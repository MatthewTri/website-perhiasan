@extends('layout.main')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.consultation.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Consultations
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-light text-gray-900">Consultation Details</h1>
                    <p class="text-sm text-gray-600 mt-1">ID: #{{ $consultation->id }}</p>
                </div>
                
                {{-- Status Badge --}}
                @switch($consultation->status)
                    @case('SCHEDULED')
                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-800">
                            <span class="w-2 h-2 mr-2 rounded-full bg-blue-400"></span>
                            Scheduled
                        </span>
                        @break
                    @case('COMPLETED')
                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 mr-2 rounded-full bg-green-400"></span>
                            Completed
                        </span>
                        @break
                    @case('CANCELLED')
                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800">
                            <span class="w-2 h-2 mr-2 rounded-full bg-red-400"></span>
                            Cancelled
                        </span>
                        @break
                    @case('RESCHEDULED')
                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-yellow-100 text-yellow-800">
                            <span class="w-2 h-2 mr-2 rounded-full bg-yellow-400"></span>
                            Rescheduled
                        </span>
                        @break
                @endswitch
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            
            {{-- Left Column: Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Customer Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h2>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                            <p class="text-base text-gray-900">{{ $consultation->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-base text-gray-900">{{ $consultation->user_email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                            <p class="text-base text-gray-900">{{ $consultation->user_phone }}</p>
                        </div>
                        @if($consultation->user)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Account</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Registered Member
                                </span>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Account</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Guest
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Appointment Details --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Appointment Details</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Consultation Type</label>
                            @if($consultation->consultation_type === 'design')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                    </svg>
                                    Design Consultation (60 minutes)
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Short Consultation (30 minutes)
                                </span>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Appointment Date & Time</label>
                            <div class="flex items-center text-base text-gray-900">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $consultation->formatted_appointment }}
                            </div>
                        </div>

                        @if($consultation->appointment_notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Notes from Customer</label>
                                <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700">
                                    {{ $consultation->appointment_notes }}
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Booking Date</label>
                            <p class="text-sm text-gray-600">{{ $consultation->created_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Reference Images --}}
                @if($consultation->images->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Reference Images ({{ $consultation->images->count() }})</h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($consultation->images as $image)
                                <a href="{{ $image->image_url }}" target="_blank" class="group relative aspect-square rounded-lg overflow-hidden bg-gray-100 hover:opacity-90 transition-opacity">
                                    <img src="{{ $image->image_url }}" alt="Reference Image" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right Column: Actions --}}
            <div class="space-y-6">
                
                {{-- Update Status --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Update Status</h2>
                    
                    <form action="{{ route('admin.consultation.updateStatus', $consultation) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="SCHEDULED" {{ $consultation->status == 'SCHEDULED' ? 'selected' : '' }}>Scheduled</option>
                                <option value="COMPLETED" {{ $consultation->status == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                                <option value="CANCELLED" {{ $consultation->status == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                                <option value="RESCHEDULED" {{ $consultation->status == 'RESCHEDULED' ? 'selected' : '' }}>Rescheduled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Update Status
                        </button>
                    </form>
                </div>

                {{-- Reschedule --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Reschedule Appointment</h2>
                    
                    <form action="{{ route('admin.consultation.reschedule', $consultation) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Date</label>
                            <input type="date" name="appointment_date" value="{{ $consultation->appointment_date->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Time</label>
                            <input type="time" name="appointment_time" value="{{ \Carbon\Carbon::parse($consultation->appointment_time)->format('H:i') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        
                        <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                            Reschedule
                        </button>
                    </form>
                </div>

                {{-- Delete --}}
                <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
                    <h2 class="text-lg font-medium text-red-900 mb-2">Danger Zone</h2>
                    <p class="text-sm text-gray-600 mb-4">Once deleted, this consultation cannot be recovered.</p>
                    
                    <form action="{{ route('admin.consultation.destroy', $consultation) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this consultation? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Delete Consultation
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection