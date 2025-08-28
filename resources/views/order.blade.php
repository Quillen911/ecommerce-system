<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <title>Siparişini Tamamla</title>
    <style>
        :root{
            --bg:#0a0a0b; --surface:#111113; --card:#1a1a1d; --elevated:#202023;
            --text:#ffffff; --text-secondary:#a1a1aa; --text-muted:#71717a;
            --border:#27272a; --border-light:#3f3f46; --accent:#6366f1; --accent-hover:#5855eb;
            --success:#10b981; --success-light:#d1fae5; --warn:#f59e0b; --danger:#ef4444;
            --gradient-primary:linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --gradient-success:linear-gradient(135deg, #10b981 0%, #059669 100%);
            --shadow-sm:0 1px 2px 0 rgba(0, 0, 0, 0.05); --shadow:0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg:0 10px 15px -3px rgba(0, 0, 0, 0.1); --shadow-xl:0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --radius:8px; --radius-lg:12px; --radius-xl:16px;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-size:15px}
        body{font-family:"Inter",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
             letter-spacing:-0.01em;line-height:1.5;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
        .shell{max-width:1400px;margin:0 auto;padding:32px 24px 100px}
        
        /* Header */
        .page-header{background:var(--surface);border-bottom:1px solid var(--border);padding:24px 0;margin:-32px -24px 40px;
                     backdrop-filter:blur(8px);position:sticky;top:0;z-index:50}
        .header-content{max-width:1400px;margin:0 auto;padding:0 24px;display:flex;justify-content:space-between;align-items:center}
        h1{font-size:28px;font-weight:700;letter-spacing:-0.02em;margin:0;color:var(--text);
           background:var(--gradient-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .header-subtitle{font-size:15px;color:var(--text-secondary);font-weight:500;margin-top:4px}
        
        /* Navigation */
        .nav-toolbar{background:var(--card);border:1px solid var(--border);border-radius:var(--radius-lg);
                     padding:20px 24px;margin-bottom:32px;box-shadow:var(--shadow-sm)}
        .nav-section{display:flex;gap:12px;align-items:center}
        
        /* Cards */
        .card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius-lg);
              padding:24px;box-shadow:var(--shadow);transition:all 0.2s ease}
        .card:hover{border-color:var(--border-light);box-shadow:var(--shadow-lg)}
        .card-header{margin-bottom:20px}
        .card-title{font-size:18px;font-weight:600;color:var(--text);margin:0 0 4px 0}
        .card-subtitle{font-size:14px;color:var(--text-muted);margin:0}
        
        /* Buttons */
        .btn{background:var(--accent);color:white;border:none;padding:12px 20px;border-radius:var(--radius);
             cursor:pointer;font-size:14px;font-weight:500;transition:all 0.2s ease;text-decoration:none;
             display:inline-flex;align-items:center;gap:8px;letter-spacing:-0.01em}
        .btn:hover{background:var(--accent-hover);transform:translateY(-1px);box-shadow:var(--shadow-lg)}
        .btn.outline{background:transparent;color:var(--accent);border:1px solid var(--border-light)}
        .btn.outline:hover{background:var(--accent);color:white;border-color:var(--accent)}
        .btn.success{background:var(--success)}
        .btn.success:hover{background:#059669;box-shadow:0 4px 12px rgba(16,185,129,0.3)}
        .btn.ghost{background:transparent;color:var(--text-secondary);border:1px solid var(--border)}
        .btn.ghost:hover{background:var(--elevated);color:var(--text)}
        
        /* Table */
        .table-container{background:var(--card);border:1px solid var(--border);border-radius:var(--radius-lg);
                         overflow:hidden;box-shadow:var(--shadow);margin-bottom:32px}
        .table-wrap{overflow:auto}
        table{width:100%;border-collapse:collapse;min-width:800px}
        thead th{font-size:12px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;
                 background:var(--elevated);border-bottom:1px solid var(--border);padding:16px 20px;text-align:left}
        tbody td{padding:20px;border-bottom:1px solid var(--border);font-size:15px;color:var(--text)}
        tbody tr:hover{background:var(--elevated)}
        tbody tr:last-child td{border-bottom:none}
        .product-cell{display:flex;align-items:center;gap:12px;font-weight:500}
        
        /* Campaign */
        .campaign{background:linear-gradient(135deg, rgba(16,185,129,0.1) 0%, rgba(16,185,129,0.05) 100%);
                  border:1px solid var(--success);border-radius:var(--radius-xl);padding:28px;margin:32px 0;
                  box-shadow:0 8px 25px rgba(16,185,129,0.1);position:relative;overflow:hidden}
        .campaign::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;
                          background:var(--gradient-success)}
        .campaign-title{font-size:18px;font-weight:700;color:var(--success);margin-bottom:20px;
                        display:flex;align-items:center;gap:10px}
        .campaign-info{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px}
        .campaign-item{background:rgba(16,185,129,0.05);border-radius:var(--radius);padding:16px;
                       border:1px solid rgba(16,185,129,0.1)}
        .campaign-label{font-size:12px;color:var(--text-muted);text-transform:uppercase;
                        letter-spacing:0.5px;margin-bottom:4px}
        .campaign-value{font-weight:600;color:var(--text);font-size:15px}
        #countdown{color:var(--warn);font-weight:700;font-size:16px}
        
        /* Summary */
        .summary{background:var(--card);border:1px solid var(--border);border-radius:var(--radius-xl);
                 padding:32px;margin:32px 0;box-shadow:var(--shadow-lg);position:relative}
        .summary::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;
                         background:var(--gradient-primary)}
        .summary-title{font-size:20px;font-weight:700;color:var(--text);margin-bottom:24px;text-align:center}
        .summary-row{display:flex;justify-content:space-between;align-items:center;padding:16px 0;
                     border-bottom:1px solid var(--border);font-size:15px}
        .summary-row:last-child{border-bottom:none;font-weight:700;font-size:20px;color:var(--success);
                                background:var(--elevated);margin:16px -32px -32px;padding:24px 32px;
                                border-radius:0 0 var(--radius-xl) var(--radius-xl)}
        
        /* Credit Cards */
        .payment-section{display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-top:40px}
        @media (max-width:1024px){.payment-section{grid-template-columns:1fr;gap:24px}}
        
        .payment-methods{background:var(--card);border:1px solid var(--border);border-radius:var(--radius-xl);
                         padding:28px;box-shadow:var(--shadow);position:relative}
        .payment-methods::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;
                                 background:var(--gradient-primary)}
        .section-title{font-size:18px;font-weight:700;color:var(--text);margin:0 0 24px 0}
        
        .credit-card-item{background:var(--elevated);border:1px solid var(--border);padding:20px;
                          border-radius:var(--radius-lg);display:flex;gap:16px;align-items:center;
                          transition:all 0.2s ease;cursor:pointer;margin-bottom:16px;position:relative}
        .credit-card-item:hover{border-color:var(--accent);box-shadow:var(--shadow-lg);transform:translateY(-2px)}
        .credit-card-item:last-child{margin-bottom:0}
        .credit-card-item.selected{border-color:var(--accent);background:rgba(99,102,241,0.05)}
        .credit-card-item input[type="radio"]{margin:0;accent-color:var(--accent)}
        .credit-card-item label{cursor:pointer;flex:1;font-weight:500}
        .card-info{color:var(--text-secondary);font-size:13px;margin-top:4px}
        .muted{color:var(--text-muted);font-size:13px}
        
        /* Form */
        .new-card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius-xl);
                  padding:28px;box-shadow:var(--shadow);position:relative}
        .new-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;
                          background:var(--gradient-success)}
        .new-card form{display:grid;grid-template-columns:1fr 1fr;gap:20px}
        .new-card .full{grid-column:1 / -1}
        .field{display:flex;flex-direction:column;gap:8px}
        .field label{font-size:13px;font-weight:500;color:var(--text-secondary);margin-bottom:4px}
        .field input,.field select{padding:14px 16px;border:1px solid var(--border);border-radius:var(--radius);
                                   outline:none;background:var(--elevated);color:var(--text);font-size:14px;
                                   transition:all 0.2s ease;-webkit-appearance:none;-moz-appearance:none;appearance:none}
        .field select{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23a1a1aa' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                      background-position:right 12px center;background-repeat:no-repeat;background-size:16px}
        .field input:focus,.field select:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,0.1)}
        .field input:hover,.field select:hover{border-color:var(--border-light)}
        
        /* Actions */
        .actions{display:flex;gap:16px;flex-wrap:wrap;margin-top:24px}
        .toggle-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 16px;
                    background:transparent;border:1px solid var(--border);border-radius:var(--radius);
                    color:var(--accent);cursor:pointer;font-size:14px;font-weight:500;
                    transition:all 0.2s ease;text-decoration:none}
        .toggle-btn:hover{background:var(--elevated);border-color:var(--accent)}
        .hidden{display:none}
        
        /* Notices */
        .notice{padding:16px 20px;border:1px solid var(--border);margin:0 0 24px;border-radius:var(--radius-lg);
                display:flex;align-items:center;gap:12px;font-size:14px;font-weight:500}
        .notice.success{color:var(--success);background:rgba(16,185,129,0.1);border-color:var(--success)}
        .notice.error{color:var(--danger);background:rgba(239,68,68,0.1);border-color:var(--danger)}
        
        /* Empty State */
        .empty-state{text-align:center;padding:80px 40px;background:var(--card);border-radius:var(--radius-xl);
                     border:2px dashed var(--border);margin:40px 0;box-shadow:var(--shadow)}
        .empty-state svg{margin-bottom:20px;opacity:0.4}
        .empty-state h3{font-size:20px;font-weight:700;color:var(--text);margin-bottom:12px}
        .empty-state p{color:var(--text-muted);margin-bottom:0;font-size:15px}
        
        /* Checkout Button */
        .checkout-section{margin-top:40px;text-align:center}
        .checkout-btn{background:var(--gradient-primary);color:white;border:none;padding:16px 32px;
                      border-radius:var(--radius-lg);font-size:16px;font-weight:600;cursor:pointer;
                      transition:all 0.2s ease;box-shadow:var(--shadow-lg);width:100%;max-width:500px}
        .checkout-btn:hover{transform:translateY(-2px);box-shadow:var(--shadow-xl)}
        
        /* Responsive */
        @media (max-width:768px){
            .shell{padding:24px 16px 80px}
            .page-header{margin:-24px -16px 32px;padding:24px 0}
            .header-content{padding:0 16px}
            h1{font-size:24px}
            .nav-toolbar{padding:16px 20px;margin-bottom:24px}
            .nav-section{justify-content:center}
            .campaign-info{grid-template-columns:1fr}
            .payment-section{grid-template-columns:1fr;gap:20px}
            .new-card form{grid-template-columns:1fr;gap:16px}
            .summary{padding:24px;margin:24px 0}
            .summary-row:last-child{margin:12px -24px -24px;padding:20px 24px}
            .table-container{margin-bottom:24px}
            thead th{padding:12px 16px;font-size:11px}
            tbody td{padding:16px;font-size:14px}
        }
    </style>
</head>
<body>
<div class="page-header">
    <div class="header-content">
        <div>
            <h1>Sipariş Özeti</h1>
            <div class="header-subtitle">Siparişinizi tamamlayın</div>
        </div>
    </div>
</div>

<div class="shell">
    <div class="nav-toolbar">
        <div class="nav-section">
            <a href="/bag" class="btn ghost">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>
                </svg>
                Sepete Dön
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="notice success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
            </svg>
            {{session('success')}}
        </div>
    @endif
    
    @if(session('error'))
        <div class="notice error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            {{session('error')}}
        </div>
    @endif

    @if($products->isEmpty())
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 3h18v18H3zM8 7h8M8 11h8M8 15h5"/>
            </svg>
            <h3>Siparişiniz Yok</h3>
            <p>Sepetinizde ürün bulunmuyor.</p>
            <a href="/bag" class="btn" style="margin-top:16px">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>
                </svg>
                Sepete Dön
            </a>
        </div>
    @else
    
        <div class="table-container">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Ürün Bilgileri</th>
                            <th>Mağaza</th>
                            <th>Kategori</th>
                            <th>Yazar</th>
                            <th>Miktar</th>
                            <th>Birim Fiyat</th>
                            <th>Toplam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $p)
                            <tr>
                                <td class="product-cell">
                                    <div>
                                        <div style="font-weight:600;margin-bottom:2px;">{{$p->product?->title ?? 'Ürün bilgisi yok'}}</div>
                                        <div style="color:var(--text-muted);font-size:13px;">ID: {{$p->product?->id ?? 'N/A'}}</div>
                                    </div>
                                </td>
                                <td>{{$p->product?->store?->name ?? 'Mağaza bilgisi yok'}}</td>
                                <td>{{$p->product->category?->category_title ?? 'Kategori yok'}}</td>
                                <td>{{$p->product?->author ?? 'Yazar bilgisi yok'}}</td>
                                <td><span style="background:var(--elevated);padding:4px 8px;border-radius:4px;font-weight:500;">{{$p->quantity}} adet</span></td>
                                <td style="font-weight:600;">{{ number_format($p->product?->list_price ?? 0,2) }} TL</td>
                                <td style="font-weight:700;color:var(--success);">{{ number_format(($p->product?->list_price ?? 0) * $p->quantity,2) }} TL</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
                    @if(isset($bestCampaignModel) && $bestCampaignModel)
                        @php
                            $endsAtTimestamp = \Carbon\Carbon::parse($bestCampaignModel->ends_at)->timestamp;
                        @endphp
                        <div class="campaign-item">
                            <span class="campaign-label">Başlangıç</span>
                            <span class="campaign-value">{{ $bestCampaignModel->starts_at ? \Carbon\Carbon::parse($bestCampaignModel->starts_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="campaign-item">
                            <span class="campaign-label">Bitiş</span>
                            <span class="campaign-value">{{ $bestCampaignModel->ends_at ? \Carbon\Carbon::parse($bestCampaignModel->ends_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="campaign-item">
                            <span class="campaign-label">Kalan Süre</span>
                            <span class="campaign-value" id="countdown"></span>
                        </div>
                        <script>
                            (function(){
                                function updateCountdown(){
                                    const now = Math.floor(Date.now()/1000);
                                    const endTime = {{ $endsAtTimestamp }};
                                    const diff = endTime - now;
                                    const el = document.getElementById('countdown');
                                    if(diff <= 0){
                                        el.textContent = 'Kampanya sona erdi.';
                                        el.style.color = 'var(--danger)'; return;
                                    }
                                    const d = Math.floor(diff/86400), h = Math.floor((diff%86400)/3600),
                                          m = Math.floor((diff%3600)/60), s = diff%60;
                                    let str = '';
                                    if(d>0) str += d+' gün ';
                                    if(h>0) str += h+' saat ';
                                    if(m>0) str += m+' dakika ';
                                    str += s+' saniye';
                                    el.textContent = str;
                                    el.style.color = 'var(--warn)';
                                }
                                updateCountdown();
                                setInterval(updateCountdown,1000);
                            })();
                        </script>
                    @endif
                </div>
            </div>
        @endif

        <div class="summary">
            <div class="summary-title">Sipariş Özeti</div>
            <div class="summary-row">
                <span>Fiyat Toplamı</span>
                <span>{{ number_format($total,2) }} TL</span>
            </div>
            <div class="summary-row">
                <span>Kargo</span>
                <span>{{ $cargoPrice == 0 ? config('order.cargo.threshold')." TL üzeri ücretsiz" : number_format($cargoPrice,2)." TL" }}</span>
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

    <div class="payment-section">
        <div class="payment-methods">
            <h3 class="section-title">Ödeme Yöntemi</h3>
            @if($creditCards->count() > 0)
                @foreach($creditCards as $card)
                    <div class="credit-card-item">
                        <input type="radio" name="credit_card_id" value="{{ $card->id }}" id="card_{{ $card->id }}" autocomplete="off">
                        <label for="card_{{ $card->id }}">
                            <div>
                                <div style="font-weight:600;margin-bottom:4px;">{{ $card->name }}</div>
                                <div class="card-info">**** **** **** {{ substr($card->card_number, -4) }}</div>
                                <div class="card-info">{{ $card->card_holder_name }}</div>
                                <div class="card-info">{{ $card->expire_month }}/{{ $card->expire_year }}</div>
                            </div>
                        </label>
                    </div>
                @endforeach
            @else
                <div style="padding:20px;text-align:center;color:var(--text-muted);background:var(--elevated);border-radius:var(--radius);border:1px solid var(--border);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:8px;opacity:0.5;">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    <p>Henüz kayıtlı kredi kartınız yok.</p>
                </div>
            @endif

            <div class="toggle-btn" id="toggleCardForm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Yeni Kart Ekle
            </div>
        </div>

        <div class="new-card hidden" id="cardForm">
            <h3 class="section-title">Yeni Kart Ekle</h3>
            <form action="{{ route('payments.storeCreditCard') }}" method="POST" autocomplete="off">
                @csrf
                <div class="field"><label for="card_name">Kart Adı</label><input type="text" id="card_name" name="name" autocomplete="off" required></div>
                <div class="field"><label for="card_number">Kart Numarası</label><input type="text" id="card_number" name="card_number" autocomplete="off" required maxlength="16" inputmode="numeric"></div>
                <div class="field"><label for="cvv">CVV</label><input type="password" id="cvv" name="cvv" autocomplete="off" required maxlength="3" inputmode="numeric"></div>
                <div class="field"><label for="expire_month">Ay</label>
                    <select id="expire_month" name="expire_month" autocomplete="off" required>
                        @for($i=1;$i<=12;$i++)
                            <option value="{{ str_pad($i,2,'0',STR_PAD_LEFT) }}">{{ str_pad($i,2,'0',STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="field"><label for="expire_year">Yıl</label>
                    <select id="expire_year" name="expire_year" autocomplete="off" required>
                        @for($i=date('Y');$i<=date('Y')+10;$i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="field"><label for="card_type">Kart Tipi</label>
                    <select id="card_type" name="card_type" autocomplete="off" required>
                        <option value="visa">Visa</option><option value="mastercard">Mastercard</option><option value="amex">Amex</option>
                    </select>
                </div>
                <div class="field full"><label for="card_holder_name">Kart Sahibi</label><input type="text" id="card_holder_name" name="card_holder_name" autocomplete="off" required></div>
                <div class="field full"><label for="is_active"><input type="checkbox" id="is_active" name="is_active" value="1" checked autocomplete="off"> Kaydet</label></div>
                <div class="actions">
                    <button type="submit" class="btn success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                        </svg>
                        Kartı Kaydet
                    </button>
                    <button type="button" class="btn outline" id="cancelCardForm">İptal</button>
                </div>
            </form>
        </div>
    </div>
    
    @if($creditCards->count() > 0)
        <div class="checkout-section">
            <form action="{{route('done')}}" method="POST">
                @csrf
                <input type="hidden" name="credit_card_id" id="credit_card_id">
                <button type="submit" class="checkout-btn" onclick="return validateCardSelection()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                    </svg>
                    Siparişi Tamamla
                </button>
            </form>
        </div>
    @else
        <div class="notice error">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            Lütfen bir kredi kartı seçiniz veya yeni bir kart ekleyiniz.
        </div>
    @endif
</div>

<script>
    // Card selection handling with visual feedback
    document.querySelectorAll('input[name="credit_card_id"]').forEach(radio=>{
        radio.addEventListener('change',()=>{
            document.getElementById('credit_card_id').value=radio.value;
            // Update visual selection
            document.querySelectorAll('.credit-card-item').forEach(item => item.classList.remove('selected'));
            radio.closest('.credit-card-item').classList.add('selected');
        });
    });
    const toggleBtn = document.getElementById('toggleCardForm');
    const cardForm = document.getElementById('cardForm');
    const cancelBtn = document.getElementById('cancelCardForm');
    toggleBtn?.addEventListener('click',()=>{
        cardForm.classList.remove('hidden');
        toggleBtn.style.display='none';
    });
    cancelBtn?.addEventListener('click',()=>{
        cardForm.classList.add('hidden');
        toggleBtn.style.display='inline-block';
    });

    function validateCardSelection() {
        const selectedCard = document.getElementById('credit_card_id').value;
        if (!selectedCard) {
            alert('Lütfen bir kredi kartı seçiniz!');
            return false;
        }
        return true;
    }

    // Kart numarası formatlaması
    document.querySelector('input[name="card_number"]')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 16) value = value.substr(0, 16);
        e.target.value = value;
    });

    // CVV formatlaması
    document.querySelector('input[name="cvv"]')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) value = value.substr(0, 3);
        e.target.value = value;
    });

    // Form gönderilmeden önce validasyon
    document.querySelector('form[action="{{ route("payments.storeCreditCard") }}"]')?.addEventListener('submit', function(e) {
        const cardNumber = this.querySelector('input[name="card_number"]').value;
        const cvv = this.querySelector('input[name="cvv"]').value;
        const cardHolder = this.querySelector('input[name="card_holder_name"]').value;
        
        if (cardNumber.length !== 16) {
            e.preventDefault();
            alert('Kart numarası 16 haneli olmalıdır!');
            return false;
        }
        
        if (cvv.length !== 3) {
            e.preventDefault();
            alert('CVV kodu 3 haneli olmalıdır!');
            return false;
        }
        
        if (!cardHolder.trim()) {
            e.preventDefault();
            alert('Kart sahibi adı gerekli!');
            return false;
        }
    });
</script>
</body>
</html>
