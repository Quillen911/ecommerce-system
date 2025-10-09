<?php

namespace App\Services\Order\Services\Refund;

use App\Services\Order\Contracts\Refund\OrderRefundInterface;
use App\Services\Order\Contracts\Refund\OrderCalculationInterface;
use App\Services\Order\Contracts\Refund\OrderCheckInterface;
use App\Services\Order\Contracts\Refund\OrderUpdateInterface;
use App\Services\Order\Services\Refund\RefundPlacementService;
use App\Services\Payments\IyzicoPaymentService;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Traits\GetUser;


class OrderRefundService implements OrderRefundInterface
{
    use GetUser;
    public function __construct(
        private IyzicoPaymentService $iyzicoService,
        private OrderCalculationInterface $orderCalculationService,
        private OrderCheckInterface $orderCheckService,
        private OrderUpdateInterface $orderUpdateService,
        private RefundPlacementService $refundPlacementService,
        private AuthenticationRepositoryInterface $authenticationRepository
    ) {
    }

    public function createRefund($order, array $payload)
    {
        $user = $this->getUser();

        if (! $user) {
            throw new \Exception('Kullanıcı bulunamadı');
        }

        $orderModel   = $this->orderCheckService->checkOrder($order->id, $user->id);
        $orderItems   = $this->orderCheckService->checkItems($orderModel, $payload['items']);
        $calculations = $this->orderCalculationService
            ->calculateRefundableItems($orderItems, $payload['items']);

        $payload['refund_total_cents'] = $calculations['totalPriceToRefundCents'] ?? 0;

        $items = $calculations['items'] ?? array_filter($calculations, 'is_array');

        $refund = $this->refundPlacementService
            ->placeRefund($payload, $items, $orderModel);

        return $refund;
    }


    public function markShipping($order, $refund, array $payload)
    {
        if ($refund->order_id !== $order->id) {
            throw new \InvalidArgumentException('İade kaydı belirtilen siparişe ait değil.');
        }

        if ($refund->status !== 'requested') {
            throw new \RuntimeException('Bu iade zaten kargo sürecine alınmış.');
        }

        $refund->fill([
            'status'            => 'awaiting_pickup',
            'shipping_provider' => $payload['shipping_provider'] ?? $refund->shipping_provider,
            'tracking_number'   => $payload['tracking_number'] ?? $refund->tracking_number,
            'approved_at'       => $payload['approved_at'] ?? $refund->approved_at,
        ]);

        $refund->save();

        return $refund->fresh();
    }
    public function markReceived($order, $refund, array $payload)
    {

    }
    public function markCompleted($order, $refund, array $payload)
    {

    }
    public function markRejected($order, $refund, array $payload)
    {

    }


  /*  public function refundSelectedItems($orderId, array $refundQuantitiesByItemId): array
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

        return $this->OrderUpdateService->updateOrderStatus($checkOrder['order'], $refundResults);
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
    }*/
}