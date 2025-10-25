@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Category</h1>

        <form id="form" action="{{ route('categories.update', $category->slug) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Category Name
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       autofocus
                       value="{{ old('name' , $category->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter category name">
                
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                {{-- Slug --}}
                <div class="mb-3">
                    <input type="hidden" name="slug" id="slug" value="{{ old('slug' , $category->slug) }}" readonly>
                </div>
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
    const form = document.querySelector('form');
    let timeout = null;

    // Ganti 'change' menjadi 'input' agar real-time update
    name.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            if (name.value.length > 0) {
                fetch('/categories/checkSlug?name=' + encodeURIComponent(name.value))
                .then(response => response.json())
                .then(data => slug.value = data.slug)
                .catch(error => console.log('Error:', error));
            } else {
                slug.value = '';
            }
        }, 300); // Debounce 300ms
    });

    // Pastikan slug update sebelum submit
    form.addEventListener('submit', function(e) {
        if (name.value && !slug.value) {
            e.preventDefault();
            fetch('/categories/checkSlug?name=' + encodeURIComponent(name.value))
            .then(response => response.json())
            .then(data => {
                slug.value = data.slug;
                form.submit();
            });
        }
    });
</script>
@endsection