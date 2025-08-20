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
        $seller = auth('seller_web')->user();
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $orderItems = $this->sellerOrderService->getSellerOrders($store);
        return view('Seller.Order.sellersOrder', compact('orderItems'));
    }
    public function confirmOrderItem($id)
    {
        $seller = auth('seller_web')->user();
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $result = $this->sellerOrderService->confirmItem($store, $id);
        if($result['success']){
            return redirect()->route('seller.order')->with('success', $result['message']);
        }else{
            return redirect()->route('seller.order')->with('error', $result['message']);
        }
    }
    public function refundOrderItem($id)
    {
        $seller = auth('seller_web')->user();
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $orderItem = $this->sellerOrderService->refundSelectedItems($store, $id);
        if($orderItem['success']){
            return redirect()->route('seller.order')->with('success', $orderItem['message']);
        }else{
            return redirect()->route('seller.order')->with('error', $orderItem['message']);
        }
    }
}
