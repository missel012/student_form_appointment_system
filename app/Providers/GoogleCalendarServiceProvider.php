<?php

namespace App\Providers;

// Define the namespace for this class. It's part of the App\Providers namespace.

use Illuminate\Support\ServiceProvider;
use Google_Client;
use Google_Service_Calendar;

// Import necessary classes. ServiceProvider is a Laravel base class for service providers, Google_Client and Google_Service_Calendar are classes from Google's API client library.

class GoogleCalendarServiceProvider extends ServiceProvider
{
    // Define the GoogleCalendarServiceProvider class, which extends Laravel's base ServiceProvider class.

    public function register()
    {
        // The register method is used to bind classes or interfaces into the service container.

        $this->app->singleton(Google_Client::class, function ($app) {
            // Register a singleton binding for Google_Client. The closure returns an instance of Google_Client.

            $client = new Google_Client();
            // Create a new Google_Client instance.

            $client->setClientId(env('GOOGLE_CLIENT_ID'));
            // Set the client ID from the environment variable 'GOOGLE_CLIENT_ID'.

            $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            // Set the client secret from the environment variable 'GOOGLE_CLIENT_SECRET'.

            $client->setRedirectUri(env('APP_URL') . '/oauth2callback');
            // Set the redirect URI for OAuth2. This is a route where Google redirects after authentication.

            $client->addScope(Google_Service_Calendar::CALENDAR);
            // Add the Google Calendar scope to the client. This defines the permissions the app is requesting.

            $client->setAccessType('offline');
            // Set the access type to 'offline' to receive a refresh token, allowing the app to access Google services even when the user is not present.

            $client->setApprovalPrompt('force');
            // Force the user to approve the app again, even if they have already done so previously. This is useful if you need to re-authenticate the user.

            return $client;
            // Return the configured Google_Client instance.
        });

        $this->app->singleton(Google_Service_Calendar::class, function ($app) {
            // Register a singleton binding for Google_Service_Calendar. The closure returns an instance of Google_Service_Calendar.

            return new Google_Service_Calendar($app->make(Google_Client::class));
            // Create and return a new Google_Service_Calendar instance, using the Google_Client instance from the service container.
        });
    }
}
