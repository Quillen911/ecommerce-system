<?php

namespace App\Services\Order\Services;

use App\Services\Order\Contracts\PaymentInterface;
use App\Services\Payments\IyzicoPaymentService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CreditCard;

class PaymentService implements PaymentInterface
{

    protected $iyzicoPaymentService;

    public function __construct(IyzicoPaymentService $iyzicoPaymentService)
    {
        $this->iyzicoPaymentService = $iyzicoPaymentService;
    }

    public function processPayment(Order $order, CreditCard $creditCard, float $amount): array
    {
        return $this->iyzicoPaymentService->processPayment($order, $creditCard, $amount);
    }

    public function handlePaymentSuccess(Order $order, array $paymentResult): void
    {
        $order->update([
            'paid_price'      => $paymentResult['paid_price'] ?? 0,
            'currency'        => $paymentResult['currency'] ?? 'TRY',
            'payment_id'      => $paymentResult['payment_id'] ?? null,
            'conversation_id' => $paymentResult['conversation_id'] ?? null,
            'payment_status'  => $paymentResult['payment_status'],
            'status'          => 'confirmed',
        ]);

        if (isset($paymentResult['payment_transaction_id'])) {
            foreach ($paymentResult['payment_transaction_id'] as $itemId => $txId){
                $orderItem = OrderItem::where('order_id', $order->id)
                    ->where('product_id', $itemId)
                    ->first();
                
                if ($orderItem) {
                    $orderItem->update([
                        'payment_transaction_id' => $txId,
                        'payment_status' => $paymentResult['payment_status'],
                        'status' => 'confirmed',
                    ]);
                }
            }
        }
    }
    public function handlePaymentFailed(Order $order, string $error, ?string $errorCode = null): void
    {
        $order->update([
            'payment_status' => 'failed',
            'status' => 'Başarısız Ödeme',
        ]);
    }
}