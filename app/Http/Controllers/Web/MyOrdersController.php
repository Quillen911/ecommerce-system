<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\MyOrder\Contracts\MyOrderInterface;
use App\Services\MyOrder\Contracts\MyOrderRefundInterface;
use App\Traits\UserBagTrait;
use App\Services\Campaigns\CampaignManager;
use Illuminate\Http\Request;
use App\Http\Requests\MyOrders\RefundRequest;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
class MyOrdersController extends Controller{

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

    public function myorders()
    {
        $user = $this->authenticationRepository->getUser();
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
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return redirect()->route('login')->with('error', 'Lütfen giriş yapınız.');
        }
        $result = $this->myOrderRefundService->refundSelectedItems($user->id, $id, [], $this->campaignManager);
        if(!$result['success']){
            return redirect()->back()->with('error', $result['error'] ?? 'Sipariş bulunamadı.');
        }
        return redirect()->route('myorders')->with('success', 'Sipariş başarıyla iptal edildi.');
    }

    public function refundItems($id, RefundRequest $request)
    {
        $user = $this->authenticationRepository->getUser();
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
        $result = $this->myOrderRefundService->refundSelectedItems($user->id, $id, $quantities, $this->campaignManager);
        return ($result['success'] ?? false)
          ? redirect()->route('myorders')->with('success', $result['message'] ?? 'Kısmi iade yapıldı.')
          : redirect()->route('myorders')->with('error', $result['error'] ?? 'İade işlemi başarısız.');
    }
}
