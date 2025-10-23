{{-- resources/views/mail/orders/refunded.blade.php --}}
@component('mail::message')
# Merhaba {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->username ?? 'Müşterimiz') }},

Siparişinizde yer alan bir ürün için iade süreciniz tamamlandı. Aşağıda detayları bulabilirsiniz.

@component('mail::panel')
@if (!empty($image))
<p style="text-align:center;margin-bottom:16px;">
    <img src="{{ $image }}" alt="{{ $orderItem['product_title'] }}" style="max-width:160px;border-radius:12px;">
</p>
@endif

**Ürün:** {{ $orderItem['product_title'] }}

**Renk:** {{ $orderItem['color_name'] ?? '-' }}  
**Adet:** {{ $orderItem['quantity'] }}  
**Durum:** {{ ucfirst($orderItem['payment_status'] == 'Refunded' ? 'İade Edildi' : 'İade Edildi') }}
@endcomponent

@component('mail::button', ['url' => $actionUrl])
İade İşlemini Görüntüle
@endcomponent

Sorularınız için istediğiniz zaman bizimle iletişime geçebilirsiniz.

Saygılarımızla,  
**Quillen Ekibi**
@endcomponent
