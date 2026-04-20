@extends('layout.checkout')

@section('content')

<div class="min-h-screen bg-stone-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        {{-- Header --}}
        <div class="mb-10 text-center md:text-left md:flex md:justify-between md:items-end border-b border-stone-200 pb-6">
            <div>
                <a href="{{ route('admin.consultation.index') }}" class="group inline-flex items-center text-xs font-bold tracking-[0.15em] text-stone-400 uppercase hover:text-stone-900 transition-colors mb-4">
                    <svg class="w-3 h-3 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Consultations
                </a>
                <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight">Calendar Settings</h1>
                <p class="text-sm text-stone-500 mt-2">Click on dates/times to disable availability</p>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
            
            {{-- Left: Calendar --}}
            <div class="lg:col-span-7">
                <div class="bg-white rounded-sm shadow-[0_2px_20px_rgba(0,0,0,0.03)] border border-stone-100 p-6 md:p-8">
                    
                    {{-- Calendar Header --}}
                    <div class="flex items-center justify-between mb-8">
                        <button type="button" id="prev-month" class="p-2 text-stone-400 hover:text-stone-900 hover:bg-stone-50 rounded-full transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <h3 class="text-lg font-serif text-stone-900 tracking-wide" id="current-month-year">January 2026</h3>
                        <button type="button" id="next-month" class="p-2 text-stone-400 hover:text-stone-900 hover:bg-stone-50 rounded-full transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    {{-- Days Header --}}
                    <div class="grid grid-cols-7 gap-2 mb-4">
                        @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $day)
                            <div class="text-center text-[10px] font-bold text-stone-400 uppercase tracking-widest py-2">{{ $day }}</div>
                        @endforeach
                    </div>

                    {{-- Dates Grid --}}
                    <div id="calendar-dates" class="grid grid-cols-7 gap-2"></div>

                    {{-- Legend di bawah calendar --}}
                    <div class="mt-6 pt-4 border-t border-stone-100 flex justify-between items-center">
                        <p class="text-[10px] text-stone-400 uppercase tracking-widest">Timezone: WIB (GMT+7)</p>
                        <div class="flex gap-3 text-xs">
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 bg-[#E3CFCF] border border-[#d6a88b] rounded-sm"></span>
                                <span class="text-stone-500">Disabled</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 bg-amber-100 border border-amber-300 rounded-sm"></span>
                                <span class="text-stone-500">Booked</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Time Slots --}}
            <div class="lg:col-span-5">
                
                {{-- Selected Date Info --}}
                <div id="selected-date-info" class="bg-stone-900 text-white p-6 md:p-8 rounded-sm shadow-xl mb-6 hidden">
                    <h3 class="text-xs font-bold text-stone-400 uppercase tracking-[0.2em] mb-3">Selected Date</h3>
                    <p class="text-lg font-serif" id="selected-date-text"></p>
                </div>

                {{-- Time Slots Container --}}
                <div id="time-slots-container" class="bg-white rounded-sm shadow-sm border border-stone-200 p-6 md:p-8 hidden">
                    
                    {{-- Loading --}}
                    <div id="loading-slots" class="text-center py-10 hidden">
                        <div class="animate-spin w-6 h-6 border-2 border-stone-200 border-t-stone-900 rounded-full mx-auto mb-3"></div>
                        <p class="text-xs text-stone-400 uppercase tracking-widest">Loading Slots...</p>
                    </div>
                    
                    {{-- Slots Grid --}}
                    <div id="time-slots" class="space-y-6"></div>

                    {{-- Tombol Disable/Enable Full Day --}}
                    <button 
                        type="button"
                        id="disable-full-day-btn"
                        class="w-full py-3 mt-4 bg-rose-100 text-rose-700 cursor-pointer rounded-sm font-bold text-xs uppercase tracking-widest hover:bg-rose-200 transition-all hidden"
                        onclick="toggleFullDay()"
                    >
                        <span id="full-day-btn-text">Disable Full Day</span>
                    </button>
                </div>

                {{-- Empty State --}}
                <div id="no-date-message" class="bg-stone-50 border border-dashed border-stone-300 rounded-sm p-8 text-center">
                    <svg class="w-10 h-10 text-stone-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-stone-400 text-sm font-light italic">Select a date to manage time slots</p>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// ===================================
// STATE MANAGEMENT
// ===================================
const state = {
    currentDate: new Date(),
    selectedDate: null,
    bookedSlots: [],
    disabledSlots: [],
    unavailableDates: [],
    cache: {
        unavailableDates: new Map(), // Cache per month
        slots: new Map() // Cache per date
    }
};

const config = {
    businessHours: { start: 9, weekdayEnd: 17, weekendEnd: 12 },
    timezone: 'WIB (GMT+7)',
    slotInterval: 30
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

function isWeekend(date) {
    const day = date.getDay();
    return day === 0 || day === 6;
}

// ===================================
// API CALLS (with caching)
// ===================================
async function fetchUnavailableDates(year, month) {
    const cacheKey = formatDateKey(year, month);
    
    // Check cache first
    if (state.cache.unavailableDates.has(cacheKey)) {
        state.unavailableDates = state.cache.unavailableDates.get(cacheKey);
        return;
    }
    
    try {
        const response = await fetch(`{{ route('admin.calendar.settings.unavailableDates') }}?year=${year}&month=${month}`);
        if (!response.ok) throw new Error('Failed to fetch unavailable dates');
        
        const data = await response.json();
        state.unavailableDates = data.unavailable_dates || [];
        
        // Cache the result
        state.cache.unavailableDates.set(cacheKey, state.unavailableDates);
    } catch (error) {
        console.error('Error fetching unavailable dates:', error);
        state.unavailableDates = [];
    }
}

async function fetchSlots(date) {
    const dateStr = formatDateStr(date);
    
    // Check cache first
    if (state.cache.slots.has(dateStr)) {
        const cached = state.cache.slots.get(dateStr);
        state.bookedSlots = cached.booked;
        state.disabledSlots = cached.disabled;
        return;
    }
    
    try {
        const [bookedRes, disabledRes] = await Promise.all([
            fetch(`{{ route('admin.calendar.settings.booked') }}?date=${dateStr}`),
            fetch(`{{ route('admin.calendar.settings.disabled') }}?date=${dateStr}`)
        ]);
        
        if (!bookedRes.ok || !disabledRes.ok) throw new Error('Failed to fetch slots');
        
        const [bookedData, disabledData] = await Promise.all([
            bookedRes.json(),
            disabledRes.json()
        ]);
        
        state.bookedSlots = bookedData.booked_slots || [];
        state.disabledSlots = disabledData.disabled_slots || [];
        
        // Cache the result
        state.cache.slots.set(dateStr, {
            booked: state.bookedSlots,
            disabled: state.disabledSlots
        });
    } catch (error) {
        console.error('Error fetching slots:', error);
        state.bookedSlots = [];
        state.disabledSlots = [];
    }
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
    
    // Empty cells
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'aspect-square';
        calendarDates.appendChild(emptyCell);
    }
    
    const today = getTodayWIB();
    
    // Date cells
    for (let day = 1; day <= daysInMonth; day++) {
        const dateObj = new Date(year, month, day);
        const dateStr = formatDateStr(dateObj);
        const isPast = dateObj < today;
        const isUnavailable = state.unavailableDates.includes(dateStr);
        const isSelected = state.selectedDate && formatDateStr(state.selectedDate) === dateStr;
        
        const dayCell = document.createElement('button');
        dayCell.type = 'button';
        dayCell.textContent = day;
        
        // ✅ UPDATED: Rose → #E3CFCF
        dayCell.className = `aspect-square flex items-center justify-center rounded-sm text-sm font-medium transition-all duration-200 ${
            isPast 
                ? 'text-stone-300 cursor-not-allowed bg-transparent' 
                : isUnavailable
                    ? 'bg-[#E3CFCF] text-white font-bold shadow-md cursor-pointer hover:bg-[#d6a88b]'
                    : isSelected
                        ? 'bg-stone-900 text-white shadow-md transform scale-105 cursor-pointer'
                        : 'text-stone-700 hover:bg-stone-100 hover:text-stone-900 cursor-pointer'
        }`;
        
        if (!isPast) {
            dayCell.onclick = () => selectDate(dateObj);
        } else {
            dayCell.disabled = true;
        }
        
        calendarDates.appendChild(dayCell);
    }
}

async function selectDate(date) {
    state.selectedDate = date;
    
    // ✅ 1. Update UI immediately (instant feedback)
    renderCalendar(state.currentDate);
    updateSelectedDateInfo();
    showTimeSlotsContainer();
    
    // ✅ 2. Show loading state
    showLoadingSlots();
    
    // ✅ 3. Fetch data in background
    await fetchSlots(date);
    
    // ✅ 4. Update UI with data
    hideLoadingSlots();
    renderTimeSlots();
}

function updateSelectedDateInfo() {
    const infoDiv = document.getElementById('selected-date-info');
    const textDiv = document.getElementById('selected-date-text');
    
    if (state.selectedDate) {
        const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
        textDiv.textContent = state.selectedDate.toLocaleDateString('en-US', options);
        infoDiv.classList.remove('hidden');
    } else {
        infoDiv.classList.add('hidden');
    }
}

function showTimeSlotsContainer() {
    document.getElementById('time-slots-container').classList.remove('hidden');
    document.getElementById('no-date-message').classList.add('hidden');
}

function showLoadingSlots() {
    document.getElementById('loading-slots').classList.remove('hidden');
    document.getElementById('time-slots').classList.add('hidden');
    document.getElementById('disable-full-day-btn').classList.add('hidden');
}

function hideLoadingSlots() {
    document.getElementById('loading-slots').classList.add('hidden');
    document.getElementById('time-slots').classList.remove('hidden');
    document.getElementById('disable-full-day-btn').classList.remove('hidden');
}

function renderTimeSlots() {
    const timeSlots = document.getElementById('time-slots');
    const fullDayBtn = document.getElementById('disable-full-day-btn');
    const fullDayBtnText = document.getElementById('full-day-btn-text');
    
    timeSlots.innerHTML = '';
    
    const isFullDayDisabled = state.disabledSlots.includes('full_day');
    
    if (isFullDayDisabled) {
        timeSlots.innerHTML = `<div class="text-center py-8 text-[#8B5A3C] font-bold text-sm">All time slots disabled for this day.</div>`;
        // Ganti class tombol enable full day:
        fullDayBtn.className = 'w-full py-3 mt-4 bg-[#E3CFCF] text-white cursor-pointer rounded-sm font-bold text-xs uppercase tracking-widest hover:bg-[#d6a88b] transition-all shadow-md';
        fullDayBtnText.textContent = 'Enable Full Day';
        return;
    } else {
        fullDayBtn.className = 'w-full py-3 mt-4 bg-[#E3CFCF] text-white cursor-pointer rounded-sm font-bold text-xs uppercase tracking-widest hover:bg-[#d6a88b] transition-all shadow-md';
        fullDayBtnText.textContent = 'Disable Full Day';
    }
    
    // Generate slots based on weekend/weekday
    const isWeekendDay = isWeekend(state.selectedDate);
    const endHour = isWeekendDay ? config.businessHours.weekendEnd : config.businessHours.weekdayEnd;
    
    const slots = generateTimeSlots(config.businessHours.start, endHour, config.slotInterval);
    
    const morning = slots.filter(s => s.hour < 12);
    const afternoon = slots.filter(s => s.hour >= 12 && s.hour < 17);
    const evening = slots.filter(s => s.hour >= 17);
    
    if (morning.length > 0) timeSlots.appendChild(createTimeSection('Morning', morning));
    if (afternoon.length > 0) timeSlots.appendChild(createTimeSection('Afternoon', afternoon));
    if (evening.length > 0) timeSlots.appendChild(createTimeSection('Evening', evening));
    
    // Weekend info
    if (isWeekendDay) {
        const infoDiv = document.createElement('div');
        infoDiv.className = 'mt-4 p-3 bg-[#F6EDE7] border border-[#E3CFCF] rounded-sm';
        infoDiv.innerHTML = `
            <p class="text-xs text-[#8B5A3C] text-center font-medium">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Weekend hours: 9:00 AM - 12:00 PM only
            </p>
        `;
        timeSlots.appendChild(infoDiv);
    }
}

function generateTimeSlots(startHour, endHour, interval) {
    const slots = [];
    let currentMinutes = startHour * 60;
    const endMinutes = endHour * 60;
    
    while (currentMinutes < endMinutes) {
        const hour = Math.floor(currentMinutes / 60);
        const minute = currentMinutes % 60;
        const time24 = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
        
        const period = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
        const time12 = `${hour12}:${minute.toString().padStart(2, '0')} ${period}`;
        
        slots.push({ hour, minute, time24, time12 });
        currentMinutes += interval;
    }
    return slots;
}

function createTimeSection(title, slots) {
    const section = document.createElement('div');
    section.innerHTML = `
        <h4 class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-3 border-b border-stone-100 pb-2">${title}</h4>
        <div class="grid grid-cols-3 gap-2 mb-6">
            ${slots.map(slot => {
                const isBooked = state.bookedSlots.includes(slot.time24);
                const isDisabled = state.disabledSlots.includes(slot.time24);
                
                // ✅ UPDATED: Rose → #E3CFCF
                return `
                    <button type="button" 
                            onclick="${isBooked ? '' : `toggleSlot('${slot.time24}')`}"
                            ${isBooked ? 'disabled' : ''}
                            class="py-2.5 px-2 border rounded-sm text-xs font-medium transition-all duration-200 ${
                                isBooked 
                                    ? 'bg-amber-50 text-amber-600 border-amber-200 cursor-not-allowed' 
                                    : isDisabled
                                        ? 'bg-[#E3CFCF] text-white border-[#d6a88b] hover:bg-[#d6a88b] cursor-pointer font-medium shadow-sm'
                                        : 'border-stone-200 text-stone-600 hover:bg-stone-100 cursor-pointer'
                            }">
                        ${slot.time12}
                        ${isBooked ? '<br><span class="text-[9px]">Booked</span>' : isDisabled ? '<br><span class="text-[9px]">Disabled</span>' : ''}
                    </button>
                `;
            }).join('')}
        </div>
    `;
    return section;
}

// ===================================
// ACTION HANDLERS
// ===================================
async function toggleSlot(timeSlot) {
    if (!state.selectedDate) return;
    
    const dateStr = formatDateStr(state.selectedDate);
    
    try {
        const response = await fetch('{{ route("admin.calendar.settings.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                date: dateStr,
                time_slot: timeSlot
            })
        });
        
        if (!response.ok) throw new Error('Failed to toggle slot');
        
        const data = await response.json();
        
        // Invalidate cache for this date
        state.cache.slots.delete(dateStr);
        
        // Re-fetch and re-render
        await fetchSlots(state.selectedDate);
        renderTimeSlots();
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
        
        Toast.fire({
            icon: data.status === 'disabled' ? 'success' : 'info',
            title: data.message
        });
        
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'Failed to update slot', 'error');
    }
}

async function toggleFullDay() {
    if (!state.selectedDate) return;

    const dateStr = formatDateStr(state.selectedDate);
    const isCurrentlyDisabled = state.disabledSlots.includes('full_day');

    const confirm = await Swal.fire({
        title: isCurrentlyDisabled ? 'Enable Full Day?' : 'Disable Full Day?',
        text: `All time slots on ${state.selectedDate.toLocaleDateString('en-US')} will be ${isCurrentlyDisabled ? 'enabled' : 'disabled'}.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `Yes, ${isCurrentlyDisabled ? 'Enable' : 'Disable'}`,
        cancelButtonText: 'Cancel'
    });
    
    if (!confirm.isConfirmed) return;

    try {
        const response = await fetch('{{ route("admin.calendar.settings.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                date: dateStr,
                time_slot: null
            })
        });

        if (!response.ok) throw new Error('Failed to toggle full day');

        const data = await response.json();

        // Invalidate caches
        state.cache.slots.delete(dateStr);
        const cacheKey = formatDateKey(state.currentDate.getFullYear(), state.currentDate.getMonth() + 1);
        state.cache.unavailableDates.delete(cacheKey);

        // Re-fetch and re-render
        await Promise.all([
            fetchUnavailableDates(state.currentDate.getFullYear(), state.currentDate.getMonth() + 1),
            fetchSlots(state.selectedDate)
        ]);
        
        renderCalendar(state.currentDate);
        renderTimeSlots();

        Swal.fire({
            icon: 'success',
            title: isCurrentlyDisabled ? 'Full day enabled!' : 'Full day disabled!',
            timer: 1500,
            showConfirmButton: false
        });

    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'Failed to update full day status', 'error');
    }
}

async function changeMonth(direction) {
    state.currentDate.setMonth(state.currentDate.getMonth() + direction);
    
    // Fetch unavailable dates for new month
    await fetchUnavailableDates(state.currentDate.getFullYear(), state.currentDate.getMonth() + 1);
    
    // Render calendar
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
})();
</script>
@endsection