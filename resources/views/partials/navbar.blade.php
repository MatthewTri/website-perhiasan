<nav class="bg-gray-200 antialiased">
  <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0 py-4">
    <div class="flex items-center justify-between">

      <div class="flex items-center space-x-8">

        <ul class="hidden lg:flex items-center justify-start gap-6 md:gap-8 py-3 sm:justify-center">
          <li>
            <a href="#" title="" class="flex text-sm font-medium text-gray-900 hover:text-primary-700">
              Home
            </a>
          </li>
          <li class="shrink-0">
            <a href="{{ route('consultation.create') }}" title="" class="flex text-sm font-medium text-gray-900 hover:text-primary-700">
              Book consultation
            </a>
          </li>
          @auth
            <li class="shrink-0">
              <a href="{{ route('gemma.index') }}" title="" class="flex text-sm font-medium text-gray-900 hover:text-primary-700">
                Gemma AI
              </a>
            </li>
          @endauth
          <li class="shrink-0">
            <a href="{{ route('products.index') }}" title="" class="text-sm font-medium text-gray-900 hover:text-primary-700">
              Manage products
            </a>
          </li>
          <li class="shrink-0">
            <a href="{{ route('categories.index') }}" title="" class="text-sm font-medium text-gray-900 hover:text-primary-700">
              Manage Categories
            </a>
          </li>
          <li class="shrink-0">
            <a href="{{ route('admin.consultation.index') }}" title="" class="text-sm font-medium text-gray-900 hover:text-primary-700">
              Customer's Consultation
            </a>
          </li>
          <li class="shrink-0">
            <a href="{{ route('admin.orders.index') }}" title="" class="text-sm font-medium text-gray-900 hover:text-primary-700">
              Customers's Orders
            </a>
          </li>
        </ul>
      </div>

      <div class="flex items-center lg:space-x-2">

        <button id="myCartDropdownButton1" data-dropdown-toggle="myCartDropdown1" type="button" class="inline-flex items-center rounded-lg justify-center p-2 hover:bg-gray-100 text-sm font-medium leading-none text-gray-900">
          <span class="sr-only">
            Cart
          </span>
          <svg class="w-5 h-5 lg:me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
          </svg> 
          <a href="{{ route('cart.index') }}">My Cart</a>       
        </button>

        <button id="userDropdownButton1" data-dropdown-toggle="userDropdown1" type="button" class="inline-flex items-center rounded-lg justify-center p-2 hover:bg-gray-100 text-sm font-medium leading-none text-gray-900">
          <svg class="w-5 h-5 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
          </svg>              
          Account
          <svg class="w-4 h-4 text-gray-900 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
          </svg> 
        </button>

        <div id="userDropdown1" class="hidden z-10 w-56 divide-y divide-gray-100 overflow-hidden overflow-y-auto rounded-lg bg-white antialiased shadow">
          <ul class="p-2 text-start text-sm font-medium text-gray-900">
            <li><a href="#" title="" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100"> My Account </a></li>
            @auth
              <li><a href="{{ route('settings.index') }}" title="" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100"> Admin Settings </a></li>
              <li><a href="{{ route('orders.index') }}" title="" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100"> My Orders </a></li>
              <li><a href="{{ route('admin.reviews.index') }}" title="" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100"> Customer's Reviews </a></li>
            @endauth
          </ul>
      
          <div class="p-2 text-sm font-medium text-gray-900">
            <form action="{{ route('logout') }}" method="POST" class="w-full">
              @csrf
              @auth
                <button type="submit" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100 text-left">
                  Sign Out
                </button>
              @endauth
            </form>
          </div>
        </div>

        <button type="button" data-collapse-toggle="ecommerce-navbar-menu-1" aria-controls="ecommerce-navbar-menu-1" aria-expanded="false" class="inline-flex lg:hidden items-center justify-center hover:bg-gray-100 rounded-md p-2 text-gray-900">
          <span class="sr-only">
            Open Menu
          </span>
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
          </svg>                
        </button>
      </div>
    </div>


  </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
      document.getElementById('myCartDropdownButton1').click();
    });
</script>