<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toplu Ürün Ekle - Satıcı Paneli</title>
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
        
        /* Form Actions */
        .form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:24px;border-top:1px solid var(--line)}
        
        /* Product List */
        .product-list{margin-top:24px}
        .product-item{background:var(--bg);border:1px solid var(--line);border-radius:8px;padding:16px;margin-bottom:16px}
        .product-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
        .product-title{font-weight:600;color:var(--text)}
        .remove-product{background:var(--danger);border:none;color:#fff;padding:4px 8px;border-radius:4px;cursor:pointer;font-size:12px}
        .product-fields{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .product-images{grid-column:1/span 2}
        
        /* Drag & Drop */
        .drag-area{position:relative;display:flex;align-items:center;justify-content:center;gap:8px;padding:40px 20px;border:2px dashed var(--line);border-radius:8px;background:var(--bg);color:var(--muted);transition:all .2s ease;cursor:pointer;min-height:120px}
        .drag-area.dragover{border-color:var(--accent);color:var(--accent);background:rgba(59,130,246,0.1)}
        .drag-area .icon{font-size:24px}
        
        @media (max-width:768px){
            .header-content{flex-direction:column;gap:12px;text-align:center}
            .form-grid{grid-template-columns:1fr;gap:16px}
            .form-group.full-width{grid-column:1}
            .form-actions{flex-direction:column-reverse}
            .btn{justify-content:center}
            .product-fields{grid-template-columns:1fr}
            .product-images{grid-column:1}
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="header">
        <div class="header-content">
            <h1>Toplu Ürün Ekle</h1>
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

    <div class="form-container">
        <form action="{{ route('seller.bulkCreateProduct') }}" method="post" enctype="multipart/form-data" id="bulkProductForm">
            @csrf
            
            <div class="toolbar">
                <h3>Ürün Listesi</h3>
                <button type="button" class="btn outline" onclick="addProduct()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Ürün Ekle
                </button>
            </div>
            
            <div id="productList" class="product-list">
                <!-- Ürünler buraya eklenecek -->
            </div>
            
            <div class="form-actions">
                <a href="{{ route('seller.product') }}" class="btn outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H6m6-7-7 7 7 7"/>
                    </svg>
                    İptal
                </a>
                <button type="submit" class="btn success" id="submitBtn" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Ürünleri Ekle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let productCount = 0;

function addProduct() {
    productCount++;
    const productList = document.getElementById('productList');
    
    const productHtml = `
        <div class="product-item" id="product_${productCount}">
            <div class="product-header">
                <span class="product-title">Ürün ${productCount}</span>
                <button type="button" class="remove-product" onclick="removeProduct(${productCount})">Kaldır</button>
            </div>
            
            <div class="product-fields">
                <div class="form-group">
                    <label class="form-label" for="title_${productCount}">Ürün Adı</label>
                    <input type="text" id="title_${productCount}" name="products[${productCount}][title]" class="form-input" placeholder="Kitap adını girin..." required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="author_${productCount}">Yazar</label>
                    <input type="text" id="author_${productCount}" name="products[${productCount}][author]" class="form-input" placeholder="Yazar adını girin..." required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="category_id_${productCount}">Kategori ID</label>
                    <input type="number" id="category_id_${productCount}" name="products[${productCount}][category_id]" class="form-input" placeholder="Kategori ID'si">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="list_price_${productCount}">Liste Fiyatı (TL)</label>
                    <input type="text" id="list_price_${productCount}" name="products[${productCount}][list_price]" class="form-input" 
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
                    <label class="form-label" for="stock_quantity_${productCount}">Stok Miktarı</label>
                    <input type="text" id="stock_quantity_${productCount}" name="products[${productCount}][stock_quantity]" class="form-input" 
                           placeholder="0"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                           required>
                </div>
                
                <div class="form-group product-images">
                    <label class="form-label">Ürün Resimleri</label>
                    <div class="drag-area" id="dragArea_${productCount}" onclick="document.getElementById('images_${productCount}').click()">
                        <input type="file" name="products[${productCount}][images][]" id="images_${productCount}" class="file-input" multiple accept="image/*" style="display: none;">
                        <div class="icon">📁</div>
                        <span>Resim dosyalarını seçin veya buraya sürükleyin</span>
                    </div>
                    <div class="file-info">
                        JPG, PNG, GIF formatları desteklenir. Maksimum dosya boyutu: 2MB
                    </div>
                </div>
            </div>
        </div>
    `;
    
    productList.insertAdjacentHTML('beforeend', productHtml);
    
    // Drag & Drop functionality
    setupDragAndDrop(productCount);
    
    updateSubmitButton();
}

function removeProduct(productId) {
    const product = document.getElementById(`product_${productId}`);
    product.remove();
    updateSubmitButton();
}

function updateSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    const productCount = document.querySelectorAll('.product-item').length;
    submitBtn.disabled = productCount === 0;
}

function setupDragAndDrop(productId) {
    const dragArea = document.getElementById(`dragArea_${productId}`);
    const fileInput = document.getElementById(`images_${productId}`);
    
    // Drag & Drop events
    dragArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dragArea.classList.add('dragover');
    });
    
    dragArea.addEventListener('dragleave', () => {
        dragArea.classList.remove('dragover');
    });
    
    dragArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dragArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            updateDragAreaText(productId, files.length);
        }
    });
    
    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            updateDragAreaText(productId, e.target.files.length);
        }
    });
}

function updateDragAreaText(productId, fileCount) {
    const dragArea = document.getElementById(`dragArea_${productId}`);
    const icon = dragArea.querySelector('.icon');
    const text = dragArea.querySelector('span');
    
    icon.textContent = '📸';
    text.textContent = `${fileCount} resim seçildi`;
}

// Sayfa yüklendiğinde ilk ürünü ekle
document.addEventListener('DOMContentLoaded', function() {
    addProduct();
});
</script>
</body>
</html>
