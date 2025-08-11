<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Bag;
use App\Models\BagItem;
use App\Models\Product;
use App\Helpers\ResponseHelper;
use App\Services\Campaigns\CampaignManager\CampaignManager;
use App\Models\Campaign;

class BagService{

    public function getBag($bag)
    {
        return $bag->bagItems()->with('product.category')->get();
    }

    public function getIndexBag()
    {
        $user = auth()->user();
        $bag = Bag::where('bag_user_id', $user->id)->first();
        $products = $bag ? $bag->bagItems()->with('product.category')->orderBy('id')->get() : collect();
        $campaigns = Campaign::where('is_active', 1)->get();
        $campaignManager = new CampaignManager();
        $bestCampaign = $campaignManager->getBestCampaigns($products->all(), $campaigns);
        
        $bestCampaignModel = null;
        if (!empty($bestCampaign['description'])) {
            $bestCampaignModel = Campaign::where('description', $bestCampaign['description'])->first();
        }
        
        $total = $products->sum(function($item){
            return $item->quantity * $item->product->list_price;
        });

        $cargoPrice = $total >= 50 ? 0 : 10;
        
        $discount = $bestCampaign['discount'] ?? 0;
        $creditCards = $user->creditCard()->where('is_active', true)->get();
        $Totally = $total +$cargoPrice -$discount;
        return ['products' => $products, 'bestCampaign' => $bestCampaign, 'total' => $total, 'cargoPrice' => $cargoPrice, 'discount' => $discount, 'Totally' => $Totally, 'bestCampaignModel' => $bestCampaignModel, 'creditCards' => $creditCards];
    }

    public function getAddBag($bag, $productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return ResponseHelper::notFound('Ürün bulunamadı!');
        }
        $productItem = $bag->bagItems()->where('product_id', $productId)->first();

        $currentQuantity = $productItem ? $productItem->quantity : 0;
        if ($currentQuantity >= $product->stock_quantity) {
            return ['error' => 'Stokta yeterli ürün yok!'];
        }

        if ($productItem) {
            $productItem->quantity += 1;
            $productItem->save();
            return $productItem;
        } else {
            return $bag->bagItems()->create([
                'product_id' => $productId,
                'product_title' => $product->title,
                'author' => $product->author,
                'quantity' => 1
            ]);
        }
    }
    
    public function showBagItem($bag, $bagItemId)
    {
        return $bag->bagItems()
        ->where('id', $bagItemId)
        ->first();   
    }

    public function destroyBagItem($bag, $bagItemId)
    {
        $bagItem = $bag->bagItems()->where('id', $bagItemId)->first();

        if ($bagItem) {
            $product = $bagItem->product;

            if ($bagItem->quantity > 1) {
                $bagItem->quantity -= 1;
                $bagItem->save();
                $message = 'Ürün sepetten 1 adet silindi.';
            } else {
                $bagItem->delete();
                $message = 'Ürün sepetten tamamen silindi.';
            }
            Cache::flush();
            return ['success' => true, 'message' => $message];
        } else {
            return ['success' => false, 'message' => 'Ürün bulunamadı!'];
        }
    }
}