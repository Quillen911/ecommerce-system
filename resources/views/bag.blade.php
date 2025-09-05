<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sepetim</title>
    <style>
        /* Color Variables - Optimized */
        :root {
            --bg: #0F0F0F;
            --text: #FFFFFF;
            --muted: #B0B0B0;
            --line: #2A2A2A;
            --accent: #3A3A3A;
            --success: #00D4AA;
            --danger: #FF4444;
            --card: #1A1A1A;
            --primary: #00D4AA;
        }

        /* Base Styles */
        * { box-sizing: border-box }
        html, body {
            margin: 0;
            padding: 0;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
        }
        body {
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
        }
        .shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 20px 80px;
        }

        /* Header */
        .page-header {
            background: var(--card);
            border-bottom: 1px solid var(--line);
            padding: 20px 0;
            margin: -24px -20px 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: var(--text);
        }
        .header-subtitle {
            font-size: 14px;
            color: var(--muted);
            font-weight: 500;
        }

        /* Toolbar */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin: 0 0 20px;
            background: var(--card);
            padding: 16px 20px;
            border-radius: 8px;
            border: 1px solid var(--line);
        }
        .nav-section {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        /* Buttons */
        .btn {
            border: 1px solid var(--accent);
            background: var(--accent);
            color: var(--text);
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 700;
            transition: all .2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn:hover {
            background: var(--accent);
            border-color: var(--accent);
            transform: translateY(-1px);
        }
        .btn.outline {
            background: transparent;
            color: var(--accent);
            border-color: var(--accent);
        }
        .btn.outline:hover {
            background: var(--accent);
            color: var(--text);
        }
        .btn.success {
            background: var(--success);
            border-color: var(--success);
        }
        .btn.success:hover {
            background: var(--primary);
        }
        .btn.danger {
            background: var(--danger);
            border-color: var(--danger);
        }
        .btn.danger:hover {
            background: var(--danger);
        }

        /* Main Layout */
        .main-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            align-items: start;
        }

        /* Cart Items */
        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .cart-item {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            gap: 20px;
            align-items: center;
            transition: all .2s ease;
        }
        .cart-item:hover {
            border-color: var(--accent);
        }

        /* Product */
        .product-image {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid var(--line);
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .product-info {
            flex: 1;
            min-width: 0;
        }
        .product-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }
        .product-author {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 8px;
            font-weight: 500;
        }
        .product-store {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
            margin-bottom: 4px;
            font-weight: 600;
        }
        .product-category {
            font-size: 12px;
            color: var(--primary);
            font-weight: 600;
        }

        /* Quantity & Price */
        .quantity-price {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
            min-width: 120px;
        }
        .quantity {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .quantity-btn {
            width: 32px;
            height: 32px;
            border: 1px solid var(--line);
            background: var(--card);
            color: var(--text);
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .2s ease;
            font-size: 16px;
            font-weight: 600;
        }
        .quantity-btn:hover {
            background: var(--accent);
            border-color: var(--accent);
        }
        .quantity-number {
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            min-width: 40px;
            text-align: center;
        }
        .price {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }
        .total-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--success);
        }

        /* Campaign */
        .campaign {
            background: var(--card);
            border: 1px solid var(--success);
            border-radius: 12px;
            padding: 20px;
        }
        .campaign-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--success);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .campaign-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }
        .campaign-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--line);
        }
        .campaign-item:last-child {
            border-bottom: none;
        }
        .campaign-label {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
        }
        .campaign-value {
            font-weight: 600;
            color: var(--text);
        }

        /* Right Sidebar */
        .right-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: sticky;
            top: 20px;
        }

        /* Summary */
        .summary {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
        }
        .summary-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 16px;
            text-align: center;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--line);
        }
        .summary-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 18px;
            color: var(--success);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 64px 32px;
            background: var(--card);
            border-radius: 20px;
            border: 2px dashed var(--line);
            margin: 32px 0;
        }
        .empty-state h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }
        .empty-state p {
            color: var(--muted);
            margin-bottom: 0;
        }

        /* Notices */
        .notice {
            padding: 12px 16px;
            border: 1px solid var(--line);
            margin: 0 0 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .notice.success {
            color: var(--success);
            background: rgba(0, 212, 170, 0.1);
            border-color: var(--success);
        }
        .notice.error {
            color: var(--danger);
            background: rgba(255, 68, 68, 0.1);
            border-color: var(--danger);
        }

        /* Responsive - Mobile First */
        @media (max-width: 768px) {
            .shell {
                padding: 16px 12px 60px;
            }
            .page-header {
                margin: -16px -12px 20px;
                padding: 20px 0;
            }
            .header-content {
                padding: 0 12px;
            }
            h1 {
                font-size: 20px;
            }
            .header-subtitle {
                font-size: 13px;
            }

            /* Mobile Layout */
            .main-layout {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .right-sidebar {
                position: static;
                order: -1;
            }

            /* Toolbar */
            .toolbar {
                flex-direction: column;
                gap: 12px;
                padding: 12px 16px;
                margin-bottom: 16px;
            }
            .nav-section {
                justify-content: center;
                width: 100%;
            }
            .btn {
                padding: 12px 20px;
                font-size: 13px;
                min-height: 44px;
                touch-action: manipulation;
            }

            /* Cart Items */
            .cart-items {
                gap: 12px;
            }
            .cart-item {
                flex-direction: column;
                text-align: center;
                gap: 16px;
                padding: 16px;
            }
            .product-image {
                width: 100px;
                height: 100px;
                margin: 0 auto;
            }
            .product-info {
                text-align: center;
            }
            .product-title {
                font-size: 15px;
                margin-bottom: 6px;
            }
            .product-author {
                font-size: 13px;
                margin-bottom: 6px;
            }
            .product-store {
                font-size: 11px;
                margin-bottom: 4px;
            }
            .product-category {
                font-size: 11px;
            }

            /* Quantity & Price */
            .quantity-price {
                align-items: center;
                min-width: auto;
                width: 100%;
            }
            .quantity {
                gap: 12px;
                justify-content: center;
            }
            .quantity-btn {
                width: 44px;
                height: 44px;
                font-size: 18px;
                touch-action: manipulation;
            }
            .quantity-number {
                font-size: 18px;
                min-width: 50px;
            }
            .price {
                font-size: 16px;
            }
            .total-price {
                font-size: 18px;
            }

            /* Campaign */
            .campaign {
                padding: 16px;
            }
            .campaign-title {
                font-size: 15px;
                margin-bottom: 10px;
            }
            .campaign-info {
                grid-template-columns: 1fr;
                gap: 8px;
            }
            .campaign-item {
                padding: 6px 0;
            }
            .campaign-label {
                font-size: 11px;
            }
            .campaign-value {
                font-size: 13px;
            }

            /* Summary */
            .summary {
                padding: 20px;
            }
            .summary-title {
                font-size: 16px;
                margin-bottom: 12px;
            }
            .summary-row {
                padding: 10px 0;
                font-size: 14px;
            }
            .summary-row:last-child {
                font-size: 16px;
            }

            /* Empty State */
            .empty-state {
                padding: 40px 20px;
                margin: 20px 0;
            }
            .empty-state h3 {
                font-size: 16px;
                margin-bottom: 6px;
            }
            .empty-state p {
                font-size: 13px;
            }

            /* Notices */
            .notice {
                padding: 10px 12px;
                margin-bottom: 16px;
                font-size: 13px;
            }
        }

        /* Tablet */
        @media (min-width: 769px) and (max-width: 1024px) {
            .shell {
                padding: 20px 16px 60px;
            }
            .cart-item {
                gap: 16px;
                padding: 18px;
            }
            .product-image {
                width: 90px;
                height: 90px;
            }
            .quantity-btn {
                width: 36px;
                height: 36px;
            }
        }

        /* Touch-friendly improvements */
        @media (hover: none) and (pointer: coarse) {
            .btn:hover {
                transform: none;
                box-shadow: none;
            }
            .quantity-btn:hover {
                background: var(--card);
                border-color: var(--line);
            }
            .cart-item:hover {
                border-color: var(--line);
            }
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
                ← Alışverişe Devam
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="notice success">
            ✓ {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="notice error">
            ✗ {{ session('error') }}
        </div>
    @endif

    @if($products->isEmpty())
        <div class="empty-state">
            <div style="font-size:48px;margin-bottom:16px;opacity:0.5;">🛒</div>
            <h3>Sepetiniz Boş</h3>
            <p>Henüz sepete ürün eklemediniz. Alışverişe başlamak için ürünlere göz atın.</p>
            <a href="{{ route('main') }}" class="btn" style="margin-top:16px">
                ← Alışverişe Başla
            </a>
        </div>
    @else
        <div class="main-layout">
            <!-- Sol Taraf - Ürünler -->
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
                                🗑️ Kaldır
                            </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sağ Taraf - Sipariş Özeti -->
            <div class="right-sidebar">
                @if(isset($bestCampaign['discount']) && $bestCampaign['discount'])
                    <div class="campaign">
                        <div class="campaign-title">
                            ⭐ Aktif Kampanya
                        </div>
                        <div class="campaign-info">
                            @if(isset($bestCampaign['store_name']) && $bestCampaign['store_name'])
                                <div class="campaign-item">
                                    <span class="campaign-label">Mağaza</span>
                                    <span class="campaign-value">{{ $bestCampaign['store_name'] }}</span>
                                </div>
                            @endif
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

                <a href="{{ route('order') }}" class="btn success" style="width:100%;justify-content:center;padding:16px;font-size:14px">
                    Sipariş Oluştur →
                </a>
            </div>
        </div>
    @endif
</div>

<script src="{{ asset('js/bag.js') }}"></script>
</body>
</html>