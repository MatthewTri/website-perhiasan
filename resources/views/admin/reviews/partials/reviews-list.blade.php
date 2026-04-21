@if($reviews->isEmpty())
<div class="py-24 text-center border border-dashed border-stone-200 rounded-lg">
    <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
    </svg>
    <h3 class="text-lg font-serif text-stone-900 mb-1">No reviews found</h3>
    <p class="text-stone-500 text-sm">Try adjusting your filters or search criteria.</p>
</div>
@else

{{-- MOBILE VIEW (Cards) - Visible on < md --}}
<div class="block md:hidden space-y-6">
    @foreach($reviews as $review)
    <div class="bg-white border border-stone-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] rounded-lg overflow-hidden relative">
        
        {{-- Card Header: Order Info --}}
        <div class="p-5 border-b border-stone-50">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs text-stone-400 uppercase tracking-wider mb-0.5">Order</p>
                    <p class="text-base font-mono font-bold text-stone-900">#{{ $review->order->order_number }}</p>
                </div>
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-stone-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
            </div>
            <p class="text-xs text-stone-500">{{ $review->product->name }}</p>
        </div>

        {{-- Card Body: Review Comment --}}
        <div class="p-5 bg-stone-50/50">
            <p class="text-xs text-stone-400 uppercase tracking-wider mb-2">Customer Review</p>
            @if($review->comment)
                <p class="text-stone-900 leading-relaxed text-sm">{{ $review->comment }}</p>
            @else
                <p class="text-stone-300 italic text-sm">No written comment provided</p>
            @endif

            @if($review->image)
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $review->image) }}" onclick="window.open(this.src, '_blank')" class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-90 transition border border-stone-200">
                    <p class="text-[10px] text-stone-400 mt-1 text-center">Tap to view full image</p>
                </div>
            @endif
        </div>

        {{-- Card Footer: Customer & Status Toggle --}}
        <div class="bg-white px-5 py-4 flex items-center justify-between border-t border-stone-100">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-stone-200 flex items-center justify-center text-xs font-bold text-stone-600">
                    {{ substr($review->anonymous ? 'A' : $review->order->shipment->first_name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs font-bold text-stone-700">
                        {{ $review->anonymous ? 'Anonymous' : $review->order->shipment->first_name }}
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-bold px-4 py-2 rounded-full border-2 transition-all {{ $review->is_visible ? 'border-[#E3CFCF] text-stone-700 bg-[#E3CFCF] hover:bg-[#d4c0c0]' : 'border-[#E3CFCF] text-stone-700 bg-white hover:bg-[#E3CFCF]' }}">
                    {{ $review->is_visible ? 'HIDE' : 'SHOW' }}
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<div class="block md:hidden mt-8">
    {{ $reviews->links('vendor.pagination.custom') }}
</div>

{{-- DESKTOP VIEW (Table) - Visible on md+ --}}
<div class="hidden md:block bg-white rounded-sm shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-stone-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-stone-50 border-b border-stone-100">
                <tr>
                    <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] w-[12%]">Order ID</th>
                    <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] w-[15%]">Customer & Rating</th>
                    <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] w-[58%]">Review Comment</th>
                    <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] text-center w-[15%]">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-50">
                @foreach($reviews as $review)
                <tr class="group hover:bg-stone-50/50 transition-colors">
                    
                    {{-- Order ID --}}
                    <td class="px-6 py-5 align-top">
                        <div class="space-y-2">
                            <p class="text-sm font-mono font-bold text-stone-900">#{{ $review->order->order_number }}</p>
                            <p class="text-xs text-stone-500 line-clamp-2">{{ $review->product->name }}</p>
                        </div>
                    </td>

                    {{-- Customer & Rating --}}
                    <td class="px-6 py-5 align-top">
                        <div class="flex items-start gap-3">
                            <div>
                                <p class="text-sm font-bold text-stone-800 mb-1.5">
                                    {{ $review->anonymous ? 'Anonymous' : $review->order->shipment->first_name . ' ' . $review->order->shipment->last_name }}
                                </p>
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-stone-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Review Comment --}}
                    <td class="px-6 py-5 align-top">
                        @if($review->comment)
                            <p class="text-sm text-stone-900 leading-relaxed">{{ $review->comment }}</p>
                        @else
                            <p class="text-stone-400 italic text-sm">No written comment provided by customer</p>
                        @endif
                        
                        @if($review->image)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $review->image) }}" 
                                     class="w-full max-w-md h-40 object-cover rounded-lg border border-stone-200 cursor-pointer hover:opacity-80 transition-all" 
                                     onclick="window.open(this.src, '_blank')"
                                     title="Click to view full size">
                                <p class="text-[10px] text-stone-400 mt-1.5">Click image to enlarge</p>
                            </div>
                        @endif
                    </td>

                    {{-- Status Toggle --}}
                    <td class="px-6 py-5 align-middle text-center">
                        <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" 
                                    class="text-xs font-bold px-4 py-2 rounded-full border-2 transition-all {{ $review->is_visible ? 'border-[#E3CFCF] text-stone-700 bg-[#E3CFCF] hover:bg-[#d4c0c0]' : 'border-[#E3CFCF] text-stone-700 bg-white hover:bg-[#E3CFCF]' }}">
                                {{ $review->is_visible ? 'HIDE' : 'SHOW' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-6 py-6 border-t border-stone-100 bg-stone-50/30">
        {{ $reviews->links('vendor.pagination.custom') }}
    </div>
</div>
@endif
