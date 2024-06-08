<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentLinkService
{
    public function generatePaymentLink($amount, $description, $remarks)
    {
        $payload = [
            'amount' => $amount,
            'description' => $description,
            'remarks' => $remarks,
        ];

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => 'Basic c2tfdGVzdF85NEVSZjk2dmhBMVhNRlI5SGtGZ0d2ZTc6',
            'content-type' => 'application/json',
        ])->post('https://api.paymongo.com/v1/links', [
            'data' => ['attributes' => $payload],
        ]);

        if ($response->failed()) {
            throw new \Exception("Failed to generate payment link: " . $response->body());
        }

        $decoded = $response->json();

        if (!isset($decoded['data']['attributes']['checkout_url'])) {
            throw new \Exception("Checkout URL not found in response");
        }

        return $decoded['data']['attributes']['checkout_url'];
    }
}

