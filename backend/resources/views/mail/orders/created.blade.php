@component('mail::message')
# Merhaba {{ $user->first_name }},

Siparişiniz başarıyla oluşturuldu.  
**Sipariş No:** #{{ $order->id }}

@component('mail::panel')
@foreach ($items as $item)
- {{ $item['title'] }} ({{ $item['color'] ?? '-' }}) – Adet: {{ $item['quantity'] }}
@endforeach
@endcomponent

**Toplam:** ₺{{ number_format($order->grand_total_cents / 100, 2) }}  
**Teslimat Adresi:** {{ $shippingAddressLine1 }} {{ $shippingAddressLine2 }} {{ $shippingAddressCity }} {{ $shippingAddressCountry }} {{ $shippingAddressPostalCode }}

@component('mail::button', ['url' => $actionUrl])
Siparişime Git
@endcomponent

Herhangi bir sorunuz olursa bizi aramaktan çekinmeyin.  
Müşteri Destek: quillen048@gmail.com

Saygılarımızla,  
Quillen Ekibi
@endcomponent
