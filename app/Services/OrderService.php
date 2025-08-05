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
        
        foreach($orderData['products'] as $productData) {
            $product = Product::find($productData['product_id']);
            
            if (!$product) {
                return ResponseHelper::error('Ürün bulunamadı', 404);
            }
            
            if ($product->stock_quantity < $productData['quantity']) {
                return ResponseHelper::error('Ürün stokta yok', 404);
            }
            
            $this->updateStock($productData['product_id'], $productData['quantity']);
        }

        if($bestCampaign['campaign_id'] && $bestCampaign['discount'] > 0){
            $appliedCampaign = Campaign::find($bestCampaign['campaign_id']);
            if($appliedCampaign){
                $campaignManager->userEligible($appliedCampaign);
                $campaignManager->decreaseUsageLimit($appliedCampaign);
            }
        }
           

        CreateOrderJob::dispatch($orderData)->onQueue('order_create');

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
        
        if (!$product) {
            return ResponseHelper::error('Ürün bulunamadı', 404);
        }
        
        if ($product->stock_quantity < $quantity) {
            return ResponseHelper::error('Ürün stokta yok', 404);
        }
        
        $product->stock_quantity -= $quantity;
        $product->save();
    }
    

}