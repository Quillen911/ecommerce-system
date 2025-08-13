<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use App\Http\Requests\OrderRequest;
use App\Traits\UserBagTrait;
use App\Services\OrderService;
use App\Helpers\ResponseHelper;


class OrderController extends Controller
{
    use UserBagTrait;
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $user = $this->getUser();
        $orders = Order::where('bag_user_id', $user->id)->get();
        if($orders->isEmpty()){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Siparişler', $orders);
    }

    public function store(OrderRequest $request)
    {
        $user = $this->getUser();
        $bag = $this->getUserBag();

        if(!$bag){
            return ResponseHelper::error('Sepetiniz bulunamadı!');
        }
        $selectedCreditCard = $request->input('credit_card_id');
        if(!$selectedCreditCard){
            return ResponseHelper::error('Lütfen bir kredi kartı seçiniz!');
        }

        $products = $bag->bagItems()->with('product.category')->get();

        if($products->isEmpty()){
            return ResponseHelper::notFound('Sepetiniz boş!');
        }
        $result = $this->orderService->createOrder($user, $products, new CampaignManager(), $selectedCreditCard);
        if($result instanceof \Exception){
            return ResponseHelper::error($result->getMessage());
        }
        if(is_array($result) && isset($result['success'])){
            if($result['success']){
                $bag->bagItems()->delete();
                Cache::flush();
                return ResponseHelper::success('Sipariş oluşturuldu.', $products);
            }else{
                return ResponseHelper::error($result['error']);
            }
        }
        return ResponseHelper::error('Beklenmeyen bir hata oluştu!' , $result['error']);
    }

    public function show($id)
    {
        $user = $this->getUser();
        $order = $this->orderService->showOrder($user->id, $id);
        if(!$order){
            return ResponseHelper::notFound('Sipariş bulunamadı.');
        }
        return ResponseHelper::success('Sipariş', $order);
    }

}