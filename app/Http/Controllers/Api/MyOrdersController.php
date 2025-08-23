<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MyOrder\Contracts\MyOrderInterface;
use App\Services\MyOrder\Contracts\MyOrderRefundInterface;
use App\Traits\UserBagTrait;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Http\Requests\MyOrders\RefundRequest;
use App\Services\Campaigns\CampaignManager;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
class MyOrdersController extends Controller
{
    use UserBagTrait;
    protected $myOrderService;
    protected $myOrderRefundService;
    protected $authenticationRepository;
    protected $campaignManager;
    
    public function __construct(
        MyOrderInterface $myOrderService,
        MyOrderRefundInterface $myOrderRefundService,
        AuthenticationRepositoryInterface $authenticationRepository,
        CampaignManager $campaignManager
    )
    {
        $this->myOrderService = $myOrderService;
        $this->myOrderRefundService = $myOrderRefundService;
        $this->authenticationRepository = $authenticationRepository;
        $this->campaignManager = $campaignManager;
    }

    public function index()
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $orders = $this->myOrderService->getOrdersforUser($user->id);
        if($orders->isEmpty()){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Siparişler', $orders);
    }
    
    public function show($id)
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $order = $this->myOrderService->getOneOrderforUser($user->id, $id);
        if(!$order){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Sipariş', $order);
    }

    public function destroy($id, Request $request)
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        
        $result = $this->myOrderRefundService->refundSelectedItems($user->id, $id, [], $this->campaignManager);
        if(!$result['success']){
            return ResponseHelper::notFound($result['error'] ?? 'Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Sipariş İptal Edildi.', $result);
    }

    public function refundItems($id, RefundRequest $request)
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
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
        $result = $this->myOrderRefundService->refundSelectedItems($user->id, $id, $quantities, $this->campaignManager);
        if ($result['success'] ?? false) {
            return ResponseHelper::success('Seçilen ürünler için kısmi iade yapıldı.', $result);
        }
        
        $errorMessage = $result['error'] ?? 'İade işlemi başarısız.';
        return ResponseHelper::errorForArray($errorMessage, $result, 400);
    }
}