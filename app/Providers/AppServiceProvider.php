<?php

namespace App\Providers {

    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\ServiceProvider;

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            //
        }

        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            Blade::directive('currency', function ($expression) {
                return "<?php echo (auth()->check() ? auth()->user()->currency ?? '$' : '$') . number_format($expression, 2); ?>";
            });

            Blade::directive('currencySymbol', function () {
                return "<?php echo auth()->check() ? auth()->user()->currency ?? '$' : '$'; ?>";
            });
        }
    }
}

namespace {
    use App\Models\User;

    if (! function_exists('currency_symbol')) {
        function currency_symbol($userId = null)
        {
            if ($userId) {
                $user = User::find($userId);

                return $user->currency ?? '$';
            }

            return auth()->check() ? auth()->user()->currency ?? '$' : '$';
        }
    }

    if (! function_exists('format_currency')) {
        function format_currency($amount, $userId = null)
        {
            return currency_symbol($userId).' '.number_format($amount, 2);
        }
    }
}
