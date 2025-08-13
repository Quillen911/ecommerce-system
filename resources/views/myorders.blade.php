<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Siparişlerim</title>
    <style>
        :root{
            --bg:#fff;
            --text:#111;
            --muted:#666;
            --line:#e6e6e6;
            --accent:#000;
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
        h1{font-size:22px;font-weight:600;text-transform:uppercase;letter-spacing:4px;margin:20px 0 10px;text-align:center;}
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
        .refund-actions{display:none;gap:12px;margin-top:12px;}
        .order-card.select-mode .refund-actions{display:flex;}
        .refund-col{text-align:center;}
        .order-card:not(.select-mode) .refund-checkbox{display:none;}
        .order-card:not(.select-mode) .select-all{display:none;}
        .qty-box{display:inline-flex;align-items:center;gap:6px;}
        .qty-btn{width:28px;height:28px;border:1px solid var(--line);background:#fafafa;border-radius:6px;cursor:pointer;}
        .refund-qty{width:64px;padding:6px;border:1px solid var(--line);border-radius:6px;}
        .order-card:not(.select-mode) .qty-box{display:none;}
    </style>
    </head>
    <body>
    <div class="shell">
        <h1>Siparişlerim</h1>

        @if(session('success')) <div class="notice success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="notice error">{{ session('error') }}</div> @endif
        @if(isset($success)) <div class="notice success">{{ $success }}</div> @endif
        @if(isset($error)) <div class="notice error">{{ $error }}</div> @endif

        <div class="actions" style="justify-content:center;margin-bottom:8px;">
            <a href="/main" class="btn outline">Ana Sayfaya Dön</a>
        </div>

        @if(empty($orders))
            <div class="empty"><strong>Siparişiniz yok</strong></div>
        @else
            @foreach($orders as $order)
                <div class="order-card">
                    <form action="{{ route('myorders.refundItems', $order->id) }}" method="POST" class="refund-form" style="margin:0;">
                        @csrf
                        <div class="table-wrap">
                        @php /* Satır bazlı hesap kullanılacak, global oran yok */ @endphp
                        <table>
                            <thead>
                                <tr>
                                    <th>Sipariş İtem ID</th>
                                    <th>Ürün</th>
                                    <th>Mağaza</th>
                                    <th>Kategori Adı</th>
                                    <th>Yazar</th>
                                    <th>Ürün Sayısı</th>
                                    <th>Fiyat</th>
                                    <th>Toplam Fiyat</th>
                                    <th>İade Adedi <label for="select-all-{{ $order->id }}"><input type="checkbox" id="select-all-{{ $order->id }}" class="select-all" title="Tümünü Doldur" autocomplete="off"> Tümünü Seç</label></th>
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
                                            $paidPrice = round(($item->paid_price ?? 0), 2);
                                            $refundedPrice = round(($item->refunded_price ?? 0), 2);
                                            $remainingRefundedPrice = max(0, round($paidPrice - $refundedPrice, 2));
                                            // Birim fiyatı yuvarlamadan hesapla; kalan adet hesabında hassasiyet önemli
                                            $unitPaidPrice = $paidPrice / (int)$item->quantity;
                                            $remainingUnits = $unitPaidPrice > 0 ? (int) floor($remainingRefundedPrice / $unitPaidPrice) : 0;
                                            $eligible = ($order->status !== 'pending')
                                                        && !in_array($item->payment_status, ['refunded','canceled'])
                                                        && $remainingUnits > 0;
                                        @endphp
                                            @if($eligible)
                                                <div class="qty-box">
                                                    <button type="button" class="qty-btn dec">-</button>
                                                    <label for="refund-qty-{{ $item->id }}" style="display:none;">İade Adedi</label>
                                                    <input type="number" id="refund-qty-{{ $item->id }}" class="refund-qty" name="refund_quantities[{{ $item->id }}]" value="0" min="0" max="{{ $remainingUnits }}" data-max="{{ $remainingUnits }}" title="İade edilebilir adet: {{ $remainingUnits }}" autocomplete="off">
                                                    <button type="button" class="qty-btn inc">+</button>
                                                </div>
                                                <div class="muted" style="font-size:12px; margin-top:6px;">İade edilebilir: {{ $remainingUnits }} adet</div>
                                            @else
                                                <span class="muted">{{ $item->payment_status === 'refunded' ?'İade edildi' : '' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        @if($order->status !== "pending")
                        <div class="refund-actions">
                            <button type="submit" class="btn">Seçilen Ürünleri İade Et</button>
                            <button type="button" class="btn outline cancel-select">Vazgeç</button>
                        </div>
                        @else
                        <div class="refund-actions">
                            <button type="button" class="btn outline cancel-select">Vazgeç</button>
                        </div>
                        @endif
                    </form>

                    <div class="totals">
                        <div class="row"><strong>Sipariş No</strong><span>#{{ $order->id }}</span></div>
                        <div class="row"><strong>Sipariş Tarihi</strong><span>{{ $order->created_at }}</span></div>
                        <div class="row"><strong>Durum</strong><span>{{ $order->status }}</span></div>
                        <div class="row"><strong>Kargo</strong>
                            <span>{{ $order->cargo_price == 0 ? 'Kargo Ücretsiz' : number_format($order->cargo_price,2).' TL' }}</span>
                        </div>
                        <div class="row"><strong>Kampanya</strong><span>{{ $order->campaign_info ?? 'Kampanya Yok' }}</span></div>
                        <div class="row"><strong>İndirim</strong><span>{{ number_format($order->discount,2) }} TL</span></div>
                        <div class="row"><strong>Toplam Fiyat</strong><span>{{ number_format($order->order_price,2) }} TL</span></div>
                        <div class="row"><strong>İndirimli Fiyat</strong><span>{{ number_format($order->campaing_price,2) }} TL</span></div>
                    </div>

                    @php
                        $canCancel = $order->status === 'pending' && !in_array($order->payment_status, ['canceled','refunded']);
                        $canRefund = $order->status !== 'pending' && !in_array($order->payment_status, ['canceled','refunded']);
                    @endphp
                    <div class="actions">
                        @if($canCancel)
                            <form action="{{ route('myorders.delete', $order->id) }}" method="POST" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn outline">Siparişi İptal Et</button>
                            </form>
                        @elseif($canRefund)
                            <button type="button" class="btn outline toggle-refund">Ürün İade Et</button>
                        @else
                            <em class="muted" style="color:red;">Bu sipariş {{ $order->payment_status === 'canceled' ? 'iptal edildi' : ($order->payment_status === 'refunded' ? 'iade edildi' : 'kısmi iade edildi') }}.</em>
                            @if($order->payment_status === 'refunded' || $order->payment_status === 'partial_refunded')
                                <em class="muted">İade Tarihi: {{ $order->refunded_at }}</em>
                            @else
                                <em class="muted">İptal Tarihi: {{ $order->canceled_at }}</em>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.order-card').forEach(function(card){
                const toggleBtn = card.querySelector('.toggle-refund');
                const cancelBtn = card.querySelector('.cancel-select');
                const selectAll = card.querySelector('.select-all');
                const refundForm = card.querySelector('.refund-form');
                const qtyInputs = card.querySelectorAll('.refund-qty');
                if(toggleBtn){
                    toggleBtn.addEventListener('click', function(){
                        card.classList.add('select-mode');
                    });
                }
                if(cancelBtn){
                    cancelBtn.addEventListener('click', function(){
                        card.classList.remove('select-mode');
                        qtyInputs.forEach(inp => inp.value = 0);
                        if(selectAll){ selectAll.checked = false; }
                    });
                }
                if(selectAll){
                    selectAll.addEventListener('change', function(){
                        const fillMax = !!this.checked;
                        qtyInputs.forEach(function(inp){
                            inp.value = fillMax ? (inp.dataset.max || inp.max || 0) : 0;
                        });
                    });
                }
                // plus/minus buttons
                card.querySelectorAll('.qty-box').forEach(function(box){
                    const dec = box.querySelector('.qty-btn.dec');
                    const inc = box.querySelector('.qty-btn.inc');
                    const inp = box.querySelector('.refund-qty');
                    const getMax = () => parseInt(inp.dataset.max || inp.max || '0', 10) || 0;
                    const clamp = v => Math.max(0, Math.min(getMax(), v|0));
                    if(dec){ dec.addEventListener('click', ()=>{ inp.value = clamp((parseInt(inp.value||'0',10)||0) - 1); }); }
                    if(inc){ inc.addEventListener('click', ()=>{ inp.value = clamp((parseInt(inp.value||'0',10)||0) + 1); }); }
                });
                if(refundForm){
                    refundForm.addEventListener('submit', function(e){
                        const anyPositive = Array.from(qtyInputs).some(inp => (parseInt(inp.value||'0',10)||0) > 0);
                        if(!anyPositive){
                            e.preventDefault();
                            alert('Lütfen iade adedi giriniz.');
                        }
                    });
                }
            });
        });
    </script>
    </body>
    </html>