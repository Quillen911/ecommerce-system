<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Siparişlerim</title>
    <style>
        :root{
            --bg:#0A0A0A; --text:#FFF; --muted:#CCCCCC; --line:#333; --accent:#404040;
            --card:#1F1F1F; --shadow:rgba(0,0,0,.3); --success:#00E6B8; --danger:#FF5555;
            --warn:#FFB84D; --price:#00E6B8;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-size:14px}
        body{font-family:"Inter",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;line-height:1.6}
        .shell{max-width:1200px;margin:0 auto;padding:24px 20px 80px}

        /* Header */
        .page-header{background:var(--card);border-bottom:1px solid var(--line);padding:20px 0;margin:-24px -20px 24px;box-shadow:0 4px 20px var(--shadow)}
        .header-content{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center}
        h1{font-size:24px;margin:0;font-weight:600}
        .header-subtitle{font-size:14px;color:var(--muted);font-weight:500}

        /* Toolbar + buttons */
        .toolbar{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin:0 0 20px;background:var(--card);padding:16px 20px;border-radius:8px;border:1px solid var(--line);box-shadow:0 1px 3px var(--shadow)}
        .nav-section{display:flex;gap:8px;align-items:center}
        .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:10px 16px;border-radius:8px;cursor:pointer;text-transform:uppercase;font-size:12px;font-weight:600;transition:all .2s;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
        .btn:hover{background:#505050;border-color:#505050;transform:translateY(-1px)}
        .btn.outline{background:transparent;color:#ddd;border-color:var(--accent)}
        .btn.outline:hover{background:var(--accent);color:#fff}
        .btn.success{background:var(--success);border-color:var(--success);color:#001a14}
        .btn.success:hover{background:#08d2aa}
        .btn.block{width:100%;justify-content:center}

        /* Order card */
        .order-card{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:24px;margin-top:20px;box-shadow:0 1px 3px var(--shadow);transition:.2s}
        .order-card:hover{border-color:var(--accent);box-shadow:0 4px 12px rgba(64,64,70,.2)}
        .order-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--line)}
        .order-id{font-weight:700}
        .order-status{padding:6px 10px;border-radius:999px;background:rgba(255,255,255,.06);border:1px solid var(--line);font-size:12px}

        /* Two-column layout inside each order */
        .order-layout{display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start}

        /* Table */
        .table-wrap{overflow:auto;border:1px solid var(--line);border-radius:8px;background:var(--card)}
        table{width:100%;border-collapse:collapse;min-width:760px}
        thead th{font-size:13px;color:#fff;font-weight:600;background:#2A2A2A;border-bottom:2px solid var(--accent);padding:14px 12px;text-align:left}
        tbody td{padding:14px 12px;border-bottom:1px solid var(--line);font-size:14px;font-weight:500;vertical-align:middle}
        tbody tr:hover{background:#2A2A2A}
        tbody tr:last-child td{border-bottom:none}

        /* Product cell with image */
        th.col-product{width:100%}
        td.col-product{min-width:300px}
        .prod{display:flex;gap:12px;align-items:center}
        .prod-thumb{width:64px;height:64px;border-radius:8px;overflow:hidden;border:1px solid var(--line);flex-shrink:0;background:#111}
        .prod-thumb img{width:100%;height:100%;object-fit:cover}
        .prod-info{min-width:0}
        .prod-title{font-weight:700}
        .prod-meta{font-size:12px;color:var(--muted);line-height:1.3}
        .price{color:var(--price);font-weight:700}

        /* Summary (right column) */
        .order-summary{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:24px;box-shadow:0 2px 8px var(--shadow)}
        .summary-title{font-size:16px;font-weight:600;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid var(--accent)}
        .summary-grid{display:grid;grid-template-columns:1fr;gap:10px}
        .summary-item{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--line)}
        .summary-item:last-child{border-bottom:none}
        .summary-label{font-size:14px;color:var(--muted);font-weight:500}
        .summary-value{font-weight:600}
        .campaign-item{background:linear-gradient(135deg, rgba(0,230,184,.05), rgba(0,230,184,.02));border:1px solid rgba(0,230,184,.25);border-radius:8px;padding:10px 12px}
        .campaign-value{color:var(--success);font-weight:700}
        .discount-item{background:linear-gradient(135deg, rgba(255,85,85,.05), rgba(255,85,85,.02));border:1px solid rgba(255,85,85,.25);border-radius:8px;padding:10px 12px}
        .discount-value{color:var(--danger);font-weight:700}

        /* Refund/selection UI */
        .refund-actions{display:none;gap:12px;margin-top:16px;padding-top:16px;border-top:1px solid var(--line)}
        .order-card.select-mode .refund-actions{display:flex}
        .refund-col{text-align:center}
        .qty-box{display:inline-flex;align-items:center;gap:8px;background:var(--card);padding:10px;border-radius:8px;border:1px solid var(--line)}
        .qty-btn{width:34px;height:34px;border:1px solid var(--line);background:var(--card);color:#fff;border-radius:6px;cursor:pointer;font-size:18px;font-weight:700;display:flex;align-items:center;justify-content:center}
        .qty-btn:hover{background:#505050;border-color:#505050}
        .refund-qty{width:72px;padding:10px;border:1px solid var(--line);border-radius:6px;background:var(--bg);color:#fff;text-align:center;font-size:15px;font-weight:700}
        .order-card:not(.select-mode) .qty-box{display:none}

        /* Notices & empty state */
        .notice{padding:12px 16px;border:1px solid var(--line);margin:0 0 20px;border-radius:8px;display:flex;align-items:center;gap:8px}
        .notice.success{color:var(--success);background:rgba(0,230,184,.1);border-color:var(--success)}
        .notice.error{color:var(--danger);background:rgba(255,85,85,.1);border-color:var(--danger)}
        .empty-state{text-align:center;padding:64px 32px;background:var(--card);border-radius:20px;border:2px dashed var(--line);margin:32px 0}

        /* Responsive */
        @media (max-width:768px){
            .shell{padding:24px 16px 60px}
            .page-header{margin:-24px -16px 24px}
            .order-layout{grid-template-columns:1fr}
            .prod-thumb{width:56px;height:56px}
        }
    </style>
</head>
<body>
<div class="page-header">
    <div class="header-content">
        <div>
            <h1>Siparişlerim</h1>
            <div class="header-subtitle">Tüm sipariş geçmişiniz</div>
        </div>
    </div>
</div>

<div class="shell">
    <div class="toolbar">
        <div class="nav-section">
            <a href="/main" class="btn outline">← Ana Sayfaya Dön</a>
        </div>
    </div>

    {{-- Global mesajlar --}}
    @if(session('success'))
        <div class="notice success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="notice error">✗ {{ session('error') }}</div>
    @endif
    @if(isset($success))
        <div class="notice success">✓ {{ $success }}</div>
    @endif
    @if(isset($error))
        <div class="notice error">✗ {{ $error }}</div>
    @endif

    {{-- Boş durum --}}
    @if(empty($orders))
        <div class="empty-state">
            <div style="font-size:48px;margin-bottom:12px;opacity:.6">🧾</div>
            <h3>Henüz Siparişiniz Yok</h3>
            <p>İlk siparişinizi vermek için alışverişe başlayın.</p>
            <a href="/main" class="btn" style="margin-top:16px">Alışverişe Başla</a>
        </div>
    @else
        @foreach($orders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div class="order-id">Sipariş #{{ $order->id }}</div>
                    <div class="order-status">
                        {{ $order->status ? 'Onaylandı' : 'Onaylanmadı' }} • {{ $order->created_at }}
                    </div>
                </div>

                <div class="order-layout">
                    {{-- SOL: Ürünler ve iade formu --}}
                    <div>
                        <form action="{{ route('myorders.refundItems', $order->id) }}" method="POST" class="refund-form">
                            @csrf
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                    <tr>
                                        <th class="col-product">Ürün</th>
                                        <th>Adet</th>
                                        <th>Birim</th>
                                        <th>Toplam</th>
                                        <th>İade Adedi</th>
                                        <th>Durum</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            {{-- Ürün hücresi (resim + bilgiler) --}}
                                            <td class="col-product">
                                                <div class="prod">
                                                    <div class="prod-thumb">
                                                        <img src="{{ $item->product?->first_image ?? '/images/no-image.png' }}"
                                                             alt="{{ $item->product_title }}"
                                                             onerror="this.src='/images/no-image.png'">
                                                    </div>
                                                    <div class="prod-info">
                                                        <div class="prod-title">{{ $item->product_title }}</div>
                                                        <div class="prod-meta">
                                                            {{ $item->product->author ?? 'Yazar yok' }} •
                                                            {{ $item->product->store->name ?? 'Mağaza yok' }} •
                                                            {{ $item->product_category_title ?? 'Kategori yok' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>{{ $item->quantity }}</td>
                                            <td class="price">{{ number_format($item->list_price,2) }} TL</td>
                                            <td class="price">{{ number_format($item->list_price * $item->quantity,2) }} TL</td>

                                            {{-- İade kontrolleri --}}
                                            <td class="refund-col">
                                                @php
                                                    $originalQuantity = (int) $item->quantity;
                                                    $paidPrice = round(($item->paid_price ?? 0),2);
                                                    $refundedPrice = round(($item->refunded_price ?? 0),2);
                                                    $remainingRefundedPrice = max(0, round($paidPrice - $refundedPrice,2));
                                                    $unitPrice = $originalQuantity > 0 ? $paidPrice / $originalQuantity : 0;
                                                    $remainingUnits = $unitPrice > 0 ? (int) round($remainingRefundedPrice / $unitPrice) : 0;
                                                    $eligible = ($item->payment_status !== 'refunded') && $remainingUnits > 0 && $remainingRefundedPrice > 0;
                                                @endphp

                                                @if($eligible)
                                                    <div class="qty-box">
                                                        <button type="button" class="qty-btn dec">-</button>
                                                        <label for="refund-qty-{{ $item->id }}" style="display:none;">İade Adedi</label>
                                                        <input type="number" id="refund-qty-{{ $item->id }}" class="refund-qty"
                                                               name="refund_quantities[{{ $item->id }}]" value="0" min="0"
                                                               max="{{ $remainingUnits }}" data-max="{{ $remainingUnits }}"
                                                               title="İade edilebilir adet: {{ $remainingUnits }}" autocomplete="off">
                                                        <button type="button" class="qty-btn inc">+</button>
                                                    </div>
                                                    <div class="refund-info" style="font-size:12px;color:var(--muted);margin-top:6px;">
                                                        İade edilebilir: {{ $remainingUnits }} adet
                                                    </div>
                                                @else
                                                    @if(($item->payment_status->value ?? $item->payment_status) === 'refunded')
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

                                            <td>{{ method_exists($item->payment_status,'label') ? $item->payment_status->label() : ($item->payment_status ?? '-') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- iade aksiyonları (select-mode iken görünür) --}}
                            @php
                                $hasRefundableItems = false;
                                foreach($order->orderItems as $it){
                                    $oq=(int)$it->quantity; $pp=round(($it->paid_price ?? 0),2); $rp=round(($it->refunded_price ?? 0),2);
                                    $rem=max(0,round($pp-$rp,2)); $up=$oq>0 ? $pp/$oq : 0; $ru=$up>0 ? (int)round($rem/$up) : 0;
                                    if(($it->payment_status!=='refunded') && $ru>0){ $hasRefundableItems=true; break; }
                                }
                            @endphp
                            @if($hasRefundableItems)
                                <div class="refund-actions">
                                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;">
                                        <input type="checkbox" class="select-all" style="width:16px;height:16px"> Tümünü Seç
                                    </label>
                                    <button type="submit" class="btn success">Seçilen Ürünleri İade Et</button>
                                    <button type="button" class="btn outline cancel-select">Vazgeç</button>
                                </div>
                            @else
                                <div class="refund-actions">
                                    <button type="button" class="btn outline cancel-select">Vazgeç</button>
                                </div>
                            @endif
                        </form>
                    </div>

                    {{-- SAĞ: Özeti blok + durum bilgileri --}}
                    <aside>
                        <div class="order-summary">
                            <div class="summary-title">Sipariş Özeti</div>
                            <div class="summary-grid">
                                <div class="summary-item"><span class="summary-label">Kargo</span><span class="summary-value">{{ $order->cargo_price == 0 ? 'Ücretsiz' : number_format($order->cargo_price,2).' TL' }}</span></div>

                                @if($order->campaign_info && $order->discount > 0)
                                    <div class="summary-item campaign-item">
                                        <span class="summary-label">Kampanya</span>
                                        <span class="summary-value campaign-value">{{ $order->campaign_info }}</span>
                                    </div>
                                    <div class="summary-item discount-item">
                                        <span class="summary-label">İndirim</span>
                                        <span class="summary-value discount-value">-{{ number_format($order->discount,2) }} TL</span>
                                    </div>
                                @else
                                    <div class="summary-item"><span class="summary-label">Kampanya</span><span class="summary-value">Yok</span></div>
                                    <div class="summary-item"><span class="summary-label">İndirim</span><span class="summary-value">0.00 TL</span></div>
                                @endif

                                <div class="summary-item"><span class="summary-label">Toplam Fiyat</span><span class="summary-value">{{ number_format($order->order_price,2) }} TL</span></div>
                                <div class="summary-item"><span class="summary-label">Ödenen Tutar</span><span class="summary-value">{{ number_format(($order->paid_price),2) }} TL</span></div>
                            </div>
                        </div>

                        <div class="actions" style="margin-top:16px">
                            @if($hasRefundableItems)
                                <button type="button" class="btn outline toggle-refund">Ürün İade Et</button>
                            @else
                                @php
                                    $allRefunded=true; $partiallyRefunded=false; $hasAnyRefund=false;
                                    foreach($order->orderItems as $it){
                                        $oq=(int)$it->quantity; $pp=round(($it->paid_price ?? 0),2); $rp=round(($it->refunded_price ?? 0),2);
                                        $rem=max(0,round($pp-$rp,2)); $up=$oq>0 ? $pp/$oq : 0; $ru=$up>0 ? (int)round($rem/$up) : 0;
                                        if($rp>0){$hasAnyRefund=true;}
                                        if(($it->payment_status ?? null)!=='refunded'){$allRefunded=false;}
                                        if($rp>0 && $ru>0){$partiallyRefunded=true;}
                                    }
                                @endphp
                                @if($allRefunded)
                                    <div style="color:var(--danger);font-weight:600">Tüm ürünler iade edildi</div>
                                    @if($order->refunded_at)
                                        <div style="color:var(--muted);font-size:13px">İade Tarihi: {{ $order->refunded_at }}</div>
                                    @endif
                                @elseif($partiallyRefunded)
                                    <div style="color:var(--warn);font-weight:600">Kısmi iade yapıldı - Kalan ürünler iade edilebilir</div>
                                @elseif($hasAnyRefund)
                                    <div style="color:var(--warn);font-weight:600">İade işlemi yapıldı</div>
                                @else
                                    <div style="color:var(--muted);font-weight:600">İade edilebilir ürün yok</div>
                                @endif
                            @endif
                        </div>
                    </aside>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script src="{{ asset('js/myorders.js') }}"></script>
</body>
</html>
