<?php

namespace App\Services\Bag\Services;

use App\Services\Bag\Contracts\BagInterface;
use App\Exceptions\InsufficientStockException;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Repositories\Contracts\Bag\BagRepositoryInterface;
use App\Traits\GetUser;

class BagService implements BagInterface
{
    use GetUser;
    protected $authenticationRepository;
    protected $stockService;
    protected $bagCalculationService;
    protected $bagRepository;

    public function __construct(
        StockService $stockService, 
        BagCalculationService $bagCalculationService, 
        AuthenticationRepositoryInterface $authenticationRepository, 
        BagRepositoryInterface $bagRepository
    )
    {
        $this->authenticationRepository = $authenticationRepository;
        $this->stockService = $stockService;
        $this->bagCalculationService = $bagCalculationService;
        $this->bagRepository = $bagRepository;
    }

    public function getBag()
    {
        $user = $this->getUser();
        $bag = $this->bagRepository->getBag($user);
        $bagItems = $bag ? $bag->bagItems()->with('variantSize.productVariant.variantImages')->orderBy('id')->get() : collect();
        $bagItems = $this->checkProductAvailability($bagItems, $bag);
        
        if($bagItems->isEmpty()){
            return ['products' => $bagItems, 'bestCampaign' => null, 'total' => 0, 'cargoPrice' => 0, 'discount' => 0, 'finalPrice' => 0];
        }
        
        //$bestCampaign = $this->bagCalculationService->getBestCampaign($bagItems);
        $total = $this->bagCalculationService->calculateTotal($bagItems);
        $cargoPrice = $this->bagCalculationService->calculateCargoPrice($total);
        //$discount = $this->bagCalculationService->calculateDiscount($bestCampaign);
        //$finalPrice = $total + $cargoPrice - $discount;
        $finalPrice = $total + $cargoPrice;

        return [
            'products' => $bagItems, 
           // 'bestCampaign' => $bestCampaign, 
            'total_cents' => $total, 
            'total' => $total/100, 
            'cargoPrice_cents' => $cargoPrice, 
            'cargoPrice' => $cargoPrice/100, 
            //'discount' => $discount, 
            'finalPrice_cents' => $finalPrice,
            'finalPrice' => $finalPrice/100
        ];
    }

    public function addToBag($variantSizeId, $quantity = 1)
    {
        try {
            $user = $this->getUser();
            if(!$user){
                throw new \Exception('Kullanıcı bulunamadı!');
            }
            $bag = $this->bagRepository->createBag($user);
            if(!$bag){
                throw new \Exception('Sepet bulunamadı!');
            }
            $productItem = $this->stockService->checkStockAvailability($bag, $variantSizeId, $quantity);
            return $this->stockService->reserveStock($productItem['itemInTheBag'], $productItem['stock'], $bag, $variantSizeId, $quantity);
        } catch (InsufficientStockException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    public function showBagItem($bagItemId)
    {
        $user = $this->getUser();
        $bag = $this->bagRepository->getBag($user);
        if(!$bag){
            return null;
        }
        return $bag->bagItems()
                ->with('variantSize.productVariant.product')
                ->where('id', $bagItemId)
                ->where('bag_id', $bag->id)
                ->first();
    }

    public function updateBagItem($bagItemId, $quantity)
    {
        $bagItem = $this->showBagItem($bagItemId);
        if($bagItem){
            $bagItem->quantity = $quantity;
            $bagItem->save();
            return $bagItem;
        }
        else{
            return ['error' => 'Ürün bulunamadı!'];
        }
    }

    public function destroyBagItem($bagItemId)
    {
        $bagItem = $this->showBagItem($bagItemId);
        if($bagItem){
            $bagItem->delete();
            return ['success' => true, 'message' => 'Ürün sepetten kaldırıldı.'];
        }
        else{
            return ['error' => 'Ürün bulunamadı!'];
        }
    }
    private function checkProductAvailability($bagItems, $bag)
    {
        $bagItems = $bagItems->filter(function($item) use ($bag) {
            if (!$item->product || $item->product->deleted_at !== null) {
                if(!$item->variantSize->productVariant || $item->variantSize->productVariant->deleted_at !== null){
                    $item->delete();
                    return false;
                }
            }

            if($item->variantSize->inventory->available <= 0){
                $item->delete();
                return false;
            }
            return true;
        });
        return $bagItems;
    }

}