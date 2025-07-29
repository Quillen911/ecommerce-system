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
        $order = Order::create([
            'Bag_User_id' => $this->orderData['user_id'],
            'price' => $this->orderData['total'] + $this->orderData['cargo_price'],
            'cargo_price' => $this->orderData['cargo_price'],
            'discount' => $this->orderData['discount'],
            'campaign_info' => $this->orderData['campaign_info'],
            'campaing_price' => $this->orderData['total'] + $this->orderData['cargo_price'] - $this->orderData['discount'],
            'status' => $this->orderData['status'],
        ]);
        foreach($this->orderData['products'] as $p){
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $p['product_id'],
                'quantity' => $p['quantity'],
                'price' => $p['price']
            ]);
        }
    }

    
}
