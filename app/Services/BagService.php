<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Bag;
use App\Models\BagItem;
use App\Models\Product;
use App\Helpers\ResponseHelper;

class BagService{

    public function getIndexBag($bag)
    {
        return $bag->bagItems()->with('product.category')->get();
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