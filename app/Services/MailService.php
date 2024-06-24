<?php

namespace App\Services;

use SendGrid\Mail\Mail;
use SendGrid;
use Exception;

class MailService
{
    protected $sendGrid; // Instance of SendGrid client

    public function __construct(SendGrid $sendGrid)
    {
        $this->sendGrid = $sendGrid; // Injected SendGrid client instance
    }

    /**
     * Send an email using SendGrid.
     *
     * @param  string  $to  Email address of the recipient
     * @param  string  $toName  Name of the recipient
     * @param  string  $from  Email address of the sender
     * @param  string  $fromName  Name of the sender
     * @param  string  $subject  Email subject
     * @param  string  $plainTextContent  Plain text content of the email
     * @param  string  $htmlContent  HTML content of the email
     * @return int|string  HTTP status code of the email sending attempt or error message
     */
    public function sendEmail($to, $toName, $from, $fromName, $subject, $plainTextContent, $htmlContent)
    {
        $email = new Mail(); // Create a new SendGrid Mail object
        $email->setFrom($from, $fromName); // Set the sender's email and name
        $email->setSubject($subject); // Set the email subject
        $email->addTo($to, $toName); // Add recipient's email and name
        $email->addContent("text/plain", $plainTextContent); // Add plain text content
        $email->addContent("text/html", $htmlContent); // Add HTML content

        try {
            $response = $this->sendGrid->send($email); // Send the email via SendGrid
            return $response->statusCode(); // Return the HTTP status code of the response
        } catch (Exception $e) {
            return 'Caught exception: ' . $e->getMessage(); // Return error message if an exception occurs
        }
    }
}

