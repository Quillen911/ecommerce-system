<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use Illuminate\Support\Collection;
use App\Models\User;
class OrderCreated extends Notification
{
    use Queueable;

    protected $order;
    protected $user;
        /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
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
        $items = $this->order->orderItems->map(function ($item) {
            $product = $item->product;
            $variant = $product?->variants->first();
            $image   = $variant?->variantImages->first();
            return [
                'title'      => $product?->title ?? 'Ürün',
                'color'      => $variant?->color_name,
                'quantity'   => $item->quantity,
                'image_url'  => $image?->image ? asset($image->image) : null,
            ];
        });

        $shippingAddressLine1 = $this->order->shippingAddress->address_line_1;
        $shippingAddressLine2 = $this->order->shippingAddress->address_line_2;
        $shippingAddressCity = $this->order->shippingAddress->city;
        $shippingAddressCountry = $this->order->shippingAddress->country;
        $shippingAddressPostalCode = $this->order->shippingAddress->postal_code;
        $actionUrl       = rtrim(env('FRONTEND_URL'), '/') . "/account/orders/{$this->order->id}";
       
        return (new MailMessage)
            ->subject("Sipariş Onayı - #{$this->order->id} | Quillen")
            ->markdown('mail.orders.created', [
                'user'            => $this->user,
                'order'           => $this->order,
                'items'           => $items,
                'shippingAddressLine1' => $shippingAddressLine1,
                'shippingAddressLine2' => $shippingAddressLine2,
                'shippingAddressCity' => $shippingAddressCity,
                'shippingAddressCountry' => $shippingAddressCountry,
                'shippingAddressPostalCode' => $shippingAddressPostalCode,
                'actionUrl'       => $actionUrl,
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
