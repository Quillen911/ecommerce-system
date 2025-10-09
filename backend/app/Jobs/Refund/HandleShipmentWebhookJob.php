<?php

namespace App\Jobs\Refund;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Order\Services\Refund\OrderRefundService;

class HandleShipmentWebhookJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $payload
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(OrderRefundService $refundService): void
    {
        $refundService->handleShipmentWebhook($this->payload);
    }
}
