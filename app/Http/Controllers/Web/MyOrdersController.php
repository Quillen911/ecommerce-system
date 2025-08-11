<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\MyOrderService;
use App\Traits\UserBagTrait;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use Illuminate\Http\Request;
use App\Http\Requests\MyOrders\RefundRequest;

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

    public function refundItems($id, RefundRequest $request)
    {
        $user = $this->getUser();
        $raw = (array) $request->input('refund_quantities', []);

        $quantities = [];
        foreach ($raw as $itemId => $qty) {
            $qty = (int) $qty;
            if ($qty > 0) {
                $quantities[(int) $itemId] = $qty;
            }
        }
        if (count($quantities) === 0) {
            return redirect()->route('myorders')->with('error', 'İade adedi giriniz.');
        }
        $result = $this->myOrderService->refundSelectedItems($user->id, $id, $quantities, new CampaignManager());
        return ($result['success'] ?? false)
          ? redirect()->route('myorders')->with('success', $result['message'] ?? 'Kısmi iade yapıldı.')
          : redirect()->route('myorders')->with('error', $result['error'] ?? 'İade işlemi başarısız.');
    }
}
