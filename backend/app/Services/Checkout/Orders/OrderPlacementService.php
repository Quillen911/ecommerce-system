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
use App\Repositories\Contracts\Bag\BagRepositoryInterface;
use App\Services\Campaigns\CampaignManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendOrderNotification;
use App\Jobs\SellerOrderNotification;

class OrderPlacementService
{
    public function __construct(
        private readonly OrderFactory $orderFactory,
        private readonly OrderItemFactory $orderItemFactory,
        private readonly InventoryService $inventoryService,
        private readonly PaymentRecorder $paymentRecorder,
        private readonly PaymentMethodRecorder $PaymentMethodRecorder,
        private readonly BagRepositoryInterface $bagRepository,
        private readonly CampaignManager $campaign
    ) {}

    public function placeFromSession(User $user, CheckoutSession $session, $data): Order
    {
        return DB::transaction(function () use ($user, $session, $data) {
            $order = $this->orderFactory->create($user, $session);
            $items = $this->orderItemFactory->createMany($order, $session);

            $this->inventoryService->decrementForOrderItems($items);
            $this->paymentRecorder->record($order, $session->payment_data);
            $this->PaymentMethodRecorder->store($user, $session->payment_data, $data);
            
            $bagPayload    = $session->bag_snapshot;
            $campaignId    = data_get($bagPayload, 'applied_campaign.id');
            $discountCents = (int) data_get($bagPayload, 'totals.discount_cents', 0);

            if ($campaignId) {
                $this->campaign->logUsage(
                    $campaignId,
                    $user->id,
                    $order->id,
                    $discountCents
                );
            }

            $bag = $this->bagRepository->getBag($user);
            $this->bagRepository->clearBagItems($bag);

            $session->update([
                'status'         => 'confirmed',
                'meta->order_id' => $order->id,
            ]);
            
            foreach ($items as $item) {
                $seller = $item->product->store->seller;
                SellerOrderNotification::dispatch($order, $seller);
            }
            SendOrderNotification::dispatch($order, $user);
            return $order;
        });
    }
}
