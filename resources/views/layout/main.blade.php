<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $defaultTitle = 'Cecile Private Collection';
        $seoTitle = trim($__env->yieldContent('title'));
        $seoTitle = $seoTitle !== '' ? $seoTitle . ' | ' . $defaultTitle : $defaultTitle;
        $seoDescription = trim($__env->yieldContent('meta_description'));
        $seoDescription = $seoDescription !== ''
            ? $seoDescription
            : 'Discover fine jewelry collections, crafted with premium materials and timeless design.';
        $canonicalUrl = url()->current();
        $ogImage = trim($__env->yieldContent('meta_image'));
        $ogImage = $ogImage !== '' ? $ogImage : asset('asset/img/logo.png');
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="icon" type="image/png" href="{{ asset('asset/img/logo.png') }}?v=1">

    {{-- Phone number country --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite('resources/css/app.css')

    {{-- Optional per-page extra <head> content --}}
    @stack('head')

    {{-- Basic JSON-LD for better SEO --}}
    <script type="application/ld+json">
        @php
            $schema = [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => $defaultTitle,
                    'url' => url('/'),
                    'logo' => asset('asset/img/logo.png'),
                ],
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    'name' => $defaultTitle,
                    'url' => url('/'),
                ],
            ];
        @endphp
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</head>
@php
    $tawkEnabled = \App\Models\Setting::getBoolValue('tawk_enabled', true);
@endphp

@if($tawkEnabled)
    <!--Start of Tawk.to Script-->
    <!--Punya ce cicil-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/69e7771044c3731c357f9395/1jmo2i6aa';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    {{-- punya gua sendiri --}}
        {{-- <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/69d4fee0b62f741c3e9267d8/1jlk077sk';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
            })();
        </script> --}}
    <!--End of Tawk.to Script-->
@endif
<body class="flex flex-col min-h-screen">
    <x-loader />
    @include('partials.navbar')

    <div class="flex-grow">
        @yield('content')
    </div>

    {{-- Quick View Modal Product Component --}}
    <x-quick-view-modal />

    {{-- Product Show Detail Component --}}
    <x-product-show-detail />
    
    {{-- Size Chart Modal Component --}}
    @include('components.size-chart-modal')
    
    {{-- Contact Us Modal Component --}}
    @include('components.contact-modal')
    
    {{-- Reviews Modal Component --}}
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

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @stack('scripts')
</body>
</html>