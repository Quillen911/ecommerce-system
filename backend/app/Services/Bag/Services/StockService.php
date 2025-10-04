<?php

namespace App\Services\Bag\Services;

use App\Services\Bag\Contracts\StockInterface;
use App\Exceptions\InsufficientStockException;
use App\Models\Inventory;

class StockService implements StockInterface
{
   public function checkStockAvailability($bag, $variantSizeId, $quantity = 1)
   {
      $stock = Inventory::where('variant_size_id', $variantSizeId)->first();

      $itemInTheBag = $bag->bagItems()->where('variant_size_id', $variantSizeId)->first();
      $currentQuantity = $itemInTheBag ? $itemInTheBag->quantity : 0;

      if($stock->available < ($currentQuantity + $quantity)){
         throw new InsufficientStockException(
            'Stokta yeterli ürün yok!', 
            $stock, 
            $quantity, 
            $currentQuantity
         );
      }
      
      return [
         'stock' => $stock,
         'itemInTheBag' => $itemInTheBag,
      ];
   }

   public function reserveStock($itemInTheBag= null, $stock, $bag, $variantSizeId, $quantity = 1)
   {
      if($itemInTheBag){
         $itemInTheBag->quantity += $quantity;
         $itemInTheBag->save();
         return $itemInTheBag;
      } else {
         return $bag->bagItems()->create([
            'variant_size_id' => $variantSizeId,
            'product_title' => $stock->variantSize->productVariant->product->title,
            'quantity' => $quantity,
            'unit_price_cents' => $stock->variantSize->productVariant->price_cents,
            'store_id' => $stock->variantSize->productVariant->product->store_id
         ]);
      }
   }
}