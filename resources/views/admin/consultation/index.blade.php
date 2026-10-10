@extends('layout.checkout')

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
                        <p class="text-2xl font-semibold '.$colorClass.'">'.$value.'</p>
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
                               class="w-full bg-stone-50 border-b-2 border-stone-200 px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-0 transition-colors">
                        <svg class="w-4 h-4 text-stone-400 absolute right-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-2">Status</label>
                    <div class="relative">
                        <select name="status" class="w-full bg-stone-50 border-b-2 border-stone-200 px-4 py-3 text-sm text-stone-900 focus:outline-none focus:ring-0 transition-colors appearance-none cursor-pointer">
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
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-2">Service Type</label>
                    <div class="relative">
                        <select name="type" class="w-full bg-stone-50 border-b-2 border-stone-200 px-4 py-3 text-sm text-stone-900 focus:outline-none focus:ring-0 transition-colors appearance-none cursor-pointer">
                            <option value="">All Services</option>
                            <option value="short" {{ request('type') == 'short' ? 'selected' : '' }}>Short Consultation (30m)</option>
                            <option value="design" {{ request('type') == 'design' ? 'selected' : '' }}>Custom Design (60m)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-stone-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>

                {{-- Rows per page --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-2">Rows</label>
                    <div class="relative">
                        <select name="per_page" class="w-full bg-stone-50 border-b-2 border-stone-200 pr-8 py-3 text-sm text-stone-900 focus:outline-none focus:ring-0 transition-colors appearance-none cursor-pointer">
                            @php
                                $selectedPerPage = (int) (request('per_page') ?? ($perPage ?? 10));
                            @endphp
                            <option value="5" {{ $selectedPerPage === 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $selectedPerPage === 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $selectedPerPage === 20 ? 'selected' : '' }}>20</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-stone-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>

                {{-- Clear Filter Button --}}
                <div class="md:col-span-2">
                    <button type="button" id="clearFiltersBtn" class="group relative inline-flex w-full items-center justify-center px-6 py-3.5 overflow-hidden bg-stone-900 rounded-sm transition duration-300 ease-out hover:bg-stone-800 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-stone-700 rounded-full group-hover:w-56 group-hover:h-56"></span>
                        <span class="relative text-xs font-bold uppercase tracking-[0.2em] text-white">Clear</span>
                    </button>
                </div>
            </form>
        </div>

        <form id="bulk-delete-form" action="{{ route('admin.consultation.bulkAction') }}" method="POST" class="hidden mb-6 bg-rose-50 border border-rose-100 rounded-sm px-5 py-4 items-center justify-between gap-4">
            @csrf
            <input type="hidden" name="action" value="delete">
            <div>
                <p class="text-sm font-bold text-rose-900"><span id="selected-consultations-count">0</span> consultations selected</p>
                <p class="text-xs text-rose-700 mt-1">All selected data and reference images will be permanently deleted.</p>
            </div>
            <div id="bulk-delete-ids"></div>
            <button type="submit" class="shrink-0 cursor-pointer inline-flex items-center px-4 py-2 bg-rose-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-sm hover:bg-rose-700 transition-colors">
                Delete
            </button>
        </form>

        {{-- Consultations Table --}}
        <div class="bg-white rounded-sm shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-stone-100 overflow-hidden">
            <div id="consultationsTableContainer">
            @if($consultations->count() > 0)
                <div class="overflow-x-auto" id="consultationsTable">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-stone-50 border-b border-stone-100">
                            <tr>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">
                                    <label class="inline-flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" id="select-all-consultations" class="consultation-checkbox">
                                        <span>Client Profile</span>
                                    </label>
                                </th>
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
                                            <input type="checkbox" name="consultation_ids[]" value="{{ $consultation->id }}" class="consultation-checkbox consultation-row-checkbox mr-4" aria-label="Select {{ $consultation->full_name }}">
                                            <div>
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
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.consultation.show', $consultation) }}"
                                               class="inline-block px-4 py-2 border border-stone-200 text-[10px] font-bold text-stone-500 uppercase tracking-widest hover:text-stone-900 hover:border-stone-400 hover:bg-white rounded-sm transition-all bg-stone-50">
                                                Manage
                                            </a>
                                            <form action="{{ route('admin.consultation.destroy', $consultation) }}" method="POST" class="inline delete-consultation-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-block cursor-pointer px-4 py-2 border border-rose-200 text-[10px] font-bold text-rose-600 uppercase tracking-widest hover:text-white hover:border-rose-600 hover:bg-rose-600 rounded-sm transition-all bg-white"
                                                        title="Delete consultation">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
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
                    <a href="{{ route('admin.consultation.index') }}" class="inline-block mt-4 text-xs font-bold text-stone-900 uppercase tracking-widest border-b border-stone-900 pb-0.5 hover:opacity-70 transition-opacity">Clear</a>
                </div>
            @endif
            </div>
        </div>

    </div>
</div>

<script>
// AJAX Filter untuk Consultation
let filterTimeout;
const selectedConsultationIds = new Set();

function applyFilters(url = null) {
    const formData = {
        search: document.querySelector('input[name="search"]').value,
        status: document.querySelector('select[name="status"]').value,
        type: document.querySelector('select[name="type"]').value,
        per_page: document.querySelector('select[name="per_page"]')?.value,
    };

    // Show loading state
    const container = document.getElementById('consultationsTableContainer');
    container.style.opacity = '0.5';
    container.style.pointerEvents = 'none';

    // Use provided URL (for pagination) or default route
    const ajaxUrl = url || '{{ route("admin.consultation.index") }}';
    const requestUrl = new URL(ajaxUrl, window.location.origin);

    // Reset to first page when filters change (not when clicking pagination)
    if (!url) {
        requestUrl.searchParams.delete('page');
    }

    Object.keys(formData).forEach(key => {
        if (formData[key]) {
            requestUrl.searchParams.set(key, formData[key]);
        } else {
            requestUrl.searchParams.delete(key);
        }
    });

    fetch(requestUrl.toString(), {
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
        window.history.pushState({}, '', requestUrl);

        // Re-attach pagination click handlers and restore selections on the new page
        attachPaginationHandlers();
        syncVisibleConsultationSelection();
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

function getSelectedConsultationIds() {
    return Array.from(selectedConsultationIds);
}

function syncVisibleConsultationSelection() {
    document.querySelectorAll('.consultation-row-checkbox').forEach(checkbox => {
        checkbox.checked = selectedConsultationIds.has(checkbox.value);
    });

    updateBulkDeleteState();
}

function setVisibleConsultationSelection(shouldSelect) {
    document.querySelectorAll('.consultation-row-checkbox').forEach(checkbox => {
        checkbox.checked = shouldSelect;

        if (shouldSelect) {
            selectedConsultationIds.add(checkbox.value);
        } else {
            selectedConsultationIds.delete(checkbox.value);
        }
    });

    syncVisibleConsultationSelection();
}

function updateBulkDeleteState() {
    const selectedIds = getSelectedConsultationIds();
    const bulkForm = document.getElementById('bulk-delete-form');
    const idsContainer = document.getElementById('bulk-delete-ids');
    const count = document.getElementById('selected-consultations-count');
    const selectAll = document.getElementById('select-all-consultations');
    const rowCheckboxes = document.querySelectorAll('.consultation-row-checkbox');
    const visibleSelectedCount = Array.from(rowCheckboxes)
        .filter(checkbox => selectedConsultationIds.has(checkbox.value)).length;

    count.textContent = selectedIds.length;
    idsContainer.innerHTML = selectedIds.map(id => `<input type="hidden" name="consultation_ids[]" value="${id}">`).join('');
    bulkForm.classList.toggle('hidden', selectedIds.length === 0);
    bulkForm.classList.toggle('flex', selectedIds.length > 0);

    if (selectAll) {
        selectAll.checked = rowCheckboxes.length > 0 && visibleSelectedCount === rowCheckboxes.length;
        selectAll.indeterminate = visibleSelectedCount > 0 && visibleSelectedCount < rowCheckboxes.length;
    }
}

function clearConsultationSelection() {
    selectedConsultationIds.clear();
    document.querySelectorAll('.consultation-checkbox').forEach(checkbox => {
        checkbox.checked = false;
        checkbox.indeterminate = false;
    });
    updateBulkDeleteState();
}

document.addEventListener('change', function(event) {
    if (event.target.matches('#select-all-consultations')) {
        setVisibleConsultationSelection(event.target.checked);
    }

    if (event.target.matches('.consultation-row-checkbox')) {
        if (event.target.checked) {
            selectedConsultationIds.add(event.target.value);
        } else {
            selectedConsultationIds.delete(event.target.value);
        }
        updateBulkDeleteState();
    }
});

document.addEventListener('submit', function(event) {
    const bulkForm = event.target.closest('#bulk-delete-form');
    const form = event.target.closest('.delete-consultation-form');

    if (bulkForm) {
        const selectedCount = getSelectedConsultationIds().length;

        if (selectedCount === 0 || bulkForm.dataset.confirmed === 'true') {
            return;
        }

        event.preventDefault();

        Swal.fire({
            title: `Delete ${selectedCount} Consultations?`,
            html: '<p class="text-sm text-gray-600">This will permanently delete all selected consultations and their related reference images.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#78716c',
            confirmButtonText: 'Yes, Delete All',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup: 'rounded-sm',
                title: 'font-serif text-stone-900',
                confirmButton: 'uppercase tracking-widest text-xs',
                cancelButton: 'uppercase tracking-widest text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                bulkForm.dataset.confirmed = 'true';
                bulkForm.submit();
            }
        });

        return;
    }

    if (!form || form.dataset.confirmed === 'true') {
        return;
    }

    event.preventDefault();

    Swal.fire({
        title: 'Delete Consultation?',
        html: '<p class="text-sm text-gray-600">This will permanently delete the consultation and all related data, including reference images.</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#78716c',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            popup: 'rounded-sm',
            title: 'font-serif text-stone-900',
            confirmButton: 'uppercase tracking-widest text-xs',
            cancelButton: 'uppercase tracking-widest text-xs'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.dataset.confirmed = 'true';
            form.submit();
        }
    });
});

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

// Rows per page changes
const perPageSelect = document.querySelector('select[name="per_page"]');
if (perPageSelect) {
    perPageSelect.addEventListener('change', function() {
        applyFilters();
    });
}

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

    input.consultation-checkbox {
        -webkit-appearance: none !important;
        appearance: none !important;
        width: 1rem;
        height: 1rem;
        border: 1px solid #a8a29e !important;   /* stone-400 */
        border-radius: 3px;
        background-color: #fff !important;
        background-position: center;
        background-repeat: no-repeat;
        background-size: 100%;
        cursor: pointer;
        outline: none !important;
        box-shadow: none !important;
    }

    input.consultation-checkbox:checked {
        background-color: #44403c !important;   /* stone-700 */
        border-color: #44403c !important;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12.2 5.3a1 1 0 0 0-1.4-1.4L6.5 8.2 5.2 6.9a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l5-5z'/%3E%3C/svg%3E") !important;
    }

    input.consultation-checkbox:focus-visible {
        box-shadow: 0 0 0 2px #d6d3d1 !important; /* stone-300 */
    }
</style>
@endsection