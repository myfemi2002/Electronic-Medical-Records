<?php

namespace App\Services;

use App\Models\WalletDeposit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    public function initiatePaystackPayment($deposit)
    {
        $payload = [
            'email' => $deposit->customer_email,
            'amount' => $deposit->amount * 100, // Convert to kobo
            'reference' => $deposit->gateway_reference,
            'callback_url' => route('user.wallet.deposit.callback.paystack'),
            'metadata' => [
                'deposit_id' => $deposit->deposit_id,
                'user_id' => $deposit->user_id,
                'custom_fields' => [
                    [
                        'display_name' => 'Deposit ID',
                        'variable_name' => 'deposit_id',
                        'value' => $deposit->deposit_id
                    ]
                ]
            ]
        ];

        $response = Http::withToken(config('services.paystack.secret_key'))
            ->post('https://api.paystack.co/transaction/initialize', $payload);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'authorization_url' => $data['data']['authorization_url'],
                'access_code' => $data['data']['access_code']
            ];
        }

        Log::error('Paystack initialization failed', $response->json());
        return ['success' => false, 'message' => 'Payment initialization failed'];
    }

    public function initiateFlutterwavePayment($deposit)
    {
        $payload = [
            'tx_ref' => $deposit->gateway_reference,
            'amount' => $deposit->amount,
            'currency' => 'NGN',
            'redirect_url' => route('user.wallet.deposit.callback.flutterwave'),
            'customer' => [
                'email' => $deposit->customer_email,
                'name' => $deposit->user->name,
                'phonenumber' => $deposit->user->phone ?? ''
            ],
            'customizations' => [
                'title' => 'Wallet Deposit',
                'description' => 'Deposit to wallet - ' . $deposit->deposit_id,
                'logo' => asset('images/logo.png')
            ],
            'meta' => [
                'deposit_id' => $deposit->deposit_id,
                'user_id' => $deposit->user_id
            ]
        ];

        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->post('https://api.flutterwave.com/v3/payments', $payload);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'payment_link' => $data['data']['link']
            ];
        }

        Log::error('Flutterwave initialization failed', $response->json());
        return ['success' => false, 'message' => 'Payment initialization failed'];
    }

    public function verifyPaystackPayment($reference)
    {
        $response = Http::withToken(config('services.paystack.secret_key'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function verifyFlutterwavePayment($transactionId)
    {
        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}