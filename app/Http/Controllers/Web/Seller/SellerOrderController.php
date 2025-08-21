<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Services\Seller\SellerOrderService;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;

class SellerOrderController extends Controller
{
    protected $sellerOrderService;
    protected $storeRepository;
    public function __construct(SellerOrderService $sellerOrderService, StoreRepositoryInterface $storeRepository)
    {
        $this->sellerOrderService = $sellerOrderService;
        $this->storeRepository = $storeRepository;
    }
    public function sellerOrders()
    {
        try{
            $seller = auth('seller_web')->user();
            if(!$seller){
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }
            $orderItems = $this->sellerOrderService->getSellerOrders($seller->id);
            return view('Seller.Order.sellersOrder', compact('orderItems'));
        }
        catch(\Exception $e){
            return redirect()->route('seller.order')->with('error', 'Siparişler alınamadı: ' . $e->getMessage());
        }
    }
    public function confirmOrderItem($id)
    {
        try{
            $seller = auth('seller_web')->user();
            if(!$seller){
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }
            $result = $this->sellerOrderService->confirmItem($seller->id, $id);
            return redirect()->route('seller.order')->with('success', 'Sipariş başarıyla onaylandı');
        }
        catch(\Exception $e){
            return redirect()->route('seller.order')->with('error', 'Sipariş onaylanamadı: ' . $e->getMessage());
        }
    }
    public function refundOrderItem($id)
    {
        try{
            $seller = auth('seller_web')->user();
            if(!$seller){
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }
            $orderItem = $this->sellerOrderService->refundSelectedItems($seller->id, $id);
            if($orderItem['success']){
                return redirect()->route('seller.order')->with('success', $orderItem['message']);
            }else{
                return redirect()->route('seller.order')->with('error', $orderItem['message']);
            }
        }
        catch(\Exception $e){
            return redirect()->route('seller.order')->with('error', 'Sipariş iade edilemedi: ' . $e->getMessage());
        }
    }
}
