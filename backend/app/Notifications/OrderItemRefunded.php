<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\OrderItem;
class OrderItemRefunded extends Notification implements ShouldQueue
{
    use Queueable;

    protected $orderItem;
    protected $price;
    protected $quantity;
    /**
     * Create a new notification instance.
     */
    public function __construct(OrderItem $orderItem, $quantity, $price) 
    {
        $this->orderItem = $orderItem;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Siparişiniz İade Edildi' . ' | Quillen')
            ->greeting('Merhaba ' . $notifiable->username . ',')
            ->line('Siparişiniz iade edildi. Aşağıda sipariş özetinizi görebilirsiniz:')
            ->line('📦 Sipariş Numarası: #' . $this->orderItem->order_id)
            ->line('📦 Ürün Adı: ' . $this->orderItem->product_title)
            ->line('📦 İade Edilen Adet: ' . $this->quantity)
            ->line('📦 İade Edilen Tutar: ₺' . number_format(floor($this->price *100 )/100, 2))
            ->action('Siparişimi Takip Et', 'http://localhost:8000/myorders')
            ->line('Herhangi bir sorunuz olursa bizimle iletişime geçmekten çekinmeyin.')
            ->line('Müşteri Destek: quillen048@gmail.com')
            ->salutation('Saygılarımızla, Quillen Ekibi');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'refunded_order_item_id' => $this->orderItem->id,
            'refunded_price' => $this->orderItem->paid_price,
            'message' => 'Siparişiniz (#' . $this->orderItem->id . ') iade edildi.'
        ];
    }
}
