@extends('layout.main')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-light text-gray-900">Consultation Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage customer consultation bookings</p>
                </div>
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Home
                </a>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-200 p-4">
                    <p class="text-xs text-blue-600 uppercase tracking-wide mb-1">Scheduled</p>
                    <p class="text-2xl font-semibold text-blue-900">{{ $stats['scheduled'] }}</p>
                </div>
                <div class="bg-green-50 rounded-lg shadow-sm border border-green-200 p-4">
                    <p class="text-xs text-green-600 uppercase tracking-wide mb-1">Completed</p>
                    <p class="text-2xl font-semibold text-green-900">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-red-50 rounded-lg shadow-sm border border-red-200 p-4">
                    <p class="text-xs text-red-600 uppercase tracking-wide mb-1">Cancelled</p>
                    <p class="text-2xl font-semibold text-red-900">{{ $stats['cancelled'] }}</p>
                </div>
                <div class="bg-yellow-50 rounded-lg shadow-sm border border-yellow-200 p-4">
                    <p class="text-xs text-yellow-600 uppercase tracking-wide mb-1">Rescheduled</p>
                    <p class="text-2xl font-semibold text-yellow-900">{{ $stats['rescheduled'] }}</p>
                </div>
                <div class="bg-purple-50 rounded-lg shadow-sm border border-purple-200 p-4">
                    <p class="text-xs text-purple-600 uppercase tracking-wide mb-1">Today</p>
                    <p class="text-2xl font-semibold text-purple-900">{{ $stats['upcoming_today'] }}</p>
                </div>
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

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('admin.consultation.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Search --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="SCHEDULED" {{ request('status') == 'SCHEDULED' ? 'selected' : '' }}>Scheduled</option>
                        <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                        <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                        <option value="RESCHEDULED" {{ request('status') == 'RESCHEDULED' ? 'selected' : '' }}>Rescheduled</option>
                    </select>
                </div>

                {{-- Type Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="short" {{ request('type') == 'short' ? 'selected' : '' }}>Short (30 min)</option>
                        <option value="design" {{ request('type') == 'design' ? 'selected' : '' }}>Design (60 min)</option>
                    </select>
                </div>

                {{-- Filter Button --}}
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        Filter
                    </button>
                </div>
            </form>

            {{-- Reset Filter --}}
            @if(request()->hasAny(['search', 'status', 'type', 'date_from', 'date_to']))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.consultation.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear all filters
                    </a>
                </div>
            @endif
        </div>

        {{-- Consultations Table --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($consultations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Images</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($consultations as $consultation)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    {{-- Customer Info --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-600">{{ substr($consultation->first_name, 0, 1) }}{{ substr($consultation->last_name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $consultation->full_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $consultation->user_email }}</div>
                                                <div class="text-xs text-gray-400">{{ $consultation->user_phone }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Type --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($consultation->consultation_type === 'design')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                                </svg>
                                                Design (60 min)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Short (30 min)
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Appointment DateTime --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $consultation->appointment_date->format('M j, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($consultation->appointment_time)->format('g:i A') }} WIB</div>
                                        @if($consultation->is_upcoming)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                Upcoming
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($consultation->status)
                                            @case('SCHEDULED')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <span class="w-2 h-2 mr-1.5 rounded-full bg-blue-400"></span>
                                                    Scheduled
                                                </span>
                                                @break
                                            @case('COMPLETED')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <span class="w-2 h-2 mr-1.5 rounded-full bg-green-400"></span>
                                                    Completed
                                                </span>
                                                @break
                                            @case('CANCELLED')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <span class="w-2 h-2 mr-1.5 rounded-full bg-red-400"></span>
                                                    Cancelled
                                                </span>
                                                @break
                                            @case('RESCHEDULED')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <span class="w-2 h-2 mr-1.5 rounded-full bg-yellow-400"></span>
                                                    Rescheduled
                                                </span>
                                                @break
                                        @endswitch
                                    </td>

                                    {{-- Images Count --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($consultation->images->count() > 0)
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $consultation->images->count() }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.consultation.show', $consultation) }}" class="text-blue-600 hover:text-blue-900 mr-4">
                                            View Details
                                        </a>
                                        <form action="{{ route('admin.consultation.destroy', $consultation) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this consultation?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $consultations->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No consultations found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search criteria.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection