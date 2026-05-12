<?php

namespace App\Providers;

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
        // 本番環境（Railwayなど）の場合のみHTTPSを強制
        if (config('app.env') === 'production' || config('app.env') === 'staging') {
            URL::forceScheme('https');
        }
    }

}
