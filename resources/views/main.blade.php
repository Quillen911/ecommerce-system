<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Ana Sayfa</title>
    <style>
        :root{
            --bg:#fff; --text:#111; --muted:#666; --line:#e6e6e6;
            --accent:#000; --success:#0f8a44; --warn:#ff9800; --danger:#c62828;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text)}
        body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Inter,"Helvetica Neue",Arial,sans-serif;letter-spacing:.2px;line-height:1.4}
        .shell{max-width:1140px;margin:0 auto;padding:24px 16px 80px}
        h1{font-size:22px;font-weight:600;text-transform:uppercase;letter-spacing:4px;margin:20px 0 10px;text-align:center}
        h3{font-size:15px;font-weight:600;letter-spacing:1.2px;text-transform:uppercase;margin:12px 0}
        .notice{padding:10px 12px;border:1px solid var(--line);margin:8px 0;border-radius:6px}
        .notice.success{color:var(--success);background:#f0f9f4;border-color:var(--success)}
        .notice.error{color:var(--danger);background:#fef2f2;border-color:var(--danger)}
        .toolbar{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;margin:12px 0 18px}
        .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:10px 16px;border-radius:28px;cursor:pointer;text-transform:uppercase;letter-spacing:1.2px;font-size:12px;transition:filter .15s ease;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
        .btn:hover{filter:brightness(.9)}
        .btn.outline{background:transparent;color:var(--accent)}
        .card{border:1px solid var(--line);border-radius:8px;padding:12px}
        .filters{display:grid;grid-template-columns:1.6fr repeat(5,1fr);gap:10px;align-items:end}
        .field{display:flex;flex-direction:column;gap:6px}
        .field label{font-size:10px;color:#333;text-transform:uppercase;letter-spacing:.6px}
        .field input,.field select{padding:2px;border:1px solid var(--line);border-radius:6px}
        .table-wrap{overflow:auto;border:1px solid var(--line);border-radius:6px;margin-top:16px}
        table{width:100%;border-collapse:collapse;min-width:860px}
        thead th{font-size:11px;color:#222;font-weight:600;text-transform:uppercase;letter-spacing:1.4px;background:#fafafa;border-bottom:1px solid var(--line);padding:12px 10px;text-align:left}
        tbody td{padding:12px 10px;border-bottom:1px solid var(--line);font-size:14px}
        tbody tr:hover{background:#fcfcfc}
        .actions{display:flex;gap:10px;flex-wrap:wrap}
        .right{justify-content:flex-end}
        .muted{color:var(--muted);font-size:12px}
    </style>
</head>
<body>
<div class="shell">
    <h1>Hoş geldiniz {{ auth()->user()->username }}</h1>

    <div class="toolbar">
        <div class="actions">
            <a href="/bag" class="btn outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/></svg>
                Sepetim
            </a>
            <a href="/myorders" class="btn outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18"/><path d="M6 10h12"/><path d="M6 13h12"/><path d="M6 16h12"/></svg>
                Siparişlerim
            </a>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="actions">
            @csrf
            <button type="submit" class="btn">Çıkış Yap</button>
        </form>
    </div>

    @if(session('success')) <div class="notice success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="notice error">{{ session('error') }}</div> @endif

    <div class="card" style="margin-top:10px">
        <form action="{{ route('search') }}" method="GET" class="filters">
            @csrf
            <div class="field" style="grid-column:1/span 2">
                <label for="q">Ürün Ara</label>
                <input id="q" type="text" name="q" placeholder="Ürün adı, yazar..." value="{{ $query ?? request('q') }}">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="size" value="12">
            </div>
            @if(isset($query) && !empty($query))
                <div class="field">
                    <label for="category_title">Kategori</label>
                    <select id="category_title" name="category_title">
                        <option value="">Seçiniz</option>
                        @foreach($categories as $category)
                            <option value="{{ strtolower($category->category_title) }}" {{ request('category_title') == strtolower($category->category_title) ? 'selected' : '' }}>
                                {{ strtolower($category->category_title) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="min_price">Min Fiyat</label>
                    <input id="min_price" type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                </div>
                <div class="field">
                    <label for="max_price">Max Fiyat</label>
                    <input id="max_price" type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                </div>
                <div class="field">
                    <label for="sorting">Sıralama</label>
                    <select id="sorting" name="sorting">
                        <option value="">Seçiniz</option>
                        <option value="price_asc">Fiyata Göre Artan</option>
                        <option value="price_desc">Fiyata Göre Azalan</option>
                        <option value="stock_quantity_asc">Stoka Göre Artan</option>
                        <option value="stock_quantity_desc">Stoka Göre Azalan</option>
                    </select>
                </div>
                <div class="actions" style="align-items:end">
                    <button class="btn outline" type="button" onclick="resetFilters()">Filtreleri Sıfırla</button>
                    <button class="btn" type="submit">Uygula</button>
                </div>
            @else
                <div class="actions right">
                    <button class="btn" type="submit">Ara</button>
                </div>
            @endif
        </form>
    </div>

    @if(empty($query))
        <div class="card" style="margin-top:12px">
            <form action="{{ route('sorting') }}" method="GET" class="actions" style="justify-content:flex-start">
                @csrf
                <div class="field" style="min-width:220px">
                    <label for="sorting2">Sıralama</label>
                    <select id="sorting2" name="sorting">
                        <option value="">Seçiniz</option>
                        <option value="price_asc">Fiyata Göre Artan</option>
                        <option value="price_desc">Fiyata Göre Azalan</option>
                        <option value="stock_quantity_asc">Stoka Göre Artan</option>
                        <option value="stock_quantity_desc">Stoka Göre Azalan</option>
                    </select>
                </div>
                <button class="btn" type="submit">Sırala</button>
            </form>
        </div>
    @endif

    <p class="muted" style="display:none">Gösterilen Ürün Sayısı: {{ count($products) }}</p>

    @if(isset($query) && !empty($query))
        <div class="muted" style="margin-top:10px"><strong>Arama Sonuçları</strong></div>
    @endif

    @if(count($products) > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ürün</th>
                        <th>Kategori ID</th>
                        <th>Kategori</th>
                        <th>Yazar</th>
                        <th>Fiyat</th>
                        <th>Stok</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($products as $p)
                    <tr>
                        <td>{{ $p['id'] }}</td>
                        <td>{{ $p['title'] }}</td>
                        <td>{{ $p['category_id'] }}</td>
                        <td>
                            @if(is_array($p))
                                {{ $p['category_title'] }}
                            @else
                                {{ $p->category?->category_title }}
                            @endif
                        </td>
                        <td>{{ $p['author'] }}</td>
                        <td>{{ $p['list_price'] }}</td>
                        <td>{{ $p['stock_quantity'] }}</td>
                        <td>
                            <form action="{{ route('add') }}" method="POST" style="margin:0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p['id'] }}">
                                <button class="btn outline" type="submit">Sepete Ekle</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="notice">Ürün bulunamadı</div>
    @endif

    <div class="actions" style="justify-content:space-between;margin-top:16px">
        <a href="{{ route('main') }}" class="btn outline">Tüm ürünleri göster</a>
    </div>
</div>

<script>
    function resetFilters(){
        const searchQuery = document.getElementById('q')?.value || '';
        const url = new URL(window.location.origin + '/search');
        url.searchParams.set('q', searchQuery);
        url.searchParams.set('page', '1');
        url.searchParams.set('size', '12');
        window.location.href = url.toString();
    }
    function resetFilter(){
        const url = new URL(window.location.origin + '/filter');
        url.searchParams.set('page', '1');
        url.searchParams.set('size', '12');
        window.location.href = url.toString();
    }
</script>
</body>
</html>