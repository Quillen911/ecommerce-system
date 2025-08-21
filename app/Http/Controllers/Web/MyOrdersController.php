<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\MyOrder\Contracts\MyOrderInterface;
use App\Services\MyOrder\Contracts\MyOrderRefundInterface;
use App\Traits\UserBagTrait;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use Illuminate\Http\Request;
use App\Http\Requests\MyOrders\RefundRequest;


class MyOrdersController extends Controller{

    use UserBagTrait;
    protected $myOrderService;
    protected $myOrderRefundService;

    public function __construct(
        MyOrderInterface $myOrderService,
        MyOrderRefundInterface $myOrderRefundService
    )
    {
        $this->myOrderService = $myOrderService;
        $this->myOrderRefundService = $myOrderRefundService;
    }

    public function myorders()
    {
        $user = $this->getUser();
        if(!$user){
            return redirect()->route('login')->with('error', 'Lütfen giriş yapınız.');
        }
        $orders = $this->myOrderService->getOrdersforUser($user->id);
        if(!$orders){
            return redirect()->with('error', 'Sipariş Bulunamadı.');
        }
        return view('myorders', compact('orders'));
    }

    public function delete($id, Request $request)
    {
        $user = $this->getUser();
        if(!$user){
            return redirect()->route('login')->with('error', 'Lütfen giriş yapınız.');
        }
        $result = $this->myOrderRefundService->refundSelectedItems($user->id, $id, [], new CampaignManager());
        if(!$result['success']){
            return redirect()->back()->with('error', $result['error'] ?? 'Sipariş bulunamadı.');
        }
        return redirect()->route('myorders')->with('success', 'Sipariş başarıyla iptal edildi.');
    }

    public function refundItems($id, RefundRequest $request)
    {
        $user = $this->getUser();
        if(!$user){
            return redirect()->route('login')->with('error', 'Lütfen giriş yapınız.');
        }
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
        $result = $this->myOrderRefundService->refundSelectedItems($user->id, $id, $quantities, new CampaignManager());
        return ($result['success'] ?? false)
          ? redirect()->route('myorders')->with('success', $result['message'] ?? 'Kısmi iade yapıldı.')
          : redirect()->route('myorders')->with('error', $result['error'] ?? 'İade işlemi başarısız.');
    }
}
