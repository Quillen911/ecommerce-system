@component('mail::message')
# Merhaba {{ $seller->name }},

Yeni bir siparişiniz var.  

@component('mail::panel')
@foreach ($items as $item)
- {{ $item['product_title'] }} ({{ $item['color_name'] ?? '-' }}) – Adet: {{ $item['quantity'] }}

@php
    $status = $item['payment_status'] ?? 'unknown';
@endphp
@if ($status === 'paid')
- Ödeme Durumu: Ödendi
@elseif ($status === 'pending')
- Ödeme Durumu: Bekliyor
@elseif ($status === 'failed')
- Ödeme Durumu: Başarısız
@elseif ($status === 'refunded')
- Ödeme Durumu: İade Edildi
@else
- Ödeme Durumu: Bilinmiyor
@endif
@endforeach
@endcomponent

**Toplam:** ₺{{ number_format($order->grand_total_cents / 100, 2) }}  

@component('mail::button', ['url' => $actionUrl])
Siparişime Git
@endcomponent

@endcomponent
