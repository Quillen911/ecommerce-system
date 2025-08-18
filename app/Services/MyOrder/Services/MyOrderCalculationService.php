<?php

namespace App\Services\MyOrder\Services;

use App\Services\MyOrder\Contracts\MyOrderCalculationInterface;

class MyOrderCalculationService implements MyOrderCalculationInterface
{
    public function calculateRefundAmount($item, $requestedQuantity): array
    {
        
        $paidPrice = round($item->paid_price ?? 0, 4);
        $refundedPrice = round($item->refunded_price ?? 0, 4);
        $remainingRefundedPrice = round(max(0, $paidPrice - $refundedPrice), 4);
        
        $unitPaidPrice = $item->quantity > 0 ? round($paidPrice / $item->quantity, 4) : 0;
        
        $maxItemsByPrice = $unitPaidPrice > 0 ? floor($remainingRefundedPrice / $unitPaidPrice) : 0;
        $itemsToRefund = min($requestedQuantity, $maxItemsByPrice);
        
        $priceToRefund = round($itemsToRefund * $unitPaidPrice, 4);
            
        $canRefund = $itemsToRefund > 0 && $priceToRefund > 0.01;
        
        return [
            'itemsToRefund' => $itemsToRefund,
            'priceToRefund' => $priceToRefund,
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
                'priceToRefund' => $calculation['priceToRefund'],
                'canRefund' => $calculation['canRefund']
            ];
        }
        
        return $calculations;
    }
}