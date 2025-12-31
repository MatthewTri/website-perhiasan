@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-2xl">
    
    <div class="mb-12">
        <a href="{{ route('categories.index') }}" class="inline-flex items-center text-xs font-bold tracking-widest text-stone-400 uppercase hover:text-stone-900 transition-colors mb-4 group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Collection
        </a>
        <h1 class="font-serif text-4xl text-stone-900 tracking-tight">Edit Collection</h1>
        <p class="mt-2 text-stone-500 font-light">Update details for <span class="font-medium text-stone-800">"{{ $category->name }}"</span> category.</p>
    </div>

    <div class="bg-white">
        <form id="form-category-edit" action="{{ route('categories.update', $category->slug) }}" method="POST" class="space-y-10">
            @method('PUT')
            @csrf
            
            {{-- 1. PARENT CATEGORY (Styled Select) --}}
            <div class="relative group">
                <label for="parent_id" class="block text-xs font-bold text-stone-400 uppercase tracking-[0.15em] mb-2 group-focus-within:text-stone-900 transition-colors">
                    Parent Hierarchy
                </label>
                
                <div class="relative">
                    <select name="parent_id" 
                            id="parent_id" 
                            class="block w-full py-4 text-stone-800 bg-transparent border-b-2 border-stone-200 appearance-none focus:outline-none focus:border-stone-900 focus:bg-stone-50/30 transition-colors cursor-pointer text-lg font-serif placeholder-stone-300"
                            @if($category->children()->count() > 0) disabled @endif>
                        <option value="" class="text-stone-500">No Parent (Main Collection)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" 
                                {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                &mdash; Sub of {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($category->children()->count() > 0)
                        <div class="text-xs text-gray-500 mt-2">
                            Cannot assign parent because this category has sub-categories. Remove all sub-categories first.
                        </div>
                    @endif
                </div>

                @error('parent_id')
                    <div class="flex items-center mt-2 text-red-500 text-xs tracking-wide">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- 2. CATEGORY NAME (Underline Input) --}}
            <div class="relative group">
                <label for="name" class="block text-xs font-bold text-stone-400 uppercase tracking-[0.15em] mb-2 group-focus-within:text-stone-900 transition-colors">
                    Category Name
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $category->name) }}"
                       class="block w-full py-4 text-xl text-stone-900 bg-transparent border-b-2 border-stone-200 appearance-none focus:outline-none focus:border-stone-900 transition-colors placeholder-stone-300 font-serif"
                       placeholder="Enter category name">
                
                @error('name')
                    <div class="flex items-center mt-2 text-red-500 text-xs tracking-wide">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </div>
                @enderror
                
                {{-- Hidden Slug Input (Logic Preserved) --}}
                <input type="hidden" name="slug" id="slug" value="{{ old('slug', $category->slug) }}">
            </div>

            {{-- 3. ACTION BUTTONS --}}
            <div class="flex items-center justify-between pt-8">
                <a href="{{ route('categories.index') }}" 
                   class="text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors uppercase tracking-widest decoration-stone-900 hover:underline underline-offset-8">
                    Cancel
                </a>

                <button type="submit" 
                        class="group relative inline-flex items-center justify-center px-10 py-4 overflow-hidden font-medium tracking-tighter text-white bg-stone-900 transition duration-300 ease-out hover:bg-stone-800 hover:shadow-2xl">
                    <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-stone-700 rounded-full group-hover:w-80 group-hover:h-80 opacity-10"></span>
                    <span class="relative flex items-center gap-2 uppercase tracking-widest text-xs font-bold">
                        Update Collection
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- STRICTLY PRESERVED LOGIC SCRIPT --}}
<script>
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');
    const form = document.querySelector('#form-category-edit');
    const categoryId = {{ $category->id }};
    const originalName = "{{ $category->name }}";
    let timeout = null;

    name.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(async () => {
            if (name.value.length > 0) {
                if (name.value !== originalName) {
                    try {
                        const response = await fetch('/categories/checkSlug?name=' + encodeURIComponent(name.value) + '&id=' + categoryId);
                        const data = await response.json();
                        slug.value = data.slug;
                    } catch (error) {
                        console.error('Slug generation failed:', error);
                    }
                }
            } else {
                slug.value = '';
            }
        }, 300);
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (name.value.length > 0) {
            if (name.value !== originalName) {
                try {
                    const response = await fetch('/categories/checkSlug?name=' + encodeURIComponent(name.value) + '&id=' + categoryId);
                    const data = await response.json();
                    slug.value = data.slug;
                } catch (error) {
                    alert('Failed to generate slug. Please try again.');
                    return;
                }
            }
            // Jika name tidak berubah, slug tetap pakai yang lama
            form.submit();
        } else {
            slug.value = '';
            form.submit();
        }
    });
</script>
@endsection