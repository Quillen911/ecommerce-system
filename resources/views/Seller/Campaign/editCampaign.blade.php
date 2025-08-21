<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampanya Düzenle - Satıcı Paneli</title>
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
        .campaign-name{font-size:16px;font-weight:600;margin-bottom:4px;color:var(--text)}
        .campaign-id{font-size:12px;opacity:0.8;text-transform:uppercase;letter-spacing:1px;color:var(--muted)}
        
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
        .condition-item, .discount-item{border:2px solid var(--line);border-radius:8px;padding:16px;margin-bottom:12px;background:var(--bg)}
        .condition-grid, .discount-grid{display:grid;grid-template-columns:1fr 2fr 1fr;gap:12px;align-items:end}
        
        /* Buttons */
        .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:12px 20px;border-radius:8px;cursor:pointer;text-transform:uppercase;letter-spacing:1px;font-size:12px;font-weight:600;transition:all .2s ease;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
        .btn:hover{background:#2563EB;border-color:#2563EB;transform:translateY(-1px);box-shadow:0 4px 12px rgba(59,130,246,0.4)}
        .btn.outline{background:transparent;color:var(--accent);border-color:var(--accent)}
        .btn.outline:hover{background:var(--accent);color:#fff}
        .btn.success{background:var(--success);border-color:var(--success)}
        .btn.success:hover{background:#16A34A;box-shadow:0 4px 12px rgba(34,197,94,0.4)}
        
        /* Form Actions */
        .form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:24px;border-top:1px solid var(--line)}
        
        /* Empty State */
        .empty-state{text-align:center;padding:40px 20px;color:var(--muted)}
        .empty-state svg{margin-bottom:12px;opacity:0.5}
        
        @media (max-width:768px){
            .header-content{flex-direction:column;gap:12px;text-align:center}
            .form-grid{grid-template-columns:1fr;gap:16px}
            .form-group.full-width{grid-column:1}
            .condition-grid, .discount-grid{grid-template-columns:1fr;gap:12px}
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="header">
        <div class="header-content">
            <h1>Kampanya Düzenle</h1>
            <div class="header-info">
                <div class="campaign-name">{{ is_string($campaigns->name) ? $campaigns->name : '' }}</div>
                <div class="campaign-id">ID: {{ $campaigns->id }}</div>
            </div>
        </div>
    </div>

    <div class="form-container">
        <form action="{{ route('seller.updateCampaign', $campaigns->id) }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="name">Kampanya Adı</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', is_string($campaigns->name) ? $campaigns->name : '') }}" placeholder="Kampanya adını girin..." required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="type">Kampanya Tipi</label>
                    <select name="type" id="type" class="form-select">
                        <option value="percentage" {{ old('type', $campaigns->type) == 'percentage' ? 'selected' : '' }}>Yüzde İndirimi</option>
                        <option value="fixed" {{ old('type', $campaigns->type) == 'fixed' ? 'selected' : '' }}>Sabit Tutar İndirimi</option>
                        <option value="x_buy_y_pay" {{ old('type', $campaigns->type) == 'x_buy_y_pay' ? 'selected' : '' }}>X Al Y Öde</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label" for="description">Kampanya Açıklaması</label>
                    <input type="text" id="description" name="description" class="form-input" value="{{ old('description', is_string($campaigns->description) ? $campaigns->description : '') }}" placeholder="Kampanya hakkında kısa açıklama...">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="priority">Öncelik</label>
                    <input type="number" id="priority" name="priority" class="form-input" value="{{ old('priority', $campaigns->priority ?? '') }}" placeholder="1-10 arası">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="is_active">Durum</label>
                    <select name="is_active" id="is_active" class="form-select">
                        <option value="1" {{ old('is_active', $campaigns->is_active) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $campaigns->is_active) ? '' : 'selected' }}>Pasif</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="usage_limit">Toplam Kullanım Limiti</label>
                    <input type="number" id="usage_limit" name="usage_limit" class="form-input" value="{{ old('usage_limit', is_numeric($campaigns->usage_limit) ? $campaigns->usage_limit : '') }}" placeholder="0" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="usage_limit_for_user">Kullanıcı Başına Limit</label>
                    <input type="number" id="usage_limit_for_user" name="usage_limit_for_user" class="form-input" value="{{ old('usage_limit_for_user', is_numeric($campaigns->usage_limit_for_user) ? $campaigns->usage_limit_for_user : '') }}" placeholder="0" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="starts_at">Başlangıç Tarihi</label>
                    <input type="date" id="starts_at" name="starts_at" class="form-input" value="{{ old('starts_at', $campaigns->starts_at ? \Carbon\Carbon::parse($campaigns->starts_at)->format('Y-m-d') : '') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="ends_at">Bitiş Tarihi</label>
                    <input type="date" id="ends_at" name="ends_at" class="form-input" value="{{ old('ends_at', $campaigns->ends_at ? \Carbon\Carbon::parse($campaigns->ends_at)->format('Y-m-d') : '') }}" required>
                </div>
            </div>
            
            <!-- Koşullar Bölümü -->
            <div class="section">
                <div class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                    </svg>
                    Kampanya Koşulları
                </div>
                @if($campaigns->conditions && $campaigns->conditions->count() > 0)
                    @foreach($campaigns->conditions as $index => $condition)
                        <div class="condition-item">
                            <div class="condition-grid">
                                <div class="form-group">
                                    <label class="form-label">Koşul Tipi</label>
                                    <select name="existing_conditions[{{ $condition->id }}][condition_type]" class="form-select">
                                        <option value="author" {{ $condition->condition_type == 'author' ? 'selected' : '' }}>Yazar</option>
                                        <option value="category" {{ $condition->condition_type == 'category' ? 'selected' : '' }}>Kategori</option>
                                        <option value="min_bag" {{ $condition->condition_type == 'min_bag' ? 'selected' : '' }}>Min. Sepet Tutarı</option>    
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Değer</label>
                                    <input type="text" name="existing_conditions[{{ $condition->id }}][condition_value]" class="form-input"
                                        value="{{ is_array($condition->condition_value) ? implode(', ', $condition->condition_value) : (is_string($condition->condition_value) && str_starts_with($condition->condition_value, '[') ? implode(', ', json_decode($condition->condition_value, true)) : $condition->condition_value) }}" 
                                        placeholder="Örn: Sabahattin Ali veya Yaşar Kemal, Sabahattin Ali">
                                    <input type="hidden" name="existing_conditions[{{ $condition->id }}][id]" value="{{ $condition->id }}">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Operatör</label>
                                    <select name="existing_conditions[{{ $condition->id }}][operator]" class="form-select">
                                        <option value="=" {{ $condition->operator == '=' ? 'selected' : '' }}>Eşittir (=)</option>
                                        <option value="!=" {{ $condition->operator == '!=' ? 'selected' : '' }}>Eşit Değil (≠)</option>
                                        <option value=">" {{ $condition->operator == '>' ? 'selected' : '' }}>Büyüktür (>)</option>
                                        <option value="<" {{ $condition->operator == '<' ? 'selected' : '' }}>Küçüktür (<)</option>
                                        <option value=">=" {{ $condition->operator == '>=' ? 'selected' : '' }}>Büyük Eşit (≥)</option>
                                        <option value="<=" {{ $condition->operator == '<=' ? 'selected' : '' }}>Küçük Eşit (≤)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                        </svg>
                        <div>Henüz koşul tanımlanmamış</div>
                    </div>
                @endif
            </div>
            
            <!-- İndirimler Bölümü -->
            <div class="section">
                <div class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 11-6.219-8.56"/><circle cx="9" cy="9" r="2"/><path d="M21 21l-6-6"/>
                    </svg>
                    Kampanya İndirimleri
                </div>
                
                @if($campaigns->discounts && $campaigns->discounts->count() > 0)
                    @foreach($campaigns->discounts as $index => $discount)
                        <div class="discount-item">
                            <div class="discount-grid">
                                <div class="form-group">
                                    <label class="form-label">İndirim Tipi</label>
                                    <select name="existing_discounts[{{ $discount->id }}][discount_type]" class="form-select">
                                        <option value="percentage" {{ $discount->discount_type == 'percentage' ? 'selected' : '' }}>Yüzde İndirimi</option>
                                        <option value="fixed" {{ $discount->discount_type == 'fixed' ? 'selected' : '' }}>Sabit Tutar</option>
                                        <option value="x_buy_y_pay" {{ $discount->discount_type == 'x_buy_y_pay' ? 'selected' : '' }}>X Al Y Öde</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">İndirim Değeri</label>
                                    <input type="text" name="existing_discounts[{{ $discount->id }}][discount_value]" class="form-input"
                                        value="{{ is_array($discount->discount_value) ? json_encode($discount->discount_value) : (is_string($discount->discount_value) ? $discount->discount_value : '') }}" 
                                        placeholder="Örn: 20 (% için) veya 50 (TL için)">
                                </div>
                                
                                <div style="display:flex;align-items:end;">
                                    <!-- Boş alan - gelecekte silme butonu eklenebilir -->
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        <div>Henüz indirim tanımlanmamış</div>
                    </div>
                @endif
            </div>
            
            <!-- Form Submit -->
            <div class="form-actions">
                <a href="{{ route('seller.campaign') }}" class="btn outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H6m6-7-7 7 7 7"/>
                    </svg>
                    İptal
                </a>
                <button type="submit" class="btn success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                    </svg>
                    Kampanyayı Güncelle
                </button>
            </div>
        </form>
    </div>

    <script>
        // Form submit edildiğinde virgülle ayrılmış yazar değerlerini JSON array'e çevir
        document.querySelector('form').addEventListener('submit', function(e) {
            // Mevcut koşullar için kontrol
            const existingConditions = document.querySelectorAll('[name*="existing_conditions"]');
            const conditionGroups = {};
            
            // Koşulları gruplandır
            existingConditions.forEach(function(element) {
                const match = element.name.match(/existing_conditions\[(\d+)\]\[(\w+)\]/);
                if (match) {
                    const id = match[1];
                    const field = match[2];
                    if (!conditionGroups[id]) conditionGroups[id] = {};
                    conditionGroups[id][field] = element;
                }
            });
            
            // Her grup için kontrol et
            Object.values(conditionGroups).forEach(function(group) {
                if (group.condition_type && group.condition_value && 
                    group.condition_type.value === 'author') {
                    const value = group.condition_value.value.trim();
                    // Eğer virgül varsa ve JSON array değilse
                    if (value.includes(',') && !value.startsWith('[')) {
                        // Virgülle ayır, temizle ve JSON array yap
                        const authors = value.split(',').map(author => author.trim());
                        group.condition_value.value = JSON.stringify(authors);
                    }
                }
            });
        });
    </script>
</body>
</html>
