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
    protected $payload;
    protected $user;
    /**
     * Create a new notification instance.
     */
    public function __construct(OrderItem $orderItem, $payload, $price, $user) 
    {
        $this->orderItem = $orderItem;
        $this->payload = $payload;
        $this->price = $price;
        $this->user = $user;
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
        $imageModel = $this->orderItem->variantSize?->productVariant?->variantImages?->first();
        $image = $imageModel?->image ? asset($imageModel->image) : null;

        $reason = $this->payload['reason'] ?? null;
        $quantity = (int) ($this->payload['quantity'] ?? 0);
        $refundAmount = number_format($this->price / 100, 2, ',', '.');

        $actionUrl = rtrim(env('FRONTEND_URL', ''), '/') . "/account/orders/{$this->orderItem->order_id}";

        return (new MailMessage)
            ->subject('Siparişiniz İade Edildi | Quillen')
            ->markdown('mail.orders.refunded', [
                'user'          => $this->user,
                'orderItem'     => $this->orderItem,
                'quantity'      => $quantity ?: $this->orderItem->quantity,
                'price'         => $refundAmount,
                'actionUrl'     => $actionUrl,
                'image'         => $image,
                'reason'        => $reason,
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
            'refunded_order_item_id' => $this->orderItem->id,
            'refunded_price'         => $this->price,
            'quantity'               => (int) ($this->payload['quantity'] ?? 0),
            'reason'                 => $this->payload['reason'] ?? null,
            'message'                => 'Siparişiniz (#' . $this->orderItem->id . ') iade edildi.',
        ];
    }
}
