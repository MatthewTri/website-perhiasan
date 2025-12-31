@extends('layout.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="bg-white min-h-screen py-8 md:py-12">
    <div class="container mx-auto px-4 max-w-7xl">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
            <div>
                <h6 class="text-xs font-bold tracking-[0.2em] text-stone-500 uppercase mb-2">Community & Feedback</h6>
                <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight">Review Moderation</h1>
            </div>
            <div class="hidden md:flex gap-6 text-sm text-stone-500">
                <div class="flex flex-col items-end">
                    <span class="font-bold text-stone-900 text-lg">{{ $reviews->total() }}</span>
                    <span>Total Reviews</span>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-2 border-emerald-500 text-emerald-800 text-sm tracking-wide shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-stone-50 rounded-sm border border-stone-100 p-6 mb-8">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                <div class="md:col-span-5">
                    <label for="search" class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Search</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Product, customer or order ID..."
                               class="w-full bg-white border-b-2 border-stone-200 px-0 py-2 text-stone-800 placeholder-stone-300 focus:outline-none focus:border-stone-800 transition-colors font-serif">
                        <svg class="w-4 h-4 text-stone-400 absolute right-0 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label for="visibility" class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Status</label>
                    <select id="visibility" name="visibility" class="w-full bg-white border-b-2 border-stone-200 px-0 py-2 text-stone-800 focus:outline-none focus:border-stone-800 cursor-pointer font-serif">
                        <option value="">All Status</option>
                        <option value="visible" {{ request('visibility') === 'visible' ? 'selected' : '' }}>Visible</option>
                        <option value="hidden" {{ request('visibility') === 'hidden' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="rating" class="block text-xs font-bold text-stone-400 uppercase tracking-widest mb-2">Rating</label>
                    <select id="rating" name="rating" class="w-full bg-white border-b-2 border-stone-200 px-0 py-2 text-stone-800 focus:outline-none focus:border-stone-800 cursor-pointer font-serif">
                        <option value="">All Stars</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="w-full bg-stone-900 hover:bg-stone-800 text-white font-medium py-3 px-4 rounded-sm transition duration-300 text-xs uppercase tracking-widest shadow-md hover:shadow-lg">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        @if($reviews->isEmpty())
        <div class="py-24 text-center border border-dashed border-stone-200 rounded-lg">
            <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            <h3 class="text-lg font-serif text-stone-900 mb-1">No reviews found</h3>
            <p class="text-stone-500 text-sm">Try adjusting your filters or search criteria.</p>
        </div>
        @else

        {{-- 
            ===========================================
            MOBILE VIEW (Cards) - Visible on < md
            =========================================== 
        --}}
        <div class="block md:hidden space-y-6">
            @foreach($reviews as $review)
            <div class="bg-white border border-stone-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] rounded-lg overflow-hidden relative">
                
                {{-- Card Header: Product Info --}}
                <div class="p-5 flex gap-4 border-b border-stone-50">
                    <div class="flex-shrink-0">
                        @if($review->product->featuredImage)
                            <img src="{{ asset('storage/' . $review->product->featuredImage->image) }}" alt="" class="w-16 h-16 object-cover rounded-sm shadow-sm">
                        @else
                            <div class="w-16 h-16 bg-stone-100 rounded-sm flex items-center justify-center text-stone-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-stone-400 uppercase tracking-wider mb-1">{{ $review->order->order_number }}</p>
                        <h3 class="text-stone-900 font-serif font-medium truncate">{{ $review->product->name }}</h3>
                        <div class="flex items-center mt-1">
                             @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-stone-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-xs text-stone-400 ml-2">{{ $review->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Card Body: Review Content --}}
                <div class="p-5">
                    @if($review->comment)
                        <div class="relative pl-3 border-l-2 border-stone-200">
                            <p class="text-stone-600 text-sm italic leading-relaxed">"{{ Str::limit($review->comment, 150) }}"</p>
                        </div>
                    @else
                        <span class="text-xs text-stone-300 italic">No written comment.</span>
                    @endif

                    @if($review->image)
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $review->image) }}" onclick="window.open(this.src, '_blank')" class="w-full h-32 object-cover rounded-sm cursor-pointer hover:opacity-90 transition">
                            <p class="text-[10px] text-stone-400 mt-1 text-center">Tap image to enlarge</p>
                        </div>
                    @endif
                </div>

                {{-- Card Footer: User & Actions --}}
                <div class="bg-stone-50 px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-stone-200 flex items-center justify-center text-[10px] font-bold text-stone-600">
                            {{ substr($review->anonymous ? 'A' : $review->order->shipment->first_name, 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-stone-700">
                            {{ $review->anonymous ? 'Anonymous' : $review->order->shipment->first_name }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- Toggle Button Mobile --}}
                        <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-full border transition-colors {{ $review->is_visible ? 'border-stone-300 text-stone-500 bg-white hover:bg-stone-100' : 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}">
                                {{ $review->is_visible ? 'Hide' : 'Publish' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- 
            ===========================================
            DESKTOP VIEW (Table) - Visible on md+
            =========================================== 
        --}}
        <div class="hidden md:block bg-white rounded-lg shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden border border-stone-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-stone-50 border-b border-stone-100">
                        <tr>
                            <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Product</th>
                            <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Customer</th>
                            <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Rating & Review</th>
                            <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Status</th>
                            <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @foreach($reviews as $review)
                        <tr class="group hover:bg-stone-50/50 transition-colors">
                            {{-- Product --}}
                            <td class="px-6 py-4 align-top w-1/4">
                                <div class="flex gap-4">
                                    @if($review->product->featuredImage)
                                        <img src="{{ asset('storage/' . $review->product->featuredImage->image) }}" class="w-12 h-12 object-cover rounded-sm shadow-sm">
                                    @else
                                        <div class="w-12 h-12 bg-stone-100 rounded-sm"></div>
                                    @endif
                                    <div>
                                        <a href="#" class="block text-sm font-serif font-medium text-stone-900 hover:underline line-clamp-2">{{ $review->product->name }}</a>
                                        <span class="text-xs text-stone-400 font-mono mt-1 block">#{{ $review->order->order_number }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Customer --}}
                            <td class="px-6 py-4 align-top">
                                <p class="text-sm font-bold text-stone-800">
                                    {{ $review->anonymous ? 'Anonymous' : $review->order->shipment->first_name . ' ' . $review->order->shipment->last_name }}
                                </p>
                                <p class="text-xs text-stone-400 mt-0.5">{{ $review->created_at->format('M d, Y') }}</p>
                                @if($review->order_id)
                                    <span class="inline-block mt-1 text-[10px] uppercase tracking-wider text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded-sm">Verified</span>
                                @endif
                            </td>

                            {{-- Rating & Review --}}
                            <td class="px-6 py-4 align-top w-1/3">
                                <div class="flex mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-stone-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                @if($review->comment)
                                    <p class="text-sm text-stone-600 italic line-clamp-3 leading-relaxed">"{{ $review->comment }}"</p>
                                @else
                                    <span class="text-xs text-stone-300 italic">No comment provided.</span>
                                @endif
                                
                                @if($review->image)
                                    <div class="mt-2 group/img relative inline-block">
                                        <img src="{{ asset('storage/' . $review->image) }}" class="w-10 h-10 object-cover rounded-sm border border-stone-200 cursor-pointer" onclick="window.open(this.src, '_blank')">
                                        <div class="absolute inset-0 bg-black/20 hidden group-hover/img:flex items-center justify-center text-white text-[8px] pointer-events-none rounded-sm">ZOOM</div>
                                    </div>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 align-middle">
                                @if($review->is_visible)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Visible
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-stone-100 text-stone-500 border border-stone-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-stone-400 mr-1.5"></span> Hidden
                                    </span>
                                @endif
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 align-middle text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="group relative p-2 rounded-full transition-colors {{ $review->is_visible ? 'text-stone-400 hover:text-amber-600 hover:bg-amber-50' : 'text-stone-400 hover:text-emerald-600 hover:bg-emerald-50' }}"
                                                title="{{ $review->is_visible ? 'Hide Review' : 'Publish Review' }}">
                                            @if($review->is_visible)
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            @endif
                                        </button>
                                    </form>

                                    {{-- Delete with SweetAlert --}}
                                    {{-- <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="delete-form" data-warning="This cannot be undone.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-stone-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0c.34-.059.68-.114 1.022-.165m-1.022-.165L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        </button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Review?',
                    text: this.getAttribute('data-warning') || "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1c1917', // stone-900
                    cancelButtonColor: '#a8a29e',  // stone-400
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'font-sans',
                        title: 'font-serif text-stone-800',
                        confirmButton: 'px-6 py-2.5 rounded-sm font-medium tracking-wide',
                        cancelButton: 'px-6 py-2.5 rounded-sm font-medium tracking-wide'
                    }
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });
    });
</script>
@endsection