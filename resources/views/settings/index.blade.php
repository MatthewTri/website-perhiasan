@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Website Settings</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- AI Settings Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <span class="text-2xl mr-2">🤖</span>
                <h2 class="text-xl font-semibold text-gray-900">Pengaturan Fitur AI</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- AI Toggle -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Aktif/Nonaktifkan Fitur AI
                    </label>
                    <div class="flex items-center">
                        <label class="inline-flex items-center">
                            <input type="hidden" name="ai_enabled" value="0">
                            <input type="checkbox" 
                                   name="ai_enabled" 
                                   value="1" 
                                   {{ $settings['ai_enabled'] ? 'checked' : '' }}
                                   class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">
                                Aktifkan fitur visualisasi AI di seluruh website
                            </span>
                        </label>
                    </div>
                </div>

                <!-- AI Quota -->
                <div class="space-y-2">
                    <label for="ai_quota_limit" class="block text-sm font-medium text-gray-700">
                        Kuota Prompt AI per Pengguna
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="ai_quota_limit" 
                               name="ai_quota_limit" 
                               value="{{ $settings['ai_quota_limit'] }}"
                               min="1" 
                               max="1000"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">kali</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Maksimum berapa kali pengguna bisa menggunakan fitur AI</p>
                </div>
            </div>
        </div>

        <!-- Financial Settings Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <span class="text-2xl mr-2">💰</span>
                <h2 class="text-xl font-semibold text-gray-900">Pengaturan Keuangan</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tax Percentage -->
                <div class="space-y-2">
                    <label for="tax_percentage" class="block text-sm font-medium text-gray-700">
                        Persentase Pajak (PPN)
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="tax_percentage" 
                               name="tax_percentage" 
                               value="{{ $settings['tax_percentage'] }}"
                               min="0" 
                               max="100" 
                               step="0.01"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">%</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Persentase pajak yang akan ditambahkan saat checkout</p>
                </div>
            </div>
        </div>

        <!-- Shipping Settings Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <span class="text-2xl mr-2">🚚</span>
                <h2 class="text-xl font-semibold text-gray-900">Pengaturan Pengiriman (Flat-Rate)</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Domestic Shipping -->
                <div class="space-y-2">
                    <label for="shipping_domestic" class="block text-sm font-medium text-gray-700">
                        Biaya Shipping Domestik
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">Rp</span>
                        </div>
                        <input type="number" 
                               id="shipping_domestic" 
                               name="shipping_domestic" 
                               value="{{ $settings['shipping_domestic'] }}"
                               min="0"
                               class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <p class="text-xs text-gray-500">Biaya kirim flat-rate untuk pengiriman dalam negeri</p>
                </div>

                <!-- International Shipping -->
                <div class="space-y-2">
                    <label for="shipping_international" class="block text-sm font-medium text-gray-700">
                        Biaya Shipping Internasional
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">Rp</span>
                        </div>
                        <input type="number" 
                               id="shipping_international" 
                               name="shipping_international" 
                               value="{{ $settings['shipping_international'] }}"
                               min="0"
                               class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <p class="text-xs text-gray-500">Biaya kirim flat-rate untuk pengiriman ke luar negeri</p>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-8 rounded-lg transition duration-200">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection