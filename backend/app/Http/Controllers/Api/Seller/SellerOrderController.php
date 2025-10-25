<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Services\Seller\SellerOrderService;
use App\Http\Resources\Order\OrderItemResource;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Seller\Order\SellerRefundItemRequest;
use Illuminate\Support\Facades\Log;
class SellerOrderController extends Controller
{
    public function __construct(
        private readonly SellerOrderService $sellerOrderService
    ){

    }
    public function index()
    {
        $orderItems = $this->sellerOrderService->getSellerOrders();
        return Response::json(OrderItemResource::collection($orderItems));    
        
    }
    public function show($id)
    {
        $orderItem = $this->sellerOrderService->getSellerOneOrder($id);
        return Response::json(OrderItemResource::make($orderItem));
    }
    public function confirmOrderItem($id)
    {
        $orderItem = $this->sellerOrderService->confirmItem($id);
        
        if (!$orderItem) {
            return ResponseHelper::error('Sipariş bulunamadı veya size ait değil');
        }
            
        return ResponseHelper::success('Sipariş Kargoya Teslim Edildi');
    }
    public function refundOrderItem(SellerRefundItemRequest $request, $id)
    {
        $result = $this->sellerOrderService->refundSelectedItems($id, $request->validated());
        return Response::json($result);
    }
    
}
