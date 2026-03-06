@extends('layout.main')

@section('content')
<div class="min-h-screen bg-stone-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        {{-- Header Navigation --}}
        <div class="mb-10 text-center md:text-left md:flex md:justify-between md:items-end border-b border-stone-200 pb-6">
            <div>
                <a href="{{ route('consultation.index') }}" class="group inline-flex items-center text-xs font-bold tracking-[0.15em] text-stone-400 uppercase hover:text-stone-900 transition-colors mb-4">
                    <svg class="w-3 h-3 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Return to Services
                </a>
                <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight">
                    Select Availability
                </h1>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <span class="block text-sm font-bold text-stone-900 uppercase tracking-widest">{{ $type === 'design' ? 'Bespoke Design' : 'General Inquiry' }}</span>
                <span class="text-xs text-stone-500 font-serif italic">{{ $type === 'design' ? '1 Hour Session' : '30 Minute Session' }}</span>
            </div>
        </div>

        {{-- Main Layout Grid --}}
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
            
            {{-- Left: Calendar (Column 7) --}}
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
                    <div id="calendar-dates" class="grid grid-cols-7 gap-2">
                        {{-- Generated via JS --}}
                    </div>

                    <div class="mt-6 pt-4 border-t border-stone-100 text-center">
                        <p class="text-[10px] text-stone-400 uppercase tracking-widest">
                            Timezone: WIB (Jakarta Time / GMT+7)
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right: Summary & Time Slots (Column 5) --}}
            <div class="lg:col-span-5 space-y-6">
                
                {{-- Appointment Card --}}
                <div class="bg-stone-900 text-white p-6 md:p-8 rounded-sm shadow-xl">
                    <h3 class="text-xs font-bold text-stone-400 uppercase tracking-[0.2em] mb-6">Your Selection</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center flex-shrink-0 text-stone-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-serif tracking-wide text-white">
                                    {{ $type === 'design' ? 'Bespoke Design Consultation' : 'General Inquiry' }}
                                </p>
                                <p class="text-xs text-stone-400 mt-1">
                                    {{ $type === 'design' ? '1 Hour • Virtual / In-store' : '30 Mins • Virtual / In-store' }}
                                </p>
                            </div>
                        </div>

                        {{-- Dynamic Date Display --}}
                        <div id="selected-datetime-display" class="hidden animate-fade-in-up">
                            <div class="flex items-center gap-4 pt-4 border-t border-stone-800">
                                <div class="w-8 h-8 rounded-full bg-stone-800 flex items-center justify-center flex-shrink-0 text-stone-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-serif tracking-wide text-white" id="selected-datetime-text"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Time Slots Section --}}
                <div id="time-slots-container" class="bg-white rounded-sm shadow-sm border border-stone-200 p-6 md:p-8 hidden">                    
                    {{-- Loading --}}
                    <div id="loading-slots" class="text-center py-10">
                        <div class="animate-spin w-6 h-6 border-2 border-stone-200 border-t-stone-900 rounded-full mx-auto mb-3"></div>
                        <p class="text-xs text-stone-400 uppercase tracking-widest">Checking Availability...</p>
                    </div>
                    
                    {{-- Slots Grid --}}
                    <div id="time-slots" class="space-y-6 hidden">
                        {{-- JS Generated --}}
                    </div>
                </div>

                {{-- Empty State (No Date Selected) --}}
                <div id="no-date-message" class="bg-stone-50 border border-dashed border-stone-300 rounded-sm p-8 text-center">
                    <p class="text-stone-400 text-sm font-light italic">Please select a date from the calendar to view available appointments.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// --- LOGIC JAVASCRIPT ASLI (TIDAK DIUBAH SECARA FUNGSIONAL, HANYA RENDER CLASS CSS) ---

const consultationType = '{{ $type }}';
const duration = consultationType === 'design' ? 60 : 30;
let currentDate = new Date();
let selectedDate = null;
let selectedTime = null;
let bookedSlots = [];
let unavailableDates = []; // ✅ Global state

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

// ✅ Fetch unavailable dates (dipanggil hanya saat load/ganti bulan)
async function fetchUnavailableDates(year, month) {
    try {
        const response = await fetch(`{{ route('consultation.unavailableDates') }}?year=${year}&month=${month}`);
        const data = await response.json();
        unavailableDates = data.unavailable_dates || [];
    } catch (error) {
        console.error('Error fetching unavailable dates:', error);
        unavailableDates = [];
    }
}

// ✅ Render calendar (TIDAK fetch API lagi, pakai state global)
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
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const isPastOrToday = dateObj <= today;
        const isUnavailable = unavailableDates.includes(dateStr); // ✅ Pakai state global
        const isSelected = selectedDate && 
            dateObj.getDate() === selectedDate.getDate() &&
            dateObj.getMonth() === selectedDate.getMonth() &&
            dateObj.getFullYear() === selectedDate.getFullYear();
        
        const dayCell = document.createElement('button');
        dayCell.type = 'button';
        dayCell.textContent = day;
        
        dayCell.className = `aspect-square flex items-center justify-center rounded-sm text-sm font-medium transition-all duration-200 ${
            isPastOrToday || isUnavailable
                ? 'text-stone-300 cursor-not-allowed bg-transparent line-through' 
                : isSelected
                    ? 'bg-stone-900 text-white shadow-md transform scale-105 cursor-pointer'
                    : 'text-stone-700 hover:bg-stone-100 hover:text-stone-900 cursor-pointer'
        }`;
        
        if (!isPastOrToday && !isUnavailable) {
            dayCell.onclick = () => selectDate(dateObj);
        } else {
            dayCell.disabled = true;
        }
        
        calendarDates.appendChild(dayCell);
    }
}

// ✅ Select date: Update UI dulu, baru fetch
async function selectDate(date) {
    selectedDate = date;
    selectedTime = null;
    
    // ✅ 1. Update calendar style dulu (instant feedback)
    renderCalendar(currentDate);
    
    // ✅ 2. Update selected date display dulu
    const displayDiv = document.getElementById('selected-datetime-display');
    const textDiv = document.getElementById('selected-datetime-text');
    const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
    const dateStr = selectedDate.toLocaleDateString('en-US', options);
    textDiv.textContent = dateStr;
    displayDiv.classList.remove('hidden');
    
    // ✅ 3. Baru fetch availability (async, non-blocking)
    await fetchAvailability(date);
}

async function fetchAvailability(date) {
    const loadingDiv = document.getElementById('loading-slots');
    const timeSlotsDiv = document.getElementById('time-slots');
    const timeSlotsContainer = document.getElementById('time-slots-container');
    const noDateMessage = document.getElementById('no-date-message');
    
    // ✅ Show container & loading
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
        
        loadingDiv.classList.add('hidden');
        timeSlotsDiv.classList.remove('hidden');
        
        if (data.is_unavailable) {
            timeSlotsDiv.innerHTML = `<div class="text-center py-8 text-rose-600 font-bold text-sm">Tanggal ini tidak tersedia untuk booking.</div>`;
            return;
        }

        bookedSlots = data.booked_slots || [];
        renderTimeSlots(date);
        
    } catch (error) {
        console.error('Error fetching availability:', error);
        bookedSlots = [];
        loadingDiv.classList.add('hidden');
        timeSlotsDiv.classList.remove('hidden');
        timeSlotsDiv.innerHTML = `<div class="text-center py-8 text-stone-400 text-sm">Failed to load time slots. Please try again.</div>`;
    }
}

function renderTimeSlots(date) {
    const dayOfWeek = date.getDay();
    const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
    const hours = isWeekend ? businessHours.weekend : businessHours.weekday;
    
    const timeSlots = document.getElementById('time-slots');
    timeSlots.innerHTML = '';
    
    const slots = generateTimeSlots(hours.start, hours.end, duration);
    
    if (slots.length === 0) {
        timeSlots.innerHTML = '<p class="text-xs text-stone-400 uppercase tracking-widest text-center py-4">No slots available</p>';
        return;
    }
    
    const morning = slots.filter(s => s.hour < 12);
    const afternoon = slots.filter(s => s.hour >= 12 && s.hour < 17);
    const evening = slots.filter(s => s.hour >= 17);
    
    if (morning.length > 0) timeSlots.appendChild(createTimeSection('Morning', morning));
    if (afternoon.length > 0) timeSlots.appendChild(createTimeSection('Afternoon', afternoon));
    if (evening.length > 0) timeSlots.appendChild(createTimeSection('Evening', evening));
}

function generateTimeSlots(startHour, endHour, duration) {
    const slots = [];
    
    if (duration === 60) {
        // ✅ DESIGN: Generate hourly slots (09:00, 10:00, 11:00, ...)
        for (let hour = startHour; hour < endHour; hour++) {
            const time24 = `${hour.toString().padStart(2, '0')}:00`;
            const period = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
            const time12 = `${hour12}:00 ${period}`;
            
            slots.push({ hour, minute: 0, time24, time12 });
        }
    } else {
        // ✅ SHORT: Generate 30-min slots (existing logic)
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

function createTimeSection(title, slots) {
    const section = document.createElement('div');
    section.innerHTML = `
        <h4 class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-3 border-b border-stone-100 pb-2">${title}</h4>
        <div class="grid grid-cols-3 gap-2 mb-6">
            ${slots.map(slot => {
                const isBooked = bookedSlots.includes(slot.time24);
                return `
                    <button type="button" 
                            onclick="selectTime('${slot.time24}', '${slot.time12}')"
                            ${isBooked ? 'disabled' : ''}
                            class="time-slot-btn cursor-pointer py-2.5 px-2 border rounded-sm text-xs font-medium transition-all duration-200 ${
                                isBooked 
                                    ? 'bg-stone-50 text-stone-300 border-stone-100 cursor-not-allowed decoration-stone-300 line-through' 
                                    : selectedTime === slot.time24 
                                        ? 'bg-stone-900 text-white border-stone-900 shadow-md' 
                                        : 'border-stone-200 text-stone-600 hover:border-stone-400 hover:bg-stone-50'
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
    selectedTime = time24;
    if(selectedDate) renderTimeSlots(selectedDate);
    setTimeout(() => proceedToDetails(), 300);
}

function proceedToDetails() {
    if (!selectedDate || !selectedTime) return;
    
    const year = selectedDate.getFullYear();
    const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
    const day = String(selectedDate.getDate()).padStart(2, '0');
    const dateStr = `${year}-${month}-${day}`;
    
    const params = new URLSearchParams({
        type: consultationType,
        date: dateStr,
        time: selectedTime
    });
    
    window.location.href = `{{ route('consultation.details') }}?${params.toString()}`;
}

// ✅ Update prev/next month: Fetch unavailable dates, baru render
document.getElementById('prev-month').onclick = async () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    await fetchUnavailableDates(currentDate.getFullYear(), currentDate.getMonth() + 1);
    renderCalendar(currentDate);
};

document.getElementById('next-month').onclick = async () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    await fetchUnavailableDates(currentDate.getFullYear(), currentDate.getMonth() + 1);
    renderCalendar(currentDate);
};

// ✅ Init: Fetch unavailable dates sekali, baru render
(async function() {
    await fetchUnavailableDates(currentDate.getFullYear(), currentDate.getMonth() + 1);
    renderCalendar(currentDate);
})();
</script>

<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection