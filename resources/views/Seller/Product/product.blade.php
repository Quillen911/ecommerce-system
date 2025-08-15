<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Yönetimi - Satıcı Paneli</title>
    <style>
        :root{
            --bg:#1E293B; --text:#F1F5F9; --muted:#94A3B8; --line:#334155;
            --accent:#3B82F6; --success:#22C55E; --warn:#F59E0B; --danger:#EF4444;
            --header:#0F172A; --card:#334155;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text)}
        body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Inter,"Helvetica Neue",Arial,sans-serif;letter-spacing:.2px;line-height:1.4}
        .shell{max-width:1400px;margin:0 auto;padding:24px 16px 80px}
        
        /* Header */
        .header{background:var(--header);color:var(--text);padding:20px 0;margin:-24px -16px 24px;border-radius:0 0 16px 16px;box-shadow:0 4px 6px rgba(0,0,0,0.3)}
        .header-content{max-width:1400px;margin:0 auto;padding:0 16px;display:flex;justify-content:space-between;align-items:center}
        .header h1{font-size:24px;font-weight:700;margin:0;letter-spacing:2px;text-transform:uppercase;color:var(--text)}
        .header-stats{display:flex;gap:24px;align-items:center}
        .stat{text-align:center}
        .stat-number{font-size:18px;font-weight:600;display:block}
        .stat-label{font-size:11px;opacity:0.8;text-transform:uppercase;letter-spacing:1px}
        
        /* Toolbar */
        .toolbar{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px;flex-wrap:wrap}
        .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:12px 20px;border-radius:8px;cursor:pointer;text-transform:uppercase;letter-spacing:1px;font-size:12px;font-weight:600;transition:all .2s ease;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
        .btn:hover{background:#2563EB;border-color:#2563EB;transform:translateY(-1px);box-shadow:0 4px 12px rgba(59,130,246,0.4)}
        .btn.outline{background:transparent;color:var(--accent);border-color:var(--accent)}
        .btn.outline:hover{background:var(--accent);color:#fff}
        .btn.danger{background:var(--danger);border-color:var(--danger)}
        .btn.danger:hover{background:#DC2626;box-shadow:0 4px 12px rgba(239,68,68,0.4)}
        .btn.success{background:var(--success);border-color:var(--success)}
        .btn.success:hover{background:#16A34A;box-shadow:0 4px 12px rgba(34,197,94,0.4)}
        
        /* Search Form */
        .search-card{background:var(--card);border-radius:12px;padding:20px;box-shadow:0 4px 6px rgba(0,0,0,0.3);margin-bottom:24px}
        .search-form{display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto auto;gap:12px;align-items:end}
        .field{display:flex;flex-direction:column;gap:6px}
        .field label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;font-weight:600}
        .field input,.field select{padding:10px 12px;border:2px solid var(--line);border-radius:8px;transition:border-color .2s ease;background:var(--bg);color:var(--text)}
        .field input:focus,.field select:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,0.1)}
        
        /* Products Table */
        .products-container{background:var(--card);border-radius:12px;overflow:hidden;box-shadow:0 4px 6px rgba(0,0,0,0.3)}
        .products-table{width:100%;border-collapse:collapse}
        .products-table th{background:var(--header);padding:16px 12px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);border-bottom:2px solid var(--line)}
        .products-table td{padding:16px 12px;border-bottom:1px solid var(--line);vertical-align:middle;color:var(--text)}
        .products-table tr:hover{background:rgba(59,130,246,0.1)}
        .products-table tr:last-child td{border-bottom:none}
        
        /* Product Image */
        .product-img{width:80px;height:80px;border-radius:8px;object-fit:cover;border:2px solid var(--line)}
        
        /* Product Info */
        .product-title{font-weight:600;color:var(--text);margin-bottom:4px}
        .product-author{font-size:12px;color:var(--muted)}
        
        /* Badges */
        .badge{padding:4px 8px;border-radius:4px;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px}
        .badge.success{background:#dcfce7;color:var(--success)}
        .badge.danger{background:#fef2f2;color:var(--danger)}
        .badge.warning{background:#fef3c7;color:#d97706}
        
        /* Price */
        .price{font-weight:600;color:var(--text);font-size:14px}
        
        /* Stock */
        .stock{font-weight:600}
        .stock.low{color:var(--danger)}
        .stock.medium{color:var(--warn)}
        .stock.high{color:var(--success)}
        
        /* Actions */
        .actions{display:flex;gap:8px}
        .btn-sm{padding:6px 12px;font-size:10px;border-radius:6px}
        
        @media (max-width:1200px){
            .search-form{grid-template-columns:1fr;gap:12px}
            .search-form .actions{grid-column:1;justify-content:flex-start}
        }
        
        @media (max-width:768px){
            .header-content{flex-direction:column;gap:16px;text-align:center}
            .header-stats{justify-content:center}
            .toolbar{flex-direction:column;align-items:stretch}
            .products-table{font-size:12px}
            .products-table th,.products-table td{padding:8px 6px}
            .product-img{width:60px;height:60px}
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="header">
        <div class="header-content">
            <h1>Ürün Yönetimi</h1>
            <div class="header-stats">
                <div class="stat">
                    <span class="stat-number">{{count($products)}}</span>
                    <span class="stat-label">Ürün</span>
                </div>
                <div class="stat">
                    <span class="stat-number">{{ auth('seller_web')->user()->name ?? 'Satıcı' }}</span>
                    <span class="stat-label">Satıcı Paneli</span>
                </div>
            </div>
        </div>
    </div>

    <div class="toolbar">
        <div class="actions">
            <a href="{{ route('seller.storeProduct') }}" class="btn success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Yeni Ürün Ekle
            </a>
        </div>
        <div class="actions">
            <a href="{{ route('seller') }}" class="btn outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                </svg>
                Ana Panel
            </a>
        </div>
    </div>
    <div class="search-card">
        <form action="{{ route('seller.searchProduct') }}" method="GET" class="search-form">
            @csrf
            <div class="field">
                <label for="q">Ürün Ara</label>
                <input type="text" id="q" name="q" placeholder="Ürün adı, yazar..." value="{{ $query ?? request('q') }}">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="size" value="12">
            </div>
            
            @if(isset($query) && !empty($query))
                <div class="field">
                    <label for="category_title">Kategori</label>
                    <select name="category_title" id="category_title">
                        <option value="">Tüm Kategoriler</option>
                        @foreach($categories as $category)
                            <option value="{{ strtolower($category->category_title) }}" {{ request('category_title') == strtolower($category->category_title) ? 'selected' : '' }}>
                                {{ ucfirst($category->category_title) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="field">
                    <label for="min_price">Min Fiyat</label>
                    <input type="number" id="min_price" name="min_price" placeholder="0" value="{{ request('min_price') }}">
                </div>
                
                <div class="field">
                    <label for="max_price">Max Fiyat</label>
                    <input type="number" id="max_price" name="max_price" placeholder="999" value="{{ request('max_price') }}">
                </div>
                
                <div class="field">
                    <label for="sorting">Sıralama</label>
                    <select name="sorting" id="sorting">
                        <option value="">Varsayılan</option>
                        <option value="price_asc" {{ request('sorting') == 'price_asc' ? 'selected' : '' }}>Fiyat: Düşük-Yüksek</option>
                        <option value="price_desc" {{ request('sorting') == 'price_desc' ? 'selected' : '' }}>Fiyat: Yüksek-Düşük</option>
                        <option value="stock_quantity_asc" {{ request('sorting') == 'stock_quantity_asc' ? 'selected' : '' }}>Stok: Az-Çok</option>
                        <option value="stock_quantity_desc" {{ request('sorting') == 'stock_quantity_desc' ? 'selected' : '' }}>Stok: Çok-Az</option>
                    </select>
                </div>
                
                <button type="submit" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path>
                    </svg>
                    Filtrele
                </button>
                
                <button type="button" class="btn outline" onclick="resetFilters()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-5h5"/>
                    </svg>
                    Sıfırla
                </button>
            @else
                <button type="submit" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path>
                    </svg>
                    Ara
                </button>
            @endif
        </form>
    </div>
    <div class="products-container">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Ürün</th>
                    <th>ID</th>
                    <th>Satış</th>
                    <th>Kategori</th>
                    <th>Fiyat</th>
                    <th>Stok</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    @php
                        $imageUrl = is_array($product) 
                            ? (empty($product['images']) ? '/images/no-image.jpg' : '/storage/productsImages/' . $product['images'][0])
                            : $product->first_image;
                        $stockQty = is_array($product) ? $product['stock_quantity'] : $product->stock_quantity;
                        $stockClass = $stockQty <= 5 ? 'low' : ($stockQty <= 20 ? 'medium' : 'high');
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <img src="{{ $imageUrl }}" alt="{{ $product['title'] }}" class="product-img">
                                <div>
                                    <div class="product-title">{{ $product['title'] }}</div>
                                    <div class="product-author">{{ $product['author'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge">{{ $product['id'] }}</span>
                        </td>
                        <td>
                            <span class="badge {{ ($product['sold_quantity'] ?? 0) > 0 ? 'success' : '' }}">
                                {{ $product['sold_quantity'] ?? 0 }} Adet
                            </span>
                        </td>
                        <td>
                            @if(is_array($product))
                                {{ ucfirst($product['category_title']) }}
                            @else
                                {{ ucfirst($product->category?->category_title) }}
                            @endif
                        </td>
                        <td>
                            <span class="price">{{ number_format($product['list_price'], 2) }} TL</span>
                        </td>
                        <td>
                            <span class="stock {{ $stockClass }}">
                                {{ $stockQty }} Adet
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('seller.editProduct', $product['id']) }}" class="btn btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                                    </svg>
                                    Düzenle
                                </a>
                                <form action="{{ route('seller.deleteProduct', $product['id']) }}" method="POST" style="margin:0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm danger" onclick="return confirm('{{ $product['title'] }} ürününü silmek istediğinize emin misiniz?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c0 1 1 2 2 2v2"/>
                                        </svg>
                                        Sil
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="actions" style="justify-content:center;margin-top:24px;">
        <a href="{{ route('seller.product') }}" class="btn outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-5h5"/>
            </svg>
            Tüm Ürünleri Göster
        </a>
    </div>

<script>
function resetFilters(){
    const searchQuery = document.getElementById('q')?.value || '';
    const url = new URL(window.location.origin + '{{ route("seller.searchProduct") }}');
    url.searchParams.set('q', searchQuery);
    url.searchParams.set('page', '1');
    url.searchParams.set('size', '12');
    window.location.href = url.toString();
}
</script>
</body>
</html>
