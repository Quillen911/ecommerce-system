<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\BagItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use App\Jobs\CreateOrderJob;
use App\Models\OrderItem;
use App\Services\Campaigns\CampaignManager;
use App\Services\Campaigns\SabahattinAliCampaign;
use App\Services\Campaigns\LocalAuthorCampaign;
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
        $orders = Order::where('Bag_User_id', $user->id)->get();
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

        $products = $bag->bagItems()->with('product.category')->get();

        if($products->isEmpty()){
            return ResponseHelper::notFound('Sepetiniz boş!');
        }

        $result = $this->orderService->createOrder($user, $products, new CampaignManager());

        $bag->bagItems()->delete();
        return ResponseHelper::success('Sipariş oluşturuldu.', $products);
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