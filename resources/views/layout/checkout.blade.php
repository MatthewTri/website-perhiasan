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

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Checkout - Cecile Private Collection</title>
</head>
<body class="bg-gray-50">
    {{-- Minimal Navbar (bisa customize lebih simple) --}}
    @include('partials.navbar-checkout')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- ⬅️ NO FOOTER! --}}

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

    {{-- Cart Migration Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        @auth
        const guestCart = localStorage.getItem('guest_cart');
        
        if (guestCart) {
            const cartData = JSON.parse(guestCart);
            
            if (Object.keys(cartData).length > 0) {
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

    @stack('scripts')
</body>
</html>