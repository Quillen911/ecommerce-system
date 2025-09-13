<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->meta_title ?? $product->title }}</title>
    <meta name="description" content="{{ $product->meta_description ?? $product->description }}">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <style>
        :root{
            --bg:#0F0F0F; --text:#F5F5F5; --muted:#B0B0B0; --line:#2A2A2A;
            --accent:#3A3A3A; --success:#10B981; --warn:#F59E0B; --danger:#EF4444;
            --card:#1A1A1A; --shadow:rgba(0,0,0,0.4); --hover:rgba(16,185,129,0.15);
            --primary:#10B981; --secondary:#34D399; --gray-50:#262626; --gray-100:#404040;
            --hover-accent:#4A4A4A; --price-color:#3B82F6; --border:#333333;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-size:14px}
        body{font-family:"Inter",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;letter-spacing:-0.025em;line-height:1.6;-webkit-font-smoothing:antialiased}
        .shell{max-width:1200px;margin:0 auto;padding:24px 20px 80px}
         .product-detail{display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-top:20px}
         .product-gallery{display:flex;flex-direction:column;gap:10px}
         .product-gallery img{width:100%;height:300px;object-fit:contain;border-radius:8px;background:var(--card);padding:10px}
        .product-info h1{font-size:28px;margin-bottom:10px}
        .product-info .author{color:var(--muted);margin-bottom:20px}
        .product-info .price{font-size:24px;font-weight:600;color:var(--price-color);margin-bottom:20px}
        .stock-available{color:var(--success);font-weight:500}
        .stock-unavailable{color:var(--danger);font-weight:500}
         .product-actions{margin-top:20px}
         .quantity-controls{display:flex;align-items:center;gap:8px;margin-bottom:16px}
         .quantity-btn{width:32px;height:32px;border:1px solid var(--border);background:var(--card);color:var(--text);border-radius:4px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:600;transition:all 0.2s ease}
         .quantity-btn:hover{background:var(--accent);border-color:var(--primary)}
         .quantity-btn:disabled{opacity:0.5;cursor:not-allowed}
         .quantity-input{width:60px;padding:8px;border:1px solid var(--border);background:var(--card);color:var(--text);border-radius:4px;text-align:center;font-weight:600;-moz-appearance:textfield}
         .quantity-input::-webkit-outer-spin-button,
         .quantity-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
         .quantity-input::-webkit-outer-spin-button,
         .quantity-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
         .add-btn{width:100%;padding:12px 24px;background:var(--primary);color:white;border:none;border-radius:6px;cursor:pointer;font-weight:500;font-size:14px;transition:all 0.2s ease}
         .add-btn:hover{background:var(--secondary);transform:translateY(-1px)}
         .add-btn:disabled{background:var(--gray-100);color:var(--muted);cursor:not-allowed;transform:none}
         .stock-overlay{position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(255,255,255,0.95);display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--danger);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-radius:8px}
         
         /* Sepet Bildirimi */
         .cart-notification{position:fixed;top:32px;right:-420px;width:380px;background:var(--card);border:1px solid var(--line);border-radius:20px;box-shadow:0 16px 64px rgba(0,0,0,0.15);z-index:1000;transition:right 0.4s cubic-bezier(0.4,0,0.2,1);font-family:inherit;backdrop-filter:blur(8px)}
         .cart-notification.show{right:32px}
         .cart-notification-content{padding:24px;display:flex;align-items:flex-start;gap:16px;border-bottom:1px solid var(--line)}
         .cart-icon{width:48px;height:48px;background:linear-gradient(135deg, var(--success) 0%, #047857 100%);border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;box-shadow:0 4px 16px rgba(5,150,105,0.3)}
         .cart-text{flex:1}
         .cart-title{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text);margin-bottom:8px}
         .cart-message{font-size:14px;color:var(--muted);font-weight:500}
         .cart-close{background:var(--hover);border:none;padding:8px;border-radius:12px;cursor:pointer;color:var(--muted);transition:all 0.2s ease;flex-shrink:0}
         .cart-close:hover{background:var(--line);transform:scale(1.1)}
         .cart-actions{padding:20px 24px 24px}
         .cart-btn{display:inline-block;padding:12px 20px;border-radius:12px;text-decoration:none;font-size:13px;font-weight:600;letter-spacing:0.01em;transition:all 0.2s cubic-bezier(0.4,0,0.2,1);text-align:center;width:100%}
         .cart-btn-outline{background:transparent;color:var(--accent);border:2px solid var(--line);box-shadow:0 2px 8px var(--shadow)}
         .cart-btn-outline:hover{background:var(--accent);color:#fff;transform:translateY(-2px);box-shadow:0 8px 24px rgba(15,23,42,0.2)}
         .cart-product{display:flex;align-items:center;gap:16px;margin:12px 0;padding:16px;background:var(--bg);border-radius:16px;border:1px solid var(--line)}
         .cart-product-image{width:64px;height:64px;flex-shrink:0;border-radius:12px;overflow:hidden}
         .cart-product-image img{width:100%;height:100%;object-fit:cover;border:1px solid var(--line)}
         .cart-product-info{flex:1;min-width:0}
         .cart-product-title{font-size:14px;font-weight:600;color:var(--text);margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
         .cart-product-author{font-size:12px;color:var(--muted);margin-bottom:4px;font-weight:500}
         .cart-product-price{font-size:14px;font-weight:700;color:var(--success)}
        .product-description{margin-top:40px;grid-column:1/-1}
        .product-description h3{font-size:20px;margin-bottom:15px}
        .similar-products{margin-top:40px;grid-column:1/-1}
        .similar-products h3{font-size:20px;margin-bottom:20px}
         .products-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,250px));gap:20px;justify-content:start}
         .similar-product-card{text-decoration:none;color:inherit;background:var(--card);border:1px solid var(--line);border-radius:8px;overflow:hidden;transition:all 0.2s ease;box-shadow:0 1px 3px var(--shadow)}
         .similar-product-card:hover{box-shadow:0 8px 24px rgba(0,0,0,0.3);transform:translateY(-4px);border-color:var(--primary);background:var(--gray-50)}
         .similar-product-image{width:100%;height:180px;background:var(--gray-50);display:flex;align-items:center;justify-content:center;overflow:hidden}
         .similar-product-image img{width:100%;height:100%;object-fit:contain;padding:8px;background:white}
         .similar-product-info{padding:12px}
         .similar-product-info h4{font-size:14px;font-weight:600;color:var(--text);margin-bottom:4px;line-height:1.4;height:2.8em;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
         .similar-product-author{font-size:12px;color:var(--muted);margin-bottom:4px;font-weight:400}
         .similar-product-price{font-size:14px;font-weight:600;color:var(--price-color);margin-bottom:0}
         
         /* Header */
         .page-header{background:var(--card);border-bottom:1px solid var(--line);padding:20px 0;margin:-24px -20px 24px;box-shadow:0 4px 20px var(--shadow)}
         .header-content{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center;gap:20px}
         .header-left{flex:1;min-width:200px}
         .header-right{flex:1;min-width:200px;display:flex;justify-content:flex-end;gap:12px}
         h1{font-size:24px;font-weight:600;letter-spacing:-0.01em;margin:0;color:var(--text)}
         .header-subtitle{font-size:14px;color:var(--muted);font-weight:500}
         .btn{border:1px solid var(--primary);background:var(--primary);color:var(--text);padding:8px 16px;border-radius:6px;cursor:pointer;font-weight:500;font-size:14px;transition:all 0.15s ease;text-decoration:none;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 8px rgba(16,185,129,0.2)}
         .btn:hover{background:var(--secondary);border-color:var(--secondary);transform:translateY(-1px);box-shadow:0 4px 12px rgba(16,185,129,0.3)}
         .btn.outline{background:transparent;color:var(--primary);border:1px solid var(--border);box-shadow:none}
         .btn.outline:hover{background:var(--accent);border-color:var(--primary);color:var(--primary)}
         .btn.primary{background:var(--primary);color:var(--text);border:1px solid var(--primary);box-shadow:0 2px 8px rgba(16,185,129,0.2)}
         .btn.primary:hover{background:var(--secondary);border-color:var(--secondary);transform:translateY(-1px);box-shadow:0 4px 12px rgba(16,185,129,0.3)}
     </style>
 </head>
 <body>
 <div class="page-header">
     <div class="header-content">
         <div class="header-left">
             <h1><a href="{{ route('main') }}" style="text-decoration: none; color: inherit;">Omnia</a></h1>
             <div class="header-subtitle"> {{ auth()->user() ? 'Hoş Geldiniz, ' . auth()->user()->username : 'Hoş Geldiniz' }}</div>
         </div>
         <div class="header-right">
             @auth('user_web')
                 <a href="/bag" class="btn outline" style="color:rgb(255, 255, 255);">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                         <path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/>
                     </svg>
                     Sepetim
                 </a>
                 <a href="{{ route('profile') }}" class="btn outline" style="color:rgb(255, 255, 255);">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                         <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                         <circle cx="12" cy="7" r="4"/>
                     </svg>
                     Hesabım
                 </a>
             @else
                 <a href="{{ route('login') }}" class="btn outline" style="color:rgb(255, 255, 255);">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                         <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                         <polyline points="10,17 15,12 10,7"/>
                         <line x1="15" y1="12" x2="3" y2="12"/>
                     </svg>
                     Giriş Yap
                 </a>
                 <a href="{{ route('register') }}" class="btn primary" style="color:rgb(255, 255, 255);">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                         <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                         <circle cx="9" cy="7" r="4"/>
                         <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                         <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                     </svg>
                     Kayıt Ol
                 </a>
             @endauth
         </div>
     </div>
 </div>
 <div class="shell">
<div class="product-detail">
    {{-- Ürün Galerisi --}}
    <div class="product-gallery">
        @foreach($product->images as $image)
            <div style="position:relative">
            <img src="/storage/productsImages/{{ $image }}" 
                 alt="{{ $product->title }}"
                 loading="lazy">
                @if($product->stock_quantity <= 0)
                    <div class="stock-overlay">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:4px">
                            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        Stokta Yok
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Ürün Bilgileri --}}
    <div class="product-info">
        <h1>{{ $product->title }}</h1>
        <p class="author">{{ $product->author }}</p>
        <p class="price">{{ number_format($product->list_price, 2) }} ₺</p>
        
        @if($product->stock_quantity > 0)
            <span class="stock-available">Stokta var</span>
        @else
            <span class="stock-unavailable">Stokta yok</span>
        @endif

        {{-- Sepete Ekleme --}}
        <div class="product-actions">
            <form action="{{ route('add') }}" method="POST" class="add-to-bag-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="product_title" value="{{ $product->title }}">
                <input type="hidden" name="product_author" value="{{ $product->author }}">
                <input type="hidden" name="product_price" value="{{ $product->list_price }}">
                <input type="hidden" name="product_image" value="{{ basename($product->images[0] ?? '') }}">
                
                {{-- Miktar Kontrolleri --}}
                <div class="quantity-controls">
                    <button type="button" class="quantity-btn" onclick="decreaseQuantity()" id="decreaseBtn">-</button>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="quantity-input" id="quantityInput" onchange="updateQuantity()">
                    <button type="button" class="quantity-btn" onclick="increaseQuantity()" id="increaseBtn">+</button>
                </div>
                
                <button type="submit" class="add-btn" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                    {{ $product->stock_quantity <= 0 ? 'STOKTA YOK' : 'SEPETE EKLE' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Açıklama --}}
    @if($product->description)
        <div class="product-description">
            <h3>Açıklama</h3>
            <p>{{ $product->description }}</p>
        </div>
    @endif

    {{-- Benzer Ürünler --}}
    @if(isset($similar) && $similar->count() > 0)
        <div class="similar-products">
            <h3>Benzer Ürünler</h3>
            <div class="products-grid">
                @foreach($similar as $similarProduct)
                    <a href="{{ route('product.detail', $similarProduct->slug) }}" class="similar-product-card">
                        <div class="similar-product-image">
                            <img src="/storage/productsImages/{{ $similarProduct->images[0] ?? 'no-image.png' }}" alt="{{ $similarProduct->title }}">
                        </div>
                        <div class="similar-product-info">
                            <h4>{{ $similarProduct->title }}</h4>
                            <p class="similar-product-author">{{ $similarProduct->author }}</p>
                            <p class="similar-product-price">{{ number_format($similarProduct->list_price, 2) }} ₺</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if(isset($similarSeller) && $similarSeller->count() > 0)
        <div class="similar-products">
            <h3>Benzer Mağaza Ürünleri</h3>
            <div class="products-grid">
                @foreach($similarSeller as $similarProduct)
                    <a href="{{ route('product.detail', $similarProduct->slug) }}" class="similar-product-card">
                        <div class="similar-product-image">
                            <img src="/storage/productsImages/{{ $similarProduct->images[0] ?? 'no-image.png' }}" alt="{{ $similarProduct->title }}">
                        </div>
                        <div class="similar-product-info">
                            <h4>{{ $similarProduct->title }}</h4>
                            <p class="similar-product-author">{{ $similarProduct->author }}</p>
                            <p class="similar-product-price">{{ number_format($similarProduct->list_price, 2) }} ₺</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
</div>

{{-- JSON-LD Schema --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Product",
    "name": "{{ $product->title }}",
    "description": "{{ $product->description }}",
    "image": [
        @foreach($product->images as $image)
            "{{ url('/storage/productsImages/' . $image) }}"{{ !$loop->last ? ',' : '' }}
        @endforeach
    ],
    "offers": {
        "@@type": "Offer",
        "price": "{{ $product->list_price }}",
        "priceCurrency": "TRY",
        "availability": "{{ $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
    },
    "brand": {
        "@@type": "Brand",
        "name": "{{ $product->store->name }}"
    }
 }
 </script>

 <script>
 function increaseQuantity() {
     const input = document.getElementById('quantityInput');
     const max = parseInt(input.getAttribute('max'));
     const current = parseInt(input.value);
     
     if (current < max) {
         input.value = current + 1;
         updateQuantity();
     }
 }

 function decreaseQuantity() {
     const input = document.getElementById('quantityInput');
     const current = parseInt(input.value);
     
     if (current > 1) {
         input.value = current - 1;
         updateQuantity();
     }
 }

 function updateQuantity() {
     const input = document.getElementById('quantityInput');
     const decreaseBtn = document.getElementById('decreaseBtn');
     const increaseBtn = document.getElementById('increaseBtn');
     const max = parseInt(input.getAttribute('max'));
     const current = parseInt(input.value);
     
     // Minimum kontrolü
     if (current < 1) {
         input.value = 1;
     }
     
     // Maksimum kontrolü
     if (current > max) {
         input.value = max;
     }
     
     // Buton durumları
     decreaseBtn.disabled = parseInt(input.value) <= 1;
     increaseBtn.disabled = parseInt(input.value) >= max;
 }
 
 // Sayfa yüklendiğinde buton durumlarını ayarla
 document.addEventListener('DOMContentLoaded', function() {
     updateQuantity();
     
     // Sepete ekleme formu için AJAX
     const forms = document.querySelectorAll('.add-to-bag-form');
     
     forms.forEach(form => {
         form.addEventListener('submit', function(e) {
             e.preventDefault();
             
             const button = form.querySelector('button');
             const originalText = button.textContent;
             button.innerHTML = `
                 <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;">
                     <path d="M21 12a9 9 0 11-6.219-8.56"/>
                 </svg>
                 Ekleniyor
             `;
             button.disabled = true;
             
             const formData = new FormData(form);
             const quantity = document.querySelector('.quantity-input').value;
             formData.set('quantity', quantity);
             
             fetch(form.action, {
                 method: 'POST',
                 body: formData,
                 headers: {
                     'X-Requested-With': 'XMLHttpRequest'
                 }
             })
             .then(response => {
                 if (response.status === 401) {
                     window.location.href = '/login';
                     return;
                 }
                 return response.json();
             })
             .then(data => {
                 if (!data) return;
                 
                 const productData = {
                     title: formData.get('product_title'),
                     author: formData.get('product_author'),
                     price: formData.get('product_price'),
                     image: formData.get('product_image')
                 };
                 
                 if(data.success) {
                     showNotification(data.message, 'success', productData);
                 } else {
                     if(data.redirect) {
                         window.location.href = data.redirect;
                         return;
                     }
                     showNotification(data.message, 'error', productData);
                 }
                 
                 button.innerHTML = originalText;
                 button.disabled = false;
             })
             .catch(error => {
                 showNotification('Bir hata oluştu!', 'error');
                 button.innerHTML = originalText;
                 button.disabled = false;
             });
         });
     });
 });

 function showNotification(message, type, productData = null) {
     const existingNotification = document.querySelector('.cart-notification');
     if (existingNotification) {
         existingNotification.remove();
     }
     
     const isSuccess = type === 'success';
     const iconBg = isSuccess ? 'var(--success)' : 'var(--danger)';
     const title = isSuccess ? 'SEPETİNİZE EKLENDİ' : 'ÜRÜN SEPETE EKLENEMEDİ';
     const iconSvg = isSuccess ? 
         `<path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/>` :
         `<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>`;
     
     let productHtml = '';
     if (productData && isSuccess) {
         productHtml = `
             <div class="cart-product">
                 <div class="cart-product-image">
                     <img src="/storage/productsImages/${productData.image}" alt="${productData.title}">
                 </div>
                 <div class="cart-product-info">
                     <div class="cart-product-title">${productData.title}</div>
                     <div class="cart-product-author">${productData.author}</div>
                     <div class="cart-product-price">${parseFloat(productData.price).toFixed(2)} TL</div>
                 </div>
             </div>
         `;
     }
     
     const notification = document.createElement('div');
     notification.className = 'cart-notification';
     notification.innerHTML = `
         <div class="cart-notification-content">
             <div class="cart-icon" style="background: ${iconBg}">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     ${iconSvg}
                 </svg>
             </div>
             <div class="cart-text">
                 <div class="cart-title">${title}</div>
                 ${productHtml}
                 <div class="cart-message">${message}</div>
             </div>
             <button class="cart-close" onclick="this.parentElement.parentElement.remove()">
                 <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                 </svg>
             </button>
         </div>
         ${isSuccess ? '<div class="cart-actions"><a href="/bag" class="cart-btn cart-btn-outline">SEPETE GİT</a></div>' : ''}
     `;
     
     document.body.appendChild(notification);
     
     setTimeout(() => {
         notification.classList.add('show');
     }, 10);
     
     setTimeout(() => {
         if (notification.parentNode) {
             notification.classList.remove('show');
             setTimeout(() => {
                 if (notification.parentNode) {
                     notification.remove();
                 }
             }, 400);
         }
     }, 5000);
 }
 </script>
 </body>
 </html>