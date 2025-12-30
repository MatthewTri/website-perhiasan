<div {{ $attributes->merge(['class' => 'fixed top-0 left-0 w-full h-[3px] z-[9999] overflow-hidden bg-transparent pointer-events-none']) }}
     style="display: none;" 
     id="global-loader">
    
    {{-- Garis Loader --}}
    <div class="h-full bg-gray-900 absolute left-0 top-0 w-full origin-left animate-elegant-loading"></div>
</div>

<style>
    /* Animasi Custom agar terlihat seperti 'Shooting Star' atau loading bar modern */
    @keyframes elegant-loading {
        0% {
            transform: translateX(-100%);
        }
        50% {
            transform: translateX(0%); /* Melambat di tengah */
        }
        100% {
            transform: translateX(100%);
        }
    }

    .animate-elegant-loading {
        animation: elegant-loading 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }
</style>

<script>
    // ✅ Langsung override fetch tanpa menunggu DOMContentLoaded
    (function() {
        const loader = document.getElementById('global-loader') || 
                      document.querySelector('#global-loader');

        // Fungsi Global untuk memanggil loader
        window.showLoader = () => {
            const loaderEl = document.getElementById('global-loader');
            if (loaderEl) loaderEl.style.display = 'block';
        };

        window.hideLoader = () => {
            const loaderEl = document.getElementById('global-loader');
            if (loaderEl) {
                setTimeout(() => {
                    loaderEl.style.display = 'none';
                }, 500);
            }
        };

        // ✅ Override fetch LANGSUNG (sebelum DOMContentLoaded)
        const originalFetch = window.fetch;
        window.fetch = function() {
            window.showLoader();
            return originalFetch.apply(this, arguments)
                .then((response) => {
                    window.hideLoader();
                    return response;
                })
                .catch((error) => {
                    window.hideLoader();
                    throw error;
                });
        };
        
        // Listener Custom Event (jika ingin dipanggil manual)
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('loader:show', window.showLoader);
            window.addEventListener('loader:hide', window.hideLoader);
        });
    })();
</script>