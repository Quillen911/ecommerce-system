<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim</title>
</head>
<body>
    <h1>Sepetim</h1>
    @if($products->isEmpty())
        <strong>Sepetiniz boş</strong> <br> <br>
    @else
        <h2>Kullanıcı: {{auth()->user()->username}}</h2>
        <h3>Sepetteki Ürünler</h3>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <table border="5" cellpadding="8" cellspacing="0" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ürün Adı</th>
                    <th>Kategori ID</th>
                    <th>Kategori Adı</th>
                    <th>Yazar</th>
                    <th>Fiyat</th>
                    <th>Ürün Sayısı</th>
                    <th>Toplam Fiyat</th>
                    <th>           </th>
                </tr>
            </thead>
            <tbody>
                    @foreach($products as $p)
                        <tr>
                            <td>{{$p->product_id}}</td>
                            <td>{{$p->product->title}}</td>
                            <td>{{$p->product->category_id}}</td>
                            <td>{{$p->product->category?->category_title}}</td>
                            <td>{{$p->product->author}}</td>
                            <td>{{$p->product->list_price}}</td>
                            <td>{{$p->quantity}}</td>
                            <td>{{($p->quantity)*($p->product->list_price)}}</td>
                            <td>
                            <form action="{{route('bag.delete', $p->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" type="submit">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
        </table> <br>

        @if(isset($bestCampaign['discount']) && $bestCampaign['discount'] )
            <div style="color: green;" >
                <strong>Kampanya: </strong>{{ $bestCampaign['description'] }} <br>
                <strong>İndirim:  </strong>{{ number_format($bestCampaign['discount'],2) }} TL<br><br>
                @if(isset($bestCampaignModel) && $bestCampaignModel)
                    <strong>Kampanya Başlangıç: </strong>
                    {{ $bestCampaignModel->starts_at ? \Carbon\Carbon::parse($bestCampaignModel->starts_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }} <br>
                    
                    <strong>Kampanya Bitiş: </strong>
                    {{ $bestCampaignModel->ends_at ? \Carbon\Carbon::parse($bestCampaignModel->ends_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }} <br>
                    @php
                        $now = \Carbon\Carbon::now();
                        $endsAt = \Carbon\Carbon::parse($bestCampaignModel->ends_at);
                        $endsAtTimestamp = $endsAt->timestamp;
                    @endphp
                    <strong>Kalan Süre: </strong><span id="countdown" style="color: orange;"></span><br>
                    <script>
                        function updateCountdown() {
                            const now = Math.floor(Date.now() / 1000);
                            const endTime = {{ $endsAtTimestamp }};
                            const timeLeft = endTime - now;
                            
                            if (timeLeft <= 0) {
                                document.getElementById('countdown').innerHTML = 'Kampanya sona erdi. Lütfen yeni bir sipariş oluşturunuz veya sayfayı yenileyiniz.';
                                document.getElementById('countdown').style.color = 'red';
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
                            
                            document.getElementById('countdown').innerHTML = timeString;
                        }
                        
                        updateCountdown();
                        setInterval(updateCountdown, 1000);
                    </script>
                @endif
            </div>
        @endif

        <br>
    
        <a style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" href="{{route('order')}}">Sipariş Oluştur</a>
    @endif
    <button style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" type='button' onclick="window.location.href='{{route('main')}}'" class="btn-secondary"> Geri Dön</button>
</body>
</html> 