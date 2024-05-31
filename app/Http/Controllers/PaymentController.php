<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.paymongo.com/v1/',
            'auth' => [env('PAYMONGO_SECRET_KEY'), '']
        ]);
    }

    public function createPaymentIntent(Request $request)
    {
        $response = $this->client->post('payment_intents', [
            'json' => [
                'data' => [
                    'attributes' => [
                        'amount' => $request->input('amount') * 100, 
                        'payment_method_allowed' => ['card', 'gcash'],
                        'currency' => 'PHP',
                        'capture_type' => 'automatic'
                    ]
                ]
            ]
        ]);

        return response()->json(json_decode($response->getBody(), true));
    }

    // Other methods to handle payment retrieval, updates, etc.
}
