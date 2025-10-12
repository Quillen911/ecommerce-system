<?php

namespace App\Http\Controllers\Api\Checkout;

use App\Models\CheckoutSession;
use App\Traits\GetUser;

use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Services\Bag\Contracts\BagInterface;
use App\Services\Checkout\CheckoutSessionService;
use App\Services\Checkout\Orders\OrderPlacementService;

use App\Http\Requests\Checkout\GetSessionRequest;
use App\Http\Requests\Checkout\UpdateShippingRequest;
use App\Http\Requests\Checkout\CreatePaymentIntentRequest;
use App\Http\Requests\Checkout\ConfirmOrderRequest;

use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    use GetUser;
    protected $bagService;
    protected $authenticationRepository;
    protected $checkoutSessionService;
    protected $orderPlacementService;

    public function __construct(
        BagInterface $bagService, 
        AuthenticationRepositoryInterface $authenticationRepository,
        CheckoutSessionService $checkoutSessionService,
        OrderPlacementService $orderPlacementService,
    )
    {
        $this->bagService = $bagService;
        $this->authenticationRepository = $authenticationRepository;
        $this->checkoutSessionService = $checkoutSessionService;
        $this->orderPlacementService = $orderPlacementService;
    }
    
    public function createSession() {
        $user = $this->getUser();

        $bagData = $this->bagService->getBag();
  
        if (empty($bagData['products'])) {
            return response()->json(['message' => 'Sepet boş'], 422);
        }

        $session = $this->checkoutSessionService->createSession($user, $bagData);

        return response()->json([
            'session_id' => $session->id,
            'expires_at' => $session->expires_at,
            'bag' => $session->bag_snapshot,
        ], 201);
    }

    public function getSession($sessionId) {
        $user = $this->getUser();
        
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
        
        $session = $this->checkoutSessionService->confirmPaymentIntent($request->validated());
        
        if ($session->status !== 'confirmed') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Ödeme doğrulanamadı veya 3D işlemi başarısız.'
            ], 422);
        }
        $user = $this->getUser();
        $order = $this->orderPlacementService->placeFromSession($user, $session);
        return response()->json([
            'order_id'    => $order->id ?? 1,
            'order_number'=> $order->order_number ?? 1,
            'status'      => 'success',
        ]);
    }

}