@component('mail::message')
# Merhaba {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->username ?? 'Müşterimiz') }},

Siparişinizde yer alan bir ürün için iade süreciniz tamamlandı. Ayrıntıları aşağıda paylaşıyoruz.

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

**İade Edilen Adet:** {{ $quantity }}
**İade Tutarı:** {{ $price }} ₺

@if (!empty($reason))
**İade Nedeni:** {{ $reason }}
@endif

@endcomponent

@component('mail::button', ['url' => $actionUrl])
İade İşlemini Görüntüle
@endcomponent

Sorularınız olursa dilediğiniz zaman bizimle iletişime geçebilirsiniz.

Saygılarımızla,  
**Quillen Ekibi**
@endcomponent
