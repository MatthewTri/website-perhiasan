@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
        <a href="{{ route('products.index') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
            Continue Shopping
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Authenticated User Cart -->
    @auth
        @if($cartItems->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16">
                                            @if($item->product->featuredImage)
                                                <img class="h-16 w-16 rounded-lg object-cover" 
                                                    src="{{ asset('storage/' . $item->product->featuredImage->image) }}" 
                                                    alt="{{ $item->product->name }}">
                                            @else
                                                <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->product->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ Str::limit($item->product->description, 50) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($item->product->price, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-200"
                                                onclick="return confirm('Remove this item from cart?')">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Cart Summary -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="text-lg font-medium text-gray-900">
                            Total Items: {{ $cartItems->sum('quantity') }}
                        </div>
                        <div class="text-2xl font-bold text-gray-900">
                            Total: Rp {{ number_format($total, 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('checkout.index') }}" 
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-8 rounded-lg transition duration-200">
                            Pre Order
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div id="empty-cart" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15M17 13v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6m8-4V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m4 0V5"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-1 text-sm text-gray-500">Start adding some products to your cart.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Start Shopping
                    </a>
                </div>
            </div>
        @endif
    @endauth

    <!-- Guest User Cart (handled by JavaScript) -->
    @guest
        <div id="guest-cart-container">
            <!-- Loading state -->
            <div id="cart-loading" class="text-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-2 text-gray-600">Loading cart...</p>
            </div>

            <!-- Cart content will be inserted here by JavaScript -->
            <div id="guest-cart-content" class="hidden"></div>

            <!-- Empty cart -->
            <div id="empty-cart" class="hidden text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15M17 13v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6m8-4V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m4 0V5"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-1 text-sm text-gray-500">Start adding some products to your cart.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Start Shopping
                    </a>
                </div>
            </div>
        </div>
    @endguest
</div>

@guest
<script>

const checkoutUrl = "{{ route('checkout.index') }}";

// Guest Cart Management
class GuestCart {

    constructor() {
        this.storageKey = 'guest_cart';
        this.init();
    }

    init() {
        this.loadGuestCart();
    }

    // Get cart from localStorage
    getCart() {
        const cart = localStorage.getItem(this.storageKey);
        return cart ? JSON.parse(cart) : {};
    }

    // Save cart to localStorage
    saveCart(cart) {
        localStorage.setItem(this.storageKey, JSON.stringify(cart));
    }

    // Add item to cart
    addItem(productId, quantity = 1) {
        const cart = this.getCart();
        if (cart[productId]) {
            cart[productId] += quantity;
        } else {
            cart[productId] = quantity;
        }
        this.saveCart(cart);
    }

    // Remove item from cart
    removeItem(productId) {
        const cart = this.getCart();
        delete cart[productId];
        this.saveCart(cart);
        this.loadGuestCart(); // Refresh display
    }

    // Load and display cart
    async loadGuestCart() {
        const cart = this.getCart();
        const productIds = Object.keys(cart);

        document.getElementById('cart-loading').style.display = 'block';
        document.getElementById('guest-cart-content').classList.add('hidden');
        document.getElementById('empty-cart').classList.add('hidden');

        if (productIds.length === 0) {
            document.getElementById('cart-loading').style.display = 'none';
            document.getElementById('empty-cart').classList.remove('hidden');
            return;
        }

        try {
            const response = await fetch('/api/cart/guest-items', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_ids: cart })
            });

            const data = await response.json();
            this.displayCart(data.items);
        } catch (error) {
            console.error('Error loading cart:', error);
            document.getElementById('cart-loading').style.display = 'none';
            document.getElementById('empty-cart').classList.remove('hidden');
        }
    }


    // Display cart items
    displayCart(items) {
        let total = 0;
        let totalItems = 0;
        
        const cartHtml = `
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${items.map(item => {
                                const itemTotal = item.product.price * item.quantity;
                                total += itemTotal;
                                totalItems += item.quantity;
                                
                                // Kondisi untuk gambar (seperti Blade template)
                                const imageHtml = item.product.featured_image 
                                    ? `<img class="h-16 w-16 rounded-lg object-cover" 
                                        src="/storage/${item.product.featured_image.image}" 
                                        alt="${item.product.name}">`
                                    : `<div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>`;
                                
                                return `
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-16 w-16">
                                                    ${imageHtml}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">${item.product.name}</div>
                                                    <div class="text-sm text-gray-500">${item.product.description.substring(0, 50)}...</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp ${new Intl.NumberFormat('id-ID').format(item.product.price)}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${item.quantity}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp ${new Intl.NumberFormat('id-ID').format(itemTotal)}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="guestCart.removeItem(${item.product.id})" 
                                                    class="text-red-600 hover:text-red-900 transition duration-200">
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="text-lg font-medium text-gray-900">
                            Total Items: ${totalItems}
                        </div>
                        <div class="text-2xl font-bold text-gray-900">
                            Total: Rp ${new Intl.NumberFormat('id-ID').format(total)}
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <a href="${checkoutUrl}" 
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-8 rounded-lg transition duration-200">
                            Pre Order
                        </a>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('cart-loading').style.display = 'none';
        document.getElementById('guest-cart-content').innerHTML = cartHtml;
        document.getElementById('guest-cart-content').classList.remove('hidden');
    }
}

// Initialize guest cart
const guestCart = new GuestCart();

async function migrateGuestCartToUser() {
    const guestCart = localStorage.getItem('guest_cart');
    
    if (!guestCart) {
        return;
    }
    
    const cartData = JSON.parse(guestCart);
    
    if (Object.keys(cartData).length === 0) {
        return;
    }
    
    try {
        const response = await fetch('/cart/migrate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(cartData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Clear localStorage setelah berhasil migrate
            localStorage.removeItem('guest_cart');
            console.log('Cart migration successful:', result.message);
        } else {
            console.error('Cart migration failed:', result.error);
        }
    } catch (error) {
        console.error('Error during cart migration:', error);
    }
}

// ✅ Make function globally available
window.migrateGuestCartToUser = migrateGuestCartToUser;
</script>
@endguest
@endsection