<?php

namespace App\Http\Controllers;

use App\Services\PaymentLinkService;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentLinkController extends Controller
{
    protected $paymentLinkService;

    public function __construct(PaymentLinkService $paymentLinkService)
    {
        $this->paymentLinkService = $paymentLinkService;
    }

    public function createLink(Request $request)
    {
        Log::info('Request received', $request->all());

        // Extract payment details from the request
        $paymentMethod = $request->input('paymentMethod');
        $amount = $request->input('amount');
        $payerName = $request->input('payerName');

        // Validate amount
        if (!is_numeric($amount) || (float)$amount < 100) {
            return response()->json(["error" => "Amount must be a numeric value of at least Php 100.00"], 400);
        }

        // Convert amount to centavos
        $amountInCents = (float)$amount * 100;

        // Check payment method and set appropriate descriptions
        if ($paymentMethod === 'cash') {
            return response()->json(["message" => "Please pay at the counter."]);
        } elseif ($paymentMethod === 'gcash' || $paymentMethod === 'card_payment') {
            try {
                // Save payment information to the database
                $payment = Payment::create([
                    'amount' => $amountInCents, // Save amount in centavos
                    'payment_method' => $paymentMethod,
                    'payer_name' => $payerName,
                ]);

                // Generate the payment link
                $link = $this->paymentLinkService->generatePaymentLink($amountInCents, $paymentMethod, 'payment');

                // Return the generated payment link along with payment ID
                return response()->json(["link" => $link, "payment_id" => $payment->id], 200);
            } catch (\Exception $e) {
                Log::error('Failed to generate payment link', ['error' => $e->getMessage()]);
                return response()->json(["error" => "Failed to generate payment link: " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["error" => "Invalid payment method"], 400);
        }
    }

    public function viewPayment($id)
    {
        try {
            // Retrieve the payment from the database
            $payment = Payment::findOrFail($id);

            // Return the payment details
            return response()->json(["payment" => $payment], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve payment', ['error' => $e->getMessage()]);
            return response()->json(["error" => "Failed to retrieve payment: " . $e->getMessage()], 500);
        }
    }

}
