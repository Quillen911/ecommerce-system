<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\OrderItemRefunded;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;

class RefundOrderItemNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $orderItem;
    protected $user;
    protected $quantity;
    protected $price;
    /**
     * Create a new job instance.
     */
    public function __construct(OrderItem $orderItem, User $user, $quantity, $price)
    {
        $this->orderItem = $orderItem;
        $this->user = $user;
        $this->quantity = $quantity;
        $this->price = $price;
        
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->notify(new OrderItemRefunded($this->orderItem, $this->quantity, $this->price));
    }
}
