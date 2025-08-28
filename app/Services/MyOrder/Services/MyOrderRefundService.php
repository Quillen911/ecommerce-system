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
use App\Traits\GetUser;
use App\Jobs\RefundOrderItemNotification;

class MyOrderRefundService implements MyOrderRefundInterface
{
    use GetUser;
    public function __construct(
        private IyzicoPaymentService $iyzicoService,
        private MyOrderCalculationInterface $MyOrderCalculationService,
        private MyOrderCheckInterface $MyOrderCheckService,
        private MyOrderUpdateInterface $MyOrderUpdateService,
    ) {
    }

    public function refundSelectedItems($orderId, array $refundQuantitiesByItemId, CampaignManager $campaignManager): array
    {
        $checkOrder = $this->MyOrderCheckService->checkOrder($orderId);
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
        
        foreach($calculations as $calculation){
            if(!$calculation['canRefund']){
                $refundResults[] = [
                    'success' => false,
                    'error' => 'İade edilebilir adet kalmamış'
                ];
                continue;
            }

            $refund = $this->iyzicoService->refundPayment(
                $calculation['item']->payment_transaction_id, 
                $calculation['priceToRefund'] 
            );

            if($refund['success']){
                DB::transaction(function() use ($calculation) {
                    $this->MyOrderUpdateService->updateProductStock($calculation['item']->product_id, $calculation['itemsToRefund']);
                    $this->MyOrderUpdateService->updateOrderItem($calculation['item'], $calculation['priceToRefund'], $calculation['itemsToRefund']);
                });
                DB::commit();
                RefundOrderItemNotification::dispatch($calculation['item'], $calculation['item']->order->user, $calculation['itemsToRefund'])->onQueue('notifications');
            }

            $refundResults[] = [
                'success' => $refund['success'],
                'error' => $refund['error'] ?? null,
                'refundedAmount' => $calculation['priceToRefund'],
                'refundedQuantity' => $calculation['itemsToRefund']
            ];
        }
        return $refundResults;
    }
}