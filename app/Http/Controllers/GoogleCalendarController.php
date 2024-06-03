<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCalendarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Event; 
use Carbon\Carbon;


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
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'calendar_id' => 'required|email', // Assuming calendar_id is an email address
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

        // Create the event in Google Calendar
        $event = $this->googleService->createEvent($accessToken, $calendarId, $eventData);

        
    // Save event details in the database
    if (!empty($event['id'])) {
        // Parse the datetime value using Carbon
        $appointmentDatetime = Carbon::parse($dateAndTime)->toDateTimeString();

        // Create a new event record in the events table
        $newEvent = new Event();
        $newEvent->google_calendar_event_id = $event['id'];
        $newEvent->email = $calendarId; // Store the email as the calendar ID
        $newEvent->appointment_for = $appointmentFor;
        $newEvent->appointment_datetime = $appointmentDatetime; // Use the formatted datetime value
        $newEvent->save();
    }


        // Return the response, including the event data if necessary
        return response()->json($event);
    }
        public function viewEvent($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        return response()->json($event);
    }

    public function updateEvent(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'appointment_for' => 'required',
            'date_and_time' => 'required|date_format:Y-m-d\TH:i:sP',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $eventData = [
            'appointment_for' => $request->input('appointment_for'),
            'date_and_time' => $request->input('date_and_time'),
        ];

        $event->update($eventData);

        return response()->json($event);
    }
    
    public function deleteEvent($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }

}