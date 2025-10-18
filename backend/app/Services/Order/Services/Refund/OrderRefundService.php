<?php

namespace App\Services\Order\Services\Refund;

use App\Services\Order\Contracts\Refund\OrderRefundInterface;
use App\Services\Order\Contracts\Refund\OrderCalculationInterface;
use App\Services\Order\Contracts\Refund\OrderCheckInterface;
use App\Services\Order\Contracts\Refund\OrderUpdateInterface;
use App\Services\Order\Services\Refund\RefundPlacementService;
use App\Services\Payments\IyzicoPaymentService;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Services\Order\Contracts\Refund\ReverseShipmentGatewayInterface;

use App\Traits\GetUser;

use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Support\Facades\Log;


class OrderRefundService implements OrderRefundInterface
{
    use GetUser;
    public function __construct(
        private IyzicoPaymentService $iyzicoService,
        private OrderCalculationInterface $orderCalculationService,
        private OrderCheckInterface $orderCheckService,
        private OrderUpdateInterface $orderUpdateService,
        private RefundPlacementService $refundPlacementService,
        private AuthenticationRepositoryInterface $authenticationRepository,
        private ReverseShipmentGatewayInterface $reverseShipmentGateway
    ) {
    }

    public function createRefund($order, array $payload)
    {
        $user = $this->getUser();

        if (! $user) {
            throw new \RuntimeException('Kullanıcı bulunamadı');
        }

        $orderModel   = $this->orderCheckService->checkOrder($order->id, $user->id);
        $orderItems   = $this->orderCheckService->checkItems($orderModel, $payload['items']);
        $calculations = $this->orderCalculationService
            ->calculateRefundableItems($orderItems, $payload['items']);

        $payload['refund_total_cents'] = $calculations['totalPriceToRefundCents'] ?? 0;

        $items = $calculations['items'] ?? array_filter($calculations, 'is_array');
        $refund = $this->refundPlacementService->placeRefund($payload, $items, $orderModel);

        //$this->requestReverseShipment($refund, $orderModel);

        return $refund;
    }

    public function handleShipmentWebhook(array $payload): void
    {
        $refund = $this->resolveRefundByTracking($payload['tracking_number']);
        match ($payload['status']) {
            'PICKED_UP'  => $this->markPickup($refund, $payload),
            'IN_TRANSIT' => $this->markInTransit($refund, $payload),
            'DELIVERED'  => $this->markReceived($refund, $payload),
            default      => Log::info('Shipment status ignored', $payload),
        };
    }

    public function handlePaymentWebhook(array $payload): void
    {
        $refund = $this->resolveRefundByPaymentReference($payload['reference']);

        if ($payload['status'] === 'SUCCESS') {
            $this->markCompleted($refund, $payload);
        } else {
            $this->markPaymentFailed($refund, $payload);
        }
    }

    private function markPickup(OrderRefund $refund, array $payload): void
    {
        $refund->update([
            'status'       => OrderRefund::STATUS_PICKED_UP,
            'picked_up_at' => $payload['timestamp'] ?? now(),
        ]);
    }

    private function markInTransit(OrderRefund $refund, array $payload): void
    {
        $refund->update([
            'status'        => OrderRefund::STATUS_IN_TRANSIT,
            'in_transit_at' => $payload['timestamp'] ?? now(),
        ]);
    }

    private function markReceived(OrderRefund $refund, array $payload)
    {
        $refund->update([
            'status'          => OrderRefund::STATUS_RECEIVED,
            'received_at'     => $payload['timestamp'] ?? now(),
        ]);
    }
    public function markCompleted(OrderRefund $refund, array $payload)
    {
        $refund->update([
            'status'       => OrderRefund::STATUS_COMPLETED,
            'refunded_at'  => $payload['timestamp'] ?? now(),
            'payment_meta' => $payload,
        ]);
    }
    public function markPaymentFailed(OrderRefund $refund, array $payload)
    {
        $refund->update([
            'status'       => OrderRefund::STATUS_PAYMENT_FAILED,
            'payment_meta' => $payload,
        ]);
    }


    private function requestReverseShipment(OrderRefund $refund, Order $order): void
    {
        try {
            $response = $this->reverseShipmentGateway->createReverseShipment([
                'order_number' => $order->order_number,
                'refund_id' => $refund->id,
                'customer' => $order->user->only(['first_name', 'last_name', 'phone_number']),
                'address'      => $order->shippingAddress?->toArray(),
                'items'        => $refund->items->map(fn ($item) => [
                    'order_item_id' => $item->order_item_id,
                    'quantity'      => $item->quantity,
                ])->all(),
            ]);
            
            $refund->update([
                'status'            => OrderRefund::STATUS_AWAITING_PICKUP,
                'shipping_provider' => $response['provider'],
                'tracking_number'   => $response['tracking_number'],
                'label_url'         => $response['label_url'] ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error('Reverse shipment create failed', [
                'refund_id' => $refund->id,
                'error'     => $e->getMessage(),
            ]);

            $refund->update(['status' => OrderRefund::STATUS_SHIPMENT_FAILED]);
        }
    }

    private function resolveRefundByTracking(string $trackingNumber): OrderRefund
    {
        return OrderRefund::where('tracking_number', $trackingNumber)->firstOrFail();
    }

    private function resolveRefundByPaymentReference(string $reference): OrderRefund
    {
        return OrderRefund::where('payment_reference', $reference)->firstOrFail();
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