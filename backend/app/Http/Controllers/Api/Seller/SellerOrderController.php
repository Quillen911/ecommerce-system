<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Services\Seller\SellerOrderService;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
class SellerOrderController extends Controller
{
    protected $sellerOrderService;
    protected $storeRepository;
    protected $authenticationRepository;
    public function __construct(SellerOrderService $sellerOrderService, StoreRepositoryInterface $storeRepository, AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->sellerOrderService = $sellerOrderService;
        $this->storeRepository = $storeRepository;
        $this->authenticationRepository = $authenticationRepository;
    }
    public function index()
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
            if (!$seller) {
                return ResponseHelper::error('Mağaza bulunamadı');
            }
            $orderItems = $this->sellerOrderService->getSellerOrders($seller->id);
            return ResponseHelper::success('Gelen siparişler başarıyla getirildi', $orderItems);    
        }
        catch(\Exception $e){
            return ResponseHelper::error('Siparişler alınamadı: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
            if (!$seller) {
                return ResponseHelper::error('Mağaza bulunamadı');
            }
            $orderItem = $this->sellerOrderService->getSellerOneOrder($seller->id, $id);
            return ResponseHelper::success('Sipariş başarıyla getirildi', $orderItem);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Sipariş bulunamadı: ' . $e->getMessage());
        }
    }
    public function confirmOrderItem($id)
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
            if (!$seller) {
                return ResponseHelper::error('Mağaza bulunamadı');
            }
            $orderItem = $this->sellerOrderService->confirmItem($seller->id, $id);
        
            if (!$orderItem) {
                return ResponseHelper::error('Sipariş bulunamadı veya size ait değil');
            }
            
            return ResponseHelper::success('Sipariş başarıyla onaylandı', $orderItem);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Sipariş onaylanamadı: ' . $e->getMessage());
        }
    }
    public function refundOrderItem($id)
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
            if (!$seller) {
                return ResponseHelper::error('Mağaza bulunamadı');
            }
            $result = $this->sellerOrderService->refundSelectedItems($seller->id, $id);
            return ResponseHelper::success('Sipariş başarıyla iade edildi', $result);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Sipariş iade edilemedi: ' . $e->getMessage());
        }
    }
    
}
