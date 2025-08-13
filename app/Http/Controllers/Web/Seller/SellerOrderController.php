<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Services\Campaigns\Seller\SellerOrderService;
use App\Models\Store;
class SellerOrderController extends Controller
{
    protected $sellerOrderService;

    public function __construct(SellerOrderService $sellerOrderService)
    {
        $this->sellerOrderService = $sellerOrderService;
    }
    public function sellerOrders()
    {
        $seller = auth('seller')->user();
        $store = Store::where('seller_id', $seller->id)->first();
        if(!$store){
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $orderItems = $this->sellerOrderService->getSellerOrders($store);
        return view('Seller.Order.sellersOrder', compact('orderItems'));
    }
    public function confirmOrderItem($id)
    {
        $seller = auth('seller')->user();
        $store = Store::where('seller_id', $seller->id)->first();
        if(!$store){
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $orderItem = $this->sellerOrderService->confirmItem($store, $id);
        return redirect()->route('seller.order')->with('success', 'Sipariş başarıyla onaylandı');
    }
    public function cancelOrderItem($id)
    {
        $seller = auth('seller')->user();
        $store = Store::where('seller_id', $seller->id)->first();
        if(!$store){
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $orderItem = $this->sellerOrderService->cancelSelectedItems($store, $id);
        return redirect()->route('seller.order')->with('success', 'Sipariş başarıyla iptal edildi');
    }
}
