<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Notifications\PasswordResetNotification;
use Illuminate\Support\Facades\Log;

class ResetPasswordNotificationJob implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $token;
    /**
     * Create a new job instance.
     */
    public function __construct($user,$token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->notify(new PasswordResetNotification($this->token));
    }
    public function failed(\Throwable $exception)
    {
        Log::error("Password reset notification failed for user: " . $this->user->id . " - " . $exception->getMessage());
    }
}
