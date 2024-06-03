<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\MailService;

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
            'message' => 'string',
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Retrieve input values from the request
        $to = $request->input('to');
        $toName = $request->input('to_name');
        $from = 'datahan.marisol012@gmail.com';
        $fromName = 'Marisol Datahan';
        $subject = 'Student Forms Appointment';
        $plainTextContent = $request->input('message', 'This is an email for Student Forms Appointment.'); 
        $htmlContent = $request->input('message', '<strong>Student Forms Appointment System</strong>'); 
        $message = $request->input('message', 'This is a test email.');

        // Send the test email
        $response = $this->mailService->sendEmail($to, $toName, $from, $fromName, $subject, $plainTextContent, $htmlContent, $message);

        // Check the response status and provide appropriate feedback
        switch ($response) {
            case 404:
                return response()->json(['status' => 'Can\'t Find User', 'message' => 'The recipient email address was not found.'], 404);
            case 202:
                return response()->json(['status' => 'Email Sent Successfully', 'message' => 'The email has been sent successfully.'], 200);
            case 400:
                return response()->json(['status' => 'Can\'t Deliver Message', 'message' => 'There was an issue delivering the email.'], 400);
            default:
                return response()->json(['status' => 'Unknown Error', 'message' => 'An unknown error occurred.'], 500);
        }
    }
}
