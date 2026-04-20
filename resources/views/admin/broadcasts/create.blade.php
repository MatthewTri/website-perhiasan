@extends('layout.checkout')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-4xl">
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('admin.subscribers.index') }}" class="p-2 text-stone-400 hover:text-stone-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h6 class="text-xs font-bold tracking-[0.2em] text-stone-500 uppercase">Newsletter Broadcast</h6>
                <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight">Create Email Campaign</h1>
            </div>
        </div>
        <p class="text-sm text-stone-500 ml-14">Send a broadcast email to all active subscribers. This email will be queued and sent in the background.</p>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-2 border-emerald-500 text-emerald-700 text-sm tracking-wide">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 p-4 bg-red-50 border-l-2 border-red-500 text-red-700 text-sm tracking-wide">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-8 p-4 bg-red-50 border-l-2 border-red-500 text-red-700 text-sm">
            <p class="font-bold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stats Card --}}
    <div class="bg-[#E3CFCF] rounded-lg p-6 mb-8 text-gray-800">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.15em] mb-1 opacity-90">Recipients</p>
                <p class="text-4xl font-serif">{{ $totalSubscribers }}</p>
                <p class="text-xs mt-2 opacity-75">Subscribers will receive this broadcast</p>
            </div>
            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.broadcasts.send') }}" method="POST" class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.14)] border border-stone-400 overflow-hidden">
        @csrf
        
        <div class="p-8 space-y-8">
            {{-- Subject --}}
            <div>
                <label for="subject" class="block text-xs font-bold text-stone-700 uppercase tracking-[0.15em] mb-3">
                    Email Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="subject" 
                       name="subject" 
                       value="{{ old('subject') }}"
                       placeholder="e.g., Exclusive New Collection Just Dropped!" 
                       class="w-full px-4 py-3 border border-stone-300 rounded-sm text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#E3CFCF] focus:border-transparent transition-all @error('subject') border-red-500 @enderror"
                       required>
                @error('subject')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-stone-500">This will be the subject line subscribers see in their inbox</p>
            </div>

            {{-- Body --}}
            <div>
                <label for="body" class="block text-xs font-bold text-stone-700 uppercase tracking-[0.15em] mb-3">
                    Email Body <span class="text-red-500">*</span>
                </label>
                <textarea id="body" 
                          name="body" 
                          rows="12" 
                          placeholder="Write your message here..."
                          class="w-full px-4 py-3 border border-stone-300 rounded-sm text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#E3CFCF] focus:border-transparent transition-all @error('body') border-red-500 @enderror"
                          required>{{ old('body') }}</textarea>
                @error('body')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-stone-500">Craft your message. Basic HTML is supported for formatting (e.g., &lt;b&gt;, &lt;i&gt;, &lt;br&gt;, &lt;p&gt;)</p>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="px-8 py-6 bg-stone-50 border-t border-stone-200 flex justify-between items-center">
            <a href="{{ route('admin.subscribers.index') }}" 
               class="px-6 py-3 text-sm font-medium text-stone-600 hover:text-stone-900 transition-colors uppercase tracking-widest">
                Cancel
            </a>
            <button type="button" 
                    id="sendBroadcastBtn"
                    class="group relative inline-flex items-center justify-center px-8 py-3 overflow-hidden font-medium text-white bg-stone-900 rounded-sm hover:shadow-lg">
                <span class="absolute w-0 h-0 rounded-full group-hover:w-56 group-hover:h-56"></span>
                <span class="relative flex items-center gap-2 text-xs cursor-pointer uppercase tracking-widest">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                    Send Broadcast
                </span>
            </button>
        </div>
    </form>
</div>

<script>
    // SweetAlert Send Broadcast Confirmation
    document.getElementById('sendBroadcastBtn').addEventListener('click', function() {
        const form = this.closest('form');
        const totalSubscribers = {{ $totalSubscribers }};
        
        Swal.fire({
            title: 'Send Broadcast Email?',
            html: `You are about to send this broadcast to<br><strong>${totalSubscribers} subscribers</strong><br><br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E3CFCF',
            cancelButtonColor: '#71717a',
            confirmButtonText: 'Yes, Send Now',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'rounded-sm px-6 py-3 text-xs uppercase tracking-widest font-bold',
                cancelButton: 'rounded-sm px-6 py-3 text-xs uppercase tracking-widest font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

@endsection
