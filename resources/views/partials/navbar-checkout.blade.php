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