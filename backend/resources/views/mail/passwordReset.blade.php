@component('mail::message')
# Merhaba {{ $name }},

Parolanızı sıfırlamak için bir talep aldık. Aşağıdaki butona tıklayarak yeni şifrenizi belirleyebilirsiniz.

@component('mail::button', ['url' => $url ])
Parolamı Sıfırla
@endcomponent

Eğer bu talebi siz yapmadıysanız bu e-postayı yok sayabilirsiniz.

Saygılarımızla,  
**Destek Ekibi**
@endcomponent
