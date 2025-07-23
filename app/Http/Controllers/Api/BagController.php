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
    public function index(Request $request)
    {
        $user = $this->getUser();
        $bag = $this->getUserBag();

        if(!$bag){
            return ResponseHelper::notFound('Sepetiniz bulunamadı!');
        }
        $products = $bag->bagItems()->with('product.category')->get();
        if($products->isEmpty()){
            $products = "Ürün Yok!";
            return ResponseHelper::success('Sepetiniz boş!',$products);
        }
        return ResponseHelper::success('Sepetiniz', $products);
    }
    public function store(BaseApiRequest $request)
    {
        $user = $this->getUser(); 
        $bag = Bag::firstOrCreate(['Bag_User_id' => $user->id]);

        if(!$bag){
            return ResponseHelper::notFound('Sepetiniz bulunamadı!');
        }
        
        $productItem = $bag->bagItems()->where('product_id', $request->product_id)->first();
        $product = Product::find($request->product_id);
        
        if ($product->stock_quantity == 0) {
            return ResponseHelper::notFound('Ürün stokta yok!');

        } else if ($productItem) {
            $productItem->quantity += 1;
            $productItem->save();
            
        } else {
            $bag->bagItems()->create([
                'product_id' => $request->product_id,
                'quantity' => 1
            ]);
            
        }
        Cache::flush();
        return ResponseHelper::success('Ürün sepete eklendi.', $product);
    }

    public function show(Request $request)
    {
        $user = $this->getUser();
        $bag = $this->getUserBag();

        if(!$bag){
            return ResponseHelper::notFound('Sepet bulunamadı!');
        }
        $products = $bag->bagItems()->with('product.category')->get();
        $bagItem = $bag->bagItems()->where('product_id', $request->product_id)
                            ->where('bag_id', $bag->id)
                            ->first();
        
        return ResponseHelper::success('Ürün', $bagItem);
    }
    public function destroy(Request $request)
    {
        $user = $this->getUser();
        $bag = $this->getUserBag();

        if(!$bag){
            return ResponseHelper::notFound('Sepet bulunamadı!');
        }

        $result = $this->bagService->destroyBagItem($user->id, $request->product_id, $bag->id);

        if(!$result['success']){
            return ResponseHelper::notFound($result['message']);
        }

        return ResponseHelper::success($result['message'], $result['product']);
    }
}
