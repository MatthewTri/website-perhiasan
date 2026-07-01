<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        View::composer('*', function ($view) {
            $paypalEnabled = Setting::isPaypalEnabled();

            // Ambil dari session, paksa IDR saat PayPal dimatikan
            $currency = $paypalEnabled ? session('currency', 'IDR') : 'IDR';
            // NOTE: For UI display we use currency code (no symbol)
            $currencySymbol = $currency === 'USD' ? 'USD' : 'IDR';
            
            $view->with([
                'globalCurrency' => $currency,
                'globalCurrencySymbol' => $currencySymbol,
                'paypalEnabled' => $paypalEnabled,
            ]);
        });

        Blade::directive('currency', function ($expression) {
            return "<?php 
                \$currency = \\App\\Models\\Setting::isPaypalEnabled() ? session('currency', 'IDR') : 'IDR';
                \$prices = {$expression};
                if (\$currency === 'USD') {
                    echo 'USD ' . number_format(\$prices['usd'], 2, '.', ',');
                } else {
                    echo 'IDR ' . number_format(\$prices['idr'], 2, ',', '.');
                }
            ?>";
        });

        Blade::directive('currencyTotal', function ($expression) {
            return "<?php 
                \$currency = \\App\\Models\\Setting::isPaypalEnabled() ? session('currency', 'IDR') : 'IDR';
                \$data = {$expression};
                \$total = \$currency === 'USD' 
                    ? \$data['usd'] * \$data['qty']
                    : \$data['idr'] * \$data['qty'];
                if (\$currency === 'USD') {
                    echo 'USD ' . number_format(\$total, 2, '.', ',');
                } else {
                    echo 'IDR ' . number_format(\$total, 2, ',', '.');
                }
            ?>";
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Admin Gate
        Gate::define('admin', function ($user) {
            return $user->is_admin === true || $user->is_admin === 1;
        });

        // Share categories with navbar
        View::composer('partials.navbar', function ($view) {
            $categories = \App\Models\Category::whereNull('parent_id')
                ->with('children')
                ->orderBy('name')
                ->get();
            $view->with('navCategories', $categories);
        });
    }
}
