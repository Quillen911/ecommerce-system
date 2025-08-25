<?php

namespace App\Services\MyOrder\Services;

use App\Services\MyOrder\Contracts\MyOrderRefundInterface;
use App\Services\MyOrder\Contracts\MyOrderInterface;
use App\Services\MyOrder\Contracts\MyOrderCalculationInterface;
use App\Services\MyOrder\Contracts\MyOrderCheckInterface;
use App\Services\MyOrder\Contracts\MyOrderUpdateInterface;
use App\Services\Campaigns\CampaignManager;
use App\Services\Payments\IyzicoPaymentService;
use Illuminate\Support\Facades\DB;

class MyOrderRefundService implements MyOrderRefundInterface
{

    public function __construct(
        private IyzicoPaymentService $iyzicoService,
        private MyOrderCalculationInterface $MyOrderCalculationService,
        private MyOrderCheckInterface $MyOrderCheckService,
        private MyOrderUpdateInterface $MyOrderUpdateService,
    ) {}

    public function refundSelectedItems($userId, $orderId, array $refundQuantitiesByItemId, CampaignManager $campaignManager): array
    {
        $checkOrder = $this->MyOrderCheckService->checkOrder($userId, $orderId);
        if(!$checkOrder['success']){
            return $checkOrder;
        }
        $checkItems = $this->MyOrderCheckService->checkItems($checkOrder['order'], $refundQuantitiesByItemId);
        if(!$checkItems['success']){
            return $checkItems;
        }
        $calculations = $this->MyOrderCalculationService->calculateRefundableItems($checkItems['items'], $refundQuantitiesByItemId);

        $refundResults = $this->processRefunds($calculations);

        return $this->MyOrderUpdateService->updateOrderStatus($checkOrder['order'], $refundResults, $campaignManager);
    }

    private function processRefunds(array $calculations): array
    {
        $refundResults = [];
        
        // İade edilebilir ürünleri filtrele
        $refundableCalculations = array_filter($calculations, fn($calc) => $calc['canRefund']);
        
        if(empty($refundableCalculations)){
            return array_map(fn($calc) => [
                'success' => false,
                'error' => 'İade edilebilir adet kalmamış'
            ], $calculations);
        }
        
        // İlk ürünün transaction ID'sini kullan (hepsi aynı olmalı)
        $firstCalculation = reset($refundableCalculations);
        $paymentTransactionId = $firstCalculation['item']->payment_transaction_id;
        
        // Toplam iade tutarını hesapla
        $totalRefundAmount = array_sum(array_column($refundableCalculations, 'priceToRefund'));
        
        // Tek seferde iade yap
        $refund = $this->iyzicoService->refundPayment($paymentTransactionId, $totalRefundAmount);
        
        if($refund['success']){
            // Tüm ürünleri güncelle
            DB::transaction(function() use ($refundableCalculations) {
                foreach($refundableCalculations as $calculation){
                    $this->MyOrderUpdateService->updateProductStock($calculation['item']->product_id, $calculation['itemsToRefund']);
                    $this->MyOrderUpdateService->updateOrderItem($calculation['item'], $calculation['priceToRefund'], $calculation['itemsToRefund']);
                }
            });
        }
        
        // Sonuçları hazırla
        foreach($calculations as $calculation){
            if(!$calculation['canRefund']){
                $refundResults[] = [
                    'success' => false,
                    'error' => 'İade edilebilir adet kalmamış'
                ];
            } else {
                $refundResults[] = [
                    'success' => $refund['success'],
                    'error' => $refund['error'] ?? null,
                    'refundedAmount' => $calculation['priceToRefund'],
                    'refundedQuantity' => $calculation['itemsToRefund']
                ];
            }
        }
        
        return $refundResults;
    }
}