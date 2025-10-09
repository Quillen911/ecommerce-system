<?php

namespace App\Services\Order\Services\Refund;

use App\Services\Order\Contracts\Refund\OrderCalculationInterface;
use Illuminate\Support\Facades\Log;

class OrderCalculationService implements OrderCalculationInterface
{
    
    public function calculateRefundableItems($items, array $payloadItems): array
    {
        $calculations = [];
        $totalPriceToRefundCents = 0;

        foreach ($payloadItems as $index => $item) {
            $requestedQuantity = $item['quantity'];
            $orderItem = $items[$index];

            $calculation = $this->calculateRefundAmount($orderItem, $requestedQuantity);

            $calculations[] = [
                'itemId' => $orderItem->id,
                'requestedQty' => $requestedQuantity,
                'itemsToRefund' => $calculation['itemsToRefund'],
                'priceToRefundCents' => $calculation['priceToRefundCents'],
                'canRefund' => $calculation['canRefund'],
            ];

            $totalPriceToRefundCents += $calculation['priceToRefundCents'];
        }
        $calculations['totalPriceToRefundCents'] = $totalPriceToRefundCents;

        return $calculations;
    }
    
    public function calculateRefundAmount($orderItem, $requestedQuantity): array
    {
        $paidCents = $orderItem->paid_price_cents;
        $refundedCents = $orderItem->refunded_price_cents;
        $remainingCents = max(0, $paidCents - $refundedCents);
        
        $availableQuantity = $orderItem->quantity - ($orderItem->refunded_quantity ?? 0);
        $refundQuantity = min($requestedQuantity, $availableQuantity);
        
        $unitPaidCents = $orderItem->price_cents;
        $priceToRefundCents = min($refundQuantity * $unitPaidCents, $remainingCents);
        $priceToRefundCents = $availableQuantity == 1 ? $remainingCents : $priceToRefundCents;

        return [
            'itemsToRefund' => $refundQuantity,
            'priceToRefundCents' => $priceToRefundCents,
            'canRefund' => $refundQuantity > 0 && $priceToRefundCents > 0
        ];
    }
}