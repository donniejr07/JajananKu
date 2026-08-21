<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Transaction;
use App\Observers\CategoryObserver;
use App\Observers\TransactionObserver;
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
        Transaction::observe(TransactionObserver::class);
        Category::observe(CategoryObserver::class);

        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp' . number_format((float) $expression, 0, ',', '.'); ?>";
        });
    }
}
