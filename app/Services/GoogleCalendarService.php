<?php

namespace App\Services;

use GuzzleHttp\Client;

class GoogleCalendarService
{
    protected $clientId; // Google OAuth client ID
    protected $clientSecret; // Google OAuth client secret
    protected $redirectUri; // Redirect URI after OAuth authorization
    protected $client; // Guzzle HTTP client instance

    /**
     * Constructor to initialize Google OAuth credentials and Guzzle HTTP client.
     */
    public function __construct()
    {
        $this->clientId = env('GOOGLE_CLIENT_ID'); // Retrieve client ID from environment configuration
        $this->clientSecret = env('GOOGLE_CLIENT_SECRET'); // Retrieve client secret from environment configuration
        $this->redirectUri = env('GOOGLE_REDIRECT_URI'); // Retrieve redirect URI from environment configuration

        $this->client = new Client(); // Initialize Guzzle HTTP client
    }

    /**
     * Get the Google OAuth authorization URL.
     *
     * @return string  Google OAuth authorization URL
     */
    public function getAuthUrl()
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => 'https://www.googleapis.com/auth/calendar', // Scope for Google Calendar API
            'response_type' => 'code',
            'access_type' => 'offline', // Request offline access to receive refresh token
            'approval_prompt' => 'force', // Force user consent every time
        ];

        return 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params); // Build and return authorization URL
    }

    /**
     * Exchange authorization code for access token and refresh token.
     *
     * @param  string  $code  Authorization code received from OAuth authorization
     * @return array  Array containing access token and refresh token
     */
    public function authenticate($code)
    {
        $response = $this->client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'grant_type' => 'authorization_code', // OAuth grant type for exchanging code for tokens
            ],
        ]);

        return json_decode($response->getBody(), true); // Decode and return response body as array
    }

    /**
     * Create a new event in the specified Google Calendar.
     *
     * @param  string  $accessToken  Access token obtained from OAuth authentication
     * @param  string  $calendarId  ID of the Google Calendar to create the event in
     * @param  array  $eventData  Data for creating the event (title, start time, etc.)
     * @return array  Array containing created event details
     */
    public function createEvent($accessToken, $calendarId, $eventData)
    {
        $url = "https://www.googleapis.com/calendar/v3/calendars/{$calendarId}/events"; // API endpoint URL

        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken, // OAuth access token for authorization
                'Content-Type' => 'application/json', // JSON content type
            ],
            'json' => $eventData, // Event data in JSON format
        ]);

        return json_decode($response->getBody(), true); // Decode and return response body as array
    }

    /**
     * Delete an event from the specified Google Calendar.
     *
     * @param  string  $accessToken  Access token obtained from OAuth authentication
     * @param  string  $calendarId  ID of the Google Calendar containing the event
     * @param  string  $eventId  ID of the event to be deleted
     * @return bool  True if event deletion was successful, false otherwise
     */
    public function deleteEvent($accessToken, $calendarId, $eventId)
    {
        $url = "https://www.googleapis.com/calendar/v3/calendars/{$calendarId}/events/{$eventId}"; // API endpoint URL

        $response = $this->client->delete($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken, // OAuth access token for authorization
                'Content-Type' => 'application/json', // JSON content type
            ],
        ]);

        return $response->getStatusCode() === 204; // Return true if status code is 204 (No Content)
    }
}

