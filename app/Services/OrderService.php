<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Jobs\CreateOrderJob;
use Illuminate\Support\Facades\Cache;
use App\Helpers\ResponseHelper;


class OrderService
{
    public function createOrder($user, $products, $campaignManager)
    {
        $bestCampaign = $campaignManager->getBestCampaigns($products->all());
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

        CreateOrderJob::dispatch($orderData);

    }
    public function showOrder($user, $id)
    {
        return Order::where('Bag_User_id', $user)
                    ->where('id',$id)
                    ->first();
    }
    

}