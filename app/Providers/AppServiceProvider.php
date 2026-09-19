<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Railway (dan layanan hosting sejenis) menghubungkan ke aplikasi kita
        // lewat HTTP di belakang layar walau alamat luarnya sudah HTTPS.
        // Baris ini memaksa semua link/form yang dibuat Laravel pakai HTTPS,
        // supaya tidak muncul peringatan "connection not secure" di browser.
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}