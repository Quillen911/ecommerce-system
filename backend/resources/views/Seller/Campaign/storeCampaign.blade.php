<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Kampanya Oluştur - Satıcı Paneli</title>
    <style>
        :root{
            --bg:#1E293B; --text:#F1F5F9; --muted:#94A3B8; --line:#334155;
            --accent:#3B82F6; --success:#22C55E; --warn:#F59E0B; --danger:#EF4444;
            --header:#0F172A; --card:#334155;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text)}
        body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Inter,"Helvetica Neue",Arial,sans-serif;letter-spacing:.2px;line-height:1.4}
        .shell{max-width:1000px;margin:0 auto;padding:24px 16px 80px}
        
        /* Header */
        .header{background:var(--header);color:var(--text);padding:20px 0;margin:-24px -16px 24px;border-radius:0 0 16px 16px;box-shadow:0 4px 6px rgba(0,0,0,0.3)}
        .header-content{max-width:1000px;margin:0 auto;padding:0 16px;display:flex;justify-content:space-between;align-items:center}
        .header h1{font-size:24px;font-weight:700;margin:0;letter-spacing:2px;text-transform:uppercase;color:var(--text)}
        .header-info{text-align:right}
        .header-subtitle{font-size:14px;opacity:0.8;text-transform:uppercase;letter-spacing:1px;color:var(--muted)}
        
        /* Notices */
        .notice{padding:12px 16px;border:1px solid var(--line);margin:0 0 20px;border-radius:8px;display:flex;align-items:center;gap:8px}
        .notice.success{color:var(--success);background:rgba(34,197,94,0.1);border-color:var(--success)}
        .notice.error{color:var(--danger);background:rgba(239,68,68,0.1);border-color:var(--danger)}
        
        /* Form Container */
        .form-container{background:var(--card);border-radius:12px;padding:24px;box-shadow:0 4px 6px rgba(0,0,0,0.3);margin-bottom:20px}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
        .form-group{display:flex;flex-direction:column;gap:8px}
        .form-group.full-width{grid-column:1/span 2}
        
        /* Form Elements */
        .form-label{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;font-weight:600}
        .form-input{padding:12px 16px;border:2px solid var(--line);border-radius:8px;transition:border-color .2s ease;font-size:14px;background:var(--bg);color:var(--text)}
        .form-input:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,0.1)}
        .form-select{padding:12px 16px;border:2px solid var(--line);border-radius:8px;background:var(--bg);color:var(--text);cursor:pointer;transition:border-color .2s ease;-webkit-appearance:none;-moz-appearance:none;appearance:none;background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23F1F5F9' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");background-position:right 8px center;background-repeat:no-repeat;background-size:16px}
        .form-select:hover{border-color:var(--accent)}
        .form-select:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,0.1)}
        
        /* Sections */
        .section{background:var(--card);border-radius:12px;padding:20px;box-shadow:0 4px 6px rgba(0,0,0,0.3);margin-bottom:20px}
        .section-title{font-size:16px;font-weight:600;color:var(--text);margin-bottom:16px;display:flex;align-items:center;gap:8px}
        .dynamic-fields{border:2px dashed var(--line);border-radius:8px;padding:16px;margin-bottom:12px;background:var(--bg)}
        .add-btn{background:var(--success);color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:1px;transition:all .2s ease}
        .add-btn:hover{background:#059669}
        
        /* Buttons */
        .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:12px 20px;border-radius:8px;cursor:pointer;text-transform:uppercase;letter-spacing:1px;font-size:12px;font-weight:600;transition:all .2s ease;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
        .btn:hover{background:#2563EB;border-color:#2563EB;transform:translateY(-1px);box-shadow:0 4px 12px rgba(59,130,246,0.4)}
        .btn.outline{background:transparent;color:var(--accent);border-color:var(--accent)}
        .btn.outline:hover{background:var(--accent);color:#fff}
        .btn.success{background:var(--success);border-color:var(--success)}
        .btn.success:hover{background:#16A34A;box-shadow:0 4px 12px rgba(34,197,94,0.4)}
        .btn.danger{background:var(--danger);border-color:var(--danger)}
        .btn.danger:hover{background:#DC2626;box-shadow:0 4px 12px rgba(239,68,68,0.4)}
        
        /* Form Actions */
        .form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:24px;border-top:1px solid var(--line)}
        
        @media (max-width:768px){
            .header-content{flex-direction:column;gap:12px;text-align:center}
            .form-grid{grid-template-columns:1fr;gap:16px}
            .form-group.full-width{grid-column:1}
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="header">
        <div class="header-content">
            <h1>Yeni Kampanya Oluştur</h1>
            <div class="header-info">
                <div class="header-subtitle">Satıcı Paneli</div>
            </div>
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

    <form action="{{ route('seller.createCampaign') }}" method="POST">
        @csrf
        
        <!-- Kampanya Bilgileri -->
        <div class="form-container">
            <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6"/>
                </svg>
                Kampanya Bilgileri
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="name">Kampanya Adı</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Kampanya adını girin..." required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="type">Kampanya Tipi</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="">Kampanya tipi seçin</option>
                        <option value="percentage">Yüzde İndirimi</option>
                        <option value="fixed">Sabit Tutar İndirimi</option>
                        <option value="x_buy_y_pay">X Al Y Öde</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label" for="description">Kampanya Açıklaması</label>
                    <input type="text" id="description" name="description" class="form-input" placeholder="Kampanya hakkında kısa açıklama...">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="priority">Öncelik</label>
                    <input type="number" id="priority" name="priority" class="form-input" placeholder="1-10 arası" min="1" max="10">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="is_active">Durum</label>
                    <select name="is_active" id="is_active" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Pasif</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="usage_limit">Toplam Kullanım Limiti</label>
                    <input type="number" id="usage_limit" name="usage_limit" class="form-input" placeholder="0" min="0" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="usage_limit_for_user">Kullanıcı Başına Limit</label>
                    <input type="number" id="usage_limit_for_user" name="usage_limit_for_user" class="form-input" placeholder="0" min="0" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="starts_at">Başlangıç Tarihi</label>
                    <input type="date" id="starts_at" name="starts_at" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="ends_at">Bitiş Tarihi</label>
                    <input type="date" id="ends_at" name="ends_at" class="form-input" required>
                </div>
            </div>
        </div>
        
        <!-- Kampanya Koşulları -->
        <div class="section">
            <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                </svg>
                Kampanya Koşulları
            </div>
            
            <div id="conditions-container">
                <div class="dynamic-fields condition-item">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Koşul Tipi</label>
                            <select name="conditions[0][condition_type]" class="form-select">
                                <option value="">Seçiniz</option>
                                <option value="author">Yazar</option>
                                <option value="category">Kategori</option>
                                <option value="min_bag">Min. Sepet Tutarı</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Değer</label>
                            <input type="text" name="conditions[0][condition_value]" class="form-input" placeholder="Örn: Sabahattin Ali veya Yaşar Kemal, Sabahattin Ali">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Operatör</label>
                            <select name="conditions[0][operator]" class="form-select">
                                <option value="=">=</option>
                                <option value="!=">!=</option>
                                <option value=">">&gt;</option>
                                <option value="<">&lt;</option>
                                <option value=">=">&gt;=</option>
                                <option value="<=">&lt;=</option>
                                <option value="in">in</option>
                                <option value="not_in">not_in</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn danger" onclick="removeCondition(this)" style="margin-top:10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c0 1 1 2 2 2v2"/>
                        </svg>
                        Kaldır
                    </button>
                </div>
            </div>
            
            <button type="button" class="add-btn" onclick="addCondition()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Koşul Ekle
            </button>
        </div>
        
        <!-- Kampanya İndirimi (TEK kaydı destekler) -->
        <div class="section">
            <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12a9 9 0 11-6.219-8.56"/><circle cx="9" cy="9" r="2"/><path d="M21 21l-6-6"/>
                </svg>
                Kampanya İndirimi
            </div>

            <div id="discount-fields" class="dynamic-fields">
                <!-- Varsayılan: boş; JS seçilen tipe göre inputları basacak -->
                <div class="form-group">
                    <div class="form-label">İndirim Değeri</div>
                    <div class="form-label" style="color: var(--muted); font-weight: 400;">
                        Kampanya tipine göre alanlar otomatik gelecektir.
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('seller.campaign') }}" class="btn outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H6m6-7-7 7 7 7"/>
                </svg>
                İptal
            </a>
            <button type="submit" class="btn success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Kampanyayı Oluştur
            </button>
        </div>
    </form>
</div>

<script>
let conditionIndex = 1;

function addCondition() {
    const container = document.getElementById('conditions-container');
    const newCondition = document.createElement('div');
    newCondition.className = 'dynamic-fields condition-item';
    newCondition.innerHTML = `
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Koşul Tipi</label>
                <select name="conditions[${conditionIndex}][condition_type]" class="form-select">
                    <option value="">Seçiniz</option>
                    <option value="author">Yazar</option>
                    <option value="category">Kategori</option>
                    <option value="min_bag">Min. Sepet Tutarı</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Değer</label>
                <input type="text" name="conditions[${conditionIndex}][condition_value]" class="form-input" placeholder="Örn: Sabahattin Ali veya Yaşar Kemal, Sabahattin Ali">
            </div>
            
            <div class="form-group">
                <label class="form-label">Operatör</label>
                <select name="conditions[${conditionIndex}][operator]" class="form-select">
                    <option value="=">=</option>
                    <option value="!=">!=</option>
                    <option value=">">&gt;</option>
                    <option value="<">&lt;</option>
                    <option value=">=">&gt;=</option>
                    <option value="<=">&lt;=</option>
                    <option value="in">in</option>
                    <option value="not_in">not_in</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn danger" onclick="removeCondition(this)" style="margin-top:10px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c0 1 1 2 2 2v2"/>
            </svg>
            Kaldır
        </button>
    `;
    container.appendChild(newCondition);
    conditionIndex++;
}

function removeCondition(button) {
    button.parentElement.remove();
}

// --- İNDİRİM ALANLARINI TİPE GÖRE ÇİZ ---
function renderDiscountFields(type) {
    const box = document.getElementById('discount-fields');
    if (!box) return;

    if (type === 'percentage' || type === 'fixed') {
        box.innerHTML = `
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">${type === 'percentage' ? 'Yüzde (%)' : 'Sabit Tutar'}</label>
                    <input type="number" min="0" step="1" name="discount_value" class="form-input" placeholder="${type === 'percentage' ? 'Örn: 20' : 'Örn: 50'}" required>
                </div>
            </div>
        `;
    } else if (type === 'x_buy_y_pay') {
        box.innerHTML = `
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">X (Alınan)</label>
                    <input type="number" min="1" step="1" name="discount_value[x]" class="form-input" placeholder="Örn: 2" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Y (Ödenen)</label>
                    <input type="number" min="1" step="1" name="discount_value[y]" class="form-input" placeholder="Örn: 1" required>
                </div>
            </div>
        `;
    } else {
        box.innerHTML = `
            <div class="form-group">
                <div class="form-label">İndirim Değeri</div>
                <div class="form-label" style="color: var(--muted); font-weight: 400;">
                    Kampanya tipine göre alanlar otomatik gelecektir.
                </div>
            </div>
        `;
    }
}

// Tip değişince alanları güncelle
const typeSelect = document.getElementById('type');
if (typeSelect) {
    typeSelect.addEventListener('change', e => renderDiscountFields(e.target.value));
    // İlk yüklemede de çiz
    renderDiscountFields(typeSelect.value);
}

// --- (KALABİLİR) Form submit'te author değerlerini JSON array'e çevir ---
document.querySelector('form').addEventListener('submit', function(e) {
    const conditionItems = document.querySelectorAll('.condition-item');
    conditionItems.forEach(function(item) {
        const typeSelect = item.querySelector('select[name*="condition_type"]');
        const valueInput = item.querySelector('input[name*="condition_value"]');
        if (typeSelect && valueInput && typeSelect.value === 'author') {
            const value = valueInput.value.trim();
            if (value.includes(',') && !value.startsWith('[')) {
                const authors = value.split(',').map(author => author.trim());
                valueInput.value = JSON.stringify(authors);
            }
        }
    });
});
</script>
</body>
</html>