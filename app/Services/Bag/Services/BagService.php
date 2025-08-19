<?php

namespace App\Services\Bag\Services;

use App\Services\Bag\Contracts\BagInterface;
use App\Exceptions\InsufficientStockException;
use App\Models\Bag;
use App\Models\Campaign;

class BagService implements BagInterface
{
    protected $stockService;
    protected $bagCalculationService;

    public function __construct(StockService $stockService, BagCalculationService $bagCalculationService)
    {
        $this->stockService = $stockService;
        $this->bagCalculationService = $bagCalculationService;
    }

    public function getBag()
    {
        $user = auth()->user();
        $bag = Bag::where('bag_user_id', $user->id)->first();
        $bagItems = $bag ? $bag->bagItems()->with('product.category')->orderBy('id')->get() : collect();
        
        $bagItems = $bagItems->filter(function($item) use ($bag) {
            if (!$item->product || $item->product->deleted_at !== null) {
                $this->destroyBagItem($bag, $item->id);
                return false;
            }
            return true;
        });
        
        if($bagItems->isEmpty()){
            return ['products' => $bagItems, 'bestCampaign' => null, 'total' => 0, 'cargoPrice' => 0, 'discount' => 0, 'finalPrice' => 0];
        }
        
        $bestCampaign = $this->bagCalculationService->getBestCampaign($bagItems);
        $total = $this->bagCalculationService->calculateTotal($bagItems);
        $cargoPrice = $this->bagCalculationService->calculateCargoPrice($total);
        $discount = $this->bagCalculationService->calculateDiscount($bestCampaign);
        $finalPrice = $total + $cargoPrice - $discount;

        return [
            'products' => $bagItems, 
            'bestCampaign' => $bestCampaign, 
            'total' => $total, 
            'cargoPrice' => $cargoPrice, 
            'discount' => $discount, 
            'finalPrice' => $finalPrice
        ];
    }

    public function addToBag($bag, $productId)
    {
        try {
            $productItem = $this->stockService->checkStockAvailability($bag, $productId);
            
            if($productItem == null){
                return $this->stockService->reserveStock($bag, $productId);
            }
            return $this->stockService->reserveStock($bag, $productId);
        } catch (InsufficientStockException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    public function showBagItem($bag, $bagItemId)
    {
        return $bag->bagItems()->where('id', $bagItemId)->first();
    }

    public function updateBagItem($bag, $bagItemId, $quantity)
    {
        $bagItem = $this->showBagItem($bag, $bagItemId);
        if($bagItem){
            $bagItem->quantity = $quantity;
            $bagItem->save();
            return $bagItem;
        }
        else{
            return ['error' => 'Ürün bulunamadı!'];
        }
    }

    public function destroyBagItem($bag, $bagItemId)
    {
        $bagItem = $this->showBagItem($bag, $bagItemId);
        if($bagItem){
            $product = $bagItem->product;
            if($bagItem->quantity > 1){
                $bagItem->quantity -= 1;
                $bagItem->save();
                return $bagItem;
            }
            else{
                $bagItem->delete();
                return $bagItem;
            }
        }
        else{
            return ['error' => 'Ürün bulunamadı!'];
        }
    }
}