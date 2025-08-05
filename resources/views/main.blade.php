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

    <button style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" onclick="window.location.href='/bag'">Sepetim</button><br><br>
    <button style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" onclick="window.location.href='/myorders'">Siparişlerim</button><br><br>
    
    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px;">
    <form action="{{ route('search') }}" method="GET">
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
        @endif
    </form>
    <form action="{{ route('sorting') }}" method="GET">
        @csrf
        <select name="sorting" id="sorting">
            <option value="">Sıralama Seç</option>
            <option value="price_asc">Fiyata Göre Artan</option>
            <option value="price_desc">Fiyata Göre Azalan</option>
            <option value="title_asc">Başlığa Göre Artan</option>
            <option value="title_desc">Başlığa Göre Azalan</option>
        </select>
        <button type="submit">Sırala</button>
    </form>
    </div>
    <script>
        function resetFilters() {
            const searchQuery = document.querySelector('input[name="q"]').value;
            const url = new URL(window.location.origin + '/search');
            url.searchParams.set('q', searchQuery);
            url.searchParams.set('page', '1');
            url.searchParams.set('size', '12');
            window.location.href = url.toString();
        }
        function resetFilter() {
            const url = new URL(window.location.origin + '/filter');
            url.searchParams.set('page', '1');
            url.searchParams.set('size', '12');
            window.location.href = url.toString();
        }
    </script>

    <br>
    @if(empty($query))
    <form action="{{ route('filter') }}" method="GET">
        @csrf
        <input type="hidden" name="page" value="1">
        <input type="hidden" name="size" value="12">
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
        <button type="button" onclick="resetFilter()">Filtreleri Sıfırla</button>
    </form>
    @endif
    @if(isset($query) && !empty($query))
        <strong>Arama Sonuçları: </strong> <br>
    @endif
    <br>
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
                        <td>{{$p['id'] }}</td>
                        <td>{{$p['title'] }}</td>
                        <td>{{$p['category_id']}}</td>
                        <td>
                            @if(is_array($p))
                                {{ $p['category_title'] }}
                            @else
                                {{ $p->category?->category_title }}
                            @endif
                        </td>
                        <td>{{$p['author'] }}</td>
                        <td>{{$p['list_price'] }}</td>
                        <td>{{$p['stock_quantity'] }}</td>
                        <td>
                            <form action="{{ route('add') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{$p['id'] }}">
                                <button style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" type="submit">Sepete Ekle</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
                 
        </table><br>
        <a href="{{ route('main') }}">Tüm ürünleri göster</a> <br> <br>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
      <button style="background-color: #000; color: #fff; border-radius: 10px; padding: 10px; border: 1px solid #000; cursor: pointer;" type="submit">Çıkış Yap</button>
    </form>
      
</body>
</html>