<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\Product;
use App\Models\Category;
use App\Models\BagItem;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\BaseApiRequest;
use App\Traits\UserBagTrait;
use App\Helpers\ResponseHelper;
use App\Services\BagService;

class BagController extends Controller
{
    use UserBagTrait;
    protected $bagService;

    public function __construct(BagService $bagService)
    {
        $this->bagService = $bagService;
    }
    public function index()
    {
        $user = $this->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $bag = $this->getUserBag();
        
        if (!$bag) {
            return ResponseHelper::notFound('Sepetiniz bulunamadı!');
        }
        $products = $this->bagService->getBag($bag);
        if($products->isEmpty()){
            $products = "Ürün Yok!";
            return ResponseHelper::success('Sepetiniz boş!',$products);
        }
        return ResponseHelper::success('Sepetiniz', $products);

        
    }
    public function store(BaseApiRequest $request)
    {
        $user = $this->getUser(); 
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $bag = Bag::firstOrCreate(['bag_user_id' => $user->id]);

        if(!$bag){
            return ResponseHelper::notFound('Sepetiniz bulunamadı!');
        }

        $productItem = $this->bagService->getAddBag($bag, $request->product_id);
        if (is_array($productItem) && isset($productItem['error'])) {
            return ResponseHelper::error($productItem['error']);
        }
        if(!$productItem){
            return ResponseHelper::notFound('Ürün bulunamadı!');
        }
        Cache::flush();
        return ResponseHelper::success('Ürün sepete eklendi.', $productItem);
    }

    public function show($id)
    {
        $user = $this->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $bag = $this->getUserBag();

        if(!$bag){
            return ResponseHelper::notFound('Sepet bulunamadı!');
        }

        $bagItem = $this->bagService->showBagItem($bag, $id);
        if(!$bagItem){
            return ResponseHelper::notFound('Ürün bulunamadı!');
        }
        
        return ResponseHelper::success('Ürün', $bagItem);
    }
    public function destroy($id)
    {
        $user = $this->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $bag = $this->getUserBag();

        if(!$bag){
            return ResponseHelper::notFound('Sepet bulunamadı!');
        }

        $result = $this->bagService->destroyBagItem($bag, $id);
        
        if(isset($result['success']) && !$result['success']){
            return ResponseHelper::notFound($result['message']);
        }

        return ResponseHelper::success($result['message'] ?? 'Ürün sepetten silindi.');
    }
}
