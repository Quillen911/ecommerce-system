<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sepetim</title>
    <style>
        :root{
            --bg:#1B1B1F; --text:#EDEDED; --muted:#A0A0A0; --line:#333338;
            --accent:#404046; --success:#00C6AE; --danger:#FF6B6B;
            --card:#232327; --shadow:rgba(0,0,0,0.3); --primary:#00C6AE;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-size:14px}
        body{font-family:"Inter",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;line-height:1.6}
        .shell{max-width:1200px;margin:0 auto;padding:24px 20px 80px}
        
        /* Header */
        .page-header{background:var(--card);border-bottom:1px solid var(--line);padding:20px 0;margin:-24px -20px 24px;box-shadow:0 4px 20px var(--shadow)}
        .header-content{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center}
        h1{font-size:24px;font-weight:600;margin:0;color:var(--text)}
        .header-subtitle{font-size:14px;color:var(--muted);font-weight:500}
        
        /* Toolbar */
        .toolbar{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin:0 0 20px;background:var(--card);padding:16px 20px;border-radius:8px;border:1px solid var(--line)}
        .nav-section{display:flex;gap:6px;align-items:center}
        
        /* Buttons */
        .btn{border:1px solid var(--accent);background:var(--accent);color:#EDEDED;padding:10px 16px;border-radius:8px;cursor:pointer;text-transform:uppercase;font-size:12px;font-weight:600;transition:all .2s ease;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
        .btn:hover{background:#505056;border-color:#505056;transform:translateY(-1px)}
        .btn.outline{background:transparent;color:var(--accent);border-color:var(--accent)}
        .btn.outline:hover{background:var(--accent);color:#EDEDED}
        .btn.success{background:var(--success);border-color:var(--success)}
        .btn.success:hover{background:#00B894}
        .btn.danger{background:var(--danger);border-color:var(--danger)}
        .btn.danger:hover{background:#FF5252}
        
        /* Cart Items */
        .cart-items{display:flex;flex-direction:column;gap:16px}
        .cart-item{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:20px;display:flex;gap:20px;align-items:center;transition:all .2s ease}
        .cart-item:hover{border-color:var(--accent)}
        
        /* Product */
        .product-image{width:100px;height:100px;border-radius:8px;overflow:hidden;flex-shrink:0;border:2px solid var(--line)}
        .product-image img{width:100%;height:100%;object-fit:cover}
        .product-info{flex:1;min-width:0}
        .product-title{font-size:16px;font-weight:600;color:var(--text);margin-bottom:4px}
        .product-author{font-size:14px;color:var(--muted);margin-bottom:8px}
        .product-store{font-size:12px;color:var(--muted);text-transform:uppercase;margin-bottom:4px}
        .product-category{font-size:12px;color:var(--primary);font-weight:500}
        
        /* Quantity & Price */
        .quantity-price{display:flex;flex-direction:column;align-items:flex-end;gap:8px;min-width:120px}
        .quantity{display:flex;align-items:center;gap:8px}
        .quantity-btn{width:32px;height:32px;border:1px solid var(--line);background:var(--card);color:var(--text);border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s ease;font-size:16px;font-weight:600}
        .quantity-btn:hover{background:var(--accent);border-color:var(--accent)}
        .quantity-number{font-size:16px;font-weight:600;color:var(--text);min-width:40px;text-align:center}
        .price{font-size:18px;font-weight:700;color:#4A90E2}
        .total-price{font-size:20px;font-weight:700;color:var(--success)}
        
        /* Campaign */
        .campaign{background:rgba(0,198,174,0.1);border:1px solid var(--success);border-radius:12px;padding:20px;margin:20px 0}
        .campaign-title{font-size:16px;font-weight:600;color:var(--success);margin-bottom:12px;display:flex;align-items:center;gap:8px}
        .campaign-info{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px}
        .campaign-item{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(0,198,174,0.2)}
        .campaign-item:last-child{border-bottom:none}
        .campaign-label{font-size:12px;color:var(--muted);text-transform:uppercase}
        .campaign-value{font-weight:600;color:var(--text)}
        
        /* Summary */
        .summary{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:24px;margin-top:24px}
        .summary-title{font-size:18px;font-weight:600;color:var(--text);margin-bottom:16px;text-align:center}
        .summary-row{display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--line)}
        .summary-row:last-child{border-bottom:none;font-weight:700;font-size:18px;color:var(--success)}
        
        /* Empty State */
        .empty-state{text-align:center;padding:64px 32px;background:var(--card);border-radius:20px;border:2px dashed var(--line);margin:32px 0}
        .empty-state svg{margin-bottom:16px;opacity:0.5}
        .empty-state h3{font-size:18px;font-weight:600;color:var(--text);margin-bottom:8px}
        .empty-state p{color:var(--muted);margin-bottom:0}
        
        /* Notices */
        .notice{padding:12px 16px;border:1px solid var(--line);margin:0 0 20px;border-radius:8px;display:flex;align-items:center;gap:8px}
        .notice.success{color:var(--success);background:rgba(0,198,174,0.1);border-color:var(--success)}
        .notice.error{color:var(--danger);background:rgba(255,107,107,0.1);border-color:var(--danger)}
        
        /* Responsive */
        @media (max-width:768px){
            .shell{padding:24px 16px 60px}
            .page-header{margin:-24px -16px 24px;padding:32px 0}
            .toolbar{flex-direction:column;align-items:stretch}
            .nav-section{justify-content:center}
            .cart-item{flex-direction:column;text-align:center;gap:16px}
            .product-image{width:120px;height:120px}
            .quantity-price{align-items:center}
            .campaign-info{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
<div class="page-header">
    <div class="header-content">
        <div>
            <h1>Sepetim</h1>
            <div class="header-subtitle">Alışveriş sepetiniz</div>
        </div>
    </div>
</div>

<div class="shell">
    <div class="toolbar">
        <div class="nav-section">
            <a href="{{ route('main') }}" class="btn outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>
                </svg>
                Alışverişe Devam
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ route('order') }}" class="btn success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/><path d="M12 5l7 7-7 7"/>
                </svg>
                Sipariş Oluştur
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="notice success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="notice error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    @if($products->isEmpty())
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/>
            </svg>
            <h3>Sepetiniz Boş</h3>
            <p>Henüz sepete ürün eklemediniz. Alışverişe başlamak için ürünlere göz atın.</p>
            <a href="{{ route('main') }}" class="btn" style="margin-top:16px">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>
                </svg>
                Alışverişe Başla
            </a>
        </div>
    @else
        <div class="cart-items">
            @foreach($products as $p)
                <div class="cart-item" data-item-id="{{ $p->id }}">
                    <div class="product-image">
                        <img src="{{ $p->product?->first_image ?? '/images/no-image.png' }}" alt="{{ $p->product?->title ?? 'Ürün' }}">
                    </div>
                    <div class="product-info">
                        <div class="product-title">{{ $p->product?->title ?? 'Ürün bilgisi yok' }}</div>
                        <div class="product-author">{{ $p->product?->author ?? 'Yazar bilgisi yok' }}</div>
                        <div class="product-store">{{ $p->product?->store?->name ?? 'Mağaza bilgisi yok' }}</div>
                        <div class="product-category">{{ $p->product->category?->category_title }}</div>
                    </div>
                    <div class="quantity-price">
                        <div class="quantity">
                            <button class="quantity-btn" onclick="updateQuantity({{ $p->id }}, -1)">-</button>
                            <span class="quantity-number">{{ $p->quantity }}</span>
                            <button class="quantity-btn" onclick="updateQuantity({{ $p->id }}, 1)">+</button>
                        </div>
                        <div class="price">{{ number_format($p->product?->list_price ?? 0,2) }} TL</div>
                        <div class="total-price">{{ number_format(($p->product?->list_price ?? 0) * $p->quantity,2) }} TL</div>
    
                    <form action="{{ route('bag.delete', $p->id) }}" method="POST" style="margin:0">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" class="btn danger" style="padding:6px 12px;font-size:10px">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            </svg>
                                Kaldır
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if(isset($bestCampaign['discount']) && $bestCampaign['discount'])
            <div class="campaign">
                <div class="campaign-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Aktif Kampanya
                </div>
                <div class="campaign-info">
                    <div class="campaign-item">
                        <span class="campaign-label">Kampanya</span>
                        <span class="campaign-value">{{ $bestCampaign['description'] }}</span>
                    </div>
                    <div class="campaign-item">
                        <span class="campaign-label">İndirim</span>
                        <span class="campaign-value">{{ number_format($bestCampaign['discount'],2) }} TL</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="summary">
            <div class="summary-title">Sepet Özeti</div>
            <div class="summary-row">
                <span>Ürün Toplamı</span>
                <span>{{ number_format($total,2) }} TL</span>
            </div>
            <div class="summary-row">
                <span>Kargo</span>
                <span>{{ $cargoPrice == 0 ? "200 TL üzeri ücretsiz" : number_format($cargoPrice,2)." TL" }}</span>
            </div>
            @if($discount > 0)
                <div class="summary-row">
                    <span>İndirim</span>
                    <span>-{{ number_format($discount,2) }} TL</span>
                </div>
            @endif
            <div class="summary-row">
                <span>Genel Toplam</span>
                <span>{{ number_format(floor($finalPrice * 100) / 100,2) }} TL</span>
            </div>
        </div>
    @endif
</div>

<script src="{{ asset('js/bag.js') }}"></script>
</body>
</html>