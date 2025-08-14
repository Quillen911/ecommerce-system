<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler</title>
</head>
<body>
    <h1>Ürünler</h1>
    <p>Gösterilen Ürün Sayısı: {{count($products)}}</p>
    <a href="{{ route('seller.storeProduct') }}">Ürün Ekle Sayfası</a><br>
    <a href="{{ route('seller') }}">Geri Dön</a> 
    <br><br>
    <form action="{{ route('seller.searchProduct') }}" method="GET">
        @csrf
        <input type="text" name="q" placeholder="Ürün Ara" value="{{ $query ?? request('q') }}">
        <button type="submit">Ara</button>
        <input type="hidden" name="page" value="1">
        <input type="hidden" name="size" value="12">
        @if(isset($query) && !empty($query))
        <select name="category_title" id="category_title">
            <option value="">Kategori Seç</option>
            @foreach($categories as $category)
                <option value="{{ strtolower($category->category_title) }}" {{ request('category_title') == strtolower($category->category_title) ? 'selected' : '' }}>
                    {{ strtolower($category->category_title) }}
                </option>
            @endforeach
        </select>
        
        <input type="number" name="min_price" placeholder="Min Fiyat" value="{{ request('min_price') }}">
        <input type="number" name="max_price" placeholder="Max Fiyat" value="{{ request('max_price') }}">

        <button type="submit">Filtrele</button>
        <button type="button" onclick="resetFilters()">Filtreleri Sıfırla</button>
        <select name="sorting" id="sorting">
            <option value="">Sıralama Seç</option>
            <option value="price_asc">Fiyata Göre Artan</option>
            <option value="price_desc">Fiyata Göre Azalan</option>
            <option value="stock_quantity_asc">Stok Miktarına Göre Artan</option>
            <option value="stock_quantity_desc">Stok Miktarına Göre Azalan</option>
        </select>
        <button type="submit">Sırala</button>
        @endif
    </form>
    <table border="1" width="100%" style="text-align: center; margin-bottom: 20px;">
        <tr>
            <th>ID</th>
            <th>Satılan Miktar</th>
            <th>Ürün Adı</th>
            <th>Kategori</th>
            <th>Yazar</th>
            <th>Liste Fiyatı</th>
            <th>Stok Miktarı</th>
            <th>Sil</th>
        </tr>
        @foreach($products as $product)
            <tr>
                <td>{{ $product['id'] }}</td>
                <td>{{ $product['sold_quantity'] }}</td>
                <td>{{ $product['title'] }}</td>
                <td>
                    @if(is_array($product))
                        {{ $product['category_title'] }}
                    @else
                        {{ $product->category?->category_title }}
                    @endif
                </td>
                <td>{{ $product['author'] }}</td>
                <td>{{ $product['list_price'] }}</td>
                <td>{{ $product['stock_quantity'] }}</td>
                <td> 
                    <form action="{{ route('seller.deleteProduct', $product['id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Sil</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    <a href="{{ route('seller.product') }}">Tüm Ürünleri Gör</a>
</body>
</html>
