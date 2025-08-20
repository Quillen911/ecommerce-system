<?php

namespace App\Services\Bag\Services;

use App\Services\Bag\Contracts\StockInterface;
use App\Exceptions\InsufficientStockException;
use App\Models\Product;

class StockService implements StockInterface
{
   public function checkStockAvailability($bag, $productId)
   {
      $product = Product::find($productId);
      if(!$product){
         throw new InsufficientStockException('Ürün bulunamadı!', null, 0, 0);
      }
      
      $productItem = $bag->bagItems()->where('product_id', $productId)->first();
      $currentQuantity = $productItem ? $productItem->quantity : 0;
      
      if($product->stock_quantity <= $currentQuantity){
         throw new InsufficientStockException(
            'Stokta yeterli ürün yok!', 
            $product, 
            $currentQuantity, 
            $product->stock_quantity
         );
      }
      
      return $productItem;
   }

   public function reserveStock($bag, $productId)
   {
      $product = Product::find($productId);
      if(!$product){
         throw new InsufficientStockException('Ürün bulunamadı!', null, 0, 0);
      }

      $productItem = $bag->bagItems()->where('product_id', $productId)->first();

      if($productItem){
         $productItem->quantity += 1;
         $productItem->save();
         return $productItem;
      } else {
         return $bag->bagItems()->create([
            'product_id' => $productId,
            'product_title' => $product->title,
            'author' => $product->author,
            'quantity' => 1,
            'store_id' => $product->store_id
         ]);
      }
   }
}