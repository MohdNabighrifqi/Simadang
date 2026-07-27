<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use App\Services\LaporanService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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

        // Render (dan PaaS lain) terminasi HTTPS di reverse proxy, jadi Laravel
        // melihat request masuk sebagai HTTP biasa — paksa skema https di production
        // supaya asset()/url() tidak generate link http:// yang diblokir browser (mixed content).
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
