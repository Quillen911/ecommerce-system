<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Bag;
use App\Models\BagItem;
use App\Models\Product;

class BagService{


    public function getAddBag($userId, $productId){

        $bag = Bag::firstOrCreate(['Bag_User_id' => $userId]);
        $productItem = $bag->bagItems()
                        ->where('product_id', $productId)
                        ->where('bag_id', $bag->id)
                        ->first();
        $product = Product::find($productId);

        if (!$product) {
            return ['success' => false, 'message' => 'Ürün bulunamadı!'];
        }
        if ($product->stock_quantity == 0) {
            return ['success' => false, 'message' => 'Ürün stokta yok!'];

        } else if ($productItem) {
            $productItem->quantity += 1;
            $productItem->save();
            
        } else {
            $bag->bagItems()->create([
                'product_id' => $productId,
                'quantity' => 1
            ]);
            
        }
        Cache::flush(); 
        return ['success' => true, 'message' => 'Ürün sepete eklendi!'];
    }
    

    public function destroyBagItem($userId, $productId, $bagId)
    {
        $bag = Bag::where('Bag_User_id', $userId)->first();
        if(!$bag){
            return ['success' => false, 'message' => 'Sepet bulunamadı!'];
        }
        $bagItem = $bag->bagItems()->where('product_id', $productId)
            ->where('bag_id', $bagId)
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
            return ['success' => true, 'message' => $message, 'product' => $product];
        } else {
            return ['success' => false, 'message' => 'Ürün bulunamadı!', 'product' => null];
        }
    }
}