<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MyOrderService;
use App\Traits\UserBagTrait;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Services\Campaigns\CampaignManager\CampaignManager;

class MyOrdersController extends Controller
{
    use UserBagTrait;
    protected $myOrderService;

    public function __construct(MyOrderService $myOrderService)
    {
        $this->myOrderService = $myOrderService;
    }

    public function index()
    {
        $user = $this->getUser();
        $orders = $this->myOrderService->getOrdersforUser($user->id);
        if($orders->isEmpty()){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Siparişler', $orders);
    }
    
    public function show($id)
    {
        $user = $this->getUser();
        $order = $this->myOrderService->getOneOrderforUser($user->id, $id);
        if(!$order){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Sipariş', $order);
    }

    public function destroy($id, Request $request)
    {
        $user = $this->getUser();
        $order = $this->myOrderService->cancelOrder($user->id, $id, new CampaignManager());
        if(!$order){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Sipariş silindi ve ürün stokları güncellendi.', $order);
    }

    public function refundItems($id, Request $request)
    {
        $user = $this->getUser();
        $quantities = (array) $request->input('refund_quantities', []);
        $quantities = array_map('intval', $quantities);
        $quantities = array_filter($quantities, fn($q) => $q > 0);
        if (empty($quantities)) {
            return ResponseHelper::error('İade adedi giriniz.');
        }
        $result = $this->myOrderService->refundSelectedItems($user->id, $id, $quantities, new CampaignManager());
        return ($result['success'] ?? false)
          ? ResponseHelper::success('Seçilen ürünler için kısmi iade yapıldı.', $result)
          : ResponseHelper::error('İade işlemi başarısız.', $result['error'] ?? 'İade işlemi başarısız.');
    }
}