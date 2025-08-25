<?php

namespace App\Services\MyOrder\Services;

use App\Services\MyOrder\Contracts\MyOrderCalculationInterface;

class MyOrderCalculationService implements MyOrderCalculationInterface
{
    
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
    
    public function calculateRefundAmount($item, $requestedQuantity): array
    {
        $paidCents = $item->paid_price_cents ?? 0;
        $refundedCents = $item->refunded_price_cents ?? 0;
        $remainingCents = max(0, $paidCents - $refundedCents);
        
        // Kampanya kapsamındaki ürünler için indirim tutarını da hesaba kat
        $discountCents = $item->discount_price_cents ?? 0;
        $totalDiscountCents = $discountCents * $item->quantity;
        
        // İade edilecek tutar: ödenen tutar - daha önce iade edilen tutar
        $refundableCents = $remainingCents;
        
        $availableQuantity = $item->quantity - ($item->refunded_quantity ?? 0);
        
        $itemsToRefund = min($requestedQuantity, $availableQuantity);
        
        // Birim fiyatı hesapla: toplam ödenen / toplam adet
        $unitPaidCents = $item->quantity > 0 ? round($paidCents / $item->quantity) : 0;
        $priceToRefundCents = $itemsToRefund * $unitPaidCents;
            
        $canRefund = $itemsToRefund > 0 && $priceToRefundCents > 0;
    
        
        return [
            'itemsToRefund' => $itemsToRefund,
            'priceToRefundCents' => $priceToRefundCents,
            'priceToRefund' => $priceToRefundCents / 100, 
            'canRefund' => $canRefund
        ];
    }
}