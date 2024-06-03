<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCalendarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class GoogleCalendarController extends Controller
{
    protected $googleService;

    public function __construct(GoogleCalendarService $googleService)
    {
        $this->googleService = $googleService;
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->googleService->getAuthUrl();
    
        return new RedirectResponse($authUrl);
    }
    
    public function handleOAuthCallback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        $code = $request->input('code');
        $accessToken = $this->googleService->authenticate($code);

        if (isset($accessToken['error'])) {
            return response()->json(['error' => $accessToken['error']], 500);
        }

        return response()->json(['access_token' => $accessToken['access_token']]);
    }

    public function createEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'calendar_id' => 'required',
            'appointment_for' => 'required',
            'date_and_time' => 'required|date_format:Y-m-d\TH:i:sP',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
    
        $accessToken = $request->input('access_token');
        $calendarId = $request->input('calendar_id');
        $appointmentFor = $request->input('appointment_for');
        $dateAndTime = $request->input('date_and_time');
    
        // Build the event data array with only "Appointment for" and "Date and Time"
        $eventData = [
            'summary' => $appointmentFor,
            'start' => [
                'dateTime' => $dateAndTime,
            ],
            'end' => [
                'dateTime' => $dateAndTime,
            ],
        ];
    
        $event = $this->googleService->createEvent($accessToken, $calendarId, $eventData);
    
        return response()->json($event);
    }
}    