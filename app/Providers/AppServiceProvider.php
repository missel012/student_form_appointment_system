<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PaymentLinkService::class, function ($app) {
            return new PaymentLinkService();
        });
    }

    public function boot()
    {
        //
    }
}
