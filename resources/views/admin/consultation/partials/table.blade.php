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
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.consultation.show', $consultation) }}"
                                   class="inline-block px-4 py-2 border border-stone-200 text-[10px] font-bold text-stone-500 uppercase tracking-widest hover:text-stone-900 hover:border-stone-400 hover:bg-white rounded-sm transition-all bg-stone-50">
                                    Manage
                                </a>
                                <form action="{{ route('admin.consultation.destroy', $consultation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-block px-4 py-2 border border-rose-200 text-[10px] font-bold text-rose-600 uppercase tracking-widest hover:text-white hover:border-rose-600 hover:bg-rose-600 rounded-sm transition-all bg-white"
                                            onclick="return confirm('Delete this consultation and all related data? This action cannot be undone.')">
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
        <button type="button" onclick="document.getElementById('clearFiltersBtn').click()" class="group relative inline-flex items-center justify-center px-6 py-2.5 overflow-hidden bg-stone-900 rounded-sm transition duration-300 ease-out hover:bg-stone-800 hover:shadow-lg cursor-pointer mt-4">
            <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-stone-700 rounded-full group-hover:w-56 group-hover:h-56"></span>
            <span class="relative text-xs font-bold uppercase tracking-[0.2em] text-white">Clear</span>
        </button>
    </div>
@endif
