@extends('layout.checkout')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-7xl">
    <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-10 gap-4">
        <div>
            <h6 class="text-xs font-bold tracking-[0.2em] text-stone-500 uppercase mb-2">Newsletter Management</h6>
            <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight">Email Subscribers</h1>
        </div>
        <a href="{{ route('admin.broadcasts.create') }}" 
           class="group relative inline-flex items-center justify-center px-8 py-3 overflow-hidden font-medium tracking-tighter text-gray-800 bg-[#E3CFCF] rounded-sm transition duration-300 hover:shadow-lg">
            <span class="absolute w-0 h-0 rounded-full group-hover:w-56 group-hover:h-56"></span>
            <span class="relative flex items-center gap-2 text-xs uppercase tracking-widest">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
                Create Broadcast
            </span>
        </a>
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

    {{-- Stats & Search --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg border border-stone-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-stone-400 uppercase tracking-[0.15em] mb-1">Total Subscribers</p>
                    <p class="text-3xl font-serif text-stone-900">{{ $totalSubscribers }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[#F5E6E0] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-[#454545]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
        </div>
        
        {{-- Search Box --}}
        <div class="bg-white rounded-lg border border-stone-200 p-6">
            <form action="{{ route('admin.subscribers.index') }}" method="GET" class="space-y-3">
                <label for="search" class="block text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Search Email</label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-stone-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Search by email address..." 
                               class="w-full pl-10 pr-4 py-2.5 border border-stone-300 rounded-sm text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#E3CFCF] focus:border-transparent transition-all">
                    </div>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-stone-900 text-white hover:bg-[#E3CFCF] transition-all duration-300 rounded-sm">
                        <span class="text-xs font-bold uppercase tracking-wider">Search</span>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.subscribers.index') }}" 
                           class="px-4 py-2.5 bg-stone-200 text-stone-700 hover:bg-stone-300 transition-all duration-300 rounded-sm flex items-center justify-center"
                           title="Clear search">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.14)] border border-stone-400 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-stone-100">
                        <th class="px-8 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Email Address</th>
                        <th class="px-8 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] text-right">Deactivate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($subscribers as $subscriber)
                        <tr class="group hover:bg-stone-50/50 transition-colors duration-200">
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-[#454545] flex items-center justify-center mr-4 transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-[#E3CFCF] group-hover:text-white">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                        </svg>                                     
                                    </div>
                                    <span class="text-sm text-stone-800 group-hover:text-stone-900 font-medium">
                                        {{ $subscriber->email }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end items-center opacity-60 group-hover:opacity-100 transition-opacity">
                                    {{-- Deactivate --}}
                                    <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" 
                                          method="POST" 
                                          class="inline deactivate-form">
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
                    @empty
                        <tr>
                            <td colspan="2" class="px-8 py-12 text-center text-stone-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-12 h-12 mx-auto mb-4 opacity-30">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                <p class="text-sm uppercase tracking-widest">No subscribers yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($subscribers->hasPages())
        <div class="px-8 py-6 border-t border-stone-100">
            {{ $subscribers->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>

<script>
    // SweetAlert Deactivate Confirmation
    document.querySelectorAll('.deactivate-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const email = this.getAttribute('data-email');
            const form = this.closest('form');
            
            Swal.fire({
                title: 'Deactivate Subscriber?',
                html: `Are you sure you want to deactivate<br><strong>${email}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E3CFCF',
                cancelButtonColor: '#71717a',
                confirmButtonText: 'Yes, Deactivate',
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
    });
</script>

@endsection
