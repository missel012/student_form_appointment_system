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
        // Redirect to Google OAuth authentication URL
        $authUrl = $this->googleService->getAuthUrl();
    
        return new RedirectResponse($authUrl);
    }
    
    public function handleOAuthCallback(Request $request)
    {
        // Validate incoming request for OAuth callback
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            // Return error response if validation fails
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        $code = $request->input('code');
        // Authenticate and obtain access token using the provided authorization code
        $accessToken = $this->googleService->authenticate($code);

        if (isset($accessToken['error'])) {
            // Return error response if authentication fails
            return response()->json(['error' => $accessToken['error']], 500);
        }

        // Return access token on successful authentication
        return response()->json(['access_token' => $accessToken['access_token']]);
    }

    public function createEvent(Request $request)
    {
        // Validate the request data for creating a new event
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'calendar_id' => 'required|email', // Assuming calendar_id is an email address
            'appointment_for' => 'required',
            'date_and_time' => 'required|date_format:Y-m-d\TH:i:sP',
        ]);

        if ($validator->fails()) {
            // Return error response if validation fails
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $accessToken = $request->input('access_token');
        $calendarId = $request->input('calendar_id');
        $appointmentFor = $request->input('appointment_for');
        $dateAndTime = $request->input('date_and_time');

        // Build the event data array with summary, start and end date/time
        $eventData = [
            'summary' => $appointmentFor,
            'start' => [
                'dateTime' => $dateAndTime,
            ],
            'end' => [
                'dateTime' => $dateAndTime,
            ],
        ];

        // Create the event in Google Calendar using Google Calendar service
        $event = $this->googleService->createEvent($accessToken, $calendarId, $eventData);

        // Save event details in the database if event creation was successful
        if (!empty($event['id'])) {
            // Parse the datetime value using Carbon for database storage
            $appointmentDatetime = Carbon::parse($dateAndTime)->toDateTimeString();

            // Create a new event record in the events table
            $newEvent = new Event();
            $newEvent->google_calendar_event_id = $event['id'];
            $newEvent->email = $calendarId; // Store the email as the calendar ID
            $newEvent->appointment_for = $appointmentFor;
            $newEvent->appointment_datetime = $appointmentDatetime; // Use the formatted datetime value
            $newEvent->save();

            // Return the response with ID included
            return response()->json(['event' => $event, 'id' => $newEvent->id]);
        }

        // Return the response, including the event data if necessary
        return response()->json($event);
    }

    public function viewEvent($id)
    {
        // Retrieve event details by ID from the events table
        $event = Event::find($id);

        if (!$event) {
            // Return error response if event is not found
            return response()->json(['error' => 'Event not found'], 404);
        }

        // Return event details as JSON response
        return response()->json($event);
    }

    public function updateEvent(Request $request, $id)
    {
        // Retrieve event by ID for updating
        $event = Event::find($id);

        if (!$event) {
            // Return error response if event is not found
            return response()->json(['error' => 'Event not found'], 404);
        }

        // Validate the request data for updating event details
        $validator = Validator::make($request->all(), [
            'appointment_for' => 'required',
            'date_and_time' => 'required|date_format:Y-m-d\TH:i:sP',
        ]);

        if ($validator->fails()) {
            // Return error response if validation fails
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Update event data with new appointment information
        $eventData = [
            'appointment_for' => $request->input('appointment_for'),
            'date_and_time' => $request->input('date_and_time'),
        ];

        // Save updated event data
        $event->update($eventData);

        // Return the response with updated event details and ID included
        return response()->json(['event' => $event, 'id' => $event->id]);
    }
    
    public function deleteEvent($id)
    {
        // Retrieve event by ID for deletion
        $event = Event::find($id);

        if (!$event) {
            // Return error response if event is not found
            return response()->json(['error' => 'Event not found'], 404);
        }

        // Delete the event
        $event->delete();

        // Return success message after deletion
        return response()->json(['message' => 'Event deleted successfully']);
    }

}
