<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentSetting;

class PayTRService
{
    private string $merchantId;
    private string $merchantKey;
    private string $merchantSalt;
    private bool $testMode;

    public function __construct()
    {
        $this->merchantId = PaymentSetting::get('paytr_merchant_id', '');
        $this->merchantKey = PaymentSetting::get('paytr_merchant_key', '');
        $this->merchantSalt = PaymentSetting::get('paytr_merchant_salt', '');
        $this->testMode = (bool) PaymentSetting::get('paytr_test_mode', true);
    }

    public function createPayment(Order $order): array
    {
        $user = $order->user;
        
        $paytrData = [
            'merchant_id' => $this->merchantId,
            'user_ip' => request()->ip(),
            'merchant_oid' => $order->order_number,
            'email' => $user->email,
            'payment_amount' => (int)($order->final_amount * 100), // Convert to kurus
            'user_basket' => base64_encode(json_encode($this->getUserBasket($order))),
            'no_installment' => 1,
            'max_installment' => 0,
            'user_name' => $user->name,
            'user_address' => 'N/A',
            'user_phone' => 'N/A',
            'merchant_ok_url' => route('checkout.success'),
            'merchant_fail_url' => route('checkout.failed'),
            'timeout_limit' => 30,
            'debug_on' => $this->testMode ? 1 : 0,
            'test_mode' => $this->testMode ? 1 : 0,
            'lang' => 'tr',
            'currency' => 'TL',
        ];

        // Generate token
        $hashStr = $this->merchantId . $user->ip() . $order->order_number . $user->email . 
                   $paytrData['payment_amount'] . $paytrData['user_basket'] . 
                   ($paytrData['no_installment'] ?? 1) . ($paytrData['max_installment'] ?? 0) . 
                   $paytrData['currency'] . $paytrData['test_mode'];
        
        $paytrData['paytr_token'] = base64_encode(hash_hmac('sha256', $hashStr . $this->merchantSalt, $this->merchantKey, true));

        return $paytrData;
    }

    private function getUserBasket(Order $order): array
    {
        $basket = [];
        foreach ($order->items as $item) {
            $basket[] = [
                $item->coinPackage->title,
                (int)($item->price * 100),
                $item->quantity
            ];
        }
        return $basket;
    }

    public function verifyCallback(array $data): bool
    {
        $hash = base64_encode(hash_hmac('sha256', 
            $data['merchant_oid'] . $this->merchantSalt . $data['status'] . $data['total_amount'], 
            $this->merchantKey, 
            true
        ));

        return $hash === $data['hash'];
    }
}
