<?php

namespace App\Services\Order\Services;

use App\Services\Order\Contracts\PaymentInterface;
use App\Services\Payments\IyzicoPaymentService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CreditCard;
use App\Repositories\Contracts\OrderItem\OrderItemRepositoryInterface;
class PaymentService implements PaymentInterface
{

    protected $iyzicoPaymentService;
    protected $orderItemRepository;

    public function __construct(IyzicoPaymentService $iyzicoPaymentService, OrderItemRepositoryInterface $orderItemRepository)
    {
        $this->iyzicoPaymentService = $iyzicoPaymentService;
        $this->orderItemRepository = $orderItemRepository;
    }

    public function processPayment(Order $order, CreditCard $creditCard, float $amount, array $tempCardData = null): array
    {
        return $this->iyzicoPaymentService->processPayment($order, $creditCard, $amount, $tempCardData);
    }

    public function handlePaymentSuccess(Order $order, array $paymentResult): void
    {
        $paidPrice = $paymentResult['paid_price'] ?? 0;
        
        $order->update([
            'paid_price'      => $paidPrice,
            'paid_price_cents' => (int)($paidPrice * 100),
            'currency'        => $paymentResult['currency'] ?? 'TRY',
            'payment_id'      => $paymentResult['payment_id'] ?? null,
            'conversation_id' => $paymentResult['conversation_id'] ?? null,
            'payment_status'  => $paymentResult['payment_status'],
            'status'          => 'confirmed',
        ]);

        if (isset($paymentResult['payment_transaction_id'])) {
            foreach ($paymentResult['payment_transaction_id'] as $itemId => $txId){
                $orderItems = $this->orderItemRepository->getOrderItemByOrderId($itemId, $order->id);
                
                foreach ($orderItems as $orderItem) {
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