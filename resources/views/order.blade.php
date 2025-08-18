<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <title>Siparişini Tamamla</title>
    <style>
        :root{
            --bg:#1B1B1F; --text:#EDEDED; --muted:#A0A0A0; --line:#333338;
            --accent:#404046; --success:#00C6AE; --warn:#ed8936; --danger:#FF6B6B;
            --card:#232327; --shadow:rgba(0,0,0,0.3); --hover:rgba(0,198,174,0.1);
            --primary:#00C6AE; --secondary:#14F1D9; --gray-50:#2A2A2F; --gray-100:#333338;
            --hover-accent:#505056; --price-color:#4A90E2;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-size:14px}
        body{font-family:"Inter",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;letter-spacing:-0.025em;line-height:1.6;-webkit-font-smoothing:antialiased}
        .shell{max-width:1200px;margin:0 auto;padding:24px 20px 80px}
        
        /* Header */
        .page-header{background:var(--card);border-bottom:1px solid var(--line);padding:20px 0;margin:-24px -20px 24px;box-shadow:0 4px 20px var(--shadow)}
        .header-content{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center}
        h1{font-size:24px;font-weight:600;letter-spacing:-0.01em;margin:0;color:var(--text)}
        .header-subtitle{font-size:14px;color:var(--muted);font-weight:500}
        
        /* Toolbar */
        .toolbar{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin:0 0 20px;background:var(--card);padding:16px 20px;border-radius:8px;box-shadow:0 1px 3px var(--shadow);border:1px solid var(--line)}
        .nav-section{display:flex;gap:6px;align-items:center}
        
        /* Cards */
        .card{background:var(--card);border:1px solid var(--line);border-radius:8px;padding:20px;box-shadow:0 1px 3px var(--shadow)}
        
        /* Buttons */
        .btn{border:1px solid var(--accent);background:var(--accent);color:#EDEDED;padding:10px 16px;border-radius:8px;cursor:pointer;text-transform:uppercase;letter-spacing:1px;font-size:12px;font-weight:600;transition:all .2s ease;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
        .btn:hover{background:var(--hover-accent);border-color:var(--hover-accent);transform:translateY(-1px);box-shadow:0 4px 12px rgba(64,64,70,0.4)}
        .btn.outline{background:transparent;color:var(--accent);border-color:var(--accent)}
        .btn.outline:hover{background:var(--accent);color:#EDEDED}
        .btn.success{background:var(--success);border-color:var(--success)}
        .btn.success:hover{background:#00B894;box-shadow:0 4px 12px rgba(0,198,174,0.4)}
        
        /* Table */
        .table-wrap{overflow:auto;border:1px solid var(--line);border-radius:8px;background:var(--gray-50)}
        table{width:100%;border-collapse:collapse;min-width:720px}
        thead th{
            font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:1.4px;
            background:var(--card);border-bottom:1px solid var(--line);padding:16px 12px;text-align:left
        }
        tbody td{padding:16px 12px;border-bottom:1px solid var(--line);font-size:14px;color:var(--text)}
        tbody tr:hover{background:var(--gray-50)}
        tbody tr:last-child td{border-bottom:none}
        
        /* Campaign */
        .campaign{background:linear-gradient(135deg, rgba(0,198,174,0.1) 0%, rgba(20,241,217,0.05) 100%);border:1px solid var(--success);border-radius:12px;padding:20px;margin:20px 0;box-shadow:0 4px 12px rgba(0,198,174,0.1)}
        .campaign-title{font-size:16px;font-weight:600;color:var(--success);margin-bottom:12px;display:flex;align-items:center;gap:8px}
        .campaign-info{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px}
        .campaign-item{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(0,198,174,0.2)}
        .campaign-item:last-child{border-bottom:none}
        .campaign-label{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px}
        .campaign-value{font-weight:600;color:var(--text)}
        #countdown{color:var(--warn);font-weight:600}
        
        /* Summary */
        .summary{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:24px;margin-top:24px;box-shadow:0 4px 12px var(--shadow)}
        .summary-title{font-size:18px;font-weight:600;color:var(--text);margin-bottom:16px;text-align:center}
        .summary-row{display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--line)}
        .summary-row:last-child{border-bottom:none;font-weight:700;font-size:18px;color:var(--success)}
        
        /* Credit Cards */
        .cards{display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-top:28px}
        @media (max-width:860px){.cards{grid-template-columns:1fr}}
        .credit-card-item{
            background:var(--card);border:1px solid var(--line);padding:16px;border-radius:12px;display:flex;gap:12px;align-items:center;
            transition:all .2s ease;cursor:pointer
        }
        .credit-card-item:hover{transform:translateY(-2px);border-color:var(--accent);box-shadow:0 4px 12px rgba(64,64,70,0.2)}
        .credit-card-item input[type="radio"]{margin:0}
        .credit-card-item label{cursor:pointer;flex:1}
        .muted{color:var(--muted);font-size:12px}
        
        /* Form */
        .new-card form{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .new-card .full{grid-column:1 / -1}
        .field{display:flex;flex-direction:column;gap:6px}
        .field label{font-size:12px;letter-spacing:.6px;text-transform:uppercase;color:var(--muted)}
        .field input,.field select{
            padding:12px;border:1px solid var(--line);border-radius:8px;outline:none;background:var(--bg);color:var(--text);-webkit-appearance:none;-moz-appearance:none;appearance:none
        }
        .field select{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23EDEDED' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");background-position:right 8px center;background-repeat:no-repeat;background-size:16px}
        .field input:focus,.field select:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(64,64,70,0.1)}
        .field input:hover,.field select:hover{border-color:var(--accent)}
        
        /* Actions */
        .actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:16px}
        .toggle-btn{margin-top:12px;display:inline-block;cursor:pointer;font-size:12px;text-transform:uppercase;letter-spacing:1px;color:var(--accent)}
        .toggle-btn:hover{text-decoration:underline}
        .hidden{display:none}
        
        /* Notices */
        .notice{padding:12px 16px;border:1px solid var(--line);margin:0 0 20px;border-radius:8px;display:flex;align-items:center;gap:8px}
        .notice.success{color:var(--success);background:rgba(0,198,174,0.1);border-color:var(--success)}
        .notice.error{color:var(--danger);background:rgba(255,107,107,0.1);border-color:var(--danger)}
        
        /* Empty State */
        .empty-state{text-align:center;padding:64px 32px;background:var(--card);border-radius:20px;border:2px dashed var(--line);margin:32px 0}
        .empty-state svg{margin-bottom:16px;opacity:0.5}
        .empty-state h3{font-size:18px;font-weight:600;color:var(--text);margin-bottom:8px}
        .empty-state p{color:var(--muted);margin-bottom:0}
        
        /* Responsive */
        @media (max-width:768px){
            .shell{padding:24px 16px 60px}
            .page-header{margin:-24px -16px 24px;padding:32px 0}
            .toolbar{padding:16px 20px;flex-direction:column;align-items:stretch}
            .nav-section{justify-content:center}
            .campaign-info{grid-template-columns:1fr}
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
    <div class="toolbar">
        <div class="nav-section">
            <a href="/bag" class="btn outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
    
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ürün Adı</th> <th>Mağaza</th> <th>Kategori Adı</th><th>Yazar</th><th>Ürün Sayısı</th><th>Fiyat</th><th>Toplam Fiyat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                        <tr>
                            <td>{{$p->product->title}}</td>
                            <td>{{$p->product->store->name}}</td>
                            <td>{{$p->product->category?->category_title}}</td>
                            <td>{{$p->product->author}}</td>
                            <td>{{$p->quantity}}</td>
                            <td>{{ number_format($p->product->list_price,2) }} TL</td>
                            <td>{{ number_format($p->product->list_price * $p->quantity,2) }} TL</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                <span>{{ number_format($Totally,2) }} TL</span>
            </div>
        </div>
    @endif

    <div class="cards">
        <div>
            <h3>Kredi Kartı Seçin</h3>
            @if($creditCards->count() > 0)
                @foreach($creditCards as $card)
                    <div class="credit-card-item">
                        <input type="radio" name="credit_card_id" value="{{ $card->id }}" id="card_{{ $card->id }}" autocomplete="off">
                        <label for="card_{{ $card->id }}">
                            <strong>{{ $card->name }}</strong><br>
                            <span class="muted">**** **** **** {{ substr($card->card_number, -4) }}</span><br>
                            <span class="muted">{{ $card->card_holder_name }}</span><br>
                            <span class="muted">{{ $card->expire_month }}/{{ $card->expire_year }}</span>
                        </label>
                    </div>
                @endforeach
            @else
                <p class="muted">Henüz kayıtlı kredi kartınız yok.</p>
            @endif

            <div class="toggle-btn" id="toggleCardForm">+ Yeni Kart Ekle</div>
        </div>

        <div class="new-card hidden" id="cardForm">
            <h4>Yeni Kart Ekle</h4>
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
                    <button type="submit" class="btn" >Kaydet</button>
                    <button type="button" class="btn outline" id="cancelCardForm">İptal</button>
                </div>
            </form>
        </div>
    </div>
    @if($creditCards->count() > 0)
        <form action="{{route('done')}}" method="POST" style="margin-top:26px;">
            @csrf
            <input type="hidden" name="credit_card_id" id="credit_card_id">
            <button type="submit" class="btn" style="width:100%;max-width:420px;" onclick="return validateCardSelection()">Siparişi Tamamla</button>
        </form>
    @else
        <div class="notice error">Lütfen bir kredi kartı seçiniz veya yeni bir kart ekleyiniz.</div>
    @endif
</div>

<script>
    document.querySelectorAll('input[name="credit_card_id"]').forEach(radio=>{
        radio.addEventListener('change',()=>document.getElementById('credit_card_id').value=radio.value);
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
