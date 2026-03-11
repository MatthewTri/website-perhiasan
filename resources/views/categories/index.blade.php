@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-6xl">
    <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-10 gap-4">
        <div>
            <h6 class="text-xs font-bold tracking-[0.2em] text-stone-500 uppercase mb-2">Inventory Management</h6>
            <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight">Category Collection</h1>
        </div>
        <a href="{{ route('categories.create') }}" 
           class="group relative inline-flex items-center justify-center px-8 py-3 overflow-hidden font-medium tracking-tighter text-white bg-stone-900 rounded-sm transition duration-300 ease-out hover:bg-stone-800 hover:shadow-lg">
            <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-stone-700 rounded-full group-hover:w-56 group-hover:h-56"></span>
            <span class="relative flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Category
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

    <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.14)] border border-stone-400 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-stone-100">
                        <th class="px-8 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Category Name</th>
                        <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Slug</th>
                        <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] text-center">Items</th>
                        <th class="px-8 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($categories as $category)
                        <tr class="group hover:bg-stone-50/50 transition-colors duration-200">
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center mr-4 text-stone-500 group-hover:bg-stone-900 group-hover:text-white transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                        </svg>                                      
                                    </div>
                                    <span class="font-serif text-lg text-stone-800 group-hover:text-stone-900 font-medium">
                                        {{ $category->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-sm text-stone-500 font-light font-mono">
                                /{{ $category->slug }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-stone-100 text-stone-600">
                                    {{ $category->totalProductsCount() }} Products
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end items-center gap-3 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('categories.edit', $category->slug) }}" class="p-2 text-stone-400 hover:text-stone-900 transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>
                                    
                                    {{-- FORM DELETE PARENT --}}
                                    <form action="{{ route('categories.destroy', $category->slug) }}" 
                                          method="POST" 
                                          class="inline delete-form" 
                                          data-title="Delete '{{ $category->name }}'?"
                                          data-warning="This will also delete all sub-categories and unlink products.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-stone-400 hover:text-red-600 transition-colors" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @foreach($category->children as $child)
                        <tr class="hover:bg-stone-50 transition-colors duration-200">
                            <td class="px-8 py-4 relative">
                                <div class="absolute left-12 top-0 bottom-1/2 w-px bg-stone-200"></div> 
                                <div class="absolute left-12 top-1/2 w-4 h-px bg-stone-200"></div> 
                                <div class="flex items-center pl-10">
                                    <span class="text-sm text-stone-600 group-hover:text-stone-900 tracking-wide">
                                        {{ $child->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-stone-400 font-mono">
                                /{{ $category->slug }}/{{ $child->slug }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs text-stone-400">
                                    {{ $child->products->count() }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <div class="flex justify-end items-center gap-3">
                                    <a href="{{ route('categories.edit', $child->slug) }}" class="text-xs text-stone-400 hover:text-stone-800 hover:underline decoration-stone-800 underline-offset-4 transition-all">
                                        Edit
                                    </a>
                                    <span class="text-stone-300 text-xs">|</span>
                                    
                                    {{-- FORM DELETE CHILD --}}
                                    <form action="{{ route('categories.destroy', $child->slug) }}" 
                                          method="POST" 
                                          class="inline delete-form"
                                          data-title="Delete '{{ $child->name }}'?"
                                          data-warning="This action cannot be undone.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-stone-400 hover:text-red-600 hover:underline decoration-red-600 underline-offset-4 transition-all">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-stone-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-12 h-12 mb-3 opacity-50">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                    <p class="text-sm">No collections found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Menunggu DOM siap
    document.addEventListener('DOMContentLoaded', function () {
        // Seleksi semua form dengan class 'delete-form'
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Mencegah submit langsung

                // Ambil data pesan dari atribut data-title dan data-warning
                const titleText = this.getAttribute('data-title') || 'Are you sure?';
                const warningText = this.getAttribute('data-warning') || 'You won\'t be able to revert this!';

                Swal.fire({
                    title: titleText,
                    text: warningText,
                    icon: 'warning',
                    showCancelButton: true,
                    // Styling Custom agar sesuai tema Stone/Jewelry
                    confirmButtonColor: '#1c1917', // stone-900
                    cancelButtonColor: '#a8a29e',  // stone-400
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'font-sans',
                        title: 'font-serif text-stone-800',
                        confirmButton: 'px-6 py-2.5 rounded-sm font-medium tracking-wide',
                        cancelButton: 'px-6 py-2.5 rounded-sm font-medium tracking-wide'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); // Submit form jika user klik Yes
                    }
                });
            });
        });
    });
</script>
@endsection