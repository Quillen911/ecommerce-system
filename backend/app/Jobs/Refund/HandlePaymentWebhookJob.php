<?php

namespace App\Jobs\Refund;

use App\Services\Order\Services\Refund\OrderRefundService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class HandlePaymentWebhookJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly array $payload
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OrderRefundService $refundService)
    {
        $refundService->handlePaymentWebhook($this->payload);
    }
}