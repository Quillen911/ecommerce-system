<?php

namespace App\Services\Checkout\Orders;

use App\Models\CheckoutSession;
use App\Models\User;
use App\Models\Order;
use App\Services\Checkout\Orders\OrderFactory;
use App\Services\Checkout\Orders\OrderItemFactory;
use App\Services\Inventory\InventoryService;
use App\Services\Payments\PaymentRecorder;
use App\Services\Payments\PaymentMethodRecorder;
use App\Events\OrderPlaced;
use Illuminate\Support\Facades\DB;

class OrderPlacementService
{
    public function __construct(
        private readonly OrderFactory $orderFactory,
        private readonly OrderItemFactory $orderItemFactory,
        private readonly InventoryService $inventoryService,
        private readonly PaymentRecorder $paymentRecorder,
        private readonly PaymentMethodRecorder $PaymentMethodRecorder
    ) {}

    public function placeFromSession(User $user, CheckoutSession $session): Order
    {
        return DB::transaction(function () use ($user, $session) {
            $order = $this->orderFactory->create($user, $session);
            $items = $this->orderItemFactory->createMany($order, $session);
            $this->inventoryService->decrementForOrderItems($items);
            $this->paymentRecorder->record($order, $session->payment_data);
            $this->PaymentMethodRecorder->store($user, $session->payment_data);

            $session->update([
                'status'         => 'confirmed',
                'meta->order_id' => $order->id,
            ]);

            //event(new OrderPlaced($order));

            return $order;
        });
    }
}
