<?php

namespace App;

use GuzzleHttp\Client;

class PaymongoAPI
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function createCheckoutSession()
    {
        $response = $this->client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ]
        ]);
        return $response->getBody();
    }

    public function retrieveCheckoutSession($sessionId)
    {
        $response = $this->client->request('GET', 'https://api.paymongo.com/v1/checkout_sessions/' . $sessionId, [
            'headers' => [
                'accept' => 'application/json',
            ]
        ]);
        return $response->getBody();
    }

    public function createPaymentIntent()
    {
        $response = $this->client->request('POST', 'https://api.paymongo.com/v1/payment_intents', [
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ]
        ]);
        return $response->getBody();
    }

    public function retrievePaymentIntent($paymentIntentId)
    {
        $response = $this->client->request('GET', 'https://api.paymongo.com/v1/payment_intents/' . $paymentIntentId, [
            'headers' => [
                'accept' => 'application/json',
            ]
        ]);
        return $response->getBody();
    }

    public function attachToPaymentIntent($paymentIntentId)
    {
        $response = $this->client->request('POST', 'https://api.paymongo.com/v1/payment_intents/' . $paymentIntentId . '/attach', [
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ]
        ]);
        return $response->getBody();
    }
}
