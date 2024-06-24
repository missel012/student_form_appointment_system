<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentLinkService
{
    /**
     * Generate a payment link using Paymongo API.
     *
     * @param  float  $amount  The amount of the payment link
     * @param  string  $description  Description of the payment link
     * @param  string  $remarks  Remarks or additional information for the payment link
     * @return string  The checkout URL of the generated payment link
     * @throws \Exception  If the request to Paymongo API fails or the checkout URL is not found in the response
     */
    public function generatePaymentLink($amount, $description, $remarks)
    {
        $payload = [
            'amount' => $amount,
            'description' => $description,
            'remarks' => $remarks,
        ];

        // Send POST request to Paymongo API to generate payment link
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => 'Basic c2tfdGVzdF85NEVSZjk2dmhBMVhNRlI5SGtGZ0d2ZTc6', // Example authorization header
            'content-type' => 'application/json',
        ])->post('https://api.paymongo.com/v1/links', [
            'data' => ['attributes' => $payload],
        ]);

        // Handle error if request fails
        if ($response->failed()) {
            throw new \Exception("Failed to generate payment link: " . $response->body());
        }

        $decoded = $response->json();

        // Verify if checkout URL exists in the response
        if (!isset($decoded['data']['attributes']['checkout_url'])) {
            throw new \Exception("Checkout URL not found in response");
        }

        // Return the checkout URL from the response
        return $decoded['data']['attributes']['checkout_url'];
    }
}

