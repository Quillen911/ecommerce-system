<?php

namespace App\Http\Controllers\Api\Checkout;

use App\Models\CheckoutSession;
use App\Traits\GetUser;

use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Services\Bag\Contracts\BagInterface;
use App\Services\Checkout\CheckoutSessionService;

use App\Http\Requests\Checkout\CreateSessionRequest;
use App\Http\Requests\Checkout\GetSessionRequest;
use App\Http\Requests\Checkout\UpdateShippingRequest;
use App\Http\Requests\Checkout\CreatePaymentIntentRequest;
use App\Http\Requests\Checkout\ConfirmOrderRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    use GetUser;
    protected $bagService;
    protected $authenticationRepository;
    protected $checkoutSessionService;

    public function __construct(
        BagInterface $bagService, 
        AuthenticationRepositoryInterface $authenticationRepository,
        CheckoutSessionService $checkoutSessionService,
    )
    {
        $this->bagService = $bagService;
        $this->authenticationRepository = $authenticationRepository;
        $this->checkoutSessionService = $checkoutSessionService;
    }
    
    public function createSession(CreateSessionRequest $request) {
        $user = $this->getUser();

        $bagData = $this->bagService->getBag();
        if (empty($bagData['products'])) {
            return response()->json(['message' => 'Sepet boş'], 422);
        }

        $session = $this->checkoutSessionService->createSession($user, $bagData, $request->validated());

        return response()->json([
            'session_id' => $session->id,
            'expires_at' => $session->expires_at,
            'bag' => $session->bag_snapshot,
        ], 201);
    }

    public function getSession(GetSessionRequest $request) {
        $user = $this->getUser();
        $sessionId = $request->validated('session_id');
        
        $session = $this->checkoutSessionService->getSession($user, $sessionId);

        return response()->json([
            'session_id'    => $session->id,
            'expires_at'    => $session->expires_at,
            'status'        => $session->status,
            'bag'           => $session->bag_snapshot,
            'shipping_data' => $session->shipping_data,
            'billing_data'  => $session->billing_data,
            'payment_data'  => $session->payment_data,
            'meta'          => $session->meta,
        ]);
        
        
    }

    public function updateShipping(UpdateShippingRequest $request) {
        $user = $this->getUser();
        $session = $this->checkoutSessionService->updateShipping($user, $request->validated());
        
        return response()->json([
            'session_id'    => $session->id,
            'status'        => $session->status,
            'shipping_data' => $session->shipping_data,
            'billing_data'  => $session->billing_data,
            'bag'           => $session->bag_snapshot,
        ]);
    }

    public function createPaymentIntent(CreatePaymentIntentRequest $request) 
    {
        $user = $this->getUser();
        
        $session = $this->checkoutSessionService->createPaymentIntent($user ,$request->validated());

        return response()->json([
            'session_id'    => $session->id,
            'status'        => $session->status,
            'payment_data'  => $session->payment_data,
        ]);
    }

    public function confirmOrder(ConfirmOrderRequest  $request) {
        \Log::debug('Checkout confirm payload', $request->all());
        
        $data = $request->validated();
        $conversationId = $data['conversationId'];

        $payload = [
            'session_id'            => $data['session_id'] ?? null,
            'payment_intent_id'  => $data['paymentId'],
            'conversation_id'    => $conversationId,
            'conversation_data'  => $data['conversationData'] ?? null,
            'mdStatus'           => $data['mdStatus'] ?? null,
            'save_card'          => $data['save_card'] ?? false,
        ];
        $session = $this->checkoutSessionService->confirmPaymentIntent($user, $request->validated());

        if ($session->status !== 'confirmed') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Ödeme doğrulanamadı veya 3D işlemi başarısız.'
            ], 422);
        }

        $order = app(\App\Services\Checkout\CheckoutOrderService::class)
            ->createOrderFromSession($user, $session);

        return response()->json([
            'order_id'    => $order->id,
            'order_number'=> $order->order_number,
            'status'      => 'success',
        ]);
    }

    private function prepareBagSnapshot(array $bagData): array
    {
        $items = $bagData['products']->map(function ($item) {
            return [
                'bag_item_id'        => $item->id,
                'variant_size_id'    => $item->variant_size_id,
                'product_id'         => $item->variantSize->productVariant->product_id,
                'product_title'      => $item->product->title ?? $item->variantSize->productVariant->product->title,
                'quantity'           => $item->quantity,
                'unit_price_cents'   => $item->variantSize->price_cents,
                'total_price_cents'  => $item->variantSize->price_cents * $item->quantity,
            ];
        })->toArray();

        return [
            'items'             => $items,
            'totals' => [
                'total_cents'       => $bagData['total_cents'],
                'cargo_cents'       => $bagData['cargoPrice_cents'],
                'final_cents'       => $bagData['finalPrice_cents'],
            ],
        ];
    }

}