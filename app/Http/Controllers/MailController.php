<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function sendEmail(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'to_email' => 'required|email',
            'to_name' => 'nullable|string',
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Retrieve input values from the request
        $to = $request->input('to_email'); // Corrected to match validator rule
        $toName = $request->input('to_name') ?? ''; // Default to empty string if to_name is null

        // Format the confirmation message
        $confirmationMessage = "Hi $toName, your request for appointment has been confirmed. Thank you for choosing Student Forms Appointment System!";

        // Send the immediate email
        $this->mailService->sendEmail(
            $to,
            $toName,
            'sia.sfas2024@gmail.com',
            'Student Forms Appointment System',
            'Confirmation Email',
            $confirmationMessage,
            $confirmationMessage
        );

        // Return response indicating email sent
        return response()->json(['status' => 'Email Sent', 'message' => 'The confirmation email has been sent.'], 200);
    }
}
