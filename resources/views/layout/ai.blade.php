<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Cecile AI - Bespoke Jewellery Visualization' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/img/logo.png') }}?v=1">
    
    {{-- Tailwind CSS --}}
    @vite('resources/css/app.css')
    
    {{-- Custom Fonts (optional) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, .font-serif {
            font-family: 'Cormorant Garamond', serif;
        }
    </style> --}}
</head>
<body class="antialiased bg-stone-50">
    @yield('content')
    
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>