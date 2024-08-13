<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GoogleOAuth;
use Google\Client as GoogleClient;

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
        //
    }

    
}
