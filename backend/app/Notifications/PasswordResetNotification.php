<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly string $token)
    {
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = rtrim(config('app.frontend_url', env('FRONTEND_URL', '')), '/');
        $resetUrl = "{$frontendUrl}/login/reset-password?{$this->token}";

        return (new MailMessage)
            ->subject('Şifre Sıfırlama Talebi')
            ->markdown('mail.passwordReset', [
                'name' => $notifiable->username ?? $notifiable->first_name . ' ' . $notifiable->last_name,
                'url' => $resetUrl,
            ]);
    }
}
