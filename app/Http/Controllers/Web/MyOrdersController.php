<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\MyOrderService;
use App\Traits\UserBagTrait;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use Illuminate\Http\Request;

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

    public function delete($id, Request $request)
    {
        $user = $this->getUser();
        $order = $this->myOrderService->cancelOrder($user->id, $id, new CampaignManager());
        if(!$order){
            return redirect()->back()->with('error', 'Sipariş bulunamadı.');
        }
        return redirect()->route('myorders')->with('success', 'Sipariş başarıyla iptal edildi.');
    }

    public function refundItems($id, Request $request)
    {
        $user = $this->getUser();
        $quantities = (array) $request->input('refund_quantities', []);
        $quantities = array_map('intval', $quantities);
        $quantities = array_filter($quantities, fn($q) => $q > 0);
        if (empty($quantities)) {
            return redirect()->route('myorders')->with('error', 'İade adedi giriniz.');
        }
        $result = $this->myOrderService->refundSelectedItems($user->id, $id, $quantities, new CampaignManager());
        return ($result['success'] ?? false)
          ? redirect()->route('myorders')->with('success', $result['message'] ?? 'Kısmi iade yapıldı.')
          : redirect()->route('myorders')->with('error', $result['error'] ?? 'İade işlemi başarısız.');
    }
}
