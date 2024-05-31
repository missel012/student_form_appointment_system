<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
    protected $client;
    protected $paymongoSecretKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->paymongoSecretKey = env('PAYMONGO_SECRET_KEY');
    }

    public function create(Request $request)
{
    // Validate request data
    $validator = Validator::make($request->all(), [
        'amount' => 'required|integer|min:100',
        'description' => 'required|string',
        'currency' => 'required|string|in:PHP',
        'payment_method' => 'required|in:gcash,card', // Ensure payment method is either 'gcash' or 'card'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    try {
        // Cast amount to integer
        $amount = (int) $request->input('amount');

        // Make request to Paymongo API to create a payment intent
        $response = $this->client->request('POST', 'https://api.paymongo.com/v1/payment_intents', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->paymongoSecretKey . ':'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'data' => [
                    'attributes' => [
                        'amount' => $amount,
                        'description' => $request->input('description'),
                        'currency' => $request->input('currency'),
                        'payment_method_allowed' => [$request->input('payment_method')],
                    ],
                ],
            ],
        ]);

        // Decode the response body
        $responseData = json_decode($response->getBody(), true);

        // Retrieve the client key from the response
        $clientKey = $responseData['data']['attributes']['client_key'];

        // Construct the redirect URL to Paymongo's checkout page
        $redirectUrl = 'https://paymongo.com/checkout/' . $clientKey;

        // Return the payment intent response and redirect URL in the response
        return response()->json([
            'payment_intent' => $responseData,
            'redirect_url' => $redirectUrl
        ], $response->getStatusCode());
    } catch (\Exception $e) {
        // Handle any errors
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    
}
