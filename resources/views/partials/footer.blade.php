
<footer class="bg-gray-800 text-white py-8 mt-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <!-- Company Info -->
            <div class="mb-4 md:mb-0">
                <h3 class="text-xl font-bold mb-2">Cecile Private Collection</h3>
                <p class="text-gray-400 text-sm">Premium Jewelry Store</p>
            </div>

            <!-- Currency Switcher -->
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-400">Currency:</span>
                <form action="{{ route('currency.switch') }}" method="POST" id="currency-form">
                    @csrf
                    <select name="currency" 
                            id="currency-selector"
                            onchange="this.form.submit()"
                            class="bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="IDR" {{ $globalCurrency === 'IDR' ? 'selected' : '' }}>
                            🇮🇩 IDR
                        </option>
                        <option value="USD" {{ $globalCurrency === 'USD' ? 'selected' : '' }}>
                            🇺🇸 USD
                        </option>
                    </select>
                </form>
            </div>

            <!-- Links -->
            <div class="mt-4 md:mt-0">
                <ul class="flex space-x-4 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-blue-400 transition">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-blue-400 transition">Products</a></li>
                    <li><a href="{{ route('consultation.create') }}" class="hover:text-blue-400 transition">Consultation</a></li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-700 mt-6 pt-6 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} Cecile Private Collection. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    // Optional: Tambah loading indicator saat switch currency
    const currencySelector = document.getElementById('currency-selector');
    const currencyForm = document.getElementById('currency-form');
    
    currencySelector.addEventListener('change', function() {
        // Bisa tambah spinner/loading indicator
        currencyForm.submit();
    });
</script>