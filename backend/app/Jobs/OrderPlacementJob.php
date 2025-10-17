<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\CheckoutSession;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Checkout\Orders\OrderPlacementService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;


class OrderPlacementJob implements ShouldQueue
{
    use Queueable, Dispatchable, SerializesModels;

    /**
     * Create a new job instance.
     */

    
    public function __construct(
        public readonly User $user,
        public readonly CheckoutSession $session,
        public readonly array $data)
    {
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(OrderPlacementService $service): void
    {
        $service->placeFromSession($this->user, $this->session, $this->data);
    }
}
