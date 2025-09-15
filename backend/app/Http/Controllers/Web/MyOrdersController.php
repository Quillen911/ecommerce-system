<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\MyOrder\Contracts\MyOrderInterface;
use App\Services\MyOrder\Contracts\MyOrderRefundInterface;
use App\Services\Campaigns\CampaignManager;
use Illuminate\Http\Request;
use App\Http\Requests\MyOrders\RefundRequest;
class MyOrdersController extends Controller{

    protected $myOrderService;
    protected $myOrderRefundService;
    protected $campaignManager;
    
    public function __construct(
        MyOrderInterface $myOrderService,
        MyOrderRefundInterface $myOrderRefundService,
        CampaignManager $campaignManager
    )
    {
        $this->myOrderService = $myOrderService;
        $this->myOrderRefundService = $myOrderRefundService;
        $this->campaignManager = $campaignManager;
    }

    public function myorders()
    {
        $orders = $this->myOrderService->getOrdersforUser();
        if(!$orders){
            return redirect()->with('error', 'Sipariş Bulunamadı.');
        }
        return view('myorders', compact('orders'));
    }

    public function delete($id, Request $request)
    {
        $result = $this->myOrderRefundService->refundSelectedItems($id, [], $this->campaignManager);
        if(!$result['success']){
            return redirect()->back()->with('error', $result['error'] ?? 'Sipariş bulunamadı.');
        }
        return redirect()->route('myorders')->with('success', 'Sipariş başarıyla iptal edildi.');
    }

    public function refundItems($id, RefundRequest $request)
    {
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
        $result = $this->myOrderRefundService->refundSelectedItems($id, $quantities, $this->campaignManager);
        return ($result['success'] ?? false)
          ? redirect()->route('myorders')->with('success', $result['message'] ?? 'Kısmi iade yapıldı.')
          : redirect()->route('myorders')->with('error', $result['error'] ?? 'İade işlemi başarısız.');
    }
}
