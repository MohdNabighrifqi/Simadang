<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use App\Services\LaporanService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LaporanService::class);
    }

    public function boot(): void
    {
        // Locale Carbon ke Bahasa Indonesia
        \Carbon\Carbon::setLocale('id');

        // Gunakan pagination sederhana (tanpa Tailwind/Bootstrap)
        Paginator::useBootstrap();

        // Strict mode di development
        Model::shouldBeStrict(! app()->isProduction());
    }
}
