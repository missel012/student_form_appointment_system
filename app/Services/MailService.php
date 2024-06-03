<?php

namespace App\Services;

use SendGrid\Mail\Mail;
use SendGrid;
use Exception;

class MailService
{
    protected $sendGrid;

    public function __construct(SendGrid $sendGrid)
    {
        $this->sendGrid = $sendGrid;
    }

    public function sendEmail($to, $toName, $from, $fromName, $subject, $plainTextContent, $htmlContent)
    {
        $email = new Mail();
        $email->setFrom($from, $fromName);
        $email->setSubject($subject);
        $email->addTo($to, $toName);
        $email->addContent("text/plain", $plainTextContent);
        $email->addContent("text/html", $htmlContent);

        try {
            $response = $this->sendGrid->send($email);
            return $response->statusCode();
        } catch (Exception $e) {
            return 'Caught exception: '. $e->getMessage();
        }
    }
}
