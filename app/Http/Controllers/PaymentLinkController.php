<?php

namespace App\Http\Controllers;

use App\Services\PaymentLinkService;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
        // Validate request inputs
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'amount' => 'required|numeric|min:50', // Keep the minimum amount to PHP 50.00
            'payment_mode' => 'required|in:Online,Onsite',
            'payer_name' => 'required|string',
        ]);

        // Check validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Extract validated data from the request
        $email = $request->input('email');
        $amount = $request->input('amount');
        $paymentMode = $request->input('payment_mode');
        $payerName = $request->input('payer_name');

        // Convert amount to centavos
        $amountInCents = (float)$amount * 100;

        // Adjust amount to meet payment gateway minimum requirement
        $adjustedAmountInCents = max($amountInCents, 10000); // Ensure at least PHP 100.00

        try {
            // Generate transaction ID
            $transactionId = 'txn_' . Str::random(12); // Generates a random alphanumeric string of length 12

            // Save payment information to the database
            $payment = Payment::create([
                'email' => $email,
                'amount' => $amountInCents, // Save original amount
                'payment_mode' => $paymentMode,
                'payer_name' => $payerName,
                'transaction_id' => $transactionId,
            ]);

            $response = [
                'transaction_id' => $transactionId,
                "payer_name" => $payerName,
                "email" => $email,
                "amount" => $amount,
                "payment_mode" => $paymentMode,
            ];

            // Set the message based on payment mode
            if ($paymentMode === 'Online') {
                $link = $this->paymentLinkService->generatePaymentLink($adjustedAmountInCents, $paymentMode, $transactionId);
                $response["message"] = "Successfully created payment. Please proceed to checkout";
                $response["checkout_here"] = $link;    
            } else {
                $response["message"] = "Please pay at the counter";
            }

            // Return the response
            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::error('Failed to generate payment link', ['error' => $e->getMessage()]);
            return response()->json(["error" => "Failed to generate payment link: " . $e->getMessage()], 500);
        }
    }

    public function viewPayment($transactionId)
    {
        try {
            // Retrieve the payment from the database based on transaction_id
            $payment = Payment::where('transaction_id', $transactionId)->first();

            if (!$payment) {
                return response()->json(["error" => "Payment not found"], 404);
            }

            // Return the payment details
            return response()->json(["payment" => $payment], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve payment', ['error' => $e->getMessage()]);
            return response()->json(["error" => "Failed to retrieve payment: " . $e->getMessage()], 500);
        }
    }
}
