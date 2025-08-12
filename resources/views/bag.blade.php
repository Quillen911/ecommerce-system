<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sepetim</title>
    <style>
        :root{
            --bg:#fff; --text:#111; --muted:#666; --line:#e6e6e6;
            --accent:#000; --success:#0f8a44; --warn:#ff9800; --danger:#c62828;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text)}
        body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Inter,"Helvetica Neue",Arial,sans-serif;letter-spacing:.2px;line-height:1.4}
        .shell{max-width:1060px;margin:0 auto;padding:24px 16px 80px}
        h1{font-size:22px;font-weight:600;text-transform:uppercase;letter-spacing:4px;margin:20px 0 10px;text-align:center}
        .notice{padding:10px 12px;border:1px solid var(--line);margin:8px 0;border-radius:4px}
        .notice.success{color:var(--success);background:#f0f9f4;border-color:var(--success)}
        .notice.error{color:var(--danger);background:#fef2f2;border-color:var(--danger);font-weight:500}
        .table-wrap{overflow:auto;border:1px solid var(--line);border-radius:6px}
        table{width:100%;border-collapse:collapse;min-width:860px}
        thead th{
            font-size:11px;color:#222;font-weight:600;text-transform:uppercase;letter-spacing:1.4px;
            background:#fafafa;border-bottom:1px solid var(--line);padding:12px 10px;text-align:left
        }
        tbody td{padding:14px 10px;border-bottom:1px solid var(--line);font-size:14px}
        tbody tr:hover{background:#fcfcfc}
        .campaign{margin-top:14px;padding:14px 12px;border:1px solid var(--line);border-radius:6px;background:#f7fff9}
        #countdown{color:var(--warn);font-weight:600}
        .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:10px 16px;border-radius:28px;cursor:pointer;text-transform:uppercase;letter-spacing:1.2px;font-size:12px;transition:filter .15s ease;text-decoration:none;display:inline-block}
        .btn.outline{background:transparent;color:var(--accent)}
        .btn:hover{filter:brightness(0.9)}
        .actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:16px}
        .actions.justify{justify-content:space-between}
        .muted{color:var(--muted);font-size:12px}
    </style>
    </head>
<body>
<div class="shell">
    <h1>Sepetim</h1>

    <div class="actions justify" style="margin-bottom:12px">
        <a href="{{ route('main') }}" class="btn outline" style="display:flex;align-items:center;gap:8px">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            Geri Dön
        </a>
        <a href="{{ route('order') }}" class="btn" style="display:flex;align-items:center;gap:8px">
            Sipariş Oluştur
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if(session('success')) <div class="notice success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="notice error">{{ session('error') }}</div> @endif

    @if($products->isEmpty())
        <div class="notice"><strong>Sepetiniz boş</strong></div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ürün</th>
                        <th>Kategori</th>
                        <th>Yazar</th>
                        <th>Adet</th>
                        <th>Fiyat</th>
                        <th>Toplam</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                        <tr>
                            <td>{{ $p->product->title }}</td>
                            <td>{{ $p->product->category?->category_title }}</td>
                            <td>{{ $p->product->author }}</td>
                            <td>{{ $p->quantity }}</td>
                            <td>{{ number_format($p->product->list_price,2) }} TL</td>
                            <td>{{ number_format($p->product->list_price * $p->quantity,2) }} TL</td>
                            <td>
                                <form action="{{ route('bag.delete', $p->id) }}" method="POST" style="margin:0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn outline">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(isset($bestCampaign['discount']) && $bestCampaign['discount'])
            <div class="campaign">
                <div><strong>Kampanya:</strong> {{ $bestCampaign['description'] }}</div>
                <div><strong>İndirim:</strong> {{ number_format($bestCampaign['discount'],2) }} TL</div>
                @if(isset($bestCampaignModel) && $bestCampaignModel)
                    @php $endsAtTimestamp = \Carbon\Carbon::parse($bestCampaignModel->ends_at)->timestamp; @endphp
                    <div style="margin-top:8px">
                        <strong>Kampanya Başlangıç:</strong>
                        {{ $bestCampaignModel->starts_at ? \Carbon\Carbon::parse($bestCampaignModel->starts_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }} <br>
                        <strong>Kampanya Bitiş:</strong>
                        {{ $bestCampaignModel->ends_at ? \Carbon\Carbon::parse($bestCampaignModel->ends_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }} <br>
                        <strong>Kalan Süre:</strong> <span id="countdown"></span>
                    </div>
                    <script>
                        (function(){
                            function updateCountdown(){
                                const now = Math.floor(Date.now()/1000);
                                const endTime = {{ $endsAtTimestamp }};
                                const diff = endTime - now;
                                const el = document.getElementById('countdown');
                                if(diff <= 0){ el.textContent = 'Kampanya sona erdi.'; el.style.color = 'var(--danger)'; return; }
                                const d = Math.floor(diff/86400), h = Math.floor((diff%86400)/3600),
                                      m = Math.floor((diff%3600)/60), s = diff%60;
                                let str = ''; if(d>0) str += d+' gün '; if(h>0) str += h+' saat '; if(m>0) str += m+' dakika '; str += s+' saniye';
                                el.textContent = str; el.style.color = 'var(--warn)';
                            }
                            updateCountdown(); setInterval(updateCountdown, 1000);
                        })();
                    </script>
                @endif
            </div>
        @endif

        <div class="actions" style="justify-content:flex-end">
            <a href="{{ route('order') }}" class="btn">Siparişi Tamamla</a>
        </div>
    @endif
</div>
</body>
</html>