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
        /* Ürün Grid */
        .products-grid{
            display:grid;grid-template-columns:repeat(5,1fr);gap:20px;margin-top:16px
        }
        .product-card{
            border:1px solid var(--line);border-radius:8px;overflow:hidden;
            transition:all 0.2s ease;background:#fff;position:relative
        }
        .product-card:hover{box-shadow:0 4px 12px rgba(0,0,0,0.1);transform:translateY(-2px)}
        .product-image{
            width:100%;height:250px;background:#f8f8f8;position:relative;overflow:hidden
        }
        .product-image img{width:100%;height:100%;object-fit:cover}
        .product-info{padding:12px}
        .product-title{
            font-size:13px;font-weight:500;color:var(--text);margin-bottom:4px;
            line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;
            -webkit-box-orient:vertical;overflow:hidden
        }
        .product-author{font-size:11px;color:var(--muted);margin-bottom:6px}
        .product-price{
            font-size:14px;font-weight:600;color:var(--text);margin-bottom:8px
        }
        .product-actions{padding:0 12px 12px}
        .add-btn{
            width:100%;padding:8px;background:var(--accent);color:#fff;border:none;
            border-radius:4px;font-size:11px;font-weight:600;text-transform:uppercase;
            letter-spacing:0.5px;cursor:pointer;transition:all 0.2s ease
        }
        .add-btn:hover{background:#333}
        .add-btn:disabled{background:#ccc;cursor:not-allowed}
        
        @media (max-width:1200px){.products-grid{grid-template-columns:repeat(4,1fr)}}
        @media (max-width:900px){.products-grid{grid-template-columns:repeat(3,1fr)}}
        @media (max-width:600px){.products-grid{grid-template-columns:repeat(2,1fr);gap:12px}}
        .actions{display:flex;gap:10px;flex-wrap:wrap}
        .right{justify-content:flex-end}
        .muted{color:var(--muted);font-size:12px}
        
        /* Sepet Bildirimi */
        .cart-notification{
            position:fixed;top:20px;right:-400px;width:350px;background:#fff;
            border:1px solid var(--line);border-radius:8px;box-shadow:0 8px 32px rgba(0,0,0,0.12);
            z-index:1000;transition:right 0.3s cubic-bezier(0.4,0,0.2,1);font-family:inherit
        }
        .cart-notification.show{right:20px}
        .cart-notification-content{
            padding:16px;display:flex;align-items:flex-start;gap:12px;
            border-bottom:1px solid var(--line)
        }
        .cart-icon{
            width:40px;height:40px;background:var(--success);border-radius:50%;
            display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0
        }
        .cart-text{flex:1}
        .cart-title{
            font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:1px;
            color:var(--text);margin-bottom:4px
        }
        .cart-message{font-size:14px;color:var(--muted)}
        .cart-close{
            background:none;border:none;padding:4px;border-radius:4px;cursor:pointer;
            color:var(--muted);transition:background-color 0.2s ease;flex-shrink:0
        }
        .cart-close:hover{background:#f5f5f5}
        .cart-actions{padding:12px 16px}
        .cart-btn{
            display:inline-block;padding:8px 16px;border-radius:4px;text-decoration:none;
            font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1px;
            transition:all 0.2s ease;text-align:center;width:100%
        }
        .cart-btn-outline{
            background:transparent;color:var(--accent);border:1px solid var(--accent)
        }
        .cart-btn-outline:hover{background:var(--accent);color:#fff}
        
        @media (max-width:480px){
            .cart-notification{width:calc(100vw - 40px);right:-100%}
            .cart-notification.show{right:20px}
        }
        
        @keyframes spin{
            from{transform:rotate(0deg)}
            to{transform:rotate(360deg)}
        }
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
        <div class="products-grid">
            @foreach($products as $p)
                @php
                    $images = null;
                    if (is_array($p)) {
                        $images = $p['images'] ?? null;
                    } else {
                        $images = $p->images ?? null;
                    }
                    
                    $imageUrl = '/images/no-image.jpg'; // Varsayılan
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
                            <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:600;">STOKTA YOK</div>
                        @endif
                    </div>
                    
                    <div class="product-info">
                        <div class="product-title">{{ is_array($p) ? $p['title'] : $p->title }}</div>
                        <div class="product-author">{{ is_array($p) ? $p['author'] : $p->author }}</div>
                        <div class="product-price">{{ number_format(is_array($p) ? $p['list_price'] : $p->list_price, 2) }} TL</div>
                    </div>
                    
                    <div class="product-actions">
                        <form action="{{ route('add') }}" method="POST" style="margin:0" class="add-to-bag-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ is_array($p) ? $p['id'] : $p->id }}">
                            <button class="add-btn" type="submit" {{ $isOutOfStock ? 'disabled' : '' }}>
                                {{ $isOutOfStock ? 'STOKTA YOK' : 'SEPETE EKLE' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
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
</script>
</body>
</html>