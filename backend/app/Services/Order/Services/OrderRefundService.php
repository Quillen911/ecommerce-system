<?php

namespace App\Services\Order\Services;

use App\Services\Order\Contracts\OrderRefundInterface;
use App\Services\Order\Contracts\OrderInterface;
use App\Services\Order\Contracts\OrderCalculationInterface;
use App\Services\Order\Contracts\OrderCheckInterface;
use App\Services\Order\Contracts\OrderUpdateInterface;
use App\Services\Campaigns\CampaignManager;
use App\Services\Payments\IyzicoPaymentService;
use Illuminate\Support\Facades\DB;
use App\Traits\GetUser;
use App\Jobs\RefundOrderItemNotification;

class OrderRefundService implements OrderRefundInterface
{
    use GetUser;
    public function __construct(
        private IyzicoPaymentService $iyzicoService,
        private OrderCalculationInterface $OrderCalculationService,
        private OrderCheckInterface $OrderCheckService,
        private OrderUpdateInterface $OrderUpdateService,
    ) {
    }

    public function refundSelectedItems($orderId, array $refundQuantitiesByItemId, CampaignManager $campaignManager): array
    {
        $checkOrder = $this->OrderCheckService->checkOrder($orderId);
        if(!$checkOrder['success']){
            return $checkOrder;
        }
        $checkItems = $this->OrderCheckService->checkItems($checkOrder['order'], $refundQuantitiesByItemId);
        if(!$checkItems['success']){
            return $checkItems;
        }
        $calculations = $this->OrderCalculationService->calculateRefundableItems($checkItems['items'], $refundQuantitiesByItemId);
        
        $refundResults = $this->processRefunds($calculations);

        return $this->OrderUpdateService->updateOrderStatus($checkOrder['order'], $refundResults, $campaignManager);
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
                $calculation['priceToRefundCents'] / 100
            );

            if($refund['success']){
                DB::transaction(function() use ($calculation) {
                    $this->OrderUpdateService->updateProductStock($calculation['item']->product_id, $calculation['itemsToRefund']);
                    $this->OrderUpdateService->updateOrderItem($calculation['item'], $calculation['priceToRefundCents'], $calculation['itemsToRefund']);
                });
                DB::commit();
                RefundOrderItemNotification::dispatch($calculation['item'], $calculation['item']->order->user, $calculation['itemsToRefund'], $calculation['priceToRefund'])->onQueue('notifications');
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