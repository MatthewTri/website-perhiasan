<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    {{-- Phone number country --}}
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

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
</body>
</html>