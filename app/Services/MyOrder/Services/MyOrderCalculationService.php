<?php

namespace App\Services\MyOrder\Services;

use App\Services\MyOrder\Contracts\MyOrderCalculationInterface;

class MyOrderCalculationService implements MyOrderCalculationInterface
{
    public function calculateRefundAmount($item, $requestedQuantity): array
    {
        // Kuruş cinsinden hesaplama
        $paidCents = $item->paid_price_cents ?? 0;
        $refundedCents = $item->refunded_price_cents ?? 0;
        $remainingCents = max(0, $paidCents - $refundedCents);
        
        $unitPaidCents = $item->quantity > 0 ? intdiv($paidCents, $item->quantity) : 0;
        
        $maxItemsByPrice = $unitPaidCents > 0 ? intdiv($remainingCents, $unitPaidCents) : 0;
        $availableQuantity = $item->quantity - ($item->refunded_quantity ?? 0);
        
        $itemsToRefund = min($requestedQuantity, $maxItemsByPrice, $availableQuantity);
        $priceToRefundCents = $itemsToRefund * $unitPaidCents;
            
        $canRefund = $itemsToRefund > 0 && $priceToRefundCents > 0;
        
        return [
            'itemsToRefund' => $itemsToRefund,
            'priceToRefundCents' => $priceToRefundCents,
            'priceToRefund' => $priceToRefundCents / 100, // TL cinsinden geri dönüş için
            'canRefund' => $canRefund
        ];
    }

    public function calculateRefundableItems($items, array $refundQuantitiesByItemId): array
    {
        $calculations = [];
       
        foreach ($items as $item){
            $requestedQuantity = ($refundQuantitiesByItemId[$item->id] ?? 0);
            $calculation = $this->calculateRefundAmount($item, $requestedQuantity);
            
            $calculations[] = [
                'item' => $item,
                'requestedQty' => $requestedQuantity,
                'itemsToRefund' => $calculation['itemsToRefund'],
                'priceToRefundCents' => $calculation['priceToRefundCents'],
                'priceToRefund' => $calculation['priceToRefund'],
                'canRefund' => $calculation['canRefund']
            ];
        }
        
        return $calculations;
    }
}