<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Import Session facade

class GoogleCalendarController extends Controller
{
    protected $client;
    protected $calendar;

    public function __construct(Google_Client $client, Google_Service_Calendar $calendar)
    {
        $this->client = $client;
        $this->calendar = $calendar;
    }

    public function redirectToGoogle()
    {
        return redirect($this->client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $code = $request->get('code');
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        $this->client->setAccessToken($token);

        // Store the token in the session
        Session::put('google_access_token', $token);

        return redirect('/api/calendar/events'); // Adjust this as necessary
    }

    public function fetchEvents(Request $request)
    {
        // Retrieve the access token from the session
        $accessToken = Session::get('google_access_token');

        // Perform logic to fetch events from the Google Calendar API using the access token

        // Return a response (for testing)
        return response()->json(['message' => 'Fetching events from Google Calendar...']);
    }
}
