<nav class="bg-white border-b border-gray-200 w-full relative z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between relative h-20">

            {{-- Mobile hamburger (admin only) --}}
            <div class="flex items-center lg:hidden">
                @auth
                    @can('admin')
                        <button type="button"
                                data-drawer-target="checkout-admin-drawer"
                                data-drawer-show="checkout-admin-drawer"
                                data-drawer-placement="left"
                                aria-controls="checkout-admin-drawer"
                                class="p-2 text-gray-900 rounded-md hover:bg-gray-50 focus:outline-none">
                            <span class="sr-only">Open Admin Menu</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    @endcan
                @endauth
            </div>

            {{-- Centered, responsive logo --}}
            <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('img/text1.png') }}"
                         alt="Cecile Private Collection"
                         class="h-8 sm:h-10 md:h-12 w-auto object-contain max-w-[200px] sm:max-w-[240px]">
                </a>
            </div>

            {{-- Right actions --}}
            <div class="flex items-center justify-end ml-auto">
                @auth
                    @can('admin')
                        <div class="relative">
                            <button id="adminDropdownCheckoutButton" data-dropdown-toggle="adminDropdownCheckout" class="group flex items-center p-2 -mr-2 text-gray-400 hover:text-gray-600 transition-colors" type="button">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>

                            <div id="adminDropdownCheckout" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-none shadow-lg w-56 border border-gray-100">
                                <div class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Admin Panel</div>
                                <ul class="py-2 text-sm text-gray-700">
                                    <li><a href="{{ route('products.index') }}" class="block px-4 py-2 hover:bg-gray-50">Manage Products</a></li>
                                    <li><a href="{{ route('categories.index') }}" class="block px-4 py-2 hover:bg-gray-50">Manage Categories</a></li>
                                    <li><a href="{{ route('admin.reviews.index') }}" class="block px-4 py-2 hover:bg-gray-50">Manage Reviews</a></li>
                                    <li><a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 hover:bg-gray-50">Customer Orders</a></li>
                                    <li><a href="{{ route('admin.consultation.index') }}" class="block px-4 py-2 hover:bg-gray-50">Customer Consultations</a></li>
                                    <li><a href="{{ route('settings.index') }}" class="block px-4 py-2 hover:bg-gray-50">Admin Settings</a></li>
                                    <li><a href="{{ route('admin.subscribers.index') }}" class="block px-4 py-2 hover:bg-gray-50">Subscribers</a></li>
                                </ul>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">Sign out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('cart.index') }}" class="group flex items-center p-2 -mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-7 h-7 text-gray-400 group-hover:text-gray-600 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </a>
                    @endcan
                @else
                    <a href="{{ route('cart.index') }}" class="group flex items-center p-2 -mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="w-7 h-7 text-gray-400 group-hover:text-gray-600 transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Mobile admin drawer (admin only) --}}
@auth
    @can('admin')
        <div id="checkout-admin-drawer" class="fixed top-0 left-0 z-50 h-screen overflow-hidden transition-transform -translate-x-full bg-white w-72 shadow-2xl" tabindex="-1" aria-labelledby="checkout-admin-drawer-label">
            <button type="button" data-drawer-hide="checkout-admin-drawer" aria-controls="checkout-admin-drawer" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center z-10">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close menu</span>
            </button>

            <div class="h-full overflow-y-auto p-4 flex flex-col">
                <div class="mb-6 mt-2">
                    <img src="{{ asset('img/text1.png') }}" alt="Cecile Private Collection" class="h-6">
                </div>

                <div id="checkout-admin-drawer-label" class="px-2 pb-4 pt-8 text-xs font-bold text-gray-400 tracking-wider uppercase">Admin Panel</div>
                <ul class="space-y-0 font-medium text-sm tracking-wide text-gray-900">
                    <li><a href="{{ route('products.index') }}" class="block py-4 border-b border-gray-100 hover:text-gray-600">Manage Products</a></li>
                    <li><a href="{{ route('categories.index') }}" class="block py-4 border-b border-gray-100 hover:text-gray-600">Manage Categories</a></li>
                    <li><a href="{{ route('admin.reviews.index') }}" class="block py-4 border-b border-gray-100 hover:text-gray-600">Manage Reviews</a></li>
                    <li><a href="{{ route('admin.orders.index') }}" class="block py-4 border-b border-gray-100 hover:text-gray-600">Customer Orders</a></li>
                    <li><a href="{{ route('admin.consultation.index') }}" class="block py-4 border-b border-gray-100 hover:text-gray-600">Customer Consultations</a></li>
                    <li><a href="{{ route('settings.index') }}" class="block py-4 border-b border-gray-100 hover:text-gray-600">Admin Settings</a></li>
                    <li><a href="{{ route('admin.subscribers.index') }}" class="block py-4 border-b border-gray-100 hover:text-gray-600">Subscribers</a></li>
                </ul>

                <div class="mt-auto pt-4 border-t border-gray-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left py-4 font-medium text-sm tracking-wide text-gray-900 hover:text-gray-600">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endauth