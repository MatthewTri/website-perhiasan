<nav class="bg-white border-b border-gray-200 w-full relative z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20"> <div class="flex-1"></div>

            <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <a href="{{ route('home') }}" class="block">
                    <img src="{{ asset('img/text1.png') }}" 
                         alt="Cecile Private Collection" 
                         class="h-12 w-auto object-contain">
                </a>
            </div>

            <div class="flex-1 flex justify-end items-center">
                <a href="{{ route('cart.index') }}" class="group flex items-center p-2 -mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                         class="w-7 h-7 text-gray-400 group-hover:text-gray-600 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    
                    {{-- <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800">0</span> --}}
                </a>
            </div>

        </div>
    </div>
</nav>