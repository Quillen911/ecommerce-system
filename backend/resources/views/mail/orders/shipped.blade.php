@component('mail::message')
# Merhaba {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->username ?? 'Müşterimiz') }},

Siparişinizde yer alan bir ürün başarıyla kargoya teslim edilmiştir. Detayları aşağıda bulabilirsiniz.

@component('mail::panel')
@if (!empty($image))
<p style="text-align:center;margin-bottom:16px;">
    <img src="{{ $image }}" alt="{{ $orderItem['product_title'] }}" style="max-width:160px;border-radius:12px;">
</p>
@endif

**Ürün:** {{ $orderItem['product_title'] }}

@if (!empty($orderItem['color_name']))
**Renk:** {{ $orderItem['color_name'] }}
@endif

@if (!empty($orderItem['size_name']))
**Beden:** {{ $orderItem['size_name'] }}
@endif

**Kargoya Verilen Adet:** {{ $quantity }}

@endcomponent

@component('mail::button', ['url' => $actionUrl])
Siparişi Takip Et
@endcomponent

Kargonuz en kısa sürede elinize ulaşacaktır. Bizi tercih ettiğiniz için teşekkürler, sorularınız olması durumunda bizimle iletişime geçebilirsiniz.

Saygılarımızla,  
**Quillen Ekibi**
@endcomponent
