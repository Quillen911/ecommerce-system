<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Seller Siparişleri</title>
    <style>
        :root{
            --bg:#fff;
            --text:#111;
            --muted:#666;
            --line:#e6e6e6;
            --accent:#7c3aed;
            --success:#0f8a44;
            --warn:#ff9800;
            --danger:#c62828;
        }
        *{box-sizing:border-box;}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text);}    
        body{
            font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Inter,"Helvetica Neue",Arial,sans-serif;
            letter-spacing:.2px; line-height:1.4;
        }
        .shell{max-width:1060px;margin:0 auto;padding:24px 16px 80px;}
        h1{font-size:22px;font-weight:600;text-transform:uppercase;letter-spacing:4px;margin:20px 0 10px;text-align:center;color:var(--accent);}
        h3,h4{font-weight:600;text-transform:uppercase;}
        h3{font-size:16px;letter-spacing:2px;margin:30px 0 12px;}
        .notice{padding:10px 12px;border:1px solid var(--line);margin:8px 0;border-radius:4px;}
        .notice.success{color:var(--success);background:#f0f9f4;border-color:var(--success);} 
        .notice.error{color:var(--danger);background:#fef2f2;border-color:var(--danger);font-weight:500;}
        .empty{text-align:center;color:var(--muted);padding:24px 0;}
        .order-card{border:1px solid var(--line);border-radius:8px;padding:16px;margin-top:16px;background:#fff;}
        .table-wrap{overflow:auto;border:1px solid var(--line);border-radius:6px;}
        table{width:100%;border-collapse:collapse;min-width:720px;}
        thead th{
            font-size:11px;color:#222;font-weight:600;text-transform:uppercase;letter-spacing:1.4px;
            background:#fafafa;border-bottom:1px solid var(--line);padding:12px 10px;text-align:left;
        }
        tbody td{padding:14px 10px;border-bottom:1px solid var(--line);font-size:14px;}
        tbody tr:hover{background:#fcfcfc;}
        .totals{margin-top:14px;border-top:1px solid var(--line);padding-top:14px;display:grid;gap:6px;}
        .totals .row{display:flex;justify-content:space-between;gap:10px;}
        .totals strong{font-weight:600;}
        .muted{color:var(--muted);}
        .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:10px 16px;border-radius:28px;cursor:pointer;text-transform:uppercase;letter-spacing:1.2px;font-size:12px;transition:filter .15s ease;display:inline-block;text-decoration:none;}
        .btn.outline{background:transparent;color:var(--accent);} 
        .btn:hover{filter:brightness(0.9);} 
        .actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:12px;}
        .status-badge{padding:4px 8px;border-radius:12px;font-size:11px;font-weight:600;text-transform:uppercase;}
        .status-pending{background:#fff3cd;color:#856404;}
        .status-completed{background:#d4edda;color:#155724;}
        .status-canceled{background:#f8d7da;color:#721c24;}
    </style>
</head>
<body>
    <div class="shell">
        <h1>Seller Siparişleri</h1>

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
                                    <th>Sipariş #{{ $orderId }}</th>
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
                                        <td>{{ $loop->first ? $order->created_at->format('d.m.Y H:i') : '' }}</td>
                                        <td>{{ $item->product->title }}</td>
                                        <td>{{ $item->product->author }}</td>
                                        <td>{{ $item->product_category_title }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->product->list_price, 2) }} TL</td>
                                        <td>{{ number_format($item->product->list_price * $item->quantity, 2) }} TL</td>
                                        <td>{{ number_format($item->paid_price, 2) }} TL</td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            @if($item->status === 'pending')
                                                <form action="{{ route('seller.confirmOrderItem', $item->order_id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn">Siparişi Onayla</button>
                                                </form>
                                                <form action="{{ route('seller.cancelOrderItem', $item->order_id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn">Siparişi İptal Et</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="totals">
                        <div class="row"><strong>Müşteri</strong><span>{{ $order->user->username ?? 'N/A' }}</span></div>
                        <div class="row"><strong>Email</strong><span>{{ $order->user->email ?? 'N/A' }}</span></div>
                        <div class="row"><strong>Sipariş Tarihi</strong><span>{{ $order->created_at->format('d.m.Y H:i') }}</span></div>
                        @if($order->campaign_info)
                            <div class="row"><strong>Kampanya</strong><span>{{ $order->campaign_info }}</span></div>
                        @endif
                        <div class="row"><strong>Kargo</strong>
                            <span>{{ $order->cargo_price == 0 ? 'Kargo Ücretsiz' : number_format($order->cargo_price,2).' TL' }}</span>
                        </div>
                    </div>

                    
                </div>
            @endforeach
        @endif
    </div>
</body>
</html>