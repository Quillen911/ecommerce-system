<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Siparişlerim</title>
    <style>
        :root {
            --bg: #0A0A0A;
            --text: #FFFFFF;
            --muted: #CCCCCC;
            --line: #333333;
            --accent: #404040;
            --success: #00E6B8;
            --danger: #FF5555;
            --card: #1F1F1F;
            --primary: #00E6B8;
            --warn: #FFB84D;
            --shadow: rgba(0,0,0,0.3);
            --hover: rgba(0,230,184,0.1);
            --secondary: #00E6B8;
            --gray-50: #2A2A2A;
            --gray-100: #333333;
            --hover-accent: #505050;
            --price-color: #00E6B8;
        }
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
        
        .page-header {
            background: var(--card);
            border-bottom: 1px solid var(--line);
            padding: 20px 0;
            margin: -24px -20px 24px;
            box-shadow: 0 4px 20px var(--shadow);
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
            box-shadow: 0 1px 3px var(--shadow);
            border: 1px solid var(--line);
        }
        .nav-section {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        
        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px var(--shadow);
        }
        
        .btn {
            border: 1px solid var(--accent);
            background: var(--accent);
            color: var(--text);
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 600;
            transition: all .2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn:hover {
            background: var(--hover-accent);
            border-color: var(--hover-accent);
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
        
        .refund-form {
            margin: 0;
        }
        
        .order-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
            margin-top: 20px;
            box-shadow: 0 1px 3px var(--shadow);
            transition: all .2s ease;
        }
        .order-card:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(64,64,70,0.2);
        }
        
        .table-wrap {
            overflow: auto;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--card);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }
        thead th {
            font-size: 13px;
            color: var(--text);
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0.5px;
            background: var(--gray-50);
            border-bottom: 2px solid var(--accent);
            padding: 16px 12px;
            text-align: left;
        }
        tbody td {
            padding: 16px 12px;
            border-bottom: 1px solid var(--line);
            font-size: 14px;
            color: var(--text);
            font-weight: 500;
        }
        tbody tr:hover {
            background: var(--gray-50);
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        
        .order-summary {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
            margin-top: 20px;
            box-shadow: 0 2px 8px var(--shadow);
        }
        .summary-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--accent);
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid var(--line);
        }
        .summary-item:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 18px;
            color: var(--success);
            padding-top: 20px;
            border-top: 2px solid var(--success);
        }
        .summary-label {
            font-size: 14px;
            color: var(--muted);
            font-weight: 500;
            text-transform: none;
            letter-spacing: 0.2px;
        }
        .summary-value {
            font-weight: 600;
            color: var(--text);
            font-size: 15px;
        }
        
        .campaign-item {
            background: linear-gradient(135deg, rgba(0,230,184,0.05) 0%, rgba(0,230,184,0.02) 100%);
            border-radius: 8px;
            padding: 12px 16px;
            margin: 4px 0;
            border: 1px solid rgba(0,230,184,0.2);
        }
        .campaign-item .summary-label {
            color: var(--success);
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        .campaign-value {
            color: var(--success);
            font-weight: 700;
        }
        .discount-item {
            background: linear-gradient(135deg, rgba(255,85,85,0.05) 0%, rgba(255,85,85,0.02) 100%);
            border-radius: 8px;
            padding: 12px 16px;
            margin: 4px 0;
            border: 1px solid rgba(255,85,85,0.2);
        }
        .discount-value {
            color: var(--danger);
            font-weight: 700;
        }
        
        .refund-actions {
            display: none;
            gap: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--line);
        }
        .order-card.select-mode .refund-actions {
            display: flex;
        }
        .refund-col {
            text-align: center;
        }
        .order-card:not(.select-mode) .refund-checkbox {
            display: none;
        }
        .order-card:not(.select-mode) .select-all {
            display: none;
        }
        .qty-box {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--card);
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--line);
        }
        .qty-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--line);
            background: var(--card);
            color: var(--text);
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .2s ease;
            font-size: 18px;
            font-weight: 600;
        }
        .qty-btn:hover {
            background: var(--accent);
            border-color: var(--accent);
        }
        .refund-qty {
            width: 70px;
            padding: 10px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: var(--bg);
            color: var(--text);
            text-align: center;
            font-size: 15px;
            font-weight: 600;
        }
        .order-card:not(.select-mode) .qty-box {
            display: none;
        }
        .refund-info {
            font-size: 13px;
            color: var(--muted);
            margin-top: 8px;
            font-weight: 500;
        }
        
        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--line);
        }
        
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
            background: rgba(0,230,184,0.1);
            border-color: var(--success);
        }
        .notice.error {
            color: var(--danger);
            background: rgba(255,85,85,0.1);
            border-color: var(--danger);
        }
        
        .empty-state {
            text-align: center;
            padding: 64px 32px;
            background: var(--card);
            border-radius: 20px;
            border: 2px dashed var(--line);
            margin: 32px 0;
        }
        .empty-state svg {
            margin-bottom: 16px;
            opacity: 0.5;
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
        
        @media (max-width: 768px) {
            .shell {
                padding: 24px 16px 60px;
            }
            .page-header {
                margin: -24px -16px 24px;
                padding: 32px 0;
            }
            .toolbar {
                padding: 16px 20px;
                flex-direction: column;
                align-items: stretch;
            }
            .nav-section {
                justify-content: center;
            }
            .summary-grid {
                grid-template-columns: 1fr;
            }
            .qty-box {
                flex-direction: column;
                gap: 4px;
            }
        }
    </style>
</head>
<body>
<div class="page-header">
    <div class="header-content">
        <div>
            <h1>Siparişlerim</h1>
            <div class="header-subtitle">Tüm siparişleriniz</div>
        </div>
    </div>
</div>

<div class="shell">
    <div class="toolbar">
        <div class="nav-section">
            <a href="/main" class="btn outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>
                </svg>
                Ana Sayfaya Dön
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
    
    @if(isset($success))
        <div class="notice success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
            </svg>
            {{ $success }}
        </div>
    @endif
    
    @if(isset($error))
        <div class="notice error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            {{ $error }}
        </div>
    @endif

    @if(empty($orders))
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 3h18v18H3zM8 7h8M8 11h8M8 15h5"/>
            </svg>
            <h3>Henüz Siparişiniz Yok</h3>
            <p>İlk siparişinizi vermek için alışverişe başlayın.</p>
            <a href="/main" class="btn" style="margin-top:16px">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>
                </svg>
                Alışverişe Başla
            </a>
        </div>
        @else
            @foreach($orders as $order)

                <div class="order-card">
                    <form action="{{ route('myorders.refundItems', $order->id) }}" method="POST" class="refund-form">
                        @csrf
                        <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ürün Adı</th>
                                    <th>Mağaza</th>
                                    <th>Kategori</th>
                                    <th>Yazar</th>
                                    <th>Adet</th>
                                    <th>Birim Fiyat</th>
                                    <th>Toplam</th>
                                    <th>İade Adedi</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->product_title }}</td>
                                        <td>{{ $item->product->store->name }}</td>
                                        <td>{{ $item->product_category_title }}</td>
                                        <td>{{ $item->product->author }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->list_price, 2) }} TL</td>
                                        <td>{{ number_format($item->list_price * $item->quantity, 2) }} TL</td>
                                        <td class="refund-col">
                                        @php
                                            // Orijinal sipariş adedi
                                            $originalQuantity = (int)$item->quantity;
                                            
                                            // Ödenen toplam fiyat
                                            $paidPrice = round(($item->paid_price ?? 0), 2);
                                            
                                            // Daha önce iade edilen fiyat
                                            $refundedPrice = round(($item->refunded_price ?? 0), 2);
                                            
                                            // Kalan iade edilebilir fiyat
                                            $remainingRefundedPrice = max(0, round($paidPrice - $refundedPrice, 2));
                                            
                                            // Birim fiyat hesapla
                                            $unitPrice = $paidPrice / $originalQuantity;
                                            
                                            // Kalan iade edilebilir adet
                                            $remainingUnits = $unitPrice > 0 ? (int) round($remainingRefundedPrice / $unitPrice) : 0;
                                            
                                            // İade edilebilir mi kontrol et
                                            $eligible = ($item->payment_status !== 'refunded') && $remainingUnits > 0 && $remainingRefundedPrice > 0;
                                            
                                        
                                            
                                            
                                        @endphp

                                            @if($eligible)
                                                <div class="qty-box">
                                                    <button type="button" class="qty-btn dec">-</button>
                                                    <label for="refund-qty-{{ $item->id }}" style="display:none;">İade Adedi</label>
                                                    <input type="number" id="refund-qty-{{ $item->id }}" class="refund-qty" name="refund_quantities[{{ $item->id }}]" value="0" min="0" max="{{ $remainingUnits }}" data-max="{{ $remainingUnits }}" title="İade edilebilir adet: {{ $remainingUnits }}" autocomplete="off">
                                                    <button type="button" class="qty-btn inc">+</button>
                                                </div>
                                                <div class="refund-info">İade edilebilir: {{ $remainingUnits }} adet</div>
                                            @else
                                                @if($item->payment_status->value === 'refunded')
                                                    <span class="muted">Tamamen iade edildi</span>
                                                @elseif($refundedPrice > 0 && $remainingUnits == 0)
                                                    <span class="muted">Tamamen iade edildi ({{ $originalQuantity }}/{{ $originalQuantity }})</span>
                                                @elseif($refundedPrice > 0 && $remainingUnits > 0)
                                                    <span class="muted">Kısmi iade edildi ({{ $originalQuantity - $remainingUnits }}/{{ $originalQuantity }})</span>
                                                @else
                                                    <span class="muted">İade edilemez</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $item->payment_status->label() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        @php
                            // Bu siparişte iade edilebilir ürün var mı kontrol et
                            $hasRefundableItems = false;
                            foreach($order->orderItems as $item) {
                                $originalQuantity = (int)$item->quantity;
                                $paidPrice = round(($item->paid_price ?? 0), 2);
                                $refundedPrice = round(($item->refunded_price ?? 0), 2);
                                $remainingRefundedPrice = max(0, round($paidPrice - $refundedPrice, 2));
                                $unitPrice = $paidPrice / $originalQuantity;
                                $remainingUnits = $unitPrice > 0 ? (int) round($remainingRefundedPrice / $unitPrice) : 0;
                                
                                // İade edilebilir kontrolü: payment_status 'refunded' değilse VE kalan adet varsa
                                if (($item->payment_status !== 'refunded') && $remainingUnits > 0) {
                                    $hasRefundableItems = true;
                                    break;
                                }
                            }
                        @endphp
                        @if($hasRefundableItems)
                        <div class="refund-actions">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                                <label for="select-all-{{ $order->id }}" style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;font-weight:500;color:var(--text);">
                                    <input type="checkbox" id="select-all-{{ $order->id }}" class="select-all" title="Tümünü Doldur" autocomplete="off" style="width:16px;height:16px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 11l3 3L22 4"/>
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                                    </svg>
                                    Tümünü Seç
                                </label>
                            </div>
                            <div style="display:flex;gap:12px;">
                                <button type="submit" class="btn success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                        <path d="M21 3v5h-5"/>
                                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                        <path d="M3 21v-5h5"/>
                                    </svg>
                                    Seçilen Ürünleri İade Et
                                </button>
                                <button type="button" class="btn outline cancel-select">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="m15 9-6 6"/>
                                        <path d="m9 9 6 6"/>
                                    </svg>
                                    Vazgeç
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="refund-actions">
                            <button type="button" class="btn outline cancel-select">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="m15 9-6 6"/>
                                    <path d="m9 9 6 6"/>
                                </svg>
                                Vazgeç
                            </button>
                        </div>
                        @endif
                    </form>

                    <div class="order-summary">
                        <div class="summary-title">Sipariş Bilgileri</div>
                        <div class="summary-grid">
                            <div class="summary-item">
                                <span class="summary-label">Sipariş No</span>
                                <span class="summary-value">#{{ $order->id }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Sipariş Tarihi</span>
                                <span class="summary-value">{{ $order->created_at }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Durum</span>
                                <span class="summary-value">{{ $order->status ? 'Onaylandı' : 'Onaylanmadı' }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Kargo</span>
                                <span class="summary-value">{{ $order->cargo_price == 0 ? 'Ücretsiz' : number_format($order->cargo_price,2).' TL' }}</span>
                            </div>
                            @if($order->campaign_info && $order->discount > 0)
                                <div class="summary-item campaign-item">
                                    <span class="summary-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;color:var(--success);">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        Kampanya
                                    </span>
                                    <span class="summary-value campaign-value">{{ $order->campaign_info }}</span>
                                </div>
                                <div class="summary-item discount-item">
                                    <span class="summary-label">İndirim</span>
                                    <span class="summary-value discount-value">-{{ number_format($order->discount,2) }} TL</span>
                                </div>
                            @else
                                <div class="summary-item">
                                    <span class="summary-label">Kampanya</span>
                                    <span class="summary-value">Yok</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">İndirim</span>
                                    <span class="summary-value">0.00 TL</span>
                                </div>
                            @endif
                            <div class="summary-item">
                                <span class="summary-label">Toplam Fiyat</span>
                                <span class="summary-value">{{ number_format($order->order_price,2) }} TL</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Ödenecek Tutar</span>
                                <span class="summary-value">{{ number_format(floor($order->paid_price * 100) / 100 ,2) }} TL</span>
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        @if($hasRefundableItems)
                            <button type="button" class="btn outline toggle-refund">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                    <path d="M21 3v5h-5"/>
                                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                    <path d="M3 21v-5h5"/>
                                </svg>
                                Ürün İade Et
                            </button>
                        @else
                            @php
                                // Tüm ürünlerin durumunu kontrol et
                                $allRefunded = true;
                                $partiallyRefunded = false;
                                $hasAnyRefund = false;
                                
                                foreach($order->orderItems as $item) {
                                    $originalQuantity = (int)$item->quantity;
                                    $paidPrice = round(($item->paid_price ?? 0), 2);
                                    $refundedPrice = round(($item->refunded_price ?? 0), 2);
                                    $remainingRefundedPrice = max(0, round($paidPrice - $refundedPrice, 2));
                                    $unitPrice = $paidPrice / $originalQuantity;
                                    $remainingUnits = $unitPrice > 0 ? (int) round($remainingRefundedPrice / $unitPrice) : 0;
                                    
                                    // Herhangi bir iade var mı kontrol et
                                    if($refundedPrice > 0) {
                                        $hasAnyRefund = true;
                                    }
                                    
                                    // Tamamen iade edilmiş mi kontrol et
                                    if($item->payment_status !== 'refunded') {
                                        $allRefunded = false;
                                    }
                                    
                                    // Kısmi iade var mı kontrol et (refunded_price > 0 ama hala iade edilebilir adet varsa)
                                    if($refundedPrice > 0 && $remainingUnits > 0) {
                                        $partiallyRefunded = true;
                                    }
                                }
                            @endphp
                            
                            @if($allRefunded)
                                <div style="display:flex;align-items:center;gap:8px;color:var(--danger);font-weight:600;font-size:14px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="m15 9-6 6"/>
                                        <path d="m9 9 6 6"/>
                                    </svg>
                                    Tüm ürünler iade edildi
                                </div>
                                @if($order->refunded_at)
                                    <div style="color:var(--muted);font-size:13px;font-weight:500;">İade Tarihi: {{ $order->refunded_at }}</div>
                                @endif
                            @elseif($partiallyRefunded)
                                <div style="display:flex;align-items:center;gap:8px;color:var(--warn);font-weight:600;font-size:14px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    Kısmi iade yapıldı - Kalan ürünler iade edilebilir
                                </div>
                            @elseif($hasAnyRefund)
                                <div style="display:flex;align-items:center;gap:8px;color:var(--warn);font-weight:600;font-size:14px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    İade işlemi yapıldı
                                </div>
                            @else
                                <div style="display:flex;align-items:center;gap:8px;color:var(--muted);font-weight:600;font-size:14px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3h18v18H3zM8 7h8M8 11h8M8 15h5"/>
                                    </svg>
                                    İade edilebilir ürün yok
                                </div>
                            @endif
                        @endif
                    </div>

                </div>
            @endforeach
        @endif
    </div>
<script src="{{ asset('js/myorders.js') }}"></script>
    </body>
    </html>