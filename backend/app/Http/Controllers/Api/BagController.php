<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Bag\BagStoreRequest;
use App\Http\Requests\Bag\SelectBagCampaignRequest;
use App\Helpers\ResponseHelper;
use App\Services\Bag\Contracts\BagInterface;
use App\Http\Resources\Bag\BagResource;

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
        Cache::flush();
        
        return ResponseHelper::success(
            'Sepetiniz',
            [
                'products' => BagResource::collection($bagData['products']),
                'total' => $bagData['total'],
                'cargoPrice' => $bagData['cargoPrice'],
                'finalPrice' => $bagData['finalPrice']
            ]
        );
    }
    public function store(BagStoreRequest $request)
    {
        $productItem = $this->bagService->addToBag($request->variant_size_id, $request->quantity);

        
        if (is_array($productItem) && isset($productItem['error'])) {
            return ResponseHelper::error($productItem['error'], 400);
        }
        
        if(!$productItem){
            return ResponseHelper::error('Ürün bulunamadı!', 404);
        }
        
        Cache::flush();
        $bagData = $this->bagService->getBag();
        return ResponseHelper::success(
            'Sepet güncellendi',
            [
                'products'   => BagResource::collection($bagData['products']),
                'total'      => $bagData['total'],
                'cargoPrice' => $bagData['cargoPrice'],
                'finalPrice' => $bagData['finalPrice'],
            ]
        );
    }

    public function select(SelectBagCampaignRequest $request)
    {
        $campaignId = $request->integer('campaign_id');

        $result = $this->bagService->selectCampaign($campaignId);
    }

    public function show($id)
    {
        $bagItem = $this->bagService->showBagItem($id);
        if(!$bagItem){
            return ResponseHelper::error('Ürün bulunamadı!', 404);
        }
        Cache::flush();
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

        Cache::flush();
        return ResponseHelper::success($result['message'] ?? 'Ürün sepetten silindi.');
    }
}
