<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Seller Siparişleri</title>
    <style>
        :root{
            --bg:#f8fafc;
            --text:#1f2937;
            --muted:#6b7280;
            --line:#e5e7eb;
            --accent:#3B82F6;
            --success:#10b981;
            --warn:#f59e0b;
            --danger:#ef4444;
            --header:#ffffff;
            --card:#ffffff;
            --table-bg:#ffffff;
            --table-text:#374151;
            --table-header:#f9fafb;
            --table-border:#e5e7eb;
        }
        *{box-sizing:border-box;}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text);}    
        body{
            font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Inter,"Helvetica Neue",Arial,sans-serif;
            letter-spacing:.2px; line-height:1.4;
        }
        .shell{max-width:1200px;margin:0 auto;padding:20px 16px 40px;}
        h1{font-size:24px;font-weight:600;margin:0 0 20px;text-align:center;color:var(--text);}
        h3,h4{font-weight:600;}
        h3{font-size:18px;margin:20px 0 12px;}
        .notice{padding:10px 12px;border:1px solid var(--line);margin:8px 0;border-radius:4px;}
        .notice.success{color:var(--success);background:#f0f9f4;border-color:var(--success);} 
        .notice.error{color:var(--danger);background:#fef2f2;border-color:var(--danger);font-weight:500;}
        .empty{text-align:center;color:var(--muted);padding:24px 0;}
        .order-card{border:1px solid var(--table-border);border-radius:12px;padding:20px;margin-top:20px;background:var(--table-bg);color:var(--table-text);box-shadow:0 1px 3px rgba(0,0,0,0.1);}
        .table-wrap{overflow:auto;border:1px solid var(--table-border);border-radius:8px;}
        table{width:100%;border-collapse:collapse;min-width:800px;color:var(--table-text);}
        thead th{
            font-size:12px;color:var(--table-text);font-weight:600;
            background:var(--table-header);border-bottom:1px solid var(--table-border);padding:12px 8px;text-align:left;
        }
        tbody td{padding:12px 8px;border-bottom:1px solid var(--table-border);font-size:14px;color:var(--table-text);}
        tbody tr:hover{background:#f9fafb;}
        .totals{margin-top:14px;border-top:1px solid var(--table-border);padding-top:14px;display:grid;gap:6px;color:var(--table-text);}
        .totals .row{display:flex;justify-content:space-between;gap:10px;}
        .totals strong{font-weight:600;color:var(--table-text);}
        .muted{color:#6b7280;}
        .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:13px;font-weight:500;transition:all .2s ease;display:inline-block;text-decoration:none;border:none;min-width:120px;}
        .btn.outline{background:transparent;color:var(--accent);border:1px solid var(--accent);} 
        .btn:hover{opacity:0.9;transform:translateY(-1px);} 
        .actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;}
        .status-badge{padding:4px 8px;border-radius:6px;font-size:11px;font-weight:500;}
        .status-confirmed{background:#fef3c7;color:#92400e;}
        .status-completed{background:#d1fae5;color:#065f46;}
        .status-canceled{background:#fee2e2;color:#991b1b;}
    </style>
</head>
<body>
    <div class="shell">
        <h1>Satıcı Siparişleri</h1>

        @if(session('success')) <div class="notice success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="notice error">{{ session('error') }}</div> @endif

        <div class="actions" style="justify-content:center;margin-bottom:8px;">
            <a href="/seller" class="btn outline">Seller Paneline Dön</a>
        </div>

        @if(empty($orderItems) || $orderItems->count() == 0)
            <div class="empty"><strong>Henüz sipariş yok</strong></div>
        @else
            @php
                $groupedOrders = $orderItems->groupBy('order_id');
            @endphp
            
            @foreach($groupedOrders as $orderId => $items)
                @php
                    $firstItem = $items->first();
                    $order = $firstItem->order;
                @endphp
                
                <div class="order-card">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sipariş item ID</th>
                                    <th>Ürün</th>
                                    <th>Yazar</th>
                                    <th>Kategori</th>
                                    <th>Ürün Sayısı</th>
                                    <th>Fiyat</th>
                                    <th>Toplam Fiyat</th>
                                    <th>Ödenen</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->product?->title ?? 'Ürün bilgisi yok' }}</td>
                                        <td>{{ $item->product?->author ?? 'Yazar bilgisi yok' }}</td>
                                        <td>{{ $item->product_category_title }}</td>
                                        <td>{{ $item->quantity }}</td>
                                                                    <td>{{ number_format($item->product?->list_price ?? 0, 2) }} TL</td>
                            <td>{{ number_format(($item->product?->list_price ?? 0) * $item->quantity, 2) }} TL</td>
                                        <td>{{ number_format($item->paid_price, 2) }} TL</td>
                                        <td>
                                            @if($item->payment_status === 'refunded')
                                                <span class="status-badge status-canceled">İade Edildi</span>
                                            @elseif($item->payment_status === 'paid' && $item->status === 'confirmed')
                                                <span class="status-badge status-confirmed">Sipariş Onaylandı</span>
                                            @elseif($item->status === 'shipped')
                                                <span class="status-badge status-completed">Gönderildi</span>
                                            @elseif($item->status === 'canceled')
                                                <span class="status-badge status-canceled">Satıcı İptal Etti</span>
                                            @else
                                                <span class="status-badge">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->payment_status->value === 'paid' && $item->status === 'confirmed')
                                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                                    <form action="{{ route('seller.confirmOrderItem', $item->id) }}" method="POST" style="margin: 0;">
                                                        @csrf
                                                        <button type="submit" style="background: #10b981; color: white; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 500; width: 100%;">Hazırla & Gönder</button>
                                                    </form>
                                                    <form action="{{ route('seller.refundOrderItem', $item->id) }}" method="POST" style="margin: 0;">
                                                        @csrf
                                                        <button type="submit" style="background: #dc2626; color: white; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 500; width: 100%;">Stok Yok - İade Et</button>
                                                    </form>
                                                </div>
                                            @elseif($item->payment_status->value === 'refunded')
                                                <span class="muted">İade Tarihi: {{ \Carbon\Carbon::parse($item->refunded_at)->format('d.m.Y H:i') ?? 'N/A' }}</span>
                                            @elseif($item->status === 'shipped')
                                                <span class="muted">Gönderildi</span>
                                            @elseif($item->status === 'canceled')
                                                <span class="muted">İptal Edildi</span>
                                            @else
                                                <span class="muted">Durum: {{ $item->status }} - {{ $item->payment_status->value }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="totals">
                        <div class="row"><strong>Müşteri</strong><span>{{ $order?->user?->username ?? 'N/A' }}</span></div>
                        <div class="row"><strong>Email</strong><span>{{ $order?->user?->email ?? 'N/A' }}</span></div>
                        <div class="row"><strong>Sipariş Tarihi</strong><span>{{ $order?->created_at?->format('d.m.Y H:i') ?? 'N/A' }}</span></div>
                        @if($order?->campaign_info)
                            <div class="row"><strong>Kampanya</strong><span>{{ $order->campaign_info }}</span></div>
                        @endif
                        <div class="row"><strong>Kargo</strong>
                            <span>{{ ($order?->cargo_price ?? 0) == 0 ? 'Kargo Ücretsiz' : number_format($order->cargo_price ?? 0, 2).' TL' }}</span>
                        </div>
                    </div>

                    
                </div>
            @endforeach
        @endif
    </div>
</body>
</html>