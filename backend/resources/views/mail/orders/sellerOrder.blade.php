@component('mail::message')
# Merhaba {{ $seller->name }},

Yeni bir siparişiniz var.  

@component('mail::panel')
@foreach ($items as $item)
- {{ $item['product_title'] }} ({{ $item['color_name'] ?? '-' }}) – Adet: {{ $item['quantity'] }}
- {{ $item['payment_status']}}
@endforeach
@endcomponent

**Toplam:** ₺{{ number_format($order->grand_total_cents / 100, 2) }}  

@component('mail::button', ['url' => $actionUrl])
Siparişime Git
@endcomponent

@endcomponent
