<?php

namespace App\Services\Checkout;

use App\Repositories\Contracts\User\AddressesRepositoryInterface;
use App\Repositories\Contracts\Payment\PaymentMethodRepositoryInterface;
use App\Services\Checkout\CheckoutPaymentService;
use App\Services\Checkout\Orders\OrderPlacementService;
use App\Repositories\Contracts\Inventory\InventoryRepositoryInterface;

use App\Jobs\OrderPlacementJob;

use App\Models\User;
use App\Models\CheckoutSession;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\ModelNotFoundException;


class CheckoutSessionService
{
    public function __construct(
        private readonly AddressesRepositoryInterface $addressesRepository,
        private readonly PaymentMethodRepositoryInterface $paymentMethods,
        private readonly CheckoutPaymentService $checkoutPaymentService,
        private readonly OrderPlacementService $orderPlacementService,
        private readonly InventoryRepositoryInterface $inventories

    ) {
    }

    public function createSession($user, array $bagData): CheckoutSession
    {
        $stock = $this->checkStock($bagData);
        if ($stock === false) {
            throw new \RuntimeException('Stoklar yetersiz. Lütfen sepeti kontrol ediniz.');
        }
        $session = CheckoutSession::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'bag_id'         => optional($bagData['products']->first())->bag_id,
            'bag_snapshot' => $this->prepareBagSnapshot($bagData),
            'status' => 'pending',
            'expires_at' => now()->addHours(1),
        ]);

        return $session;
    }

    public function getSession($user, $sessionId)
    {
        return $this->findSessionForUser($sessionId, $user->id);
    }
    
    public function updateShipping($user, array $data): CheckoutSession
    {

        $session = $this->findSessionForUser($data['session_id'], $user->id);
        if ($session->status === 'confirmed') {
            throw new \RuntimeException('Checkout oturumu zaten onaylandı.');
        }
        $shippingAddress = $this->addressesRepository->getAddressById($data['shipping_address_id'], $user->id);
        if (!$shippingAddress) {
            throw new ModelNotFoundException('Teslimat adresi size ait değil.');
        }
        $billingAddress = null;
        if (!empty($data['billing_address_id'])) {
            $billingAddress = $this->addressesRepository->getAddressById($data['billing_address_id'], $user->id);

            if (!$billingAddress) {
                throw new ModelNotFoundException('Fatura adresi size ait değil.');
            }
        }

        $session->shipping_data = [
            'shipping_address_id' => $shippingAddress->id,
            'delivery_method' => $data['delivery_method'],
            'notes' => $data['notes'] ?? null,
        ];

        $session->billing_data = $billingAddress ? [
            'billing_address_id' => $billingAddress->id,
        ] : null;

        $session->status = 'shipping_selected';
        $session->save();

        return $session->fresh();

    }

    public function createPaymentIntent(User $user, array $data): CheckoutSession
    {
        $session = $this->findSessionForUser($data['session_id'], $user->id);

        if ($session->status === 'confirmed') {
            throw new \RuntimeException('Checkout oturumu zaten onaylandı.');
        }

        if ($data['payment_method'] === 'saved_card') {
            $paymentMethod = $this->paymentMethods->getPaymentMethodForUser($user->id, $data['payment_method_id']);

            if (! $paymentMethod) {
                throw new ModelNotFoundException('Geçerli bir ödeme yöntemi bulunamadı.');

            }

        } elseif ($data['payment_method'] === 'new_card') {
            $paymentMethod = $this->checkoutPaymentService->buildTemporaryMethodFromData($user, $data);
        } else {
            throw new \InvalidArgumentException('Desteklenmeyen ödeme yöntemi.');

        }

        $intent = $this->checkoutPaymentService->createPaymentIntent(
            $user,
            $session,
            $paymentMethod,
            $data
        );


        $paymentData = $session->payment_data ?? [];
        $paymentData['provider']            = $intent['provider'];
        $paymentData['method']              = $data['payment_method'];
        $paymentData['payment_method_id']   = $paymentMethod->id ?? null;
        $paymentData['installment']         = $data['installment'] ?? 1;
        $paymentData['intent']              = $intent;
        $paymentData['status']              = $intent['status'] ?? 'payment_pending';
        $paymentData['save_card']           = (bool) ($data['save_card'] ?? false);
        $paymentData['new_card_payload'] = ($paymentData['save_card'] && ! $paymentMethod->exists)
            ? [
                'card_alias' => $data['card_alias'] ?? 'Kredi Kartım',
                'last4'      => substr($data['card_number'] ?? '', -4),
            ]
            : null;

        $session->payment_data = $paymentData;
        if (!empty($intent['requires_3ds'])) {
            $session->status = 'pending_3ds';
        } else {
            $session->status = 'confirmed';

            OrderPlacementJob::dispatch($user, $session, $data);
        }
        $session->save();

        return $session->fresh();
    }

    public function confirmPaymentIntent(array $data): CheckoutSession
    {
        $conversationId = $data['conversationId'] ?? null;
        $paymentId      = $data['paymentId'] ?? null;

        $session = null;

        if ($conversationId) {
            $session = CheckoutSession::where(
                'payment_data->intent->conversation_id',
                $conversationId
            )->first();
        }

        if (!$session && $paymentId) {
            $session = CheckoutSession::where(
                'payment_data->intent->payment_id',
                $paymentId
            )->first();
        }

        if (!$session && config('app.env') !== 'production') {
            $session = CheckoutSession::where('status', 'confirmed')
                ->where('payment_data->provider', 'iyzico')
                ->latest()
                ->first();

        }

        if (!$session) {
            throw new \RuntimeException('Checkout oturumu bulunamadı.');
        }

        $session->loadMissing('user');

        if (!$session->user && $session->user_id) {
            $session->setRelation('user', User::find($session->user_id));
        }

        if (!$session->user) {
            throw new \RuntimeException('Kullanıcı bulunamadı.');
        }

        $result = $this->checkoutPaymentService->confirmPaymentIntent($session, $data);

        $paymentData = $session->payment_data ?? [];
        $paymentData['intent_result'] = $result;
        $paymentData['status']        = $result['status'];
        
        if (!empty($result['payment_transaction_id'])) {
            $paymentData['intent']['payment_transaction_id'] = $result['payment_transaction_id'];
        }
        if (($paymentData['save_card'] ?? false) && ($paymentData['new_card_payload'] ?? null)) {
            $payload                     = $paymentData['new_card_payload'];
            $payload['result']           = $result;
            $paymentData['new_card_payload'] = $payload;
        }

        $session->payment_data = $paymentData;
        $session->status       = 'confirmed';
        $session->save();

        return $session->fresh();
    }

    private function findSessionForUser($sessionId, $user)
    {
        $session = CheckoutSession::where('id', $sessionId)
            ->where('user_id', $user)
            ->first();

        if (!$session) {
            throw new ModelNotFoundException('Checkout oturumu bulunamadı.');
        }

        if ($session->expires_at && $session->expires_at->isPast()) {
            throw new \RuntimeException('Checkout oturumunun süresi doldu.');
        }

        return $session;
    }

    private function prepareBagSnapshot(array $bagData): array
    {
        $items = $bagData['products']->map(function ($item) {
            return [
                'bag_item_id'        => $item->id,
                'store_id'           => $item->store_id,
                'variant_size_id'    => $item->variant_size_id,
                'product_id'         => $item->variantSize->productVariant->product_id,
                'product_title'      => $item->variantSize->productVariant->color_name . ' ' . $item->variantSize->productVariant->product->title,
                'product_category_title' => $item->variantSize->productVariant->product->category->title,
                'size_name'          => $item->variantSize->sizeOption->value,
                'color_name'         => $item->variantSize->productVariant->color_name,
                'quantity'           => $item->quantity,
                'unit_price_cents'   => $item->unit_price_cents,
                'total_price_cents'  => $item->unit_price_cents * $item->quantity,
            ];
        })->toArray();

        return [
            'items'             => $items,
            'totals' => [
                'total_cents'       => $bagData['total_cents'],
                'cargo_cents'       => $bagData['cargo_price_cents'],
                'discount_cents'    => $bagData['discount_cents'] ?? 0, 
                'final_cents'       => $bagData['final_price_cents'],
            ],
            'applied_campaign'  => [
                'id'          => $bagData['applied_campaign']['id'] ?? null,
                'name'         => $bagData['applied_campaign']['name'] ?? null,
                'discount_items' => $bagData['discount_items'] ?? [],
            ] ?? null
        ];
    }

    private function checkStock(array $bagData): bool
    {
       $items = $bagData['products']->map(function ($item) {
            return [
                'variant_size_id'    => $item->variant_size_id,
                'quantity'           => $item->quantity,

            ];
        })->toArray(); 

        foreach ($items as $item) {
            if ($this->inventories->checkStock($item['variant_size_id'], $item['quantity']) === false) {
                return false;
            }
        }

        return true;
    }

}