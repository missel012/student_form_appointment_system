<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ScheduledEmail;
use App\Services\MailService;
use Carbon\Carbon;

class MailController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function sendTestEmail(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'to_name' => 'string',
            'scheduled_datetime' => 'required|date_format:Y-m-d H:i',
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Retrieve input values from the request
        $to = $request->input('to');
        $toName = $request->input('to_name');
        $scheduledDatetime = Carbon::createFromFormat('Y-m-d H:i', $request->input('scheduled_datetime'));

        // Set the message for immediate email
        $immediateMessage = 'Your request for appointment has been confirmed. We will notify you on the day of scheduled appointment. Thank you!';

        // Set the message for scheduled email
        $scheduledMessage = 'Your appointment for Student Forms is today.';

        // Check if the scheduled datetime is in the future
        if ($scheduledDatetime->isFuture()) {
            // Queue the email for sending at the scheduled datetime
            ScheduledEmail::create([
                'to' => $to,
                'to_name' => $toName,
                'scheduled_datetime' => $scheduledDatetime,
                'message' => $scheduledMessage,
            ]);

            return response()->json(['status' => 'Email Scheduled', 'message' => 'The email has been scheduled for delivery.'], 200);
        } else {
            // Send the email immediately
            $this->mailService->sendEmail($to, $toName, 'sia.sfas2024@gmail.com', 'Studen Forms Appointment', 'Student Forms Appointment System', $immediateMessage, 'Your request for appointment has been confirmed. We will notify you on the day of scheduled appointment. Thank you!');
            
            return response()->json(['status' => 'Email Sent', 'message' => 'The email has been sent.'], 200);
        }
    }

    // This method should be called periodically by a scheduler or background job processing system
    public function processScheduledEmails()
    {
        // Get all scheduled emails where the scheduled datetime is in the past
        $scheduledEmails = ScheduledEmail::where('scheduled_datetime', '<=', Carbon::now())->get();

        foreach ($scheduledEmails as $email) {
            // Send the email
            $this->mailService->sendEmail($email->to, $email->to_name, 'datahan.marisol012@gmail.com', 'Student Forms Appointment', 'Student Forms Appointment System', $email->message, $email->message);

            // Delete the scheduled email from the database
            $email->delete();
        }
    }
}
