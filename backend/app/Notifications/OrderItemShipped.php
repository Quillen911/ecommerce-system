<?php

namespace App\Notifications;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderItemShipped extends Notification
{
    use Queueable;

    protected $orderItem;

    /**
     * Create a new notification instance.
     */
    public function __construct(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'sms'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Siparişiniz kargoya teslim edilmiştir.')
            ->greeting('Merhaba ' . $notifiable->username . ',')
            ->line('Siparişiniz kargoya teslim edilmiştir.')
            ->line('Kargo takip numarası: ' . $this->orderItem->shippingItem->tracking_number)
            ->line('Herhangi bir sorunuz olursa bizimle iletişime geçmekten çekinmeyin.')
            ->line('Müşteri Destek: quillen048@gmail.com')
            ->salutation('Saygılarımızla, Quillen Ekibi');
    }

    public function toSms(object $notifiable): string
    {
        return "{$this->orderItem->id} numaralı siparişiniz kargoya teslim edilmiştir. Kargo takip numarası: {$this->orderItem->shippingItem->tracking_number}";
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_item_id' => $this->orderItem->id,
            'order_item_tracking_number' => $this->orderItem->shippingItem->tracking_number,
        ];
    }
}
