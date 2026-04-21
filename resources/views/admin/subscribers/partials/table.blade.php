@if($subscribers->count() > 0)
    <div class="overflow-x-auto" id="subscribersTable">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-stone-100">
                    <th class="px-8 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Email Address</th>
                    <th class="px-8 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] text-right">Deactivate</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-50">
                @foreach($subscribers as $subscriber)
                    <tr class="group hover:bg-stone-50/50 transition-colors duration-200">
                        <td class="px-8 py-5">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-[#454545] flex items-center justify-center mr-4 transition-colors duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-[#E3CFCF] group-hover:text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                    </svg>
                                </div>
                                <span class="text-sm text-stone-800 group-hover:text-stone-900 font-medium">{{ $subscriber->email }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end items-center opacity-60 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" class="inline deactivate-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="deactivate-btn p-2 text-stone-400 hover:text-red-600 transition-colors"
                                            title="Deactivate Subscriber"
                                            data-email="{{ $subscriber->email }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-8 py-6 border-t border-stone-100" id="subscribersPagination">
        {{ $subscribers->links('vendor.pagination.custom') }}
    </div>
@else
    <div class="text-center py-20 bg-stone-50/30">
        <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4 text-stone-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
        </div>
        <h3 class="text-lg font-serif text-stone-900 mb-1">No subscribers found</h3>
        <p class="text-sm text-stone-500 font-light">Try adjusting your search keyword.</p>
    </div>
@endif
