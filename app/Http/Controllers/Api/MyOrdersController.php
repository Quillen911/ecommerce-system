<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MyOrderService;
use App\Traits\UserBagTrait;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Http\Requests\MyOrders\RefundRequest;
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
        return ResponseHelper::success('Sipariş İptal Edildi.', $order);
    }

    public function refundItems($id, RefundRequest $request)
    {
        $user = $this->getUser();
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
        $result = $this->myOrderService->refundSelectedItems($user->id, $id, $quantities, new CampaignManager());
        if ($result['success'] ?? false) {
            return ResponseHelper::success('Seçilen ürünler için kısmi iade yapıldı.', $result);
        }
        
        $errorMessage = $result['error'] ?? 'İade işlemi başarısız.';
        return ResponseHelper::errorForArray($errorMessage, $result, 400);
    }
}