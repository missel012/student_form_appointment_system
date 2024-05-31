<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use DateTime;

class GoogleCalendarController extends Controller
{
    protected $client;
    protected $calendar;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        $this->calendar = new Google_Service_Calendar($this->client);
    }

    public function redirectToGoogle()
    {
        return redirect($this->client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $code = $request->get('code');
        $token = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            return response()->json(['error' => $token['error_description']], 400);
        }

        $this->client->setAccessToken($token);

        // Store the token in the session
        Session::put('google_access_token', $token);

        return redirect()->route('show-appointment');
    }

    public function fetchEvents(Request $request)
    {
        $accessToken = Session::get('google_access_token');

        if (!$accessToken) {
            return redirect('/redirect');
        }

        $this->client->setAccessToken($accessToken);

        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = $this->client->getRefreshToken();
            $token = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            Session::put('google_access_token', $token);
            $this->client->setAccessToken($token);
        }

        $calendarId = 'primary';
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );

        $results = $this->calendar->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        return view('book_appointment', compact('events'));
    }

    public function showAppointmentForm()
    {
        return view('book_appointment');
    }

    public function bookAppointment(Request $request)
    {
        if (!$request->isMethod('post')) {
            abort(405); // Method Not Allowed
        }

        $accessToken = Session::get('google_access_token');

        if (!$accessToken) {
            return redirect('/redirect');
        }

        $this->client->setAccessToken($accessToken);

        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = $this->client->getRefreshToken();
            $token = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            Session::put('google_access_token', $token);
            $this->client->setAccessToken($token);
        }

        $validator = Validator::make($request->all(), [
            'appointment_for' => 'required|string',
            'appointment_time' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $appointmentTime = new DateTime($request->input('appointment_time'));
        $currentTime = new DateTime();
        $currentTime->modify('+3 days');

        if ($appointmentTime < $currentTime) {
            return response()->json(['message' => 'Appointment must be at least 3 days from now.'], 400);
        }

        $event = new Google_Service_Calendar_Event([
            'summary' => $request->input('appointment_for'),
            'start' => [
                'dateTime' => $appointmentTime->format(DateTime::RFC3339),
                'timeZone' => 'UTC',
            ],
            'end' => [
                'dateTime' => $appointmentTime->modify('+1 hour')->format(DateTime::RFC3339),
                'timeZone' => 'UTC',
            ],
        ]);

        $calendarId = 'primary';
        $createdEvent = $this->calendar->events->insert($calendarId, $event);
        $eventUrl = $createdEvent->htmlLink;

        return redirect($eventUrl);
    }
}
