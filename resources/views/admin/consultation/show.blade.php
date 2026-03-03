@extends('layout.main')

@section('content')
<div class="min-h-screen bg-stone-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Navigation & Title --}}
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <a href="{{ route('admin.consultation.index') }}" class="group inline-flex items-center text-xs font-bold tracking-[0.15em] text-stone-400 uppercase hover:text-stone-900 transition-colors mb-4">
                        <svg class="w-3 h-3 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to List
                    </a>
                    <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight">Consultation #{{ $consultation->id }}</h1>
                </div>

                {{-- Status Badge (Redesigned) --}}
                <div class="flex items-center">
                    @php
                        $statusClasses = [
                            'SCHEDULED' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            'COMPLETED' => 'bg-stone-100 text-stone-600 border-stone-200',
                            'CANCELLED' => 'bg-rose-50 text-rose-700 border-rose-100',
                            'RESCHEDULED' => 'bg-amber-50 text-amber-700 border-amber-100',
                        ];
                        $badgeClass = $statusClasses[$consultation->status] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                    @endphp
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest border {{ $badgeClass }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2 bg-current opacity-60"></span>
                        {{ $consultation->status }}
                    </span>
                </div>
            </div>
        </div>


        {{-- Main Content Grid --}}
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
            
            {{-- Left Column: Calendar & Reschedule (Sticky) --}}
            <div class="lg:col-span-5">
                <div class="sticky top-6 space-y-6">
                    
                    {{-- Calendar Card --}}
                    @if(!in_array($consultation->status, ['COMPLETED', 'CANCELLED']))
                        <div class="bg-white rounded-sm shadow-[0_2px_20px_rgba(0,0,0,0.03)] border border-stone-100 p-6 md:p-8">
                            <h2 class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-6">Reschedule Appointment</h2>
                            
                            {{-- Calendar Header --}}
                            <div class="flex items-center justify-between mb-6">
                                <button type="button" id="prev-month" class="p-2 text-stone-400 hover:text-stone-900 hover:bg-stone-50 rounded-full transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <h3 class="text-base font-serif text-stone-900 tracking-wide" id="current-month-year">January 2026</h3>
                                <button type="button" id="next-month" class="p-2 text-stone-400 hover:text-stone-900 hover:bg-stone-50 rounded-full transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>

                            {{-- Calendar Grid Header --}}
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $day)
                                    <div class="text-center text-[10px] font-bold text-stone-300 uppercase py-2">{{ $day }}</div>
                                @endforeach
                            </div>

                            {{-- Calendar Dates --}}
                            <div id="calendar-dates" class="grid grid-cols-7 gap-1 mb-6"></div>

                            <p class="text-[10px] text-stone-400 text-center mb-6 uppercase tracking-widest">Timezone: WIB (GMT+7)</p>

                            {{-- Time Slots --}}
                            <div id="time-slots-container" class="hidden border-t border-stone-100 pt-6">
                                <h3 class="text-sm font-serif text-stone-900 mb-4" id="selected-date-header"></h3>
                                
                                <div id="loading-slots" class="text-center py-6">
                                    <div class="animate-spin w-5 h-5 border-2 border-stone-200 border-t-stone-900 rounded-full mx-auto mb-2"></div>
                                    <p class="text-[10px] text-stone-400 uppercase tracking-widest">Loading...</p>
                                </div>
                                
                                <div id="time-slots" class="space-y-3 hidden max-h-60 overflow-y-auto custom-scrollbar pr-2"></div>
                            </div>

                            {{-- Empty State --}}
                            <div id="no-date-message" class="bg-stone-50 border border-dashed border-stone-200 rounded-sm p-6 text-center">
                                <p class="text-xs text-stone-400 italic">Select a date to view slots.</p>
                            </div>

                            {{-- Confirm Reschedule Form --}}
                            <form id="reschedule-form" action="{{ route('admin.consultation.reschedule', $consultation) }}" method="POST" class="hidden mt-6 pt-6 border-t border-stone-100">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="appointment_date" id="new-appointment-date">
                                <input type="hidden" name="appointment_time" id="new-appointment-time">
                                
                                <div class="bg-gray-50 border border-gray-100 rounded-sm p-4 mb-4">
                                    <p class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">New Schedule Selected</p>
                                    <p class="text-sm text-gray-900" id="new-appointment-display"></p>
                                </div>

                                <button type="button" onclick="confirmReschedule()" class="w-full py-3 bg-stone-900 text-white text-xs font-bold uppercase tracking-[0.2em] rounded-sm hover:bg-stone-800 transition-colors shadow-lg cursor-pointer">
                                    Confirm Reschedule
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Locked State --}}
                        <div class="bg-white rounded-sm shadow-[0_2px_20px_rgba(0,0,0,0.03)] border border-stone-100 p-8 text-center">
                            <div class="w-12 h-12 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-4 text-stone-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <h3 class="text-lg font-serif text-stone-900 mb-2">Schedule Locked</h3>
                            <p class="text-sm text-stone-500 font-light">This consultation is <strong>{{ strtolower($consultation->status) }}</strong> and cannot be modified.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: Information & Actions --}}
            <div class="lg:col-span-7 space-y-8">
                
                {{-- 1. Appointment Info --}}
                <div class="bg-white rounded-sm shadow-[0_2px_20px_rgba(0,0,0,0.03)] border border-stone-100 p-6 md:p-8">
                    <h2 class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-6">Appointment Details</h2>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] text-stone-400 uppercase tracking-wider mb-1">Type</label>
                            @if($consultation->consultation_type === 'design')
                                <p class="text-base text-stone-900 font-serif">Bespoke Design Consultation</p>
                                <p class="text-xs text-stone-500 mt-0.5">60 Minutes • Private Suite</p>
                            @else
                                <p class="text-base text-stone-900 font-serif">General Inquiry</p>
                                <p class="text-xs text-stone-500 mt-0.5">30 Minutes • In-Store / Virtual</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-[10px] text-stone-400 uppercase tracking-wider mb-1">Current Schedule</label>
                            <p class="text-base text-stone-900">{{ $consultation->formatted_appointment }}</p>
                        </div>
                    </div>
                </div>

                {{-- Meeting Location/Type --}}
                <div class="bg-white rounded-sm shadow-[0_2px_20px_rgba(0,0,0,0.03)] border border-stone-100 p-6 md:p-8">
                    <h2 class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-6">
                        @if($consultation->meeting_type === 'offline')
                            Meeting Location
                        @else
                            Virtual Meeting
                        @endif
                    </h2>
                    
                    @if($consultation->meeting_type === 'offline')
                        <div>
                            <p class="text-lg text-stone-900 font-serif mb-2">Cécile Studio</p>
                            <p class="text-sm text-stone-600 leading-relaxed">
                                Jl. Prapen Indah B no. 12<br>
                                Surabaya 60299, Indonesia
                            </p>
                            <div class="mt-4 pt-4 border-t border-stone-100">
                                <p class="text-xs text-stone-500 italic">
                                    Client has been instructed to arrive 5-10 minutes early. Parking available on-site.
                                </p>
                            </div>
                        </div>
                    @else
                        <div>
                            <p class="text-base text-stone-900 font-medium mb-2">Zoom / Google Meet</p>
                            <div class="bg-amber-50 border border-amber-100 rounded-sm p-4">
                                <p class="text-sm text-amber-900 flex items-start">
                                    <svg class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>
                                        <strong>Reminder:</strong> Send meeting link to client <strong>30 minutes before</strong> the scheduled time via WhatsApp and Email.
                                    </span>
                                </p>
                            </div>
                            <div class="mt-3">
                                <p class="text-xs text-stone-500">
                                    Client Email: <a href="mailto:{{ $consultation->user_email }}" class="text-stone-900 underline">{{ $consultation->user_email }}</a><br>
                                    Client Phone: <a href="tel:{{ $consultation->user_phone }}" class="text-stone-900 underline">{{ $consultation->user_phone }}</a>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- 2. Customer Info --}}
                <div class="bg-white rounded-sm shadow-[0_2px_20px_rgba(0,0,0,0.03)] border border-stone-100 p-6 md:p-8">
                    <h2 class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-6">Client Profile</h2>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] text-stone-400 uppercase tracking-wider mb-1">Name</label>
                            <p class="text-base text-stone-900 font-medium">{{ $consultation->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] text-stone-400 uppercase tracking-wider mb-1">Contact</label>
                            <p class="text-sm text-stone-900">{{ $consultation->user_email }}</p>
                            <p class="text-sm text-stone-900">{{ $consultation->user_phone }}</p>
                        </div>
                    </div>

                    @if($consultation->appointment_notes)
                        <div class="mt-8 pt-6 border-t border-stone-50">
                            <label class="block text-[10px] text-stone-400 uppercase tracking-wider mb-2">Client Notes</label>
                            <div class="bg-stone-50 p-4 border border-stone-100 rounded-sm">
                                <p class="text-sm text-stone-600 italic leading-relaxed">"{{ $consultation->appointment_notes }}"</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- 3. Reference Images --}}
                @if($consultation->images->count() > 0)
                    <div class="bg-white rounded-sm shadow-[0_2px_20px_rgba(0,0,0,0.03)] border border-stone-100 p-6 md:p-8">
                        <h2 class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-6">Visual References ({{ $consultation->images->count() }})</h2>
                        <div class="grid grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach($consultation->images as $image)
                                <div class="group relative aspect-square bg-stone-100 rounded-sm overflow-hidden cursor-zoom-in"
                                    onclick="openLightbox('{{ $image->image_url }}')">
                                    <img src="{{ $image->image_url }}" alt="Ref" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- 4. Actions --}}
                @if(!in_array($consultation->status, ['COMPLETED', 'CANCELLED']))
                    <div class="bg-white rounded-sm shadow-[0_2px_20px_rgba(0,0,0,0.03)] border border-stone-100 p-6 md:p-8">
                        <h2 class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-6">Actions</h2>
                        <div class="flex flex-col md:flex-row gap-4">
                            {{-- Complete Button dengan SweetAlert --}}
                            <button type="button" onclick="confirmComplete()" class="flex-1 w-full py-3 px-4 border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-bold uppercase tracking-widest rounded-sm cursor-pointer transition-all">
                                Mark as Completed
                            </button>
                            
                            {{-- Cancel Button --}}
                            <button type="button" onclick="openCancelModal()" class="flex-1 w-full py-3 px-4 bg-[#E8B49E] hover:bg-[#d6a88b] text-white text-xs font-bold uppercase tracking-widest rounded-sm transition-all border border-[#E8B49E] shadow-md cursor-pointer">
                                Cancel Consultation
                            </button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- Hidden form untuk Complete --}}
<form id="complete-form" action="{{ route('admin.consultation.updateStatus', $consultation) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="COMPLETED">
</form>

{{-- Cancel Modal (Minimalist) --}}
<div id="cancelModal" class="fixed inset-0 bg-stone-900/60 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-sm shadow-2xl max-w-md w-full transform transition-all scale-100">
        <div class="p-8">
            <h3 class="text-xl font-serif text-stone-900 mb-6">Cancel Appointment</h3>
            <form action="{{ route('admin.consultation.updateStatus', $consultation) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="CANCELLED">
                
                <div class="mb-6">
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-widest mb-2">Reason for Cancellation <span class="text-rose-500">*</span></label>
                    <textarea name="admin_notes" rows="3" required class="w-full bg-stone-50 border-b-2 border-stone-200 p-3 text-sm focus:outline-none focus:border-stone-900 transition-colors resize-none" placeholder="e.g. Client requested cancellation..."></textarea>
                    <p class="text-[10px] text-stone-400 mt-2">This note will be sent to the client.</p>
                </div>
                
                <div class="flex gap-4">
                    <button type="button" onclick="closeCancelModal()" class="flex-1 py-3 text-xs cursor-pointer font-bold text-stone-500 uppercase tracking-widest hover:text-stone-800 transition-colors">
                        Nevermind
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-[#E8B49E] hover:bg-[#d6a88b] text-white text-xs font-bold uppercase tracking-widest rounded-sm cursor-pointer shadow-md border border-[#E8B49E] transition-colors">
                        Confirm Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('components.image-lightbox')

<script>
    // SweetAlert untuk Complete Confirmation
    function confirmComplete() {
        Swal.fire({
            title: 'Mark as Completed?',
            html: '<p class="text-sm text-gray-600">This will mark the consultation as completed and cannot be undone.</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#292524',
            cancelButtonColor: '#d1d5db',
            confirmButtonText: 'Yes, Complete',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-sm',
                title: 'text-stone-900',
                confirmButton: 'uppercase tracking-widest text-xs',
                cancelButton: 'uppercase tracking-widest text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('complete-form').submit();
            }
        });
    }

    function confirmReschedule() {
        Swal.fire({
            title: 'Confirm Reschedule?',
            html: '<p class="text-sm text-gray-600">Are you sure you want to reschedule this consultation to the selected date and time?</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#292524',
            cancelButtonColor: '#d1d5db',
            confirmButtonText: 'Yes, Reschedule',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-sm',
                title: 'font-serif text-stone-900',
                confirmButton: 'uppercase tracking-widest text-xs',
                cancelButton: 'uppercase tracking-widest text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('reschedule-form').submit();
            }
        });
    }

    // Modal Cancel
    function openCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
        document.getElementById('cancelModal').classList.add('flex');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
        document.getElementById('cancelModal').classList.remove('flex');
    }

    document.getElementById('cancelModal').addEventListener('click', function(e) {
        if (e.target === this) closeCancelModal();
    });

    // Success/Error Notification dengan SweetAlert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            html: '<p class="text-sm text-gray-600">{{ session("success") }}</p>',
            confirmButtonColor: '#292524',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'rounded-sm',
                title: 'font-serif text-stone-900',
                confirmButton: 'uppercase tracking-widest text-xs'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: '<p class="text-sm text-gray-600">{{ session("error") }}</p>',
            confirmButtonColor: '#292524',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'rounded-sm',
                title: 'font-serif text-stone-900',
                confirmButton: 'uppercase tracking-widest text-xs'
            }
        });
    @endif

    // ===================================
    // STATE MANAGEMENT
    // ===================================
    const state = {
        consultationType: '{{ $consultation->consultation_type }}',
        duration: '{{ $consultation->consultation_type }}' === 'design' ? 60 : 30,
        consultationId: {{ $consultation->id }},
        currentDate: new Date('{{ $consultation->appointment_date->format("Y-m-d") }}'),
        selectedDate: null,
        selectedTime: null,
        bookedSlots: [],
        unavailableDates: [],
        cache: {
            unavailableDates: new Map(),
            slots: new Map()
        }
    };

    const appointmentDateStr = '{{ $consultation->appointment_date->format("Y-m-d") }}';
    const appointmentTimeStr = '{{ \Carbon\Carbon::parse($consultation->appointment_time)->format("H:i") }}';
    const currentAppointmentDate = new Date(appointmentDateStr);

    const businessHours = {
        weekday: { start: 9, end: 17 },
        weekend: { start: 9, end: 12 }
    };

    // ===================================
    // UTILITY FUNCTIONS
    // ===================================
    function getTodayWIB() {
        const now = new Date();
        const wibOffset = 7 * 60;
        const localOffset = now.getTimezoneOffset();
        const wibTime = new Date(now.getTime() + (wibOffset + localOffset) * 60 * 1000);
        wibTime.setHours(0, 0, 0, 0);
        return wibTime;
    }

    function formatDateKey(year, month) {
        return `${year}-${String(month).padStart(2, '0')}`;
    }

    function formatDateStr(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // ===================================
    // API CALLS (with caching)
    // ===================================
    async function fetchUnavailableDates(year, month) {
        const cacheKey = formatDateKey(year, month);
        
        if (state.cache.unavailableDates.has(cacheKey)) {
            state.unavailableDates = state.cache.unavailableDates.get(cacheKey);
            return;
        }
        
        try {
            const response = await fetch(`{{ route('consultation.unavailableDates') }}?year=${year}&month=${month}`);
            if (!response.ok) throw new Error('Failed to fetch unavailable dates');
            
            const data = await response.json();
            state.unavailableDates = data.unavailable_dates || [];
            
            state.cache.unavailableDates.set(cacheKey, state.unavailableDates);
        } catch (error) {
            console.error('Error fetching unavailable dates:', error);
            state.unavailableDates = [];
        }
    }

    async function fetchAvailability(date) {
        const dateStr = formatDateStr(date);
        
        // ✅ Check cache first
        if (state.cache.slots.has(dateStr)) {
            state.bookedSlots = state.cache.slots.get(dateStr);
            return; // No need to show loading if cached
        }
        
        try {
            const response = await fetch(`{{ route('consultation.availability') }}?date=${dateStr}&type=${state.consultationType}`);
            if (!response.ok) throw new Error('Failed to fetch availability');
            
            const data = await response.json();
            
            // ✅ Check if unavailable
            if (data.is_unavailable) {
                state.bookedSlots = [];
                state.cache.slots.set(dateStr, []);
                return;
            }
            
            state.bookedSlots = data.booked_slots || [];
            state.cache.slots.set(dateStr, state.bookedSlots);
            
        } catch (error) {
            console.error('Error fetching availability:', error);
            state.bookedSlots = [];
        }
    }

    async function selectDate(date) {
        state.selectedDate = date;
        state.selectedTime = null;
        
        // ✅ 1. Update calendar UI immediately
        renderCalendar(state.currentDate);
        
        // ✅ 2. Show container & loading
        const container = document.getElementById('time-slots-container');
        const noDateMsg = document.getElementById('no-date-message');
        const loading = document.getElementById('loading-slots');
        const slotsDiv = document.getElementById('time-slots');
        
        container.classList.remove('hidden');
        noDateMsg.classList.add('hidden');
        loading.classList.remove('hidden');
        slotsDiv.classList.add('hidden');
        
        // ✅ 3. Fetch data
        await fetchAvailability(date);
        
        // ✅ 4. Hide loading & show slots
        loading.classList.add('hidden');
        slotsDiv.classList.remove('hidden');
        
        // ✅ 5. Render time slots
        renderTimeSlots(date);
        
        // ✅ 6. Hide reschedule form
        document.getElementById('reschedule-form').classList.add('hidden');
    }

    // ===================================
    // RENDER FUNCTIONS
    // ===================================
    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        document.getElementById('current-month-year').textContent = `${monthNames[month]} ${year}`;
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        const calendarDates = document.getElementById('calendar-dates');
        calendarDates.innerHTML = '';
        
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'aspect-square';
            calendarDates.appendChild(emptyCell);
        }
        
        const today = getTodayWIB();
        
        for (let day = 1; day <= daysInMonth; day++) {
            const dateObj = new Date(year, month, day);
            const dateStr = formatDateStr(dateObj);
            const isPastOrToday = dateObj <= today;
            const isUnavailable = state.unavailableDates.includes(dateStr);
            
            const isCurrentAppointment = dateObj.getDate() === currentAppointmentDate.getDate() &&
                dateObj.getMonth() === currentAppointmentDate.getMonth() &&
                dateObj.getFullYear() === currentAppointmentDate.getFullYear();
            
            const isSelected = state.selectedDate && 
                dateObj.getDate() === state.selectedDate.getDate() &&
                dateObj.getMonth() === state.selectedDate.getMonth() &&
                dateObj.getFullYear() === state.selectedDate.getFullYear();
            
            const dayCell = document.createElement('button');
            dayCell.type = 'button';
            dayCell.textContent = day;
            
            // ✅ UPDATED: Tambahkan handling untuk unavailable dates
            dayCell.className = `aspect-square flex items-center justify-center rounded-sm text-xs font-medium transition-all ${
                isPastOrToday || isUnavailable
                    ? 'text-stone-300 cursor-not-allowed bg-transparent line-through' 
                    : isCurrentAppointment
                        ? 'bg-[#E8B49E] text-white border-[#E8B49E] font-bold shadow-md hover:bg-[#d6a88b] hover:border-[#d6a88b] cursor-pointer'
                        : isSelected
                            ? 'bg-stone-900 text-white shadow-md scale-105 cursor-pointer'
                            : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900 cursor-pointer'
            }`;
            
            if (!isPastOrToday && !isUnavailable) {
                dayCell.onclick = () => selectDate(dateObj);
            } else {
                dayCell.disabled = true;
            }
            
            calendarDates.appendChild(dayCell);
        }
    }

    async function selectDate(date) {
        state.selectedDate = date;
        state.selectedTime = null;
        
        // ✅ 1. Update calendar UI
        renderCalendar(state.currentDate);
        
        // ✅ 2. Show time slots container & hide empty state
        document.getElementById('time-slots-container').classList.remove('hidden');
        document.getElementById('no-date-message').classList.add('hidden');
        
        // ✅ 3. Show loading
        document.getElementById('loading-slots').classList.remove('hidden');
        document.getElementById('time-slots').classList.add('hidden');
        
        // ✅ 4. Fetch availability
        await fetchAvailability(date);
        
        // ✅ 5. Hide loading & show slots
        document.getElementById('loading-slots').classList.add('hidden');
        document.getElementById('time-slots').classList.remove('hidden');
        
        // ✅ 6. Render time slots
        renderTimeSlots(date);
        
        // ✅ 7. Hide reschedule form (reset state)
        document.getElementById('reschedule-form').classList.add('hidden');
    }

    function renderTimeSlots(date) {
        const dayOfWeek = date.getDay();
        const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
        const hours = isWeekend ? businessHours.weekend : businessHours.weekday;
        
        const selectedDateHeader = document.getElementById('selected-date-header');
        const timeSlots = document.getElementById('time-slots');
        
        const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
        selectedDateHeader.textContent = date.toLocaleDateString('en-US', options);
        
        timeSlots.innerHTML = '';
        
        const now = new Date();
        const wibOffset = 7 * 60;
        const localOffset = now.getTimezoneOffset();
        const nowWIB = new Date(now.getTime() + (wibOffset + localOffset) * 60 * 1000);

        const selected = new Date(date);
        selected.setHours(0,0,0,0);
        const today = new Date(nowWIB);
        today.setHours(0,0,0,0);

        let isTodayOrPast = selected.getTime() <= today.getTime();
        const slots = generateTimeSlots(hours.start, hours.end, state.duration);

        let availableSlots = slots.filter(slot => {
            if (state.bookedSlots.includes(slot.time24)) return false;
            if (selected.getTime() === today.getTime()) {
                const slotDate = new Date(date);
                slotDate.setHours(Number(slot.time24.split(':')[0]), Number(slot.time24.split(':')[1]), 0, 0);
                if (slotDate.getTime() <= nowWIB.getTime()) return false;
            }
            return true;
        });

        if (isTodayOrPast || availableSlots.length === 0) {
            timeSlots.innerHTML = '<p class="text-xs text-stone-400 text-center py-4">No available slots.</p>';
            return;
        }
        
        const isCurrentAppointmentDate = date.getDate() === currentAppointmentDate.getDate() &&
            date.getMonth() === currentAppointmentDate.getMonth() &&
            date.getFullYear() === currentAppointmentDate.getFullYear();
        
        const morning = slots.filter(s => s.hour < 12);
        const afternoon = slots.filter(s => s.hour >= 12 && s.hour < 17);
        const evening = slots.filter(s => s.hour >= 17);
        
        if (morning.length > 0) timeSlots.appendChild(createTimeSection('Morning', morning, isCurrentAppointmentDate));
        if (afternoon.length > 0) timeSlots.appendChild(createTimeSection('Afternoon', afternoon, isCurrentAppointmentDate));
        if (evening.length > 0) timeSlots.appendChild(createTimeSection('Evening', evening, isCurrentAppointmentDate));
    }

    function generateTimeSlots(startHour, endHour, duration) {
        const slots = [];
        
        if (duration === 60) {
            for (let hour = startHour; hour < endHour; hour++) {
                const time24 = `${hour.toString().padStart(2, '0')}:00`;
                const period = hour >= 12 ? 'PM' : 'AM';
                const hour12 = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
                const time12 = `${hour12}:00 ${period}`;
                slots.push({ hour, minute: 0, time24, time12 });
            }
        } else {
            let currentMinutes = startHour * 60;
            const endMinutes = endHour * 60;
            while (currentMinutes + duration <= endMinutes) {
                const hour = Math.floor(currentMinutes / 60);
                const minute = currentMinutes % 60;
                const time24 = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                const period = hour >= 12 ? 'PM' : 'AM';
                const hour12 = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
                const time12 = `${hour12}:${minute.toString().padStart(2, '0')} ${period}`;
                slots.push({ hour, minute, time24, time12 });
                currentMinutes += duration;
            }
        }
        return slots;
    }

    function createTimeSection(title, slots, isCurrentAppointmentDate) {
        const section = document.createElement('div');
        section.innerHTML = `
            <h4 class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-2">${title}</h4>
            <div class="grid grid-cols-2 gap-2 mb-4">
                ${slots.map(slot => {
                    const isBooked = state.bookedSlots.includes(slot.time24);
                    const isCurrentSlot = isCurrentAppointmentDate && slot.time24 === appointmentTimeStr;
                    const isSelected = state.selectedTime === slot.time24;

                    return `
                        <button type="button" 
                            ${isCurrentSlot ? 'disabled' : `onclick="selectTime('${slot.time24}', '${slot.time12}')"` }
                            ${isBooked && !isCurrentSlot ? 'disabled' : ''}
                            class="time-slot-btn py-2 px-2 border rounded-sm text-xs transition-colors relative ${
                                isCurrentSlot
                                    ? 'bg-[#E8B49E] text-white border-[#E8B49E] font-bold shadow-md'
                                    : isBooked 
                                        ? 'bg-stone-50 text-stone-300 border-stone-100 line-through decoration-stone-300' 
                                        : isSelected 
                                            ? 'bg-stone-900 text-white border-stone-900 font-medium shadow-md cursor-pointer'
                                            : 'border-stone-200 text-stone-600 hover:border-stone-400 hover:bg-stone-50 cursor-pointer'
                            }">
                            ${slot.time12}
                        </button>
                    `;
                }).join('')}
            </div>
        `;
        return section;
    }

    function selectTime(time24, time12) {
        if (state.bookedSlots.includes(time24) && time24 !== appointmentTimeStr) return;
        
        state.selectedTime = time24;

        renderTimeSlots(state.selectedDate);

        const dateStr = formatDateStr(state.selectedDate);
        
        document.getElementById('new-appointment-date').value = dateStr;
        document.getElementById('new-appointment-time').value = time24;
        
        const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
        const formattedDate = state.selectedDate.toLocaleDateString('en-US', options);
        document.getElementById('new-appointment-display').textContent = `${formattedDate} at ${time12}`;
        
        document.getElementById('reschedule-form').classList.remove('hidden');
    }

    async function changeMonth(direction) {
        state.currentDate.setMonth(state.currentDate.getMonth() + direction);
        
        await fetchUnavailableDates(state.currentDate.getFullYear(), state.currentDate.getMonth() + 1);
        
        renderCalendar(state.currentDate);
    }

    // ===================================
    // EVENT LISTENERS
    // ===================================
    document.getElementById('prev-month').onclick = () => changeMonth(-1);
    document.getElementById('next-month').onclick = () => changeMonth(1);

    // ===================================
    // INITIALIZATION
    // ===================================
    (async function init() {
        await fetchUnavailableDates(state.currentDate.getFullYear(), state.currentDate.getMonth() + 1);
        renderCalendar(state.currentDate);
        selectDate(currentAppointmentDate);
    })();
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #d6d3d1; border-radius: 10px; }
</style>
@endsection