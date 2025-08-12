<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <title>Siparişini Tamamla</title>
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
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Inter,"Helvetica Neue",Arial,sans-serif;
            letter-spacing:.2px; line-height:1.4;
        }
        .shell{max-width:1060px;margin:0 auto;padding:24px 16px 80px;}
        h1{font-size:22px; font-weight:600; text-transform:uppercase; letter-spacing:4px; margin:20px 0 10px; text-align:center;}
        h3,h4{font-weight:600; text-transform:uppercase;}
        h3{font-size:16px; letter-spacing:2px; margin:30px 0 12px;}
        h4{font-size:14px; letter-spacing:1.2px; margin:24px 0 10px;}
        .notice{padding:10px 12px;border:1px solid var(--line);margin:8px 0;border-radius:4px;}
        .notice.success{color:var(--success);background:#f0f9f4;border-color:var(--success);}
        .notice.error{color:var(--danger);background:#fef2f2;border-color:var(--danger);font-weight:500;}
        .empty{text-align:center;color:var(--muted);padding:24px 0;}
        .table-wrap{overflow:auto;border:1px solid var(--line);border-radius:6px;}
        table{width:100%;border-collapse:collapse;min-width:720px;}
        thead th{
            font-size:11px;color:#222;font-weight:600; text-transform:uppercase; letter-spacing:1.4px;
            background:#fafafa;border-bottom:1px solid var(--line); padding:12px 10px; text-align:left;
        }
        tbody td{padding:14px 10px;border-bottom:1px solid var(--line);font-size:14px;}
        tbody tr:hover{background:#fcfcfc;}
        .campaign{margin-top:14px; padding:14px 12px; border:1px solid var(--line); border-radius:6px; background:#f7fff9;}
        #countdown{color:var(--warn);font-weight:600;}
        .totals{margin-top:16px;border-top:1px solid var(--line);padding-top:16px;display:grid;gap:6px;max-width:420px;}
        .totals .row{display:flex;justify-content:space-between;}
        .totals .em{font-weight:700;}
        .cards{display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-top:28px;}
        @media (max-width:860px){.cards{grid-template-columns:1fr;}}
        .credit-card-item{
            border:1px solid var(--line); padding:12px; border-radius:8px; display:flex; gap:12px; align-items:center;
            transition:transform .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        .credit-card-item:hover{transform:translateY(-2px); border-color:#d8d8d8; box-shadow:0 2px 10px rgba(0,0,0,0.03);}
        .muted{color:var(--muted);font-size:12px;}
        .new-card form{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
        .new-card .full{grid-column:1 / -1;}
        .field{display:flex;flex-direction:column;gap:6px;}
        .field label{font-size:12px;letter-spacing:.6px;text-transform:uppercase;color:#333;}
        .field input,.field select{
            padding:10px;border:1px solid var(--line);border-radius:6px;outline:none;background:#fff;
        }
        .field input:focus,.field select:focus{border-color:#bbb;}
        .btn{
            border:1px solid var(--accent); background:var(--accent); color:#fff;
            padding:12px 16px; border-radius:28px; cursor:pointer; text-transform:uppercase; letter-spacing:1.6px; font-size:12px;
            transition:filter .15s ease;
        }
        .btn.outline{background:transparent;color:var(--accent);}
        .btn:hover{filter:brightness(0.9);}
        .actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:16px;}
        .toggle-btn{margin-top:12px;display:inline-block;cursor:pointer;font-size:12px;text-transform:uppercase;letter-spacing:1px;color:var(--accent);}
        .toggle-btn:hover{text-decoration:underline;}
        .hidden{display:none;}
    </style>
</head>
<body>
<div class="shell">
    <h1>Sipariş Özeti</h1>
    
    <div class="actions" style="justify-content:left;margin-bottom:20px;">
        <a href="/bag" class="btn outline" style="display:flex;align-items:center;gap:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            Sepete Dön
        </a>
    </div>
    @if(session('success')) <div class="notice success">{{session('success')}}</div> @endif
    @if(session('error')) <div class="notice error">{{session('error')}}</div> @endif

    @if($products->isEmpty())
        <div class="empty"><strong>Siparişiniz yok</strong></div>
    @else
    
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ürün Adı</th><th>Kategori Adı</th><th>Yazar</th><th>Ürün Sayısı</th><th>Fiyat</th><th>Toplam Fiyat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                        <tr>
                            <td>{{$p->product->title}}</td>
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
                <div><strong>Kampanya:</strong> {{ $bestCampaign['description'] }}</div>
                <div><strong>İndirim:</strong> {{ number_format($bestCampaign['discount'],2) }} TL</div>
                @if(isset($bestCampaignModel) && $bestCampaignModel)
                    @php
                        $endsAtTimestamp = \Carbon\Carbon::parse($bestCampaignModel->ends_at)->timestamp;
                    @endphp
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
        @endif

        <div class="totals">
            <div class="row"><strong>Fiyat Toplamı</strong><span>{{ number_format($total,2) }} TL</span></div>
            <div class="row"><strong>Kargo</strong>
                <span>{{ $cargoPrice == 0 ? "50 TL üzeri ücretsiz" : number_format($cargoPrice,2)." TL" }}</span>
            </div>
            @if($discount > 0)
                <div class="row"><strong>İndirim</strong><span>{{ number_format($discount,2) }} TL</span></div>
            @endif
            <div class="row em"><strong>Genel Toplam</strong><span>{{ number_format($Totally,2) }} TL</span></div>
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
