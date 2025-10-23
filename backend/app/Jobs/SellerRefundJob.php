<?php

namespace App\Jobs;

use App\Models\OrderItem;
use App\Services\Seller\SellerOrderPlacement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SellerRefundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly OrderItem $orderItem,
        private readonly array $payload,
        private readonly int $refundAmount,
    ) 
    {
        $this->onQueue('notifications');
    }

    public function handle(SellerOrderPlacement $sellerOrderPlacement): void
    {
        $sellerOrderPlacement->placeSellerOrder(
            $this->orderItem,
            $this->payload,
            $this->refundAmount
        );
    }
    public function failed($exception)
    {
        Log::error('SellerRefundJob failed: ' . $exception->getMessage());
    }
}