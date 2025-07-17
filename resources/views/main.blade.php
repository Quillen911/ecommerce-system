<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Ana Sayfa</title>
</head>
<body>
    <h1>Hoşgeldiniz {{auth()->user()->username }} </h1>
    <h2>Ürünler</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <button onclick="window.location.href='/bag'">Sepetim</button><br><br>
    <button onclick="window.location.href='/listmyorders'">Siparişlerim</button><br><br>
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
                    <th>           </th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->title }}</td>
                        <td>{{ $p->category_id }}</td>
                        <td>{{ $p->category?->category_title }}</td>
                        <td>{{ $p->author }}</td>
                        <td>{{ $p->list_price }}</td>
                        <td>{{ $p->stock_quantity }}</td>
                        <td>
                            <form action="{{ route('add') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id }}">
                                <button type="submit">Sepete Ekle</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            
        </table><br>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
      <button type="submit">Çıkış Yap</button>
    </form>


</body>
</html>