<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use App\Models\Seller;
class OrderCreated extends Notification
{
    use Queueable;

    protected $order;
        /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
            ->subject('Sipariş Onayı - #' . $this->order->id . ' | Quillen')
            ->greeting('Merhaba ' . $notifiable->username . ',')
            ->line('Siparişiniz başarıyla oluşturuldu. Aşağıda sipariş özetinizi görebilirsiniz:')
            ->line('📦 Sipariş Numarası: #' . $this->order->id)
            ->line('💳 Ödeme Tutarı: ₺' . number_format(floor($this->order->paid_price *100 )/100, 2))
            ->line('🛒 Ödeme Yöntemi: ' . ($this->order->credit_card_id ? 'Kredi Kartı' : 'Havale'))
            ->line('Siparişiniz en kısa sürede hazırlanıp kargoya verilecektir.')
            ->action('Siparişime Git', 'http://localhost:8000/myorders')
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
            'order_id' => $this->order->id,
            'order_total' => number_format(floor($this->order->paid_price *100 )/100, 2),
            'message' => 'Siparişiniz (#' . $this->order->id . ') başarıyla oluşturuldu.'
        ];
        
    }
}
