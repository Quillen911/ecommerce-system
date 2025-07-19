<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişini Tamamla</title>
</head>
<body>
    <h1>Sipariş Özeti</h1>
    
    
    @if(isset($success))
        <p>{{$success}}</p>
    @endif
    @if(isset($error))
        <p>{{$error}}</p>
    @endif
    @if($products->isEmpty())
        <strong>Siparişiniz yok</strong> <br> <br>
    @else
        <table border="5" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Ürün Adı</th>
                    <th>Kategori Adı</th>
                    <th>Yazar</th>
                    <th>Ürün Sayısı</th>
                    <th>Fiyat</th>
                    <th>Toplam Fiyat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                    <tr>
                        <td>{{$p->product->title}}</td>
                        <td>{{$p->product->category?->category_title}}</td>
                        <td>{{$p->product->author}}</td>
                        <td>{{$p->quantity}}</td>
                        <td>{{$p->product->list_price}}</td>
                        <td>{{$p->product->list_price * $p->quantity}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        @if(isset($bestCampaign['discount']) && $bestCampaign['discount'] )
            <div style="color: green;" >
                <strong>Kampanya: </strong>{{ $bestCampaign['description'] }} <br>
                <strong>İndirim:  </strong>{{ number_format($bestCampaign['discount'],2) }} TL<br>
            </div>
        @endif

        <p>
            <strong>Fiyat Toplamı</strong> {{ number_format($total,2) }} TL <br>
            <strong>Kargo: </strong> {{ $cargoPrice == 0 ? "50 TL üzeri siparişlerde kargo ücretsizdir!" : number_format($cargoPrice,2). " TL" }} <br>
            @if($discount > 0)
                <strong>İndirim:</strong> {{ number_format($discount,2) }} TL <br>
            @endif
            <strong>Genel Toplam</strong> {{ number_format($Totally,2) }} TL <br>
        </p>

        <br>

        <form action="{{route('ordergo')}}" method="POST">
            @csrf
            <button style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" type="submit">Siparişi Tamamla</button>
        </form>
    @endif
    <a style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" href="{{route('bag')}}">Sepetine geri dön</a>
</body>
</html>