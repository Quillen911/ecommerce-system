<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler</title>
</head>
<body>
    <h1>Ürünler</h1>

    <table border="1" width="100%" style="text-align: center; margin-bottom: 20px;">
        <tr>
            
            <th>ID</th>
            <th>Ürün Adı</th>
            <th>Kategori</th>
            <th>Yazar</th>
            <th>Liste Fiyatı</th>
            <th>Stok Miktarı</th>
        </tr>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->title }}</td>
                <td>{{ $product->category->category_title }}</td>
                <td>{{ $product->author }}</td>
                <td>{{ $product->list_price }}</td>
                <td>{{ $product->stock_quantity }}</td>
            </tr>
        @endforeach
    </table>

    <a href="{{ route('admin.storeProduct') }}">Ürün Ekle Sayfası</a><br>
    <a href="{{ route('admin') }}">Geri Dön</a> 
</body>
</html>