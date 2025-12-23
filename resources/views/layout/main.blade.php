<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    {{-- Phone number country --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    @vite('resources/css/app.css')

    <title>Cecile Private Collection</title>
</head>
<body>
    @include('partials.navbar')

    <div>
        @yield('content')
    </div>

    @include('components.reviews-modal')

    @include('partials.footer')
    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    {{-- Phone number country --}}
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

    <!-- CLEAR GUEST CART SCRIPT - HARUS DI ATAS -->
    <script>
        // Clear guest cart via query parameter (reliable)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('clear_cart') === '1') {
            localStorage.removeItem('guest_cart');
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = '0';
                cartBadge.classList.add('hidden');
            }
            document.querySelectorAll('[data-cart-count]').forEach(el => {
                el.textContent = '0';
                el.classList.add('hidden');
            });
            window.dispatchEvent(new Event('cart-cleared'));
            console.log('✅ Guest cart cleared via query param!');
            // Bersihkan query param dari URL agar tidak eksekusi ulang
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>

    <!-- CART MIGRATION SCRIPT -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        @auth
        // User sudah login, cek apakah ada guest cart untuk dimigrasi
        const guestCart = localStorage.getItem('guest_cart');
        
        if (guestCart) {
            const cartData = JSON.parse(guestCart);
            
            if (Object.keys(cartData).length > 0) {
                // Migrate guest cart ke user cart
                fetch('/cart/migrate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(cartData)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        localStorage.removeItem('guest_cart');
                        console.log('Cart migrated:', result.message);
                        
                        // Optional: Refresh halaman atau update cart display
                        // window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Cart migration error:', error);
                });
            }
        }
        @endauth
    });
    </script>

    <script>
        // Clear guest cart after successful checkout
        @if(session('clear_guest_cart'))
            // Clear localStorage
            localStorage.removeItem('guest_cart');
            
            // Update cart badge di navbar
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = '0';
                cartBadge.classList.add('hidden');
            }

            // Update semua cart count elements
            const cartCounts = document.querySelectorAll('[data-cart-count]');
            cartCounts.forEach(element => {
                element.textContent = '0';
                element.classList.add('hidden');
            });

            // Dispatch event untuk component lain
            window.dispatchEvent(new Event('cart-cleared'));

            console.log('✅ Guest cart cleared successfully after checkout');
        @endif
    </script>

    @stack('scripts')
</body>
</html>