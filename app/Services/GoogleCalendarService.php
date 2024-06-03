<?php

namespace App\Services;

use GuzzleHttp\Client;

class GoogleCalendarService
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $client;

    public function __construct()
    {
        $this->clientId = env('GOOGLE_CLIENT_ID');
        $this->clientSecret = env('GOOGLE_CLIENT_SECRET');
        $this->redirectUri = env('GOOGLE_REDIRECT_URI');

        $this->client = new Client();
    }

    public function getAuthUrl()
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => 'https://www.googleapis.com/auth/calendar',
            'response_type' => 'code',
            'access_type' => 'offline',
            'approval_prompt' => 'force',
        ];

        return 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
    }

    public function authenticate($code)
    {
        $response = $this->client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'grant_type' => 'authorization_code',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function createEvent($accessToken, $calendarId, $eventData)
    {
        $url = "https://www.googleapis.com/calendar/v3/calendars/{$calendarId}/events";

        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $eventData,
        ]);

        return json_decode($response->getBody(), true);
    }
}
