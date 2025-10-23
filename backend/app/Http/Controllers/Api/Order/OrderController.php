<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Services\Order\Contracts\OrderInterface;
use App\Services\Order\Contracts\Refund\OrderRefundInterface;
use App\Services\User\AddressesService;
use App\Helpers\ResponseHelper;
use App\Http\Resources\Order\OrderItemResource;
use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Order\OrderDetailResource;
use App\Http\Requests\Order\RefundRequest;
use App\Repositories\Contracts\Payment\PaymentRepositoryInterface;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderInterface $orderService,
        private readonly OrderRefundInterface $OrderRefundService,
        private readonly AddressesService $userAddressService,
        private readonly PaymentRepositoryInterface $paymentRepository
    ) {
    }
    public function index()
    {
        $orders = $this->orderService->getOrdersforUser();

        return response()->json($orders);
    }
    
    public function show($orderId)
    {
        $orderItems = $this->orderService->getOneOrderforUser($orderId);
        $order = $orderItems->first()->order;
        $userShippingAddress = $order->shippingAddress;
        $userBillingAddress = $order->billingAddress;

        return Response::json([
            'order' => OrderItemResource::collection($orderItems),
            'userShippingAddress' => AddressResource::make($userShippingAddress),
            'userBillingAddress' => AddressResource::make($userBillingAddress),
            
        ]);
    }

   /* public function refundItems($id, RefundRequest $request)
    {
        $data = $request->input('refund_quantities');

        $quantities = [];
        foreach ($data as $itemId => $quantity) {
            if ($quantity > 0) {
                $quantities[$itemId] = $quantity;
            }
        }

        $result = $this->OrderRefundService->refundSelectedItems($id, $quantities);

        if ($result['success'] ?? false) {
            return ResponseHelper::success('Seçilen ürünler için kısmi iade yapıldı.', $result);

        }
        
        $errorMessage = $result['error'] ?? 'İade işlemi başarısız.';
        
        return ResponseHelper::errorForArray($errorMessage, $result, 400);
    }*/
}