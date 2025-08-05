<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\MyOrderService;
use App\Traits\UserBagTrait;
use App\Services\Campaigns\CampaignManager\CampaignManager;

class MyOrdersController extends Controller{

    use UserBagTrait;
    protected $myOrderService;

    public function __construct(MyOrderService $myOrderService)
    {
        $this->myOrderService = $myOrderService;
    }

    public function myorders()
    {
        $user = $this->getUser();
        $orders = $this->myOrderService->getOrdersforUser($user->id);
        if(!$orders){
            return redirect()->with('error', 'Sipariş Bulunamadı.');
        }
        return view('myorders', compact('orders'));
    }

    public function delete($id)
    {
        $user = $this->getUser();
        $order = $this->myOrderService->cancelOrder($user->id, $id, new CampaignManager());
        if(!$order){
            return redirect()->back()->with('error', 'Sipariş bulunamadı.');
        }
        return redirect()->route('myorders')->with('success', 'Sipariş başarıyla iptal edildi.');
    }
}
