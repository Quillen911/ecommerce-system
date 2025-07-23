<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Bag;
use App\Models\BagItem;
use App\Models\Product;
use App\Traits\UserBagTrait;
use App\Helpers\ResponseHelper;

class BagService{

    use UserBagTrait;
    public function getIndexBag($bag)
    {
        return $products = $bag->bagItems()->with('product.category')->get();
    }

    public function getAddBag($bag, $productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return ResponseHelper::notFound('Ürün bulunamadı!');
        }
        $productItem = $bag->bagItems()->where('product_id', $productId)->first();

        // Sepette varsa, toplam miktar stoktan fazla olmasın
        $currentQuantity = $productItem ? $productItem->quantity : 0;
        if ($product->stock_quantity <= $currentQuantity) {
            return ResponseHelper::notFound('Stokta yeterli ürün yok!');
        }

        if ($productItem) {
            $productItem->quantity += 1;
            $productItem->save();
            return $productItem;
        } else {
            return $bag->bagItems()->create([
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }
    }
    
    public function showBagItem($bag, $productId)
    {
        return $bag->bagItems()
        ->where('product_id', $productId)
        ->first();   
    }

    public function destroyBagItem($bag, $productId)
    {
        $bagItem = $bag->bagItems()
                ->where('product_id', $productId)
                ->first();

        if ($bagItem) {
            $product = Product::find($bagItem->product_id);

            if ($bagItem->quantity > 1) {
                $bagItem->quantity -= 1;
                $bagItem->save();
                $message = 'Ürün sepetten 1 adet silindi.';
            } else {
                $bagItem->delete();
                $message = 'Ürün sepetten tamamen silindi.';
            }
            Cache::flush();
            return ResponseHelper::success($message, $product);
        } else {
            return ResponseHelper::notFound('Ürün bulunamadı!');
        }
    }
}