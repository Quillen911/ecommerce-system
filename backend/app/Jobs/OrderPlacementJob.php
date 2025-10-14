<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\CheckoutSession;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Checkout\Orders\OrderPlacementService;

class OrderPlacementJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $userId,
        public readonly string $sessionId,
        public readonly array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(OrderplacementService $service): void
    {
        $user = User::find($this->userId);
        $session = CheckoutSession::where('id', $this->sessionId)->firstOrFail();

        $service->placeFromSession($user, $session, $this->data);
    }
}
