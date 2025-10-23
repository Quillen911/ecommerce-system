<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderCreated;
use Illuminate\Support\Facades\Log;

class SendOrderNotification implements ShouldQueue 
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;

        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->order->load('orderItems.product.variants', 'orderItems.product.variants.variantImages', 'orderItems.product.variants.variantSizes');
        $this->user->notify(new OrderCreated($this->order, $this->user));
    }
    public function failed(\Throwable $exception)
    {
        Log::error("Order notification failed for order: " . $this->order->id . " - " . $exception->getMessage());
    }
}
