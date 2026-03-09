@extends('layout.main')

@section('content')
<div class="min-h-screen bg-stone-100 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
            <div>
                <h6 class="text-xs font-bold tracking-[0.2em] text-stone-500 uppercase mb-2">Concierge Dashboard</h6>
                <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight">Consultation Requests</h1>
            </div>
            
            <div class="flex gap-4">
                <a href="{{ route('admin.calendar.settings') }}" class="group inline-flex items-center text-xs font-bold tracking-[0.15em] text-stone-600 uppercase hover:text-stone-900 transition-colors px-4 py-2 border border-stone-200 rounded-sm hover:border-stone-400 bg-white">
                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Calendar Settings
                </a>
                <a href="{{ route('home') }}" class="group inline-flex items-center text-xs font-bold tracking-[0.15em] text-stone-400 uppercase hover:text-stone-900 transition-colors">
                    <svg class="w-3 h-3 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Site
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border-l-2 border-emerald-500 text-emerald-800 text-sm tracking-wide shadow-sm flex items-center animate-fade-in-up">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Statistics Cards (Elegant Grid) --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-10">
            {{-- Helper component for stats card --}}
            @php
                function statCard($label, $value, $colorClass = 'text-stone-900') {
                    return '
                    <div class="bg-white p-5 rounded-sm border border-stone-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-md transition-shadow group">
                        <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1 group-hover:text-stone-600 transition-colors">'.$label.'</p>
                        <p class="text-2xl font-serif '.$colorClass.'">'.$value.'</p>
                    </div>';
                }
            @endphp

            {!! statCard('Total Requests', $stats['total']) !!}
            {!! statCard('Upcoming', $stats['scheduled'], 'text-blue-600') !!}
            {!! statCard('Completed', $stats['completed'], 'text-emerald-600') !!}
            {!! statCard('Rescheduled', $stats['rescheduled'], 'text-amber-600') !!}
            {!! statCard('Cancelled', $stats['cancelled'], 'text-rose-600') !!}
            <div class="bg-stone-900 p-5 rounded-sm border border-stone-900 shadow-md group">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1 group-hover:text-stone-200 transition-colors">Today's Agenda</p>
                <p class="text-2xl font-serif text-white">{{ $stats['upcoming_today'] }}</p>
            </div>
        </div>

        {{-- Filters & Search Bar --}}
        <div class="bg-white rounded-sm shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-stone-100 p-6 mb-8">
            <form method="GET" action="{{ route('admin.consultation.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                
                {{-- Search --}}
                <div class="md:col-span-4">
                    <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-2">Search Client</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email or phone..." 
                               class="w-full bg-stone-50 border-b-2 border-stone-200 px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-stone-900 transition-colors">
                        <svg class="w-4 h-4 text-stone-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-2">Status</label>
                    <div class="relative">
                        <select name="status" class="w-full bg-stone-50 border-b-2 border-stone-200 px-4 py-3 text-sm text-stone-900 focus:outline-none focus:border-stone-900 transition-colors appearance-none cursor-pointer">
                            <option value="">All Status</option>
                            <option value="SCHEDULED" {{ request('status') == 'SCHEDULED' ? 'selected' : '' }}>Upcoming</option>
                            <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                            <option value="RESCHEDULED" {{ request('status') == 'RESCHEDULED' ? 'selected' : '' }}>Rescheduled</option>
                            <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-stone-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>

                {{-- Type Filter --}}
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-2">Service Type</label>
                    <div class="relative">
                        <select name="type" class="w-full bg-stone-50 border-b-2 border-stone-200 px-4 py-3 text-sm text-stone-900 focus:outline-none focus:border-stone-900 transition-colors appearance-none cursor-pointer">
                            <option value="">All Services</option>
                            <option value="short" {{ request('type') == 'short' ? 'selected' : '' }}>Short Consultation (30m)</option>
                            <option value="design" {{ request('type') == 'design' ? 'selected' : '' }}>Custom Design (60m)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-stone-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>

                {{-- Clear Filter Button --}}
                <div class="md:col-span-2">
                    <button type="button" id="clearFiltersBtn" class="w-full bg-rose-500 text-white py-3.5 px-6 text-xs font-bold uppercase tracking-[0.2em] hover:bg-rose-600 transition-all rounded-sm shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        Clear Filters
                    </button>
                </div>
            </form>
        </div>

        {{-- Consultations Table --}}
        <div class="bg-white rounded-sm shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-stone-100 overflow-hidden">
            <div id="consultationsTableContainer">
            @if($consultations->count() > 0)
                <div class="overflow-x-auto" id="consultationsTable">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-stone-50 border-b border-stone-100">
                            <tr>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Client Profile</th>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Service Type</th>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Schedule (WIB)</th>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Status</th>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50">
                            @foreach($consultations as $consultation)
                                <tr class="group hover:bg-stone-50/50 transition-colors duration-200">
                                    
                                    {{-- Customer --}}
                                    <td class="px-6 py-5">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-stone-900 group-hover:text-stone-600 transition-colors">{{ $consultation->full_name }}</div>
                                                <div class="text-xs text-stone-400 mt-0.5">{{ $consultation->user_email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Type --}}
                                    <td class="px-6 py-5">
                                        @if($consultation->consultation_type === 'design')
                                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider flex items-center">
                                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400 mr-2"></span> Custom Design
                                            </span>
                                            <span class="block text-[10px] text-stone-400 mt-1 pl-3.5">60 Mins</span>
                                        @else
                                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider flex items-center">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-2"></span> Short Consultation
                                            </span>
                                            <span class="block text-[10px] text-stone-400 mt-1 pl-3.5">30 Mins</span>
                                        @endif
                                        
                                        {{-- Meeting Type Badge --}}
                                        <div class="mt-2 pl-3.5">
                                            @if($consultation->meeting_type === 'offline')
                                                <span class="inline-flex items-center text-[10px] text-stone-600">
                                                    <svg class="w-2.5 h-2.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    In-Person
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-[10px] text-stone-600">
                                                    <svg class="w-2.5 h-2.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Virtual
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Appointment --}}
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-stone-900">{{ $consultation->appointment_date->format('M j, Y') }}</span>
                                            <span class="text-xs text-stone-500 mt-0.5 flex items-center">
                                                <svg class="w-3 h-3 mr-1 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $consultation->formatted_appointment_time ?? \Carbon\Carbon::parse($consultation->appointment_time)->format('H:i') }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-5">
                                        @php
                                            $statusStyles = [
                                                'SCHEDULED' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'COMPLETED' => 'bg-stone-100 text-stone-600 border-stone-200',
                                                'CANCELLED' => 'bg-rose-50 text-rose-700 border-rose-100',
                                                'RESCHEDULED' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            ];
                                            $style = $statusStyles[$consultation->status] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-sm text-[10px] font-bold uppercase tracking-wider border {{ $style }}">
                                            {{ $consultation->status === 'SCHEDULED' ? 'Upcoming' : $consultation->status }}
                                        </span>
                                    </td>


                                    {{-- Actions --}}
                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('admin.consultation.show', $consultation) }}" 
                                           class="inline-block px-4 py-2 border border-stone-200 text-[10px] font-bold text-stone-500 uppercase tracking-widest hover:text-stone-900 hover:border-stone-400 hover:bg-white rounded-sm transition-all bg-stone-50">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-6 border-t border-stone-100 bg-stone-50/30" id="consultationsPagination">
                    {{ $consultations->links('vendor.pagination.custom') }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-20 bg-stone-50/30">
                    <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4 text-stone-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-serif text-stone-900 mb-1">No consultations found</h3>
                    <p class="text-sm text-stone-500 font-light">Try adjusting your filters to find what you're looking for.</p>
                    <a href="{{ route('admin.consultation.index') }}" class="inline-block mt-4 text-xs font-bold text-stone-900 uppercase tracking-widest border-b border-stone-900 pb-0.5 hover:opacity-70 transition-opacity">Clear Filters</a>
                </div>
            @endif
            </div>
        </div>

    </div>
</div>

<script>
// AJAX Filter untuk Consultation
let filterTimeout;

function applyFilters(url = null) {
    const formData = {
        search: document.querySelector('input[name="search"]').value,
        status: document.querySelector('select[name="status"]').value,
        type: document.querySelector('select[name="type"]').value,
    };

    // Show loading state
    const container = document.getElementById('consultationsTableContainer');
    container.style.opacity = '0.5';
    container.style.pointerEvents = 'none';

    // Use provided URL (for pagination) or default route
    const ajaxUrl = url || '{{ route("admin.consultation.index") }}';

    fetch(ajaxUrl + '?' + new URLSearchParams(formData), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update table content
        document.getElementById('consultationsTableContainer').innerHTML = data.html;
        
        // Update pagination if exists
        if (data.pagination) {
            const paginationContainer = document.getElementById('consultationsPagination');
            if (paginationContainer) {
                paginationContainer.innerHTML = data.pagination;
            }
        }

        // Remove loading state
        container.style.opacity = '1';
        container.style.pointerEvents = 'auto';

        // Update URL without page reload
        const newUrl = new URL(window.location);
        Object.keys(formData).forEach(key => {
            if (formData[key]) {
                newUrl.searchParams.set(key, formData[key]);
            } else {
                newUrl.searchParams.delete(key);
            }
        });
        window.history.pushState({}, '', newUrl);

        // Re-attach pagination click handlers
        attachPaginationHandlers();
    })
    .catch(error => {
        console.error('Error:', error);
        container.style.opacity = '1';
        container.style.pointerEvents = 'auto';
    });
}

function attachPaginationHandlers() {
    document.querySelectorAll('#consultationsPagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            if (url) {
                applyFilters(url);
            }
        });
    });
}

// Real-time search
document.querySelector('input[name="search"]').addEventListener('input', function() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        applyFilters();
    }, 500); // Debounce 500ms
});

// Real-time filter changes
document.querySelectorAll('select[name="status"], select[name="type"]').forEach(select => {
    select.addEventListener('change', function() {
        applyFilters();
    });
});

// Clear filters button
document.getElementById('clearFiltersBtn').addEventListener('click', function() {
    document.querySelector('input[name="search"]').value = '';
    document.querySelector('select[name="status"]').value = '';
    document.querySelector('select[name="type"]').value = '';
    applyFilters();
});

// Initialize pagination handlers
attachPaginationHandlers();
</script>

<style>
    /* Smooth fade in animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }
</style>
@endsection