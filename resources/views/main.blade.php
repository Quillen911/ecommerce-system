<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Ana Sayfa</title>
    <style>
        :root{
            --bg: #fafafa; 
            --text: #1a1a1a; 
            --muted: #6b7280; 
            --line: #e5e7eb;
            --accent: #111827; 
            --accent-light: #374151;
            --success: #059669; 
            --warn: #d97706; 
            --danger: #dc2626;
            --gradient-primary: linear-gradient(135deg, #111827 0%, #374151 100%);
            --gradient-success: linear-gradient(135deg, #059669 0%, #10b981 100%);
            --card-bg: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        * { box-sizing: border-box; }
        
        html, body {
            margin: 0; 
            padding: 0; 
            background: var(--bg); 
            color: var(--text);
            scroll-behavior: smooth;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Inter, "Helvetica Neue", Arial, sans-serif;
            letter-spacing: 0.025em;
            line-height: 1.6;
            font-weight: 400;
        }
        
        .shell {
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 0 20px 80px;
        }
        
        /* Header Improvements */
        .header {
            position: sticky;
            top: 0;
            background: rgba(250, 250, 250, 0.95);
            backdrop-filter: blur(20px);
            z-index: 100;
            border-bottom: 1px solid var(--line);
            margin: 0 -20px 32px;
            padding: 24px 20px;
            box-shadow: var(--shadow-sm);
        }
        
        h1 {
            font-size: 28px; 
            font-weight: 700; 
            letter-spacing: 0.02em;
            margin: 0 0 20px; 
            text-align: center;
            background: var(--gradient-primary);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: none;
        }
        
        h3 {
            font-size: 16px; 
            font-weight: 600; 
            letter-spacing: 0.025em;
            margin: 16px 0 8px;
            color: var(--text);
        }
        
        /* Notice Improvements */
        .notice {
            padding: 16px 20px; 
            border: none;
            margin: 16px 0; 
            border-radius: 12px;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }
        .notice.success { 
            color: var(--success); 
            background: rgba(5, 150, 105, 0.1);
            border-left: 4px solid var(--success);
        }
        .notice.error { 
            color: var(--danger); 
            background: rgba(220, 38, 38, 0.1);
            border-left: 4px solid var(--danger);
        }
        
        /* Toolbar Improvements */
        .toolbar {
            display: flex; 
            justify-content: space-between; 
            gap: 16px; 
            flex-wrap: wrap; 
            margin: 0;
        }
        
        /* Button Improvements */
        .btn {
            border: 2px solid var(--accent); 
            background: var(--gradient-primary);
            color: #fff; 
            padding: 12px 24px; 
            border-radius: 50px;
            cursor: pointer; 
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.05em;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none; 
            display: inline-flex; 
            align-items: center; 
            gap: 8px;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn.outline {
            background: var(--card-bg); 
            color: var(--accent);
            border: 2px solid var(--line);
        }
        
        .btn.outline:hover {
            border-color: var(--accent);
            background: var(--accent);
            color: #fff;
        }
        
        /* Card Improvements */
        .card {
            border: none;
            border-radius: 16px; 
            padding: 24px;
            background: var(--card-bg);
            box-shadow: var(--shadow-md);
            transition: box-shadow 0.2s ease;
        }
        
        .card:hover {
            box-shadow: var(--shadow-lg);
        }
        
        /* Form Improvements */
        .filters {
            display: grid; 
            grid-template-columns: 2fr repeat(5, 1fr); 
            gap: 16px; 
            align-items: end;
        }
        
        .field {
            display: flex; 
            flex-direction: column; 
            gap: 8px;
        }
        
        .field label {
            font-size: 11px; 
            color: var(--muted); 
            font-weight: 600;
            text-transform: uppercase; 
            letter-spacing: 0.05em;
        }
        
        .field input, .field select {
            padding: 12px 16px; 
            border: 2px solid var(--line); 
            border-radius: 12px;
            background: var(--card-bg);
            transition: all 0.2s ease;
            font-size: 14px;
        }
        
        .field input:focus, .field select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.1);
        }

        /* Product Grid Improvements */
        .products-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
            gap: 24px; 
            margin-top: 32px;
        }
        
        .product-card {
            border: none;
            border-radius: 20px; 
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--card-bg); 
            position: relative;
            box-shadow: var(--shadow-md);
        }
        
        .product-card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-8px);
        }
        
        .product-image {
            width: 100%; 
            height: 280px; 
            background: #f8fafc; 
            position: relative; 
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%; 
            height: 100%; 
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.05);
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-title {
            font-size: 15px; 
            font-weight: 600; 
            color: var(--text); 
            margin-bottom: 8px;
            line-height: 1.4; 
            display: -webkit-box; 
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical; 
            overflow: hidden;
        }
        
        .product-author {
            font-size: 12px; 
            color: var(--muted); 
            margin-bottom: 12px;
            font-weight: 500;
        }
        
        .product-price {
            font-size: 18px; 
            font-weight: 700; 
            color: var(--accent); 
            margin-bottom: 16px;
        }
        
        .product-actions {
            padding: 0 20px 20px;
        }
        
        .add-btn {
            width: 100%; 
            padding: 14px 20px; 
            background: var(--gradient-primary);
            color: #fff; 
            border: none;
            border-radius: 50px; 
            font-size: 12px; 
            font-weight: 700; 
            letter-spacing: 0.05em;
            cursor: pointer; 
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .add-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }
        
        .add-btn:hover::before {
            left: 100%;
        }
        
        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .add-btn:disabled {
            background: var(--muted); 
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .add-btn:disabled::before {
            display: none;
        }

        /* Stock Overlay Improvements */
        .stock-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            backdrop-filter: blur(2px);
        }

        /* Responsive Improvements */
        @media (max-width: 1200px) { .products-grid { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); } }
        @media (max-width: 900px) { 
            .products-grid { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; }
            .filters { grid-template-columns: 1fr; }
            .shell { padding: 0 16px 60px; }
        }
        @media (max-width: 600px) { 
            .products-grid { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
            .btn { padding: 10px 20px; font-size: 12px; }
            h1 { font-size: 24px; }
        }

        .actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .right { justify-content: flex-end; }
        .muted { color: var(--muted); font-size: 13px; font-weight: 500; }

        /* Cart Notification Improvements */
        .cart-notification {
            position: fixed; 
            top: 24px; 
            right: -420px; 
            width: 380px; 
            background: var(--card-bg);
            border: none;
            border-radius: 16px; 
            box-shadow: var(--shadow-xl);
            z-index: 1000; 
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }
        
        .cart-notification.show { right: 24px; }
        
        .cart-notification-content {
            padding: 20px; 
            display: flex; 
            align-items: flex-start; 
            gap: 16px;
            border-bottom: 1px solid var(--line);
        }
        
        .cart-icon {
            width: 48px; 
            height: 48px; 
            background: var(--gradient-success);
            border-radius: 50%;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: #fff; 
            flex-shrink: 0;
            box-shadow: var(--shadow-md);
        }
        
        .cart-text { flex: 1; }
        
        .cart-title {
            font-size: 12px; 
            font-weight: 700; 
            letter-spacing: 0.05em;
            color: var(--text); 
            margin-bottom: 6px;
        }
        
        .cart-message { 
            font-size: 14px; 
            color: var(--muted); 
            line-height: 1.4;
        }
        
        .cart-close {
            background: none; 
            border: none; 
            padding: 8px; 
            border-radius: 8px; 
            cursor: pointer;
            color: var(--muted); 
            transition: all 0.2s ease; 
            flex-shrink: 0;
        }
        
        .cart-close:hover { 
            background: #f3f4f6; 
            color: var(--text);
        }
        
        .cart-actions { 
            padding: 16px 20px; 
        }
        
        .cart-btn {
            display: inline-block; 
            padding: 12px 20px; 
            border-radius: 50px; 
            text-decoration: none;
            font-size: 12px; 
            font-weight: 600; 
            letter-spacing: 0.05em;
            transition: all 0.2s ease; 
            text-align: center; 
            width: 100%;
        }
        
        .cart-btn-outline {
            background: transparent; 
            color: var(--accent); 
            border: 2px solid var(--accent);
        }
        
        .cart-btn-outline:hover { 
            background: var(--accent); 
            color: #fff; 
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        @media (max-width: 480px) {
            .cart-notification { width: calc(100vw - 32px); right: -100%; }
            .cart-notification.show { right: 16px; }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Loading Animation Improvements */
        .loading-spinner {
            animation: spin 1s linear infinite;
            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="header">
        <h1>Hoş geldiniz {{ auth()->user()->username }}</h1>
        
        <div class="toolbar">
            <div class="actions">
                <a href="/bag" class="btn outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/></svg>
                    Sepetim
                </a>
                <a href="/myorders" class="btn outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                    Siparişlerim
                </a>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="actions">
                @csrf
                <button type="submit" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16,17 21,12 16,7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Çıkış Yap
                </button>
            </form>
        </div>
    </div>

    @if(session('success')) <div class="notice success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="notice error">{{ session('error') }}</div> @endif

    <div class="card">
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
                    <button class="btn" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                        Ara
                    </button>
                </div>
            @endif
        </form>
    </div>

    @if(empty($query))
        <div class="card" style="margin-top:20px">
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

    @if(isset($query) && !empty($query))
        <div class="muted" style="margin-top:24px; font-weight: 600;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 8px;"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
            Arama Sonuçları
        </div>
    @endif

    @if(count($products) > 0)
        <div class="products-grid">
            @foreach($products as $p)
                @php
                    $images = null;
                    if (is_array($p)) {
                        $images = $p['images'] ?? null;
                    } else {
                        $images = $p->images ?? null;
                    }
                    
                    $imageUrl = '/images/no-image.jpg';
                    if ($images) {
                        if (is_string($images)) {
                            $imagesArray = json_decode($images, true);
                        } else {
                            $imagesArray = $images;
                        }
                        if (is_array($imagesArray) && !empty($imagesArray)) {
                            $imageUrl = $imagesArray[0];
                        }
                    }
                    
                    $isOutOfStock = (is_array($p) ? $p['stock_quantity'] : $p->stock_quantity) <= 0;
                @endphp
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ $imageUrl }}" alt="{{ is_array($p) ? $p['title'] : $p->title }}">
                        @if($isOutOfStock)
                            <div class="stock-overlay">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                STOKTA YOK
                            </div>
                        @endif
                    </div>
                    
                    <div class="product-info">
                        <div class="product-title">{{ is_array($p) ? $p['title'] : $p->title }}</div>
                        <div class="product-author">{{ is_array($p) ? $p['author'] : $p->author }}</div>
                        <div class="product-price">{{ number_format(is_array($p) ? $p['list_price'] : $p->list_price, 2) }} ₺</div>
                    </div>
                    
                    <div class="product-actions">
                        <form action="{{ route('add') }}" method="POST" style="margin:0" class="add-to-bag-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ is_array($p) ? $p['id'] : $p->id }}">
                            <button class="add-btn" type="submit" {{ $isOutOfStock ? 'disabled' : '' }}>
                                @if($isOutOfStock)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                    STOKTA YOK
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/></svg>
                                    SEPETE EKLE
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="notice" style="margin-top: 32px; text-align: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 12px; color: var(--muted);"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            Ürün bulunamadı
        </div>
    @endif

    <div class="actions" style="justify-content:space-between;margin-top:32px">
        <a href="{{ route('main') }}" class="btn outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h18m-9-9l9 9-9 9"/></svg>
            Tüm ürünleri göster
        </a>
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

    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.add-to-bag-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = form.querySelector('button');
                const originalText = button.innerHTML;
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="loading-spinner">
                        <path d="M21 12a9 9 0 11-6.219-8.56"/>
                    </svg>
                    Ekleniyor...
                `;
                button.disabled = true;
                
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message, 'error');
                    }
                    
                    button.innerHTML = originalText;
                    button.disabled = false;
                })
                .catch(error => {
                    showNotification('Bir hata oluştu!', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            });
        });
    });

    function showNotification(message, type) {
        const existingNotification = document.querySelector('.cart-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const isSuccess = type === 'success';
        const iconBg = isSuccess ? 'var(--gradient-success)' : 'var(--danger)';
        const title = isSuccess ? 'SEPETİNİZE EKLENDİ' : 'SEPETE EKLENEMEDİ';
        const iconSvg = isSuccess ? 
            `<path d="M20 6L9 17l-5-5"/>` :
            `<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>`;
        
        const notification = document.createElement('div');
        notification.className = 'cart-notification';
        notification.innerHTML = `
            <div class="cart-notification-content">
                <div class="cart-icon" style="background: ${iconBg}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        ${iconSvg}
                    </svg>
                </div>
                <div class="cart-text">
                    <div class="cart-title">${title}</div>
                    <div class="cart-message">${message}</div>
                </div>
                <button class="cart-close" onclick="this.parentElement.parentElement.remove()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            ${isSuccess ? '<div class="cart-actions"><a href="/bag" class="cart-btn cart-btn-outline">SEPETE GİT</a></div>' : ''}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 400);
            }
        }, 5000);
    }
</script>
</body>
</html>