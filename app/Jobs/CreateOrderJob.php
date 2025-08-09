<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Campaign;

class CreateOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderData;

    public function __construct($orderData)
    {
        $this->queue = 'order_create';
        $this->orderData = $orderData;
    }
    
    public function handle()
    {
        try {
            foreach($this->orderData['products'] as $productData) {
                $product = Product::find($productData['product_id']);
                
                if (!$product) {
                    throw new \Exception('Ürün bulunamadı: ' . $productData['product_id']);
                }
                
                if ($product->stock_quantity < $productData['quantity']) {
                    throw new \Exception('Ürün stokta yok: ' . $product->title);
                }
                
                $product->stock_quantity -= $productData['quantity'];
                $product->save();
            }
            
            $order = Order::create([
                'Bag_User_id' => $this->orderData['user_id'],
                'user_id' => $this->orderData['user_id'],
                'credit_card_id' => $this->orderData['credit_card_id'] ?? null,
                'card_holder_name' => $this->orderData['credit_card_holder'] ?? null,
                'price' => $this->orderData['total'] + $this->orderData['cargo_price'],
                'cargo_price' => $this->orderData['cargo_price'],
                'discount' => $this->orderData['discount'],
                'campaign_id' => $this->orderData['campaign_id'] ?? null,
                'campaign_info' => $this->orderData['campaign_info'] ?? null,
                'campaing_price' => $this->orderData['total'] + $this->orderData['cargo_price'] - $this->orderData['discount'],
                'status' => $this->orderData['status'],
            ]);
            
            foreach($this->orderData['products'] as $productData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price']
                ]);
            }
            
            Log::info('Order created successfully via job', ['order_id' => $order->id]);
            
        } catch (\Exception $e) {
            Log::error('Error creating order via job: ' . $e->getMessage());
            throw $e;
        }
    }
}
