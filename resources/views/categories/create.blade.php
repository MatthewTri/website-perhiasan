@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Add New Category</h1>
        
        <form id="form-category" action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Category Name
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       autofocus
                       value="{{ old('name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter category name">
                
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                {{-- Slug --}}
                <div class="mb-3">
                    <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}" readonly>
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
    const form = document.querySelector('#form-category');

    name.addEventListener('change' , function() {
        fetch('/categories/checkSlug?name=' + name.value)
        .then(response => response.json())
        .then(data => slug.value = data.slug)
    });

    form.addEventListener('submit', function(e) {
        if (name.value && !slug.value) {
            e.preventDefault(); // Stop submit
            fetch('/categories/checkSlug?name=' + name.value)
            .then(response => response.json())
            .then(data => {
                slug.value = data.slug;
                form.submit(); // Submit ulang setelah slug ready
            });
        }
    });
</script>
@endsection