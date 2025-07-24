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

        $cargoPrice = $total >= 50 ? 0 : 10;

        $discount = $bestCampaign['discount'] ?? 0;

        $Totally = $total + $cargoPrice - $discount;
        $campaignInfo = $bestCampaign['description'] ?? '';    
        
        $order = Order::create([
            'Bag_User_id' => $user->id,
            'price' => $total + $cargoPrice,
            'cargo_price' => $cargoPrice,
            'campaign_info' => $campaignInfo,
            'campaing_price' => $Totally,
            'status' => 'bekliyor',
        ]);

        foreach($products as $p){
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $p->product->id,
                'quantity' => $p->quantity,
                'price' => $p->product->list_price,
            ]);
            CreateOrderJob::dispatch($orderItem);
        }

        foreach($products as $p){
            $productTable = Product::find($p->product_id);
            if($productTable->stock_quantity < $p->quantity) {
                return ResponseHelper::notFound('Ürün Stokta Yok!');
            }
            $productTable->stock_quantity -= $p->quantity;
            $productTable->save();
        }

        
    }
    

}