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
        // Register SendGrid as a singleton in the service container
        $this->app->singleton(SendGrid::class, function ($app) {
            return new SendGrid(env('SENDGRID_API_KEY')); // Create a new instance of SendGrid with the API key from environment variables
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
