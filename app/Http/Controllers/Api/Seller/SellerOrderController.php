<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
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
    public function index()
    {
        $seller = auth('seller')->user();
        if (!$seller) {
            return ResponseHelper::error('Unauthorized', 401);
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            return ResponseHelper::error('Lütfen giriş yapınız');
        }
        $orderItems = $this->sellerOrderService->getSellerOrders($store);
        return ResponseHelper::success('Gelen siparişler başarıyla getirildi', $orderItems);    
            
    }
    public function show($id)
    {
        $seller = auth('seller')->user();
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            return ResponseHelper::error('Lütfen giriş yapınız');
        }
        $orderItem = $this->sellerOrderService->getSellerOneOrder($store, $id);
        return ResponseHelper::success('Sipariş başarıyla getirildi', $orderItem);
    }
    public function confirmOrderItem($id)
    {
        $seller = auth('seller')->user();
        if (!$seller) {
            return ResponseHelper::error('Unauthorized', 401);
        }
        
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            return ResponseHelper::error('Lütfen giriş yapınız', 400);
        }
        
        $orderItem = $this->sellerOrderService->confirmItem($store, $id);
        
        if (!$orderItem) {
            return ResponseHelper::error('Sipariş bulunamadı veya size ait değil', 404);
        }
        
        return ResponseHelper::success('Sipariş başarıyla onaylandı', $orderItem);
    }
    public function refundOrderItem($id)
    {
        $seller = auth('seller')->user();
        if (!$seller) {
            return ResponseHelper::error('Unauthorized', 401);
        }
        
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            return ResponseHelper::error('Lütfen giriş yapınız', 400);
        }
        
        $result = $this->sellerOrderService->refundSelectedItems($store, $id);
        
        if (!$result['success']) {
            return ResponseHelper::error($result['message'] ?? 'Sipariş iade edilemedi', 400);
        }
        
        return ResponseHelper::success($result['message'], $result['orderItem']);
    }
}
