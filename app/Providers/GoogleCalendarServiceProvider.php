<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Google_Client::class, function ($app) {
            $client = new Google_Client();
            $client->setClientId(env('GOOGLE_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            $client->setRedirectUri(env('APP_URL') . '/oauth2callback'); // Adjust as per your environment configuration
            $client->addScope(Google_Service_Calendar::CALENDAR);
            // Optionally set access type and prompt
            $client->setAccessType('offline'); // Allows for refresh tokens
            $client->setApprovalPrompt('force'); // Forces to show consent screen every time

            return $client;
        });

        $this->app->singleton(Google_Service_Calendar::class, function ($app) {
            return new Google_Service_Calendar($app->make(Google_Client::class));
        });
    }
}
