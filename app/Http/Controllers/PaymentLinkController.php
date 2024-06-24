<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Import Validator facade for data validation
use App\Services\MailService; // Import MailService for sending emails
use Carbon\Carbon; // Import Carbon for date manipulation
use Illuminate\Support\Facades\Log; // Import Log facade for logging

class MailController extends Controller
{
    protected $mailService; // Protected property to hold MailService instance

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService; // Inject MailService instance via constructor
    }

    public function sendEmail(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'to_email' => 'required|email', // Validate 'to_email' field as required and must be an email
            'to_name' => 'nullable|string', // 'to_name' field is optional and must be a string if provided
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Retrieve input values from the request
        $to = $request->input('to_email'); // Get 'to_email' value from request
        $toName = $request->input('to_name') ?? ''; // Get 'to_name' value or default to empty string if null

        // Format the confirmation message
        $confirmationMessage = "Hi $toName, your request for appointment has been confirmed. Thank you for choosing Student Forms Appointment System!";

        // Send the immediate email using MailService
        $this->mailService->sendEmail(
            $to, // Recipient email address
            $toName, // Recipient name (if provided)
            'sia.sfas2024@gmail.com', // Sender email address
            'Student Forms Appointment System', // Sender name
            'Confirmation Email', // Email subject
            $confirmationMessage, // Email body
            $confirmationMessage // Alternative email body (not used in this case)
        );

        // Return response indicating email sent successfully
        return response()->json(['status' => 'Email Sent', 'message' => 'The confirmation email has been sent.'], 200);
    }
}
