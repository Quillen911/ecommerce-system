<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use App\Models\Seller;
class SellerOrderCreated extends Notification
{
    use Queueable;

    protected $order;
    protected $seller;
    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, Seller $seller)
    {
        $this->order = $order;
        $this->seller = $seller;
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
        $actionUrl = rtrim(env('FRONTEND_URL'), '/') . "/seller/order";
        return (new MailMessage)
            ->subject("Yeni Bir Siparişiniz Var")
            ->markdown('mail.orders.sellerOrder', [
                'actionUrl'       => $actionUrl,
                'order'           => $this->order,
                'seller'          => $this->seller,
                'items'           => $this->order->orderItems,
        ]);
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
            'order_total' => number_format(($this->order->grand_total_cents /100), 2),
            'message' => 'Siparişiniz (#' . $this->order->id . ') başarıyla oluşturuldu.'
        ];
    }
}
