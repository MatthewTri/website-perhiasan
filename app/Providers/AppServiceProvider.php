<?php

namespace App\Providers;

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
            // Ambil dari session, default IDR
            $currency = session('currency', 'IDR');
            $currencySymbol = $currency === 'USD' ? '$' : 'Rp';
            
            $view->with([
                'globalCurrency' => $currency,
                'globalCurrencySymbol' => $currencySymbol,
            ]);
        });

        Blade::directive('currency', function ($expression) {
            return "<?php 
                \$currency = session('currency', 'IDR');
                \$prices = {$expression};
                if (\$currency === 'USD') {
                    echo '$' . number_format(\$prices['usd'], 2);
                } else {
                    echo 'Rp ' . number_format(\$prices['idr'], 2, ',', '.');
                }
            ?>";
        });

        Blade::directive('currencyTotal', function ($expression) {
            return "<?php 
                \$currency = session('currency', 'IDR');
                \$data = {$expression};
                \$total = \$currency === 'USD' 
                    ? \$data['usd'] * \$data['qty']
                    : \$data['idr'] * \$data['qty'];
                if (\$currency === 'USD') {
                    echo '$' . number_format(\$total, 2);
                } else {
                    echo 'Rp ' . number_format(\$total, 0, ',', '.');
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
    }
}
