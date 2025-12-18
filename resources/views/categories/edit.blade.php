@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Category</h1>

        <form id="form-category-edit" action="{{ route('categories.update', $category->slug) }}" method="POST">
            @method('PUT')
            @csrf

            {{-- ⬅️ PARENT CATEGORY DROPDOWN --}}
            <div class="mb-4">
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Parent Category (Optional)
                </label>
                <select name="parent_id" 
                        id="parent_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- None (Main Category) --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" 
                                {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                
                @error('parent_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Category Name
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       autofocus
                       value="{{ old('name', $category->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter category name">
                
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                {{-- Slug --}}
                <input type="hidden" name="slug" id="slug" value="{{ old('slug', $category->slug) }}">
            </div>

            <div class="flex space-x-3">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Save Category
                </button>
                <a href="{{ route('categories.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');
    const form = document.querySelector('#form-category-edit');
    let timeout = null;

    name.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(async () => {
            if (name.value.length > 0) {
                try {
                    const response = await fetch('/categories/checkSlug?name=' + encodeURIComponent(name.value));
                    const data = await response.json();
                    slug.value = data.slug;
                } catch (error) {
                    slug.value = '';
                }
            } else {
                slug.value = '';
            }
        }, 300);
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (name.value.length > 0) {
            try {
                const response = await fetch('/categories/checkSlug?name=' + encodeURIComponent(name.value));
                const data = await response.json();
                slug.value = data.slug;
                form.submit();
            } catch (error) {
                alert('Failed to generate slug. Please try again.');
            }
        } else {
            slug.value = '';
            form.submit();
        }
    });
</script>
@endsection