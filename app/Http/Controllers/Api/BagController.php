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
use App\Helpers\ResponseHelper;
use App\Services\Bag\Contracts\BagInterface;
use App\Traits\UserBagTrait;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
class BagController extends Controller
{
    use UserBagTrait;
    protected $bagService;
    protected $authenticationRepository;
    public function __construct(BagInterface $bagService, AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->bagService = $bagService;
        $this->authenticationRepository = $authenticationRepository;
    }
    public function index()
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $bag = $this->getUserBag();
        
        if (!$bag) {
            return ResponseHelper::error('Sepetiniz bulunamadı!', 404);
        }
        
        $bagData = $this->bagService->getBag();
        if(empty($bagData['products']) || $bagData['products']->isEmpty()){
            return ResponseHelper::success('Sepetiniz boş!', []);
        }
        
        return ResponseHelper::success('Sepetiniz', $bagData);
    }
    public function store(BaseApiRequest $request)
    {
        $user = $this->authenticationRepository->getUser(); 
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $bag = Bag::firstOrCreate(['bag_user_id' => $user->id]);

        if(!$bag){
            return ResponseHelper::error('Sepetiniz bulunamadı!', 400);
        }

        $productItem = $this->bagService->addToBag($bag, $request->product_id);

        
        if (is_array($productItem) && isset($productItem['error'])) {
            return ResponseHelper::error($productItem['error'], 400);
        }
        
        if(!$productItem){
            return ResponseHelper::error('Ürün bulunamadı!', 404);
        }
        
        Cache::flush();
        return ResponseHelper::success('Ürün sepete eklendi.', $productItem);
    }

    public function show($id)
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $bag = $this->getUserBag();

        if(!$bag){
            return ResponseHelper::error('Sepet bulunamadı!', 404);
        }

        $bagItem = $this->bagService->showBagItem($bag, $id);
        if(!$bagItem){
            return ResponseHelper::error('Ürün bulunamadı!', 404);
        }
        
        return ResponseHelper::success('Ürün', $bagItem);
    }

    public function update(Request $request, $id)
    {
        $user = $this->authenticationRepository->getUser();
        $bag = $this->getUserBag();
        
        if(!$bag){
            return ResponseHelper::error('Sepet bulunamadı!', 404);
        }

        $quantity = $request->input('quantity');

        if($quantity < 1){
            return ResponseHelper::error('Ürün adedi 1\'den az olamaz!', 400);
        }

        $bagItem = $this->bagService->updateBagItem($bag, $id, $request->quantity);

        if(isset($bagItem['error'])){
            return ResponseHelper::error($bagItem['error'], 400);
        }

        Cache::flush();
        return ResponseHelper::success('Ürün adedi güncellendi.', $bagItem);
    }

    public function destroy($id)
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::error('Kullanıcı bulunamadı.', 404);
        }
        $bag = $this->getUserBag();

        if(!$bag){
            return ResponseHelper::error('Sepet bulunamadı!', 404);
        }

        $result = $this->bagService->destroyBagItem($bag, $id);
        
        if(isset($result['error'])){
            return ResponseHelper::error($result['error'], 400);
        }

        return ResponseHelper::success($result['message'] ?? 'Ürün sepetten silindi.');
    }
}
