<div class="bg-[#E3CFCF] text-white text-[10px] md:text-xs font-bold text-center py-2 tracking-[0.2em] uppercase">
    ✦ Free shipping all across Indonesia ✦
</div>

<nav id="main-navbar" class="bg-white antialiased w-full z-30 sticky top-0 border-b border-gray-100 transition-transform duration-300 ease-in-out">
    <div class="max-w-screen-2xl mx-auto px-4 lg:px-6 py-6 md:py-9">
        
        <div class="flex items-center justify-between relative">

            <div class="flex items-center lg:hidden">
                <button type="button" data-drawer-target="mobile-menu-drawer" data-drawer-show="mobile-menu-drawer" data-drawer-placement="left" aria-controls="mobile-menu-drawer" class="p-2 text-gray-900 rounded-md hover:bg-gray-50 focus:outline-none">
                    <span class="sr-only">Open Menu</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <div class="absolute left-1/2 transform -translate-x-1/2">
                <a href="/" class="flex items-center">
                    <img src="{{ asset('asset/img/text1.png') }}" alt="Cecile Private Collection Logo" class="h-8 md:h-12 object-contain">
                </a>
            </div>

            <div class="flex items-center space-x-3 md:space-x-5 ml-auto">
                
                {{-- Search icon --}}
                {{-- <button type="button" class="p-1 text-gray-900 hover:text-gray-600 cursor-pointer">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button> --}}

                @auth
                @can('admin')
                <div class="relative hidden sm:block">
                    <button id="adminDropdownButton" data-dropdown-toggle="adminDropdown" class="p-1 text-gray-900 hover:text-gray-600 cursor-pointer transition-colors">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </button>
                    <div id="adminDropdown" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-none shadow-lg w-48 border border-gray-100">
                        <div class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Admin Panel</div>
                        <ul class="py-2 text-sm text-gray-700">
                            <li><a href="{{ route('products.index') }}" class="block px-4 py-2 hover:bg-gray-50">Manage Products</a></li>
                            <li><a href="{{ route('categories.index') }}" class="block px-4 py-2 hover:bg-gray-50">Manage Categories</a></li>
                            <li><a href="{{ route('admin.reviews.index') }}" class="block px-4 py-2 hover:bg-gray-50">Manage Reviews</a></li>
                            <li><a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 hover:bg-gray-50">Customer Orders</a></li>
                            <li><a href="{{ route('admin.consultation.index') }}" class="block px-4 py-2 hover:bg-gray-50">Customer Consultations</a></li>
                            <li><a href="{{ route('settings.index') }}" class="block px-4 py-2 hover:bg-gray-50">Admin Settings</a></li>
                            {{-- <li><a href="{{ route('orders.index') }}" class="block px-4 py-2 hover:bg-gray-50">My Orders</a></li> --}}
                            <li><a href="{{ route('admin.subscribers.index') }}" class="block px-4 py-2 hover:bg-gray-50">Subscribers</a></li>

                        </ul>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">Sign out</button>
                        </form>
                    </div>
                </div>
                @else
                <div class="relative hidden sm:block">
                    <button id="userDropdownButton" data-dropdown-toggle="userDropdown" class="p-1 text-gray-900 hover:text-gray-600 cursor-pointer">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </button>
                    <div id="userDropdown" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-none shadow-lg w-44 border border-gray-100">
                        <ul class="text-sm text-gray-700">
                            {{-- <li><a href="#" class="block px-4 py-2 hover:bg-gray-50">My Account</a></li> --}}
                            {{-- <li><a href="{{ route('orders.index') }}" class="block px-4 py-2 hover:bg-gray-50">My Orders</a></li> --}}
                        </ul>
                        <div class="py-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">Sign out</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endcan
            @else
            <div class="relative hidden sm:block">
                <button id="userDropdownButton" data-dropdown-toggle="userDropdown" class="p-1 text-gray-900 hover:text-gray-600 cursor-pointer">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </button>
                <div id="userDropdown" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-none shadow-lg w-44 border border-gray-100">
                    <div class="py-2">
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Login / Register</a>
                    </div>
                </div>
            </div>
            @endauth

            {{-- Cart icon --}}
                {{-- <a href="{{ route('cart.index') }}" class="p-1 text-gray-900 hover:text-gray-600 relative">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </a> --}}

            </div>
        </div>

        <div class="hidden lg:flex justify-center items-center mt-8 pt-4">
            <ul class="flex space-x-8 text-[11px] xl:text-xs font-medium text-gray-900 tracking-[0.15em] uppercase">
                <li><a href="{{ route('products.index') }}" class="hover:text-[#E3CFCF] transition-colors">Best Sellers</a></li>
                
                @foreach($navCategories as $category)
                <li class="relative group">
                    <a href="{{ route('categories.show', $category->slug) }}" class="hover:text-[#E3CFCF] transition-colors">
                        {{ $category->name }}
                    </a>
                    
                    @if($category->children->count() > 0)
                    {{-- Dropdown Menu --}}
                    <div class="absolute left-0 top-full pt-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="bg-white border border-gray-100 shadow-lg min-w-[200px]">
                            @foreach($category->children as $child)
                            <a href="{{ route('categories.show', $child->slug) }}" class="block px-6 py-3 text-[10px] tracking-[0.15em] uppercase hover:bg-gray-50 hover:text-[#E3CFCF] transition-colors border-b border-gray-50 last:border-b-0">
                                {{ $child->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </li>
                @endforeach
                
                @if(\App\Models\Setting::getBoolValue('ai_enabled', false))
                <li><a href="{{ route('gemma.index') }}" class="text-gray-900 hover:text-[#E3CFCF] transition-colors">Chat With AI</a></li>
                @endif
                
                <li><a href="{{ route('consultation.index') }}" class="hover:text-[#E3CFCF] transition-colors">Book Consultation</a></li>
            </ul>
        </div>

    </div>
</nav>

<div id="mobile-menu-drawer" class="fixed top-0 left-0 z-50 h-screen overflow-hidden transition-transform -translate-x-full bg-white w-70 shadow-2xl" tabindex="-1">
    
    <button type="button" data-drawer-hide="mobile-menu-drawer" aria-controls="mobile-menu-drawer" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center z-10">
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        <span class="sr-only">Close menu</span>
    </button>

    {{-- Main Menu (Level 1) --}}
    <div id="main-menu" class="h-full overflow-y-auto p-4 transition-transform duration-300 ease-in-out">
        <div class="mb-6 mt-2">
            <img src="{{ asset('asset/img/text1.png') }}" alt="Cecile Private Collection" class="h-6">
        </div>

        <ul class="space-y-0 font-medium text-sm tracking-wide text-gray-900">
            <li>
                <a href="{{ route('products.index') }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">Best Sellers</a>
            </li>
            
            @foreach($navCategories as $category)
            <li>
                @if($category->children->count() > 0)
                {{-- Category with Subcategories - Show Arrow --}}
                <button type="button" 
                        onclick="openSubmenu('{{ $category->slug }}', '{{ $category->name }}')"
                        class="flex items-center justify-between w-full py-4 border-b border-gray-100 hover:text-[#E3CFCF]">
                    <span>{{ $category->name }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                @else
                {{-- Category without Subcategories - Direct Link --}}
                <a href="{{ route('categories.show', $category->slug) }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">
                    {{ $category->name }}
                </a>
                @endif
            </li>
            @endforeach
            
            @if(\App\Models\Setting::getBoolValue('ai_enabled', false))
            <li>
                <a href="{{ route('gemma.index') }}" class="block py-4 border-b border-gray-100 text-gray-900 hover:text-[#E3CFCF]">Chat With AI</a>
            </li>
            @endif
            <li>
                <a href="{{ route('consultation.index') }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">Book Consultation</a>
            </li>

            @can('admin')
            <li class="mt-4 pt-4">
                <button type="button" onclick="toggleAdminMenu()" class="flex items-center justify-between w-full py-3 text-xs text-gray-400 font-bold tracking-wider hover:text-gray-600 transition-colors">
                    <span>Administration</span>
                    <svg id="admin-chevron" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <ul id="admin-submenu" class="hidden space-y-0 font-medium text-sm tracking-wide text-gray-900">
                    <li><a href="{{ route('products.index') }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">Manage Products</a></li>
                    <li><a href="{{ route('categories.index') }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">Manage Categories</a></li>
                    <li><a href="{{ route('admin.reviews.index') }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">Manage Reviews</a></li>
                    <li><a href="{{ route('admin.orders.index') }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">Customer Orders</a></li>
                    <li><a href="{{ route('admin.consultation.index') }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">Customer Consultations</a></li>
                    <li><a href="{{ route('settings.index') }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">Admin Settings</a></li>
                    <li><a href="{{ route('admin.subscribers.index') }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">Subscribers</a></li>
                </ul>
            </li>
            @endcan
        </ul>

        <div class="mt-auto pt-4 border-t border-gray-100">
            @auth
                {{-- <a href="#" class="block py-4 border-b border-gray-100 font-medium text-sm tracking-wide text-gray-900 hover:text-[#E3CFCF]">
                    My Account
                </a> --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="block w-full text-left py-4 border-b border-gray-100 font-medium text-sm tracking-wide text-gray-900 hover:text-[#E3CFCF]">
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block w-full text-center py-2 bg-gray-900 text-white uppercase text-xs tracking-widest hover:bg-gray-700">Login</a>
            @endauth
        </div>
    </div>

    {{-- Submenu Panels (Level 2) - One for each parent category --}}
    @foreach($navCategories as $category)
        @if($category->children->count() > 0)
        <div id="submenu-{{ $category->slug }}" class="absolute top-0 left-0 w-full h-full bg-white p-4 overflow-y-auto transform translate-x-full transition-transform duration-300 ease-in-out">
            <div class="mb-6 mt-2">
                <img src="{{ asset('asset/img/text1.png') }}" alt="Cecile Private Collection" class="h-6">
            </div>
            <button type="button" 
                    onclick="closeSubmenu('{{ $category->slug }}')"
                    class="flex items-center mb-6 py-4 text-gray-900 hover:text-[#E3CFCF]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span class="text-sm font-medium tracking-wide">{{ $category->name }}</span>
            </button>

            <ul class="space-y-0 font-medium text-sm tracking-wide text-gray-900">
                <li>
                    <a href="{{ route('categories.show', $category->slug) }}" class="block py-4 border-b border-gray-100 text-gray-900 hover:text-[#E3CFCF]">
                        All {{ $category->name }}
                    </a>
                </li>
                @foreach($category->children as $child)
                <li>
                    <a href="{{ route('categories.show', $child->slug) }}" class="block py-4 border-b border-gray-100 hover:text-[#E3CFCF]">
                        {{ $child->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    @endforeach
</div>

<script>
  // Mobile Submenu Navigation
  function openSubmenu(categorySlug, categoryName) {
      const mainMenu = document.getElementById('main-menu');
      const submenu = document.getElementById('submenu-' + categorySlug);
      
      // Slide main menu to left (hide)
      mainMenu.classList.add('-translate-x-full');
      
      // Slide submenu to center (show)
      submenu.classList.remove('translate-x-full');
  }

  function closeSubmenu(categorySlug) {
      const mainMenu = document.getElementById('main-menu');
      const submenu = document.getElementById('submenu-' + categorySlug);
      
      // Slide submenu to right (hide)
      submenu.classList.add('translate-x-full');
      
      // Slide main menu back to center (show)
      mainMenu.classList.remove('-translate-x-full');
  }

  function toggleAdminMenu() {
      const menu = document.getElementById('admin-submenu');
      const chevron = document.getElementById('admin-chevron');
      menu.classList.toggle('hidden');
      chevron.classList.toggle('rotate-180');
  }

  document.addEventListener('DOMContentLoaded', function () {
        let lastScrollTop = 0;
        const navbar = document.getElementById('main-navbar');
        const scrollThreshold = 100; // Jarak scroll sebelum efek mulai aktif (untuk melewati banner)

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // 1. Jika masih di paling atas (area banner), biarkan default
            if (scrollTop <= 0) {
                navbar.classList.remove('-translate-y-full');
                return;
            }

            // 2. Logic Hide/Show
            // Jika scroll ke bawah DAN sudah melewati banner (threshold)
            if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
                // Sembunyikan Navbar (naik ke atas layar)
                navbar.classList.add('-translate-y-full'); 
            } else {
                // Scroll ke atas -> Munculkan Navbar
                navbar.classList.remove('-translate-y-full');
            }
            
            lastScrollTop = scrollTop;
        });
    });
</script>