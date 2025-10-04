<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\BagStoreRequest;
use App\Helpers\ResponseHelper;
use App\Services\Bag\Contracts\BagInterface;

class BagController extends Controller
{

    protected $bagService;
    public function __construct(BagInterface $bagService)
    {
        $this->bagService = $bagService;
    }
    public function index()
    {
        $bagData = $this->bagService->getBag();
        if(empty($bagData['products']) || $bagData['products']->isEmpty()){
            return ResponseHelper::success('Sepetiniz boş!', []);
        }
        
        return ResponseHelper::success('Sepetiniz', $bagData);
    }
    public function store(BagStoreRequest $request)
    {
        $productItem = $this->bagService->addToBag($request->product_id);

        
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
        $bagItem = $this->bagService->showBagItem($id);
        if(!$bagItem){
            return ResponseHelper::error('Ürün bulunamadı!', 404);
        }
        
        return ResponseHelper::success('Ürün', $bagItem);
    }

    public function update(Request $request, $id)
    {
        $quantity = $request->input('quantity');

        if($quantity < 1){
            return ResponseHelper::error('Ürün adedi 1\'den az olamaz!', 400);
        }

        $bagItem = $this->bagService->updateBagItem($id, $request->quantity);

        if(isset($bagItem['error'])){
            return ResponseHelper::error($bagItem['error'], 400);
        }

        Cache::flush();
        return ResponseHelper::success('Ürün adedi güncellendi.', $bagItem);
    }

    public function destroy($id)
    {
        $result = $this->bagService->destroyBagItem($id);
        
        if(isset($result['error'])){
            return ResponseHelper::error($result['error'], 400);
        }

        return ResponseHelper::success($result['message'] ?? 'Ürün sepetten silindi.');
    }
}
