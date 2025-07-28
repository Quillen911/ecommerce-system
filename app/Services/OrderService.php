<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Campaign;
use App\Jobs\CreateOrderJob;
use Illuminate\Support\Facades\Cache;
use App\Helpers\ResponseHelper;



class OrderService
{
    public function createOrder($user, $products, $campaignManager)
    {
        $campaigns = Campaign::where('is_active', 1)
                            ->where('starts_at', '<=', now())
                            ->where('ends_at', '>=', now())
                            ->get();
                            
        $bestCampaign = $campaignManager->getBestCampaigns($products->all(), $campaigns);
        $total = $products->sum(function($items) {
            return $items->quantity * $items->product->list_price; 
        });

        $cargo_price = $total >= 50 ? 0 : 10;

        $discount = $bestCampaign['discount'] ?? 0;

        $totally = $total + $cargo_price - $discount;
        $campaign_info = $bestCampaign['description'] ?? '';    
        
        $orderData = [
            'user_id' => $user->id,
            'products' => $products->map(function($item){
                return [
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->list_price,
                ];
               
            })->toArray(),
            'campaign_info' => $campaign_info,
            'total' => $total,
            'cargo_price' => $cargo_price,
            'discount' => $discount,
            'status' => 'bekliyor',   
            
        ];
        
        foreach($orderData['products'] as $product) {
            $product = Product::find($product['product_id']);
            if($product->stock_quantity < $product['quantity']){
                    throw new \Exception('Ürün stokta yok');
                }
                $product->stock_quantity -= $product['quantity'];
                $product->save();
            }
        
        CreateOrderJob::dispatch($orderData);

    }
    public function showOrder($user, $id)
    {
        return Order::where('Bag_User_id', $user)
                    ->where('id',$id)
                    ->first();
    }
    
    private function updateStock($productId, $quantity)
    {
        $product = Product::find($productId);
        if($product->stock_quantity < $quantity){
            throw new \Exception('Ürün stokta yok');
        }
        $product->stock_quantity -= $quantity;
        $product->save();
    }
    

}