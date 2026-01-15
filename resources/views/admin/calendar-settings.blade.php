@extends('layout.main')

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

                    <div class="mt-6 pt-4 border-t border-stone-100 flex justify-between items-center">
                        <p class="text-[10px] text-stone-400 uppercase tracking-widest">Timezone: WIB (GMT+7)</p>
                        <div class="flex gap-3 text-xs">
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 bg-rose-100 border border-rose-300 rounded-sm"></span>
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
                    <div id="loading-slots" class="text-center py-10">
                        <div class="animate-spin w-6 h-6 border-2 border-stone-200 border-t-stone-900 rounded-full mx-auto mb-3"></div>
                        <p class="text-xs text-stone-400 uppercase tracking-widest">Loading Slots...</p>
                    </div>
                    
                    {{-- Slots Grid --}}
                    <div id="time-slots" class="space-y-6 hidden"></div>

                    {{-- ✅ Tombol Disable/Enable Full Day --}}
                    <button 
                        type="button"
                        id="disable-full-day-btn"
                        class="w-full py-3 mt-4 bg-rose-100 text-rose-700 border border-rose-300 rounded-sm font-bold text-xs uppercase tracking-widest hover:bg-rose-200 transition-all hidden"
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
let currentDate = new Date();
let selectedDate = null;
let bookedSlots = [];
let disabledSlots = [];
let unavailableDates = []; // ✅ Tambahkan untuk track unavailable dates

const businessHours = { start: 9, end: 17 };

function getTodayWIB() {
    const now = new Date();
    const wibOffset = 7 * 60;
    const localOffset = now.getTimezoneOffset();
    const wibTime = new Date(now.getTime() + (wibOffset + localOffset) * 60 * 1000);
    wibTime.setHours(0, 0, 0, 0);
    return wibTime;
}

// ✅ Fetch unavailable dates untuk bulan tertentu
async function fetchUnavailableDates(year, month) {
    try {
        const response = await fetch(`{{ route('admin.calendar.settings.unavailableDates') }}?year=${year}&month=${month}`);
        const data = await response.json();
        unavailableDates = data.unavailable_dates || [];
    } catch (error) {
        console.error('Error fetching unavailable dates:', error);
        unavailableDates = [];
    }
}

// ✅ Update renderCalendar dengan highlight unavailable dates
async function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();
    
    // Fetch unavailable dates untuk bulan ini
    await fetchUnavailableDates(year, month + 1);
    
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
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const isPast = dateObj < today;
        const isUnavailable = unavailableDates.includes(dateStr); // ✅ Check unavailable
        const isSelected = selectedDate && 
            dateObj.getDate() === selectedDate.getDate() &&
            dateObj.getMonth() === selectedDate.getMonth() &&
            dateObj.getFullYear() === selectedDate.getFullYear();
        
        const dayCell = document.createElement('button');
        dayCell.type = 'button';
        dayCell.textContent = day;
        
        // ✅ Update styling untuk unavailable dates
        dayCell.className = `aspect-square flex items-center justify-center rounded-sm text-sm font-medium transition-all duration-200 ${
            isPast 
                ? 'text-stone-300 cursor-not-allowed bg-transparent' 
                : isUnavailable
                    ? 'bg-rose-100 text-rose-700 border-2 border-rose-300 cursor-pointer hover:bg-rose-200' // ✅ Highlight unavailable
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
    selectedDate = date;
    await renderCalendar(currentDate);
    
    const infoDiv = document.getElementById('selected-date-info');
    const textDiv = document.getElementById('selected-date-text');
    const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
    textDiv.textContent = selectedDate.toLocaleDateString('en-US', options);
    infoDiv.classList.remove('hidden');
    
    await fetchSlots(date);
    renderTimeSlots();
}

async function fetchSlots(date) {
    const loadingDiv = document.getElementById('loading-slots');
    const timeSlotsDiv = document.getElementById('time-slots');
    const timeSlotsContainer = document.getElementById('time-slots-container');
    const noDateMessage = document.getElementById('no-date-message');
    
    timeSlotsContainer.classList.remove('hidden');
    noDateMessage.classList.add('hidden');
    loadingDiv.classList.remove('hidden');
    timeSlotsDiv.classList.add('hidden');
    
    try {
        const dateStr = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        
        const [bookedRes, disabledRes] = await Promise.all([
            fetch(`{{ route('admin.calendar.settings.booked') }}?date=${dateStr}`),
            fetch(`{{ route('admin.calendar.settings.disabled') }}?date=${dateStr}`)
        ]);
        
        const bookedData = await bookedRes.json();
        const disabledData = await disabledRes.json();
        
        bookedSlots = bookedData.booked_slots || [];
        disabledSlots = disabledData.disabled_slots || [];
        
        loadingDiv.classList.add('hidden');
        timeSlotsDiv.classList.remove('hidden');
        
    } catch (error) {
        console.error('Error:', error);
        bookedSlots = [];
        disabledSlots = [];
        loadingDiv.classList.add('hidden');
        timeSlotsDiv.classList.remove('hidden');
    }
}

function renderTimeSlots() {
    const timeSlots = document.getElementById('time-slots');
    const fullDayBtn = document.getElementById('disable-full-day-btn');
    const fullDayBtnText = document.getElementById('full-day-btn-text');
    
    timeSlots.innerHTML = '';
    fullDayBtn.classList.remove('hidden');
    
    // ✅ Cek apakah full day disabled
    const isFullDayDisabled = disabledSlots.includes('full_day');
    
    if (isFullDayDisabled) {
        timeSlots.innerHTML = `<div class="text-center py-8 text-rose-600 font-bold text-sm">All time slots disabled for this day.</div>`;
        fullDayBtn.classList.remove('bg-rose-100', 'text-rose-700', 'border-rose-300', 'hover:bg-rose-200');
        fullDayBtn.classList.add('bg-green-100', 'text-green-700', 'border-green-300', 'hover:bg-green-200');
        fullDayBtnText.textContent = 'Enable Full Day';
        return;
    } else {
        fullDayBtn.classList.remove('bg-green-100', 'text-green-700', 'border-green-300', 'hover:bg-green-200');
        fullDayBtn.classList.add('bg-rose-100', 'text-rose-700', 'border-rose-300', 'hover:bg-rose-200');
        fullDayBtnText.textContent = 'Disable Full Day';
    }
    
    const slots = generateTimeSlots(businessHours.start, businessHours.end, 30);
    
    const morning = slots.filter(s => s.hour < 12);
    const afternoon = slots.filter(s => s.hour >= 12 && s.hour < 17);
    const evening = slots.filter(s => s.hour >= 17);
    
    if (morning.length > 0) timeSlots.appendChild(createTimeSection('Morning', morning));
    if (afternoon.length > 0) timeSlots.appendChild(createTimeSection('Afternoon', afternoon));
    if (evening.length > 0) timeSlots.appendChild(createTimeSection('Evening', evening));
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
                const isBooked = bookedSlots.includes(slot.time24);
                const isDisabled = disabledSlots.includes(slot.time24);
                
                return `
                    <button type="button" 
                            onclick="${isBooked ? '' : `toggleSlot('${slot.time24}')`}"
                            ${isBooked ? 'disabled' : ''}
                            class="py-2.5 px-2 border rounded-sm text-xs font-medium transition-all duration-200 ${
                                isBooked 
                                    ? 'bg-amber-50 text-amber-600 border-amber-200 cursor-not-allowed' 
                                    : isDisabled
                                        ? 'bg-rose-100 text-rose-700 border-rose-300 hover:bg-rose-200 cursor-pointer'
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

async function toggleSlot(timeSlot) {
    if (!selectedDate) return;
    
    const dateStr = `${selectedDate.getFullYear()}-${String(selectedDate.getMonth() + 1).padStart(2, '0')}-${String(selectedDate.getDate()).padStart(2, '0')}`;
    
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
        
        const data = await response.json();
        
        await fetchSlots(selectedDate);
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

// ✅ Toggle Full Day (Disable/Enable)
async function toggleFullDay() {
    if (!selectedDate) return;

    const dateStr = `${selectedDate.getFullYear()}-${String(selectedDate.getMonth() + 1).padStart(2, '0')}-${String(selectedDate.getDate()).padStart(2, '0')}`;
    const isCurrentlyDisabled = disabledSlots.includes('full_day');

    const confirm = await Swal.fire({
        title: isCurrentlyDisabled ? 'Enable Full Day?' : 'Disable Full Day?',
        text: `All time slots on ${selectedDate.toLocaleDateString('en-US')} will be ${isCurrentlyDisabled ? 'enabled' : 'disabled'}.`,
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

        const data = await response.json();

        await fetchSlots(selectedDate);
        await renderCalendar(currentDate); // ✅ Re-render calendar untuk update warna
        renderTimeSlots();

        Swal.fire({
            icon: 'success',
            title: isCurrentlyDisabled ? 'Full day enabled!' : 'Full day disabled!',
            timer: 1500,
            showConfirmButton: false
        });

    } catch (error) {
        Swal.fire('Error', 'Failed to update full day status', 'error');
    }
}

document.getElementById('prev-month').onclick = async () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    await renderCalendar(currentDate);
};

document.getElementById('next-month').onclick = async () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    await renderCalendar(currentDate);
};

renderCalendar(currentDate);
</script>
@endsection