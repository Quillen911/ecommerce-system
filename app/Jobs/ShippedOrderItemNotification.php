<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\OrderItemShipped;
use Illuminate\Support\Facades\Log;

class ShippedOrderItemNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderItem;
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(OrderItem $orderItem, User $user)
    {
        $this->orderItem = $orderItem;
        $this->user = $user;

        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->notify(new OrderItemShipped($this->orderItem));
    }
    public function failed(\Throwable $exception)
    {
        Log::error("ShippedOrderItemNotification failed for order: " . $this->orderItem->id . " - " . $exception->getMessage());
    }
}
