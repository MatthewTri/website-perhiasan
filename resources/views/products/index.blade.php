@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Products</h1>
        <a href="{{ route('products.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
            Add Product
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
                <!-- Product Image -->
                <div class="aspect-square bg-gray-100 relative">
                    @if($product->featuredImage)
                        <img src="{{ asset('storage/' . $product->featuredImage->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Category Badge -->
                    @if($product->category)
                        <div class="absolute top-3 left-3">
                            <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                {{ $product->category->name }}
                            </span>
                        </div>
                    @endif

                    <!-- Add to Cart Icon "+" -->
                    <div class="absolute bottom-3 right-3">
                        <button onclick="addToCartLater({{ $product->id }})" 
                                class="bg-white hover:bg-blue-500 text-gray-800 hover:text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0">
                            <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="font-semibold text-lg text-gray-900 mb-1 line-clamp-2">
                        {{ $product->name }}
                    </h3>
                    
                    <p class="text-gray-600 text-sm mb-2 line-clamp-2">
                        {{ Str::limit($product->description, 80) }}
                    </p>
                    
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xl font-bold text-gray-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Stock: {{ $product->stock }}
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-2">
                        <button class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 px-3 rounded transition duration-200">
                            View
                        </button>
                        <a href="{{ route('products.edit', $product) }}" 
                        class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-3 rounded transition duration-200">
                            Edit
                        </a>
                        <!-- Delete Button dengan Konfirmasi -->
                        <form action="{{ route('products.destroy', $product) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirmDelete('{{ $product->name }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-600 text-white text-sm py-2 px-3 rounded transition duration-200">
                                Del
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
                    <div class="mt-6">
                        <a href="{{ route('products.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Add Product
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<script>
    function confirmDelete(productName) {
        return confirm(`Are you sure you want to delete "${productName}"?\n\nThis action cannot be undone and will also delete all associated images.`);
    }

    function addToCartLater(productId) {
        // Cek apakah user sudah login
        @guest
            alert('Please login first to add items to cart!');
            return;
        @endguest

        // Kirim AJAX request untuk menambah ke cart
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product added to cart successfully!');
                // Optional: Update cart counter di navbar jika ada
            } else {
                alert(data.error || 'Something went wrong!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong!');
        });
    }
</script>
@endsection