<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order;

class CreateOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderData;

    public function __construct($orderData = null)
    {
        $this->orderData = $orderData;
    }
    public function handle()
    {
        Log::info('CreateOrderJob çalıştı', ['orderData' => $this->orderData]);
        Order::create([
            'product_id' => $this->orderData['product_id'],
            'Bag_User_id' => $this->orderData['Bag_User_id'],
            'quantity' => $this->orderData['quantity'],
            'price' => $this->orderData['price'],
            'status' => $this->orderData['status'],
        ]);

    }
}
