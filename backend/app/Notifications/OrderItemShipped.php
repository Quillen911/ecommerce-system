<?php

namespace App\Notifications;

use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderItemShipped extends Notification
{
    use Queueable;

    protected OrderItem $orderItem;
    protected User $user;

    public function __construct(OrderItem $orderItem, User $user)
    {
        $this->orderItem = $orderItem;
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'sms'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $imageModel = $this->orderItem->variantSize?->productVariant?->variantImages?->first();
        $image = $imageModel?->image ? asset($imageModel->image) : null;

        $quantity = $this->orderItem->quantity;
        $refundedQuantity = $this->orderItem->refunded_quantity;
        $shippedQuantity = $quantity - $refundedQuantity;
        $actionUrl = rtrim(env('FRONTEND_URL', ''), '/') . "/account/orders/{$this->orderItem->order_id}";

        return (new MailMessage)
            ->subject('Siparişiniz Kargoya Teslim Edildi | Quillen')
            ->markdown('mail.orders.shipped', [
                'user' => $this->user,
                'orderItem' => $this->orderItem,
                'quantity' => $shippedQuantity,
                'actionUrl' => $actionUrl,
                'image' => $image,
            ]);
    }

    public function toSms(object $notifiable): string
    {
        return "{$this->orderItem->id} numaralı siparişiniz kargoya teslim edilmiştir.";
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_item_id' => $this->orderItem->id,
        ];
    }
}
