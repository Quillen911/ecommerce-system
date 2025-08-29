<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Ana Sayfa</title>
    <style>
        :root{
            --bg:#1B1B1F; --text:#EDEDED; --muted:#A0A0A0; --line:#333338;
            --accent:#404046; --success:#00C6AE; --warn:#ed8936; --danger:#FF6B6B;
            --card:#232327; --shadow:rgba(0,0,0,0.3); --hover:rgba(0,198,174,0.1);
            --primary:#00C6AE; --secondary:#14F1D9; --gray-50:#2A2A2F; --gray-100:#333338;
            --hover-accent:#505056; --price-color:#4A90E2;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-size:14px}
        body{font-family:"Inter",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;letter-spacing:-0.025em;line-height:1.6;-webkit-font-smoothing:antialiased}
        .shell{max-width:1200px;margin:0 auto;padding:24px 20px 80px}
        
        /* Header */
        .page-header{background:var(--card);border-bottom:1px solid var(--line);padding:20px 0;margin:-24px -20px 24px;box-shadow:0 4px 20px var(--shadow)}
        .header-content{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center}
        h1{font-size:24px;font-weight:600;letter-spacing:-0.01em;margin:0;color:var(--text)}
        .header-subtitle{font-size:14px;color:var(--muted);font-weight:500}
        
        /* Toolbar */
        .toolbar{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin:0 0 20px;background:var(--card);padding:16px 20px;border-radius:8px;box-shadow:0 1px 3px var(--shadow);border:1px solid var(--line)}
        .nav-section{display:flex;gap:6px;align-items:center}
        .btn{border:1px solid var(--accent);background:var(--accent);color:#EDEDED;padding:8px 16px;border-radius:6px;cursor:pointer;font-weight:500;font-size:14px;transition:all 0.15s ease;text-decoration:none;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 8px rgba(64,64,70,0.3)}
        .btn:hover{background:var(--hover-accent);border-color:var(--hover-accent);transform:translateY(-1px);box-shadow:0 4px 12px rgba(80,80,86,0.4)}
        .btn.outline{background:transparent;color:var(--accent);border:1px solid var(--accent);box-shadow:none}
        .btn.outline:hover{background:var(--gray-50);border-color:var(--hover-accent);color:var(--hover-accent)}
        
        /* Notices */
        .notice{padding:12px 16px;border:1px solid var(--line);margin:0 0 16px;border-radius:6px;background:var(--card);font-size:14px}
        .notice.success{border-color:var(--success);background:rgba(0,198,174,0.1);color:var(--success)}
        .notice.error{border-color:var(--danger);background:rgba(255,107,107,0.1);color:var(--danger)}
        
        /* Cards */
        .card{background:var(--card);border:1px solid var(--line);border-radius:8px;padding:20px;box-shadow:0 1px 3px var(--shadow)}
        
        /* Filters */
        .filters{display:grid;grid-template-columns:2fr 0.8fr 0.8fr 0.8fr 1fr auto;gap:8px;align-items:end}
        .field{display:flex;flex-direction:column;gap:8px}
        .field label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;font-weight:600}
        .field input,.field select{padding:8px 12px;border:2px solid var(--line);border-radius:8px;transition:all 0.2s ease;background:var(--card);color:var(--text);font-size:13px;font-weight:500;-webkit-appearance:none;-moz-appearance:none;appearance:none}
        .field select{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23EDEDED' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");background-position:right 8px center;background-repeat:no-repeat;background-size:16px}
        .field input:hover,.field select:hover{border-color:var(--accent)}
        .field input:focus,.field select:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(64,64,70,0.1)}
        .field input::placeholder{color:var(--muted)}
        
        /* Filter Actions */
        .filter-actions{display:flex;gap:4px;flex-wrap:wrap}
        
        /* Search Group */
        .search-group{display:flex;gap:4px;align-items:end}
        .btn-sm{padding:8px 12px;font-size:11px;border-radius:6px}
        
        /* Custom Dropdown */
        .custom-dropdown{position:relative;display:inline-block;width:200px}
        .dropdown-btn{
            width:100%;padding:10px 16px;background:var(--card);border:2px solid var(--line);
            border-radius:6px;cursor:pointer;display:flex;justify-content:space-between;
            align-items:center;font-size:14px;color:var(--text);transition:all 0.15s ease
        }
        .dropdown-btn:hover{border-color:var(--accent)}
        .dropdown-btn.active{border-color:var(--accent);box-shadow:0 0 0 3px rgba(64,64,70,0.1)}
        .dropdown-content{
            position:absolute;top:100%;left:0;right:0;background:var(--card);
            border:1px solid var(--line);border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,0.1);
            z-index:100;max-height:0;overflow:hidden;transition:all 0.2s ease
        }
        .dropdown-content.show{max-height:300px;overflow-y:auto}
        .dropdown-item{
            padding:10px 16px;cursor:pointer;border-bottom:1px solid var(--line);
            transition:background-color 0.15s ease;font-size:14px;color:var(--text)
        }
        .dropdown-item:last-child{border-bottom:none}
        .dropdown-item:hover{background:var(--gray-50)}
        .dropdown-item.selected{background:var(--accent);color:#EDEDED}
        .dropdown-arrow{
            width:0;height:0;border-left:4px solid transparent;border-right:4px solid transparent;
            border-top:4px solid var(--text);transition:transform 0.2s ease
        }
        .dropdown-btn.active .dropdown-arrow{transform:rotate(180deg)}
        /* Ürün Grid */
        .products-grid{
            display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;margin-top:24px
        }
        .product-card{
            background:var(--card);border:1px solid var(--line);border-radius:8px;overflow:hidden;
            transition:all 0.2s ease;position:relative;box-shadow:0 1px 3px var(--shadow)
        }
        .product-card:hover{
            box-shadow:0 4px 12px rgba(0,0,0,0.1);transform:translateY(-2px);
            border-color:var(--primary)
        }
        .product-image{
            width:100%;height:220px;background:var(--gray-50);position:relative;overflow:hidden;
            display:flex;align-items:center;justify-content:center
        }
        .product-image img{
            width:100%;height:100%;object-fit:contain;padding:8px;background:white
        }
        .product-info{padding:16px}
        .product-title{
            font-size:15px;font-weight:600;color:var(--text);margin-bottom:4px;
            line-height:1.4;height:2.8em;display:-webkit-box;-webkit-line-clamp:2;
            -webkit-box-orient:vertical;overflow:hidden
        }
        .product-author{
            font-size:13px;color:var(--muted);margin-bottom:4px;font-weight:400
        }
        .product-store{
            font-size:11px;color:var(--primary);font-weight:500;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px
        }
        .product-price{
            font-size:16px;font-weight:600;color:var(--price-color);margin-bottom:12px
        }
        .product-actions{padding:0 16px 16px}
        .add-btn{
            width:100%;padding:10px 16px;background:var(--accent);color:#EDEDED;border:none;
            border-radius:6px;font-size:14px;font-weight:500;cursor:pointer;
            transition:all 0.15s ease;text-transform:none;box-shadow:0 2px 8px rgba(64,64,70,0.3)
        }
        .add-btn:hover{background:var(--hover-accent);transform:translateY(-1px);box-shadow:0 4px 12px rgba(80,80,86,0.4)}
        .add-btn:disabled{background:#9ca3af;cursor:not-allowed}
        
        /* Out of Stock Overlay */
        .stock-overlay{
            position:absolute;top:0;left:0;right:0;bottom:0;
            background:rgba(255,255,255,0.95);display:flex;flex-direction:column;
            align-items:center;justify-content:center;color:var(--danger);
            font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em
        }
        
        /* Responsive */
        @media (max-width:1024px){
            .products-grid{grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px}
            .filters{grid-template-columns:1fr;gap:12px}
            .filter-actions{grid-column:1;justify-content:stretch;gap:8px;flex-direction:column}
            .filter-actions .btn{width:100%}
        }
        @media (max-width:768px){
            .shell{padding:24px 16px 60px}
            .page-header{margin:-24px -16px 24px;padding:32px 0}
            h1{font-size:24px}
            .toolbar{padding:16px 20px;flex-direction:column;align-items:stretch}
            .nav-section{justify-content:center}
            .search-group{flex-direction:column;gap:8px}
            .search-group .btn{margin-top:0!important}
            .products-grid{grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px}
            .product-image{height:200px}
            .product-info{padding:16px}
            .product-title{font-size:14px}
            .product-price{font-size:16px}
            .card{padding:20px}
        }
        
        /* Utilities */
        .actions{display:flex;gap:12px;flex-wrap:wrap;align-items:center}
        .right{justify-content:flex-end}
        .center{justify-content:center}
        .muted{color:var(--muted);font-size:13px;font-weight:500}
        .section-title{font-size:20px;font-weight:700;color:var(--text);margin:32px 0 16px;letter-spacing:-0.02em}
        
        /* Empty State */
        .empty-state{
            text-align:center;padding:64px 32px;background:var(--card);
            border-radius:20px;border:2px dashed var(--line);margin:32px 0
        }
        .empty-state svg{margin-bottom:16px;opacity:0.5}
        .empty-state h3{font-size:18px;font-weight:600;color:var(--text);margin-bottom:8px}
        .empty-state p{color:var(--muted);margin-bottom:0}
        
        /* Sepet Bildirimi */
        .cart-notification{
            position:fixed;top:32px;right:-420px;width:380px;background:var(--card);
            border:1px solid var(--line);border-radius:20px;box-shadow:0 16px 64px rgba(0,0,0,0.15);
            z-index:1000;transition:right 0.4s cubic-bezier(0.4,0,0.2,1);font-family:inherit;
            backdrop-filter:blur(8px)
        }
        .cart-notification.show{right:32px}
        .cart-notification-content{
            padding:24px;display:flex;align-items:flex-start;gap:16px;
            border-bottom:1px solid var(--line)
        }
        .cart-icon{
            width:48px;height:48px;background:linear-gradient(135deg, var(--success) 0%, #047857 100%);
            border-radius:16px;display:flex;align-items:center;justify-content:center;
            color:#fff;flex-shrink:0;box-shadow:0 4px 16px rgba(5,150,105,0.3)
        }
        .cart-text{flex:1}
        .cart-title{
            font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;
            color:var(--text);margin-bottom:8px
        }
        .cart-message{font-size:14px;color:var(--muted);font-weight:500}
        .cart-close{
            background:var(--hover);border:none;padding:8px;border-radius:12px;cursor:pointer;
            color:var(--muted);transition:all 0.2s ease;flex-shrink:0
        }
        .cart-close:hover{background:var(--line);transform:scale(1.1)}
        .cart-actions{padding:20px 24px 24px}
        .cart-btn{
            display:inline-block;padding:12px 20px;border-radius:12px;text-decoration:none;
            font-size:13px;font-weight:600;letter-spacing:0.01em;
            transition:all 0.2s cubic-bezier(0.4,0,0.2,1);text-align:center;width:100%
        }
        .cart-btn-outline{
            background:transparent;color:var(--accent);border:2px solid var(--line);
            box-shadow:0 2px 8px var(--shadow)
        }
        .cart-btn-outline:hover{
            background:var(--accent);color:#fff;transform:translateY(-2px);
            box-shadow:0 8px 24px rgba(15,23,42,0.2)
        }
        
        /* Product Info in Notification */
        .cart-product{
            display:flex;align-items:center;gap:16px;margin:12px 0;
            padding:16px;background:var(--bg);border-radius:16px;border:1px solid var(--line)
        }
        .cart-product-image{width:64px;height:64px;flex-shrink:0;border-radius:12px;overflow:hidden}
        .cart-product-image img{
            width:100%;height:100%;object-fit:cover;border:1px solid var(--line)
        }
        .cart-product-info{flex:1;min-width:0}
        .cart-product-title{
            font-size:14px;font-weight:600;color:var(--text);margin-bottom:4px;
            overflow:hidden;text-overflow:ellipsis;white-space:nowrap
        }
        .cart-product-author{font-size:12px;color:var(--muted);margin-bottom:4px;font-weight:500}
        .cart-product-price{font-size:14px;font-weight:700;color:var(--success)}
        
        @media (max-width:480px){
            .cart-notification{width:calc(100vw - 40px);right:-100%}
            .cart-notification.show{right:20px}
        }
        
        @keyframes spin{
            from{transform:rotate(0deg)}
            to{transform:rotate(360deg)}
        }
        
        /* Otomatik Tamamlama Stilleri */
        .autocomplete-container {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--card);
            border: 1px solid var(--line);
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .autocomplete-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid var(--line);
            transition: background-color 0.15s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .autocomplete-item:hover,
        .autocomplete-item.selected {
            background: var(--accent);
            color: #EDEDED;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-product-image {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border-radius: 6px;
            overflow: hidden;
            background: var(--gray-50);
        }

        .autocomplete-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .autocomplete-product-info {
            flex: 1;
            min-width: 0;
        }

        .autocomplete-product-title {
            font-size: 14px;
            font-weight: 500;
            color: inherit;
            margin-bottom: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .autocomplete-product-author {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 2px;
        }

        .autocomplete-product-store {
            font-size: 12px;
            color: var(--primary);
            font-weight: 500;
        }

        .autocomplete-product-price {
            font-size: 14px;
            font-weight: 600;
            color: var(--price-color);
            flex-shrink: 0;
        }

        .search-group {
            position: relative;
        }

        /* Loading animasyonu */
        .autocomplete-loading {
            padding: 16px;
            text-align: center;
            color: var(--muted);
            font-size: 14px;
        }

        .autocomplete-loading::after {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid var(--line);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }

        /* Otomatik Tamamlama Stilleri */
    .autocomplete-container {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--card);
        border: 1px solid var(--line);
        border-top: none;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        display: none;
    }

    .autocomplete-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid var(--line);
        transition: background-color 0.15s ease;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .autocomplete-item:hover,
    .autocomplete-item.selected {
        background: var(--accent);
        color: #EDEDED;
    }

    .autocomplete-item:last-child {
        border-bottom: none;
    }

    .autocomplete-product-image {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border-radius: 6px;
        overflow: hidden;
        background: var(--gray-50);
    }

    .autocomplete-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .autocomplete-product-info {
        flex: 1;
        min-width: 0;
    }

    .autocomplete-product-title {
        font-size: 14px;
        font-weight: 500;
        color: inherit;
        margin-bottom: 2px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .autocomplete-product-author {
        font-size: 12px;
        color: var(--muted);
        margin-bottom: 2px;
    }

    .autocomplete-product-store {
        font-size: 12px;
        color: var(--primary);
        font-weight: 500;
    }

    .autocomplete-product-price {
        font-size: 14px;
        font-weight: 600;
        color: var(--price-color);
        flex-shrink: 0;
    }

    .search-group {
        position: relative;
    }

    /* Loading animasyonu */
    .autocomplete-loading {
        padding: 16px;
        text-align: center;
        color: var(--muted);
        font-size: 14px;
    }

    .autocomplete-loading::after {
        content: '';
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid var(--line);
        border-radius: 50%;
        border-top-color: var(--primary);
        animation: spin 1s linear infinite;
        margin-left: 8px;
    }
    </style>
</head>
<body>
<div class="page-header">
    <div class="header-content">
        <div>
            <h1>Kitap Mağazası</h1>
            <div class="header-subtitle">Hoş geldiniz, {{ auth()->user()->username }}</div>
        </div>
        <div class="nav-section">
            <a href="/bag" class="btn outline" style="color:rgb(255, 255, 255);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/>
                </svg>
                Sepetim
            </a>
            <a href="/myorders" class="btn outline" style="color:rgb(255, 255, 255);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3h18v18H3zM8 7h8M8 11h8M8 15h5"/>
                </svg>
                Siparişlerim
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Çıkış Yap
                </button>
            </form>
        </div>
    </div>
</div>

<div class="shell">

    @if(session('success')) <div class="notice success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="notice error">{{ session('error') }}</div> @endif

    <div class="card" style="margin-top:10px">
        <form action="{{ route('search') }}" method="GET" class="filters">
            @csrf
            <div class="search-group">
                <div class="field" style="flex:1">
                    <label for="q">Ürün Ara</label>
                    <input id="q" type="text" name="q" placeholder="Ürün adı, Mağaza adı, yazar..." value="{{ $query ?? request('q') }}" autocomplete="off">
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="size" value="12">
                </div>
                <button class="btn" type="submit" style="margin-top:26px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    Ara
                </button>
            </div>
            @if(isset($query) && !empty($query))
                <div class="field">
                    <label for="category_title">Kategori</label>
                    <select id="category_title" name="category_title">
                        <option value="">Seçiniz</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_title }}" {{ request('category_title') == $category->category_title ? 'selected' : '' }}>
                                {{ $category->category_title }}
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
                <div class="filter-actions">
                    <button class="btn outline btn-sm" type="button" onclick="resetFilters()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-5h5"/>
                        </svg>
                        Sıfırla
                    </button>
                    <button class="btn btn-sm" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                        Uygula
                    </button>
                </div>
            @endif
        </form>
    </div>

    @if(empty($query))
        <div class="card" style="margin-top:12px">
            <form action="{{ route('sorting') }}" method="GET" class="actions" style="justify-content:flex-start;align-items:center">
                @csrf
                <div class="custom-dropdown">
                    <div class="dropdown-btn" onclick="toggleDropdown()" id="sortingDropdown">
                        <span id="selectedOption">Önerilen</span>
                        <div class="dropdown-arrow"></div>
                    </div>
                    <div class="dropdown-content" id="dropdownContent">
                        <div class="dropdown-item selected" onclick="selectOption('', 'Önerilen')">Önerilen</div>
                        <div class="dropdown-item" onclick="selectOption('price_asc', 'En Düşük Fiyat')">En Düşük Fiyat</div>
                        <div class="dropdown-item" onclick="selectOption('price_desc', 'En Yüksek Fiyat')">En Yüksek Fiyat</div>
                        <div class="dropdown-item" onclick="selectOption('stock_quantity_asc', 'En Az Stok')">En Az Stok</div>
                        <div class="dropdown-item" onclick="selectOption('stock_quantity_desc', 'En Çok Stok')">En Çok Stok</div>
                    </div>
                    <input type="hidden" name="sorting" id="sortingValue" value="">
                </div>
                <button class="btn" type="submit" style="margin-left:16px">Sırala</button>
            </form>
        </div>
    @endif

    <p class="muted" style="display:none">Gösterilen Ürün Sayısı: {{ count($products) }}</p>

    @if(isset($query) && !empty($query))
        <div class="muted" style="margin-top:10px"><strong>Arama Sonuçları</strong></div>
    @endif

    @if(count($products) > 0)
        <div class="products-grid">
            @foreach($products as $p)
                @php
                    $isOutOfStock = (is_array($p) ? $p['stock_quantity'] : $p->stock_quantity) <= 0;
                    $imageUrl = is_array($p) 
                        ? '/storage/productsImages/' . $p['images'][0]
                        : $p->first_image;
                @endphp
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ $imageUrl }}" alt="{{ is_array($p) ? $p['title'] : $p->title }}">
                        @if($isOutOfStock)
                            <div class="stock-overlay">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:4px">
                                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                Stokta Yok
                            </div>
                        @endif
                    </div>
                    
                    <div class="product-info">
                        <div class="product-title">{{ is_array($p) ? $p['title'] : $p->title }}</div>
                        <div class="product-author">{{ is_array($p) ? $p['author'] : $p->author }}</div>
                        <div class="product-store">{{ is_array($p) ? $p['store_name'] : $p->store->name }}</div>
                        <div class="product-price">{{ number_format(is_array($p) ? $p['list_price'] : $p->list_price, 2) }} TL</div>
                    </div>
                    
                    <div class="product-actions">
                        <form action="{{ route('add') }}" method="POST" style="margin:0" class="add-to-bag-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ is_array($p) ? $p['id'] : $p->id }}">
                            <input type="hidden" name="product_title" value="{{ is_array($p) ? $p['title'] : $p->title }}">
                            <input type="hidden" name="product_author" value="{{ is_array($p) ? $p['author'] : $p->author }}">
                            <input type="hidden" name="product_price" value="{{ is_array($p) ? $p['list_price'] : $p->list_price }}">
                            <input type="hidden" name="product_image" value="{{ basename($imageUrl) }}">
                            <button class="add-btn" type="submit" {{ $isOutOfStock ? 'disabled' : '' }}>
                                {{ $isOutOfStock ? 'STOKTA YOK' : 'SEPETE EKLE' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/>
            </svg>
            <h3>Ürün Bulunamadı</h3>
            <p>Aradığınız kriterlere uygun ürün bulunamadı. Filtreleri değiştirmeyi deneyin.</p>
        </div>
    @endif

    <div class="actions center" style="margin-top:32px">
        <a href="{{ route('main') }}" class="btn outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-5h5"/>
            </svg>
            Tüm Ürünleri Göster
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
    function resetFilter(){
        const url = new URL(window.location.origin + '/filter');
        url.searchParams.set('page', '1');
        url.searchParams.set('size', '12');
        window.location.href = url.toString();
    }

    // Dropdown functionality
    function toggleDropdown() {
        const dropdown = document.getElementById('sortingDropdown');
        const content = document.getElementById('dropdownContent');
        
        dropdown.classList.toggle('active');
        content.classList.toggle('show');
    }

    function selectOption(value, text) {
        document.getElementById('selectedOption').textContent = text;
        document.getElementById('sortingValue').value = value;
        
        // Update selected state
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.classList.remove('selected');
        });
        event.target.classList.add('selected');
        
        // Close dropdown
        document.getElementById('sortingDropdown').classList.remove('active');
        document.getElementById('dropdownContent').classList.remove('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.custom-dropdown');
        if (!dropdown.contains(event.target)) {
            document.getElementById('sortingDropdown').classList.remove('active');
            document.getElementById('dropdownContent').classList.remove('show');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.add-to-bag-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = form.querySelector('button');
                const originalText = button.textContent;
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;">
                        <path d="M21 12a9 9 0 11-6.219-8.56"/>
                    </svg>
                    Ekleniyor
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
                    // Form verilerini al
                    const productData = {
                        title: formData.get('product_title'),
                        author: formData.get('product_author'),
                        price: formData.get('product_price'),
                        image: formData.get('product_image')
                    };
                    
                    if(data.success) {
                        showNotification(data.message, 'success', productData);
                    } else {
                        showNotification(data.message, 'error', productData);
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

    function showNotification(message, type, productData = null) {
        // Mevcut bildirimleri kaldır
        const existingNotification = document.querySelector('.cart-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const isSuccess = type === 'success';
        const iconBg = isSuccess ? 'var(--success)' : 'var(--danger)';
        const title = isSuccess ? 'SEPETİNİZE EKLENDİ' : 'ÜRÜN SEPETE EKLENEMEDİ';
        const iconSvg = isSuccess ? 
            `<path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/>` :
            `<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>`;
        
        // Ürün bilgisi HTML'i
        let productHtml = '';
        if (productData && isSuccess) {
            productHtml = `
                <div class="cart-product">
                    <div class="cart-product-image">
                        <img src="/storage/productsImages/${productData.image}" alt="${productData.title}">
                    </div>
                    <div class="cart-product-info">
                        <div class="cart-product-title">${productData.title}</div>
                        <div class="cart-product-author">${productData.author}</div>
                        <div class="cart-product-price">${parseFloat(productData.price).toFixed(2)} TL</div>
                    </div>
                </div>
            `;
        }
        
        // Sepet bildirimi oluştur
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
                    ${productHtml}
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
        
        // Body'ye ekle
        document.body.appendChild(notification);
        
        // Animasyonla göster
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // 5 saniye sonra kaldır
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }

    // Otomatik Tamamlama Sistemi
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('q');
        const searchGroup = document.querySelector('.search-group');
        
        if (!searchInput || !searchGroup) return;
        
        // Otomatik tamamlama container'ı oluştur
        const autocompleteContainer = document.createElement('div');
        autocompleteContainer.className = 'autocomplete-container';
        searchGroup.appendChild(autocompleteContainer);
        
        let debounceTimer;
        let selectedIndex = -1;
        let suggestions = [];
        let isLoading = false;
        
        // Debounce fonksiyonu
        function debounce(func, delay) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(func, delay);
        }
        
        // Otomatik tamamlama verilerini getir
        async function fetchAutocomplete(query) {
            if (query.length < 2) {
                hideAutocomplete();
                return;
            }
            
            isLoading = true;
            showLoadingState();
            
            try {
                // Web route kullanıyoruz (/search/autocomplete)
                const response = await fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success && data.data && data.data.products && data.data.products.length > 0) {
                    suggestions = data.data.products;
                    showAutocomplete();
                } else {
                    showNoResults();
                }
            } catch (error) {
                console.error('Otomatik tamamlama hatası:', error);
                showError(error.message);
            } finally {
                isLoading = false;
            }
        }
        
        // Loading durumunu göster
        function showLoadingState() {
            autocompleteContainer.innerHTML = '<div class="autocomplete-loading">Aranıyor...</div>';
            autocompleteContainer.style.display = 'block';
        }
        
        // Sonuç bulunamadı mesajı
        function showNoResults() {
            autocompleteContainer.innerHTML = '<div class="autocomplete-loading">Sonuç bulunamadı</div>';
            autocompleteContainer.style.display = 'block';
        }
        
        // Hata mesajı
        function showError(message = 'Bir hata oluştu') {
            autocompleteContainer.innerHTML = `<div class="autocomplete-loading">${message}</div>`;
            autocompleteContainer.style.display = 'block';
        }
        
        // Otomatik tamamlama listesini göster
        function showAutocomplete() {
            autocompleteContainer.innerHTML = '';
            
            suggestions.forEach((product, index) => {
                const item = document.createElement('div');
                item.className = 'autocomplete-item';
                
                const imageUrl = product.images && product.images.length > 0 
                    ? `/storage/productsImages/${product.images[0]}`
                    : '';
                
                item.innerHTML = `
                    <div class="autocomplete-product-image">
                        ${imageUrl ? `<img src="${imageUrl}" alt="${product.title}" onerror="this.style.display='none'">` : ''}
                    </div>
                    <div class="autocomplete-product-info">
                        <div class="autocomplete-product-title">
                            ${highlightQuery(product.title, searchInput.value)}
                        </div>
                        <div class="autocomplete-product-author">${product.author || ''}</div>
                        <div class="autocomplete-product-store">${product.store_name || ''}</div>
                    </div>
                    <div class="autocomplete-product-price">
                        ${parseFloat(product.list_price || 0).toFixed(2)} TL
                    </div>
                `;
                
                item.addEventListener('click', () => {
                    selectSuggestion(product);
                });
                
                item.addEventListener('mouseenter', () => {
                    selectedIndex = index;
                    updateSelection();
                });
                
                autocompleteContainer.appendChild(item);
            });
            
            autocompleteContainer.style.display = 'block';
            selectedIndex = -1;
        }
        
        // Otomatik tamamlama listesini gizle
        function hideAutocomplete() {
            autocompleteContainer.style.display = 'none';
            selectedIndex = -1;
        }
        
        // Seçili öğeyi vurgula
        function updateSelection() {
            const items = autocompleteContainer.querySelectorAll('.autocomplete-item');
            items.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            });
        }
        
        // Öneriyi seç
        function selectSuggestion(product) {
            searchInput.value = product.title;
            hideAutocomplete();
            
            // Arama formunu otomatik gönder
            const form = searchInput.closest('form');
            if (form) {
                form.submit();
            }
        }
        
        // Arama terimini vurgula
        function highlightQuery(text, query) {
            if (!query || !text) return text;
            const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return text.replace(regex, '<mark style="background: var(--primary); color: white; padding: 1px 2px; border-radius: 2px;">$1</mark>');
        }
        
        // Input event listener
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            debounce(() => fetchAutocomplete(query), 300);
        });
        
        // Klavye navigasyonu
        searchInput.addEventListener('keydown', function(e) {
            if (autocompleteContainer.style.display === 'none') return;
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, suggestions.length - 1);
                    updateSelection();
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelection();
                    break;
                    
                case 'Enter':
                    e.preventDefault();
                    if (selectedIndex >= 0 && suggestions[selectedIndex]) {
                        selectSuggestion(suggestions[selectedIndex]);
                    } else {
                        // Form gönder
                        const form = this.closest('form');
                        if (form) form.submit();
                    }
                    break;
                    
                case 'Escape':
                    hideAutocomplete();
                    break;
            }
        });
        
        // Dışarı tıklandığında gizle
        document.addEventListener('click', function(e) {
            if (!searchGroup.contains(e.target)) {
                hideAutocomplete();
            }
        });
        
        // Focus olduğunda mevcut değer varsa önerileri göster
        searchInput.addEventListener('focus', function() {
            const query = this.value.trim();
            if (query.length >= 2) {
                fetchAutocomplete(query);
            }
        });
    });
    // Otomatik Tamamlama Sistemi
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('q');
    const searchGroup = document.querySelector('.search-group');
    
    if (!searchInput || !searchGroup) return;
    
    // Otomatik tamamlama container'ı oluştur
    const autocompleteContainer = document.createElement('div');
    autocompleteContainer.className = 'autocomplete-container';
    searchGroup.appendChild(autocompleteContainer);
    
    let debounceTimer;
    let selectedIndex = -1;
    let suggestions = [];
    let isLoading = false;
    
    // Debounce fonksiyonu
    function debounce(func, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(func, delay);
    }
    
    // Otomatik tamamlama verilerini getir
    async function fetchAutocomplete(query) {
        if (query.length < 2) {
            hideAutocomplete();
            return;
        }
        
        isLoading = true;
        showLoadingState();
        
        try {
            // Web route kullanıyoruz (/search/autocomplete)
            const response = await fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            
            if (data.success && data.data && data.data.products && data.data.products.length > 0) {
                suggestions = data.data.products;
                showAutocomplete();
            } else {
                showNoResults();
            }
        } catch (error) {
            console.error('Otomatik tamamlama hatası:', error);
            showError();
        } finally {
            isLoading = false;
        }
    }
    
    // Loading durumunu göster
    function showLoadingState() {
        autocompleteContainer.innerHTML = '<div class="autocomplete-loading">Aranıyor...</div>';
        autocompleteContainer.style.display = 'block';
    }
    
    // Sonuç bulunamadı mesajı
    function showNoResults() {
        autocompleteContainer.innerHTML = '<div class="autocomplete-loading">Sonuç bulunamadı</div>';
        autocompleteContainer.style.display = 'block';
    }
    
    // Hata mesajı
    function showError() {
        autocompleteContainer.innerHTML = '<div class="autocomplete-loading">Bir hata oluştu</div>';
        autocompleteContainer.style.display = 'block';
    }
    
    // Otomatik tamamlama listesini göster
    function showAutocomplete() {
        autocompleteContainer.innerHTML = '';
        
        suggestions.forEach((product, index) => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            
            const imageUrl = product.images && product.images.length > 0 
                ? `/storage/productsImages/${product.images[0]}`
                : '';
            
            item.innerHTML = `
                <div class="autocomplete-product-image">
                    ${imageUrl ? `<img src="${imageUrl}" alt="${product.title}" onerror="this.style.display='none'">` : ''}
                </div>
                <div class="autocomplete-product-info">
                    <div class="autocomplete-product-title">
                        ${highlightQuery(product.title, searchInput.value)}
                    </div>
                    <div class="autocomplete-product-author">${product.author || ''}</div>
                    <div class="autocomplete-product-store">${product.store_name || ''}</div>
                </div>
                <div class="autocomplete-product-price">
                    ${parseFloat(product.list_price || 0).toFixed(2)} TL
                </div>
            `;
            
            item.addEventListener('click', () => {
                selectSuggestion(product);
            });
            
            item.addEventListener('mouseenter', () => {
                selectedIndex = index;
                updateSelection();
            });
            
            autocompleteContainer.appendChild(item);
        });
        
        autocompleteContainer.style.display = 'block';
        selectedIndex = -1;
    }
    
    // Otomatik tamamlama listesini gizle
    function hideAutocomplete() {
        autocompleteContainer.style.display = 'none';
        selectedIndex = -1;
    }
    
    // Seçili öğeyi vurgula
    function updateSelection() {
        const items = autocompleteContainer.querySelectorAll('.autocomplete-item');
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
    }
    
    // Öneriyi seç
    function selectSuggestion(product) {
        searchInput.value = product.title;
        hideAutocomplete();
        
        // Arama formunu otomatik gönder
        const form = searchInput.closest('form');
        if (form) {
            form.submit();
        }
    }
    
    // Arama terimini vurgula
    function highlightQuery(text, query) {
        if (!query || !text) return text;
        const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        return text.replace(regex, '<mark style="background: var(--primary); color: white; padding: 1px 2px; border-radius: 2px;">$1</mark>');
    }
    
    // Input event listener
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        debounce(() => fetchAutocomplete(query), 300);
    });
    
    // Klavye navigasyonu
    searchInput.addEventListener('keydown', function(e) {
        if (autocompleteContainer.style.display === 'none') return;
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, suggestions.length - 1);
                updateSelection();
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection();
                break;
                
            case 'Enter':
                e.preventDefault();
                if (selectedIndex >= 0 && suggestions[selectedIndex]) {
                    selectSuggestion(suggestions[selectedIndex]);
                } else {
                    // Form gönder
                    const form = this.closest('form');
                    if (form) form.submit();
                }
                break;
                
            case 'Escape':
                hideAutocomplete();
                break;
        }
    });
    
    // Dışarı tıklandığında gizle
    document.addEventListener('click', function(e) {
        if (!searchGroup.contains(e.target)) {
            hideAutocomplete();
        }
    });
    
    // Focus olduğunda mevcut değer varsa önerileri göster
    searchInput.addEventListener('focus', function() {
        const query = this.value.trim();
        if (query.length >= 2) {
            fetchAutocomplete(query);
        }
    });
});
</script>
</body>
</html>