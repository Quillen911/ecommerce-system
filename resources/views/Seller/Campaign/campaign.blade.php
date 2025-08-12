<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satıcı Kampanya</title>
</head>
<body>
    <h1>Kampanya Ayarları</h1>

    <table border="1" width="100%" style="text-align: center; margin-bottom: 20px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kampanya Adı</th>
                <th>Kampanya Tipi</th>
                <th>Kampanya Açıklaması</th>
                <th>Kampanya Önceliği</th>
                <th>Kalan Limit</th>
                <th>Kullanıcı Kullanım Limit</th>
                <th>Kampanya Başlangıç Tarihi</th>
                <th>Kampanya Bitiş Tarihi</th>
                <th>Kalan Süre</th>
                <th>Kampanya Aktiflik Durumu</th>
                <th>Düzenle</th>
            </tr>
        </thead>
        <tbody>
        @foreach($campaigns as $campaign)
            <tr>
                <td>{{ $campaign->id }}</td>
                <td>{{ $campaign->name }}</td>
                <td>{{ $campaign->type }}</td>
                <td>{{ $campaign->description }}</td>
                <td>{{ $campaign->priority ?? 'Yok' }}</td>
                <td>{{ $campaign->usage_limit }}</td> 
                <td>{{ $campaign->usage_limit_for_user }}</td>
                <td>{{ $campaign->starts_at }}</td>
                <td>{{ $campaign->ends_at }}</td>
                @php
                    $now = \Carbon\Carbon::now();
                    $endsAt = \Carbon\Carbon::parse($campaign->ends_at);
                    $endsAtTimestamp = $endsAt->timestamp;
                @endphp
                <td>
                    @if($campaign->usage_limit == 0)
                        <p style="color: red; font-size: 15.3px; font-weight: italic;">Kullanım Limiti Tükendi</p>
                    @else
                    <span id="countdown-{{ $campaign->id }}" style="color: darkgreen;"></span><br>
                        <script>
                            function updateCountdown() {
                                const now = Math.floor(Date.now() / 1000);
                                const endTime = {{ $endsAtTimestamp }};
                                const timeLeft = endTime - now;
                                
                                if (timeLeft <= 0) {
                                    document.getElementById('countdown-{{ $campaign->id }}').innerHTML = 'Kampanya sona erdi.';
                                    document.getElementById('countdown-{{ $campaign->id }}').style.color = 'red';
                                    return;
                                }
                                
                                const days = Math.floor(timeLeft / 86400);
                                const hours = Math.floor((timeLeft % 86400) / 3600);
                                const minutes = Math.floor((timeLeft % 3600) / 60);
                                const seconds = timeLeft % 60;
                                
                                let timeString = '';
                                if (days > 0) timeString += days + ' gün ';
                                if (hours > 0) timeString += hours + ' saat ';
                                if (minutes > 0) timeString += minutes + ' dakika ';
                                timeString += seconds + ' saniye';
                                
                                document.getElementById('countdown-{{ $campaign->id }}').innerHTML = timeString;
                            }
                            
                            updateCountdown();
                            setInterval(updateCountdown, 1000);
                        </script>
                    @endif    
                </td>
                <td>{{ $campaign->is_active ? 'Aktif' : 'Pasif' }}</td>
                <td>
                    <a href="{{ route('seller.editCampaign', $campaign->id) }}">Düzenle</a>
                </td>
                <td>
                    <form action="{{ route('seller.deleteCampaign', $campaign->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
    </table>
    <br>
    
    <a href="{{ route('seller.storeCampaign') }}">Kampanya Ekle</a><br>
    <a href="{{ route('seller') }}">Geri Dön</a> 
</body>
</html>
