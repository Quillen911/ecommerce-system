<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Services\Order\Contracts\OrderInterface;
use App\Services\Order\Contracts\OrderRefundInterface;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Http\Resources\Order\OrderItemResource;
use App\Http\Requests\Orders\RefundRequest;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
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

  /*  public function refundItems($id, RefundRequest $request)
    {
        $raw = (array) $request->input('refund_quantities', []);

        $quantities = [];
        foreach ($raw as $itemId => $qty) {
            if ($qty > 0) {
                $quantities[$itemId] = $qty;
            }
        }
        
        if (empty($quantities)) {
            return ResponseHelper::error('İade edilecek ürün seçiniz.');
        }
        $result = $this->OrderRefundService->refundSelectedItems($id, $quantities);
        if ($result['success'] ?? false) {
            return ResponseHelper::success('Seçilen ürünler için kısmi iade yapıldı.', $result);
        }
        
        $errorMessage = $result['error'] ?? 'İade işlemi başarısız.';
        return ResponseHelper::errorForArray($errorMessage, $result, 400);
    }*/
}