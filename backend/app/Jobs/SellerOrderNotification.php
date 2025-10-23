<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Notifications\SellerOrderCreated;
use Illuminate\Support\Facades\Log;

class SellerOrderNotification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $seller;
    /**
     * Create a new job instance.
     */
    public function __construct($order,$seller)
    {
        $this->order = $order;
        $this->seller = $seller;
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->seller->notify(new SellerOrderCreated($this->order, $this->seller));
    }
    public function failed(\Throwable $exception)
    {
        Log::error("Seller order notification failed for order: " . $this->order->id . " - " . $exception->getMessage());
    }
}
