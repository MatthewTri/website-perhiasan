@extends('layout.main')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
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
                </div>
                
                {{-- Status Badge --}}
                @switch($consultation->status)
                    @case('SCHEDULED')
                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-800">
                            <span class="w-2 h-2 mr-2 rounded-full bg-blue-400"></span>
                            Upcoming
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

        <div class="grid lg:grid-cols-12 gap-6">
            
            {{-- Left Column: Calendar (Sticky) --}}
            <div class="lg:col-span-5">
                <div class="sticky top-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-medium text-gray-900 mb-6">Reschedule Appointment</h2>
                        
                        {{-- Calendar Navigation --}}
                        <div class="flex items-center justify-between mb-6">
                            <button type="button" id="prev-month" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <h3 class="text-lg font-semibold" id="current-month-year">January 2026</h3>
                            <button type="button" id="next-month" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Calendar Grid --}}
                        <div class="grid grid-cols-7 gap-1 mb-4">
                            <div class="text-center text-xs font-medium text-gray-500 py-2">Su</div>
                            <div class="text-center text-xs font-medium text-gray-500 py-2">Mo</div>
                            <div class="text-center text-xs font-medium text-gray-500 py-2">Tu</div>
                            <div class="text-center text-xs font-medium text-gray-500 py-2">We</div>
                            <div class="text-center text-xs font-medium text-gray-500 py-2">Th</div>
                            <div class="text-center text-xs font-medium text-gray-500 py-2">Fr</div>
                            <div class="text-center text-xs font-medium text-gray-500 py-2">Sa</div>
                        </div>

                        <div id="calendar-dates" class="grid grid-cols-7 gap-1 mb-4">
                            {{-- Dates will be generated by JavaScript --}}
                        </div>

                        {{-- Timezone Info --}}
                        <p class="text-xs text-gray-500 text-center mb-6">Times are shown in WIB (Jakarta Time / GMT+7)</p>

                        {{-- Time Slots --}}
                        <div id="time-slots-container" class="hidden">
                            <h3 class="font-medium text-gray-900 mb-4" id="selected-date-header"></h3>
                            
                            <div id="loading-slots" class="text-center py-8">
                                <svg class="animate-spin h-8 w-8 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm text-gray-500 mt-2">Loading available times...</p>
                            </div>
                            
                            <div id="time-slots" class="space-y-4 hidden max-h-96 overflow-y-auto">
                                {{-- Time slots will be generated by JavaScript --}}
                            </div>
                        </div>

                        {{-- No Date Selected Message --}}
                        <div id="no-date-message" class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-blue-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-600">Select a date to view available times</p>
                        </div>

                        {{-- Reschedule Form (Hidden, will be shown when time selected) --}}
                        <form id="reschedule-form" action="{{ route('admin.consultation.reschedule', $consultation) }}" method="POST" class="hidden mt-6">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="appointment_date" id="new-appointment-date">
                            <input type="hidden" name="appointment_time" id="new-appointment-time">
                            
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <h4 class="text-sm font-medium text-yellow-900 mb-2">New Appointment</h4>
                                <p class="text-sm text-yellow-800" id="new-appointment-display"></p>
                            </div>

                            <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                Confirm Reschedule
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Column: Details & Actions --}}
            <div class="lg:col-span-7 space-y-6">
                
                {{-- Current Appointment Info --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Current Appointment</h2>
                    
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
                            <label class="block text-sm font-medium text-gray-500 mb-1">Scheduled Date & Time</label>
                            <div class="flex items-center text-lg font-medium text-gray-900">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $consultation->formatted_appointment }}
                            </div>
                        </div>
                    </div>
                </div>

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
                    </div>

                    @if($consultation->appointment_notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Customer Notes</label>
                            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700">
                                {{ $consultation->appointment_notes }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Reference Images --}}
                @if($consultation->images->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Reference Images ({{ $consultation->images->count() }})</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($consultation->images as $image)
                                <div class="w-20 h-20 rounded-md border border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center">
                                    <img src="{{ $image->image_url }}" alt="Reference Image {{ $loop->iteration }}" class="object-cover w-full h-full" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Quick Actions --}}
                <div class="grid md:grid-cols-2 gap-6">
                    
                    {{-- Update Status --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Update Status</h2>
                        <div class="flex gap-3">
                            <form action="{{ route('admin.consultation.updateStatus', $consultation) }}" method="POST" onsubmit="return confirm('Mark this consultation as completed?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="COMPLETED">
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    Complete Consultation
                                </button>
                            </form>
                            <form action="{{ route('admin.consultation.updateStatus', $consultation) }}" method="POST" onsubmit="return confirm('Cancel this consultation?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="CANCELLED">
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    Cancel Consultation
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
const consultationType = '{{ $consultation->consultation_type }}';
const duration = consultationType === 'design' ? 60 : 30;
const consultationId = {{ $consultation->id }};

// ✅ Parse appointment date & time dari backend
const appointmentDateStr = '{{ $consultation->appointment_date->format("Y-m-d") }}';
const appointmentTimeStr = '{{ \Carbon\Carbon::parse($consultation->appointment_time)->format("H:i") }}';
const currentAppointmentDate = new Date(appointmentDateStr);

let currentDate = new Date(appointmentDateStr); // ✅ Auto navigate ke bulan appointment
let selectedDate = null;
let selectedTime = null;
let bookedSlots = [];

const businessHours = {
    weekday: { start: 9, end: 17 },
    weekend: { start: 9, end: 12 }
};

function getTodayWIB() {
    const now = new Date();
    const wibOffset = 7 * 60;
    const localOffset = now.getTimezoneOffset();
    const wibTime = new Date(now.getTime() + (wibOffset + localOffset) * 60 * 1000);
    wibTime.setHours(0, 0, 0, 0);
    return wibTime;
}

function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();
    
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
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
        const isPastOrToday = dateObj <= today;
        
        // ✅ Check if this is the current appointment date
        const isCurrentAppointment = dateObj.getDate() === currentAppointmentDate.getDate() &&
            dateObj.getMonth() === currentAppointmentDate.getMonth() &&
            dateObj.getFullYear() === currentAppointmentDate.getFullYear();
        
        const isSelected = selectedDate && 
            dateObj.getDate() === selectedDate.getDate() &&
            dateObj.getMonth() === selectedDate.getMonth() &&
            dateObj.getFullYear() === selectedDate.getFullYear();
        
        const dayCell = document.createElement('button');
        dayCell.type = 'button';
        dayCell.textContent = day;
        dayCell.className = `aspect-square flex items-center justify-center rounded-lg text-sm transition-colors ${
            isPastOrToday 
                ? 'text-gray-300 cursor-not-allowed' 
                : isCurrentAppointment
                    ? 'bg-blue-600 text-white font-semibold ring-2 ring-blue-300'
                    : isSelected
                        ? 'bg-gray-900 text-white font-semibold'
                        : 'hover:bg-gray-100 text-gray-900'
        }`;
        
        if (!isPastOrToday) {
            dayCell.onclick = () => selectDate(dateObj);
        } else {
            dayCell.disabled = true;
        }
        
        calendarDates.appendChild(dayCell);
    }
}

async function selectDate(date) {
    selectedDate = date;
    selectedTime = null;
    renderCalendar(currentDate);
    await fetchAvailability(date);
    renderTimeSlots(date);
    
    document.getElementById('reschedule-form').classList.add('hidden');
}

async function fetchAvailability(date) {
    const loadingDiv = document.getElementById('loading-slots');
    const timeSlotsDiv = document.getElementById('time-slots');
    const timeSlotsContainer = document.getElementById('time-slots-container');
    const noDateMessage = document.getElementById('no-date-message');
    
    timeSlotsContainer.classList.remove('hidden');
    noDateMessage.classList.add('hidden');
    loadingDiv.classList.remove('hidden');
    timeSlotsDiv.classList.add('hidden');
    
    try {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const dateStr = `${year}-${month}-${day}`;
        
        const response = await fetch(`{{ route('consultation.availability') }}?date=${dateStr}&type=${consultationType}`);
        const data = await response.json();
        
        bookedSlots = data.booked_slots || [];
        
        loadingDiv.classList.add('hidden');
        timeSlotsDiv.classList.remove('hidden');
        
    } catch (error) {
        console.error('Error fetching availability:', error);
        bookedSlots = [];
        loadingDiv.classList.add('hidden');
        timeSlotsDiv.classList.remove('hidden');
    }
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
    
    // --- Cek apakah hari ini atau sebelumnya (WIB) ---
    const now = new Date();
    const wibOffset = 7 * 60;
    const localOffset = now.getTimezoneOffset();
    const nowWIB = new Date(now.getTime() + (wibOffset + localOffset) * 60 * 1000);

    const selected = new Date(date);
    selected.setHours(0,0,0,0);
    const today = new Date(nowWIB);
    today.setHours(0,0,0,0);

    let isTodayOrPast = selected.getTime() <= today.getTime();

    // --- Generate slots ---
    const slots = generateTimeSlots(hours.start, hours.end, duration);

    // --- Filter slot yang available (tidak booked & belum lewat jika hari ini) ---
    let availableSlots = slots.filter(slot => {
        if (bookedSlots.includes(slot.time24)) return false;
        if (selected.getTime() === today.getTime()) {
            // Hari ini, filter jam yang sudah lewat
            const slotDate = new Date(date);
            slotDate.setHours(Number(slot.time24.split(':')[0]), Number(slot.time24.split(':')[1]), 0, 0);
            if (slotDate.getTime() <= nowWIB.getTime()) return false;
        }
        return true;
    });

    // --- Jika tanggal sudah lewat atau tidak ada slot available ---
    if (isTodayOrPast || availableSlots.length === 0) {
        timeSlots.innerHTML = `
            <div class="flex flex-col items-center justify-center py-8">
                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <button type="button" onclick="findNextAvailable()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Find Next Available Schedule
                </button>
            </div>
        `;
        return;
    }
    
    // ✅ Check if selected date is current appointment date
    const isCurrentAppointmentDate = date.getDate() === currentAppointmentDate.getDate() &&
        date.getMonth() === currentAppointmentDate.getMonth() &&
        date.getFullYear() === currentAppointmentDate.getFullYear();
    
    const morning = slots.filter(s => s.hour < 12);
    const afternoon = slots.filter(s => s.hour >= 12 && s.hour < 17);
    const evening = slots.filter(s => s.hour >= 17);
    
    if (morning.length > 0) {
        timeSlots.appendChild(createTimeSection('Morning', morning, isCurrentAppointmentDate));
    }
    if (afternoon.length > 0) {
        timeSlots.appendChild(createTimeSection('Afternoon', afternoon, isCurrentAppointmentDate));
    }
    if (evening.length > 0) {
        timeSlots.appendChild(createTimeSection('Evening', evening, isCurrentAppointmentDate));
    }
}

async function findNextAvailable() {
    // Mulai dari besok
    let searchDate = new Date();
    searchDate.setDate(searchDate.getDate() + 1);

    // Cek 60 hari ke depan
    for (let i = 0; i < 60; i++) {
        const year = searchDate.getFullYear();
        const month = String(searchDate.getMonth() + 1).padStart(2, '0');
        const day = String(searchDate.getDate()).padStart(2, '0');
        const dateStr = `${year}-${month}-${day}`;

        // Fetch slot
        const response = await fetch(`{{ route('consultation.availability') }}?date=${dateStr}&type=${consultationType}`);
        const data = await response.json();
        const slots = generateTimeSlots(
            (searchDate.getDay() === 0 || searchDate.getDay() === 6) ? businessHours.weekend.start : businessHours.weekday.start,
            (searchDate.getDay() === 0 || searchDate.getDay() === 6) ? businessHours.weekend.end : businessHours.weekday.end,
            duration
        );
        const available = slots.filter(slot => !data.booked_slots.includes(slot.time24));
        if (available.length > 0) {
            // Otomatis redirect ke tanggal & jam available pertama
            currentDate = new Date(searchDate);
            renderCalendar(currentDate);
            await selectDate(currentDate);
            // Scroll ke calendar
            document.getElementById('calendar-dates').scrollIntoView({ behavior: 'smooth' });
            return;
        }
        searchDate.setDate(searchDate.getDate() + 1);
    }
    alert('No available schedule found in the next 60 days.');
}

function generateTimeSlots(startHour, endHour, duration) {
    const slots = [];
    let currentMinutes = startHour * 60;
    const endMinutes = endHour * 60;
    
    while (currentMinutes + duration <= endMinutes) {
        const hour = Math.floor(currentMinutes / 60);
        const minute = currentMinutes % 60;
        const time24 = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
        
        const period = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
        const time12 = `${hour12}:${minute.toString().padStart(2, '0')} ${period}`;
        
        slots.push({
            hour,
            minute,
            time24,
            time12
        });
        
        currentMinutes += duration;
    }
    
    return slots;
}

function createTimeSection(title, slots, isCurrentAppointmentDate) {
    const section = document.createElement('div');
    section.innerHTML = `
        <h4 class="text-sm font-medium text-gray-700 mb-3">${title}</h4>
        <div class="grid grid-cols-2 gap-2 mb-4">
            ${slots.map(slot => {
                const isBooked = bookedSlots.includes(slot.time24);
                // ✅ Check if this is the current consultation time
                const isCurrentSlot = isCurrentAppointmentDate && slot.time24 === appointmentTimeStr;
                
                return `
                    <button type="button" 
                        ${isCurrentSlot ? 'disabled' : `onclick="selectTime('${slot.time24}', '${slot.time12}')"` }
                        ${isBooked && !isCurrentSlot ? 'disabled' : ''}
                        class="time-slot-btn py-2 px-4 border rounded-lg text-sm transition-colors relative ${
                            isCurrentSlot
                                ? 'bg-blue-600 text-white border-blue-600 ring-2 ring-blue-300 font-medium'
                                : isBooked 
                                    ? 'bg-gray-100 text-gray-400 border-gray-200' 
                                    : selectedTime === slot.time24 
                                        ? 'bg-gray-900 text-white border-gray-900' 
                                        : 'border-gray-300 text-gray-900 hover:border-gray-900 hover:bg-gray-50'
                        }">
                        ${slot.time12}
                        ${isCurrentSlot ? '<span class="block text-xs mt-0.5 font-normal">Current Schedule</span>' : ''}
                        ${isBooked && !isCurrentSlot ? '<span class="block text-xs mt-1">(Booked)</span>' : ''}
                    </button>
                `;
            }).join('')}
        </div>
    `;
    return section;
}

function selectTime(time24, time12) {
    if (bookedSlots.includes(time24) && time24 !== appointmentTimeStr) {
        alert('This time slot is already booked. Please select another time.');
        return;
    }
    
    selectedTime = time24;
    
    // Update all time slot buttons
    document.querySelectorAll('.time-slot-btn').forEach(btn => {
        // Don't change the current appointment slot style
        if (!btn.textContent.includes('Current Schedule')) {
            btn.classList.remove('bg-gray-900', 'text-white', 'border-gray-900');
            btn.classList.add('border-gray-300', 'text-gray-900', 'hover:border-gray-900', 'hover:bg-gray-50');
        }
    });
    
    if (!event.target.textContent.includes('Current Schedule')) {
        event.target.classList.remove('border-gray-300', 'text-gray-900', 'hover:border-gray-900', 'hover:bg-gray-50');
        event.target.classList.add('bg-gray-900', 'text-white', 'border-gray-900');
    }
    
    // Show reschedule form
    const year = selectedDate.getFullYear();
    const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
    const day = String(selectedDate.getDate()).padStart(2, '0');
    const dateStr = `${year}-${month}-${day}`;
    
    document.getElementById('new-appointment-date').value = dateStr;
    document.getElementById('new-appointment-time').value = time24;
    
    const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
    const formattedDate = selectedDate.toLocaleDateString('en-US', options);
    document.getElementById('new-appointment-display').textContent = `${formattedDate} at ${time12}`;
    
    document.getElementById('reschedule-form').classList.remove('hidden');
}

document.getElementById('prev-month').onclick = () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
};

document.getElementById('next-month').onclick = () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
};

// ✅ Initialize: Render calendar dan auto-select appointment date
renderCalendar(currentDate);
selectDate(currentAppointmentDate);
</script>
@endsection