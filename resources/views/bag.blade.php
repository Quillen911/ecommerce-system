<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim</title>
</head>
<body>
    <h1>Sepetim</h1>
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
                        <form action="{{route('delete', $p->id)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Sil</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
    </table> <br>

    <a href="{{route('order')}}">Sipariş Oluştur</a>

    <button type='button' onclick="window.location.href='{{route('main')}}'" class="btn-secondary"> Geri Dön</button>
</body>
</html> 