<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Services\Order\Contracts\OrderInterface;
use App\Services\Order\Contracts\Refund\OrderRefundInterface;
use App\Helpers\ResponseHelper;
use App\Http\Resources\Order\OrderItemResource;
use App\Http\Requests\Order\RefundRequest;


class OrderController extends Controller
{
    public function __construct(
        private readonly OrderInterface $orderService,
        private readonly OrderRefundInterface $OrderRefundService
    ) {
    }
    public function index()
    {
        $orders = $this->orderService->getOrdersforUser();

        return ResponseHelper::success('Siparişler', $orders);
    }
    
    public function show($orderId)
    {
        $order = $this->orderService->getOneOrderforUser($orderId);

        return response()->json(OrderItemResource::collection($order));
    }

    public function refundItems($id, RefundRequest $request)
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
    }
}