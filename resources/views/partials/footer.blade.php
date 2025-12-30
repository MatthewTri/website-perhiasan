<footer class="bg-white border-t border-gray-100 mt-20 pt-16 pb-12">
    <div class="max-w-screen-2xl mx-auto px-6 lg:px-12">
        
        {{-- TOP SECTION: Links & Brand --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-16">
            
            {{-- COLUMN 1: BRAND --}}
            <div class="flex flex-col space-y-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-[0.15em]">
                    Cecile Private Collection
                </h3>
                <div class="text-xs text-gray-500 leading-relaxed font-light space-y-4 max-w-xs">
                    <p>Timeless elegance crafted for the modern individual. Discover our curated selection of fine jewelry designed to celebrate life's precious moments.</p>
                </div>
                
                {{-- Social Icons (Minimalist) --}}
                <div class="flex items-center space-x-6 pt-2">
                    <a href="#" class="text-gray-400 hover:text-gray-900 transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-900 transition-colors">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </div>

            {{-- COLUMN 2: SHOP --}}
            <div class="flex flex-col space-y-6">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.15em]">Shop</h3>
                <ul class="space-y-4 text-xs text-gray-500 font-light tracking-wide">
                    <li><a href="{{ route('products.index') }}" class="hover:text-gray-900 hover:underline underline-offset-4 transition-all">All Products</a></li>
                    <li><a href="#" class="hover:text-gray-900 hover:underline underline-offset-4 transition-all">New Arrivals</a></li>
                    <li><a href="#" class="hover:text-gray-900 hover:underline underline-offset-4 transition-all">Best Sellers</a></li>
                    <li><a href="#" class="hover:text-gray-900 hover:underline underline-offset-4 transition-all">Collections</a></li>
                </ul>
            </div>

            {{-- COLUMN 3: SUPPORT --}}
            <div class="flex flex-col space-y-6">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.15em]">Support</h3>
                <ul class="space-y-4 text-xs text-gray-500 font-light tracking-wide">
                    <li><a href="{{ route('consultation.create') }}" class="hover:text-gray-900 hover:underline underline-offset-4 transition-all">Book Consultation</a></li>
                    <li><a href="#" class="hover:text-gray-900 hover:underline underline-offset-4 transition-all">Size Guide</a></li>
                    <li><a href="#" class="hover:text-gray-900 hover:underline underline-offset-4 transition-all">Shipping & Returns</a></li>
                    <li><a href="#" class="hover:text-gray-900 hover:underline underline-offset-4 transition-all">Contact Us</a></li>
                </ul>
            </div>

            {{-- COLUMN 4: CURRENCY --}}
            <div class="flex flex-col space-y-6">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.15em]">Currency</h3>
                
                {{-- Clean Custom Dropdown --}}
                <form action="{{ route('currency.switch') }}" method="POST" id="footer-currency-form" class="relative max-w-[150px]">
                    @csrf
                    <div class="relative">
                        <select name="currency" 
                                id="footer-currency-selector"
                                onchange="this.form.submit()"
                                class="w-full bg-transparent border-b border-gray-300 text-xs text-gray-600 font-medium py-2 pl-0 pr-8 focus:outline-none focus:border-gray-900 cursor-pointer appearance-none rounded-none tracking-widest uppercase">
                            <option value="IDR" {{ $globalCurrency === 'IDR' ? 'selected' : '' }}>IDR (Rp)</option>
                            <option value="USD" {{ $globalCurrency === 'USD' ? 'selected' : '' }}>USD ($)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center text-gray-400">
                        </div>
                    </div>
                </form>
                
                <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                    Prices are shown in {{ $globalCurrency }}. <br>
                </p>
            </div>
        </div>

        {{-- BOTTOM SECTION: Copyright & Payment Icons --}}
        <div class="border-t border-gray-100 pt-8 flex flex-col-reverse md:flex-row justify-between items-center gap-6">
            
            {{-- Copyright --}}
            <div class="text-[10px] text-gray-400 font-light tracking-wide uppercase">
                &copy; {{ date('Y') }} Cecile Private Collection. All rights reserved.
            </div>

            {{-- Payment Icons (Pure CSS/SVG placeholders or Images) --}}
            <div class="flex items-center space-x-3 transition-all duration-300">
                {{-- PayPal --}}
                <img src="{{ asset('asset/paypal.png') }}" alt="PayPal" class="h-8 w-auto" loading="lazy">
                {{-- BCA --}}
                <img src="{{ asset('asset/bca-logo.png') }}" alt="BCA" class="h-12 w-auto" loading="lazy">
            </div>
        </div>
    </div>
</footer>

<script>
    // Simple spinner logic for currency switch (Optional)
    const currencySelector = document.getElementById('footer-currency-selector');
    const currencyForm = document.getElementById('footer-currency-form');
    
    if(currencySelector) {
        currencySelector.addEventListener('change', function() {
            // Change cursor to waiting
            document.body.style.cursor = 'wait';
            currencyForm.submit();
        });
    }
</script>