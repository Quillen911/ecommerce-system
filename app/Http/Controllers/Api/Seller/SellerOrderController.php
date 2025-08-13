<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Helpers\ResponseHelper;
use App\Services\Campaigns\Seller\SellerOrderService;
use App\Models\Store;
class SellerOrderController extends Controller
{
    protected $sellerOrderService;

    public function __construct(SellerOrderService $sellerOrderService)
    {
        $this->sellerOrderService = $sellerOrderService;
    }
    public function index()
    {
        $seller = auth('seller')->user();
        if (!$seller) {
            return ResponseHelper::error('Unauthorized', 401);
        }
        $store = Store::where('seller_id', $seller->id)->first();
        if(!$store){
            return ResponseHelper::error('Lütfen giriş yapınız');
        }
        $orderItems = $this->sellerOrderService->getSellerOrders($store);
        return ResponseHelper::success('Gelen siparişler başarıyla getirildi', $orderItems);    
            
    }
    public function show($id)
    {
        $seller = auth('seller')->user();
        $store = Store::where('seller_id', $seller->id)->first();
        if(!$store){
            return ResponseHelper::error('Lütfen giriş yapınız');
        }
        $orderItem = $this->sellerOrderService->getSellerOneOrder($store, $id);
        return ResponseHelper::success('Sipariş başarıyla getirildi', $orderItem);
    }
    public function confirmOrderItem($id)
    {
        $seller = auth('seller')->user();
        $store = Store::where('seller_id', $seller->id)->first();
        if(!$store){
            return ResponseHelper::error('Lütfen giriş yapınız');
        }
        $orderItem = $this->sellerOrderService->confirmItem($store, $id);
        return ResponseHelper::success('Sipariş başarıyla onaylandı', $orderItem);
    }
    public function cancelOrderItem($id)
    {
        $seller = auth('seller')->user();
        $store = Store::where('seller_id', $seller->id)->first();
        if(!$store){
            return ResponseHelper::error('Lütfen giriş yapınız');
        }
        $orderItem = $this->sellerOrderService->cancelSelectedItems($store, $id);
        return ResponseHelper::success('Sipariş başarıyla iptal edildi', $orderItem);
    }
}
