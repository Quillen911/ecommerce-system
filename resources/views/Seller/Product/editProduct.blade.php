<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Düzenle - Satıcı Paneli</title>
    <style>
        :root{
            --bg:#1E293B; --text:#F1F5F9; --muted:#94A3B8; --line:#334155;
            --accent:#3B82F6; --success:#22C55E; --warn:#F59E0B; --danger:#EF4444;
            --header:#0F172A; --card:#334155;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text)}
        body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Inter,"Helvetica Neue",Arial,sans-serif;letter-spacing:.2px;line-height:1.4}
        .shell{max-width:800px;margin:0 auto;padding:24px 16px 80px}
        
        /* Header */
        .header{background:var(--header);color:var(--text);padding:20px 0;margin:-24px -16px 24px;border-radius:0 0 16px 16px;box-shadow:0 4px 6px rgba(0,0,0,0.3)}
        .header-content{max-width:800px;margin:0 auto;padding:0 16px;display:flex;justify-content:space-between;align-items:center}
        .header h1{font-size:24px;font-weight:700;margin:0;letter-spacing:2px;text-transform:uppercase;color:var(--text)}
        .header-info{text-align:right}
        .product-name{font-size:16px;font-weight:600;margin-bottom:4px;color:var(--text)}
        .product-id{font-size:12px;opacity:0.8;text-transform:uppercase;letter-spacing:1px;color:var(--muted)}
        
        /* Notices */
        .notice{padding:12px 16px;border:1px solid var(--line);margin:0 0 20px;border-radius:8px;display:flex;align-items:center;gap:8px}
        .notice.success{color:var(--success);background:rgba(34,197,94,0.1);border-color:var(--success)}
        .notice.error{color:var(--danger);background:rgba(239,68,68,0.1);border-color:var(--danger)}
        
        /* Toolbar */
        .toolbar{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px;flex-wrap:wrap}
        .btn{border:1px solid var(--accent);background:var(--accent);color:#fff;padding:12px 20px;border-radius:8px;cursor:pointer;text-transform:uppercase;letter-spacing:1px;font-size:12px;font-weight:600;transition:all .2s ease;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
        .btn:hover{background:#2563EB;border-color:#2563EB;transform:translateY(-1px);box-shadow:0 4px 12px rgba(59,130,246,0.4)}
        .btn.outline{background:transparent;color:var(--accent);border-color:var(--accent)}
        .btn.outline:hover{background:var(--accent);color:#fff}
        .btn.danger{background:var(--danger);border-color:var(--danger)}
        .btn.danger:hover{background:#DC2626;box-shadow:0 4px 12px rgba(239,68,68,0.4)}
        .btn.success{background:var(--success);border-color:var(--success)}
        .btn.success:hover{background:#16A34A;box-shadow:0 4px 12px rgba(34,197,94,0.4)}
        
        /* Form Container */
        .form-container{background:var(--card);border-radius:12px;padding:24px;box-shadow:0 4px 6px rgba(0,0,0,0.3)}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
        .form-group{display:flex;flex-direction:column;gap:8px}
        .form-group.full-width{grid-column:1/span 2}
        
        /* Form Elements */
        .form-label{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;font-weight:600}
        .form-input{padding:12px 16px;border:2px solid var(--line);border-radius:8px;transition:border-color .2s ease;font-size:14px;background:var(--bg);color:var(--text)}
        .form-input:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,0.1)}
        .form-textarea{min-height:80px;resize:vertical}
        .form-select{padding:12px 16px;border:2px solid var(--line);border-radius:8px;background:var(--bg);color:var(--text);cursor:pointer;transition:border-color .2s ease}
        .form-select:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,0.1)}
        
        /* File Upload */
        .file-upload{position:relative;display:inline-block;width:100%}
        .file-input{position:absolute;opacity:0;width:100%;height:100%;cursor:pointer}
        .file-label{display:flex;align-items:center;justify-content:center;gap:8px;padding:40px 20px;border:2px dashed var(--line);border-radius:8px;background:var(--bg);color:var(--muted);transition:all .2s ease;cursor:pointer}
        .file-label:hover{border-color:var(--accent);color:var(--accent);background:rgba(59,130,246,0.1)}
        .file-info{margin-top:8px;font-size:12px;color:var(--muted)}
        
        /* Current Image */
        .current-image{margin-bottom:12px}
        .current-image img{width:120px;height:120px;object-fit:cover;border-radius:8px;border:2px solid var(--line)}
        .image-label{font-size:12px;color:var(--muted);margin-bottom:8px;display:block}
        
        /* Form Actions */
        .form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:24px;border-top:1px solid var(--line)}
        
        @media (max-width:768px){
            .header-content{flex-direction:column;gap:12px;text-align:center}
            .form-grid{grid-template-columns:1fr;gap:16px}
            .form-group.full-width{grid-column:1}
            .form-actions{flex-direction:column-reverse}
            .btn{justify-content:center}
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="header">
        <div class="header-content">
            <h1>Ürün Düzenle</h1>
            <div class="header-info">
                <div class="product-name">{{ $products->title }}</div>
                <div class="product-id">ID: {{ $products->id }}</div>
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

    <div class="form-container">
        <form action="{{ route('seller.updateProduct', $products->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="title">Ürün Adı</label>
                    <input type="text" id="title" name="title" class="form-input" value="{{ $products->title }}" placeholder="Kitap adını girin..." required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="author">Yazar</label>
                    <input type="text" id="author" name="author" class="form-input" value="{{ $products->author }}" placeholder="Yazar adını girin..." required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="category_id">Kategori ID</label>
                    <input type="number" id="category_id" name="category_id" class="form-input" value="{{ $products->category_id }}" placeholder="Kategori ID'si">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="list_price">Liste Fiyatı (TL)</label>
                    <input type="text" id="list_price" name="list_price" class="form-input" 
                           value="{{ $products->list_price }}" 
                           placeholder="0.00"
                           oninput="
                               let val = this.value.replace(/[^0-9\.]/g, '');
                               let parts = val.split('.');
                               if (parts.length > 2) {
                                   val = parts[0] + '.' + parts.slice(1).join('');
                               }
                               this.value = val;"
                           required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="stock_quantity">Stok Miktarı</label>
                    <input type="text" id="stock_quantity" name="stock_quantity" class="form-input" 
                           value="{{ $products->stock_quantity }}" 
                           placeholder="0"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                           required>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label">Ürün Resimleri</label>
                    
                    @if($products->images && count($products->images) > 0)
                        <div class="current-image">
                            <span class="image-label">Mevcut Resim:</span>
                            <img src="{{ $products->first_image }}" alt="{{ $products->title }}">
                        </div>
                    @endif
                    
                    <div class="file-upload">
                        <input type="file" name="images[]" id="images" class="file-input" multiple accept="image/*">
                        <label for="images" class="file-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/>
                            </svg>
                            Yeni resim seçin veya mevcut resmi değiştirin
                        </label>
                        <div class="file-info">
                            JPG, PNG, GIF formatları desteklenir. Maksimum dosya boyutu: 2MB
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('seller.product') }}" class="btn outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H6m6-7-7 7 7 7"/>
                    </svg>
                    İptal
                </a>
                <button type="submit" class="btn success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                    </svg>
                    Ürünü Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// File upload feedback
document.getElementById('images').addEventListener('change', function(e) {
    const label = document.querySelector('.file-label');
    const fileCount = e.target.files.length;
    
    if (fileCount > 0) {
        label.style.borderColor = 'var(--success)';
        label.style.color = 'var(--success)';
        label.style.background = '#f0f9f4';
        
        if (fileCount === 1) {
            label.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                </svg>
                ${fileCount} resim seçildi
            `;
        } else {
            label.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                </svg>
                ${fileCount} resim seçildi
            `;
        }
    }
});
</script>
</body>
</html>
