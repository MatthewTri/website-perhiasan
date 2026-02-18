@extends('layout.main')

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- Main Container --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-16">
        
        {{-- Header Section --}}
        <div class="text-center mb-12 lg:mb-16">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif text-gray-900 mb-3 tracking-tight">
                Website Settings
            </h1>
            <p class="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-gray-400 font-medium">
                Configure your platform preferences
            </p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-8 bg-[#F0F9FF] border border-[#E8B49E]/30 px-6 py-4 transition-all duration-300">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-[#E8B49E] mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-gray-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="mb-8 bg-red-50 border border-red-200 px-6 py-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <ul class="text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Settings Form --}}
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- AI Settings Card --}}
            <div class="bg-white border border-gray-100 shadow-sm transition-shadow duration-300 hover:shadow-md">
                
                {{-- Card Header --}}
                <div class="px-6 sm:px-8 py-6 border-b border-gray-100 bg-[#FAFAFA]">
                    <div class="flex items-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#E8B49E] to-[#D4A088] rounded-full flex items-center justify-center mr-4 shadow-sm">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-serif text-gray-900 mb-1">AI Feature Settings</h2>
                            <p class="text-[10px] sm:text-xs uppercase tracking-[0.15em] text-gray-400">
                                Manage artificial intelligence capabilities
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- Card Body --}}
                <div class="px-6 sm:px-8 py-8 sm:py-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                        
                        {{-- AI Toggle --}}
                        <div class="space-y-4">
                            <label class="block">
                                <span class="text-xs uppercase tracking-[0.1em] text-gray-500 font-medium mb-3 block">
                                    Feature Status
                                </span>
                                <div class="bg-gray-50 border border-gray-200 px-6 py-5 transition-all duration-200 hover:border-[#E8B49E]/50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 mr-4">
                                            <label for="ai_enabled" class="text-sm text-gray-900 font-medium cursor-pointer block mb-1">
                                                Enable AI Visualization
                                            </label>
                                            <p class="text-xs text-gray-500 leading-relaxed">
                                                Activate AI-powered design visualization features across the entire platform
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <input type="hidden" name="ai_enabled" value="0">
                                            {{-- Toggle Switch --}}
                                            <label for="ai_enabled" class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" 
                                                       id="ai_enabled"
                                                       name="ai_enabled" 
                                                       value="1" 
                                                       {{ $settings['ai_enabled'] ? 'checked' : '' }}
                                                       class="sr-only peer">
                                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#E8B49E]/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#E8B49E] shadow-inner"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        {{-- AI Quota --}}
                        <div class="space-y-4">
                            <label for="ai_quota_limit" class="block">
                                <span class="text-xs uppercase tracking-[0.1em] text-gray-500 font-medium mb-3 block">
                                    User Quota Limit
                                </span>
                                <div class="relative">
                                    <input type="number" 
                                           id="ai_quota_limit" 
                                           name="ai_quota_limit" 
                                           value="{{ $settings['ai_quota_limit'] }}"
                                           min="1" 
                                           max="1000"
                                           class="block w-full px-5 py-4 pr-20 border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#E8B49E] focus:bg-white transition-all duration-200 text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                        <span class="text-xs uppercase tracking-wider text-gray-400 font-medium">prompts</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-3 leading-relaxed">
                                    Maximum number of AI generations allowed per user account
                                </p>
                            </label>
                        </div>

                    </div>

                    {{-- Info Box --}}
                    <div class="mt-8 lg:mt-10 bg-gradient-to-r from-[#FAFAFA] to-white border-l-2 border-[#E8B49E] px-6 py-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-[#E8B49E] mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-xs text-gray-600 leading-relaxed">
                                <strong class="text-gray-900">Note:</strong> Changes to AI settings will take effect immediately for all users. Quota limits help manage API usage and costs.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 pt-4">
                <a href="{{ route('home') }}" 
                   class="px-8 py-4 text-center border border-gray-300 text-gray-700 text-xs uppercase tracking-[0.15em] font-medium hover:border-gray-900 hover:text-gray-900 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-8 py-4 bg-[#E8B49E] text-white text-xs uppercase tracking-[0.15em] font-medium hover:bg-[#D4A088] transition-all duration-200 shadow-sm hover:shadow-md">
                    Save Changes
                </button>
            </div>
        </form>

    </div>
</div>

{{-- Custom Styles --}}
<style>
    /* Smooth transitions for form elements */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        opacity: 0.5;
    }

    input[type="number"]:hover::-webkit-inner-spin-button,
    input[type="number"]:hover::-webkit-outer-spin-button {
        opacity: 1;
    }

    /* Toggle switch animation */
    .peer:checked ~ div {
        background-color: #E8B49E;
    }
    
    .peer:focus ~ div {
        box-shadow: 0 0 0 3px rgba(232, 180, 158, 0.1);
    }

    /* Remove autofill styling */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0px 1000px #FAFAFA inset;
        transition: background-color 5000s ease-in-out 0s;
    }

    input:focus:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px white inset;
    }
</style>
@endsection