<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SendGrid;

class SendGridServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SendGrid::class, function ($app) {
            return new SendGrid(env('SENDGRID_API_KEY'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
