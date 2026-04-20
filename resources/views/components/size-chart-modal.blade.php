{{-- Size Chart Modal Component --}}
<div id="sizeChartModal" class="hidden fixed inset-0 z-[9999] bg-black/60 flex items-center justify-center p-4 transition-opacity duration-300 opacity-0">
    <div class="bg-white w-full max-w-2xl shadow-2xl transform transition-all duration-300 scale-95 relative max-h-[90vh] overflow-y-auto">
        
        {{-- Close Button --}}
        <button type="button" onclick="closeSizeChart()" class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-900 transition-colors p-2 rounded-full hover:bg-gray-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Modal Content --}}
        <div class="p-8 md:p-12">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-normal uppercase tracking-[0.2em] text-gray-900 mb-2">Ring Size Guide</h2>
                <p class="text-sm text-gray-500 tracking-wider">Find your perfect fit</p>
            </div>

            {{-- Size Chart Image --}}
            <div class="bg-gray-50 p-6 mb-8 flex items-center justify-center">
                <img src="{{ asset('asset/size-chart.png') }}" 
                     alt="Ring Size Chart" 
                     class="w-full max-w-lg object-contain"
                     onerror="this.src='{{ asset('img/no-image.jpg') }}'">
            </div>

            {{-- Description --}}
            <div class="space-y-4 text-sm text-gray-600 leading-relaxed">
                <h3 class="text-base font-bold text-gray-900 uppercase tracking-wider mb-3">How to Measure Your Ring Size</h3>
                
                <p>
                    Finding the perfect ring size is essential for comfort and style. Our rings are crafted with precision, 
                    and we want to ensure you get the right fit. Follow these simple steps to determine your ring size accurately.
                </p>

                <div class="bg-gray-50 p-5 border-l-4 border-[#E3CFCF]">
                    <p class="font-medium text-gray-900 mb-2">Method 1: Use a Ring You Already Own</p>
                    <p class="text-xs">
                        Measure the inner diameter of a ring that fits you well. Compare the measurement with our size chart above 
                        to find your size. This is the most accurate method if you have a ring that fits perfectly.
                    </p>
                </div>

                <div class="bg-gray-50 p-5 border-l-4 border-[#E3CFCF]">
                    <p class="font-medium text-gray-900 mb-2">Method 2: Measure Your Finger</p>
                    <p class="text-xs">
                        Wrap a string or strip of paper around the base of your finger. Mark where the ends meet, 
                        then measure the length with a ruler. Use the measurement to find your size on the chart above. 
                        For the most accurate reading, measure your finger at the end of the day when it's at its largest.
                    </p>
                </div>

                <p class="text-xs text-gray-500 italic pt-4 border-t border-gray-200">
                    <strong>Note:</strong> If you're between sizes, we recommend choosing the larger size for comfort. 
                    Ring sizes can vary slightly between different finger widths. For custom sizing or additional assistance, 
                    please contact our customer service team.
                </p>
            </div>

            {{-- Action Button --}}
            <div class="mt-8 text-center">
                <button type="button" onclick="closeSizeChart()" 
                        class="px-8 py-3 bg-gray-900 text-white text-xs uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors">
                    Got It
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openSizeChart() {
        const modal = document.getElementById('sizeChartModal');
        if (!modal) return;
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Trigger transition
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
            modal.querySelector('div').classList.add('scale-100');
        });
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeSizeChart() {
        const modal = document.getElementById('sizeChartModal');
        if (!modal) return;
        
        // Trigger exit transition
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.remove('scale-100');
        modal.querySelector('div').classList.add('scale-95');
        
        // Hide after transition
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('sizeChartModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeSizeChart();
            }
        }
    });

    // Close on backdrop click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('sizeChartModal');
        if (e.target === modal) {
            closeSizeChart();
        }
    });
</script>
