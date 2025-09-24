<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($category) ? $category->category_title . ' - ' . config('app.name') : 'Ana Sayfa - ' . config('app.name') }}</title>
    <meta name="description" content="{{ isset($category) ? $category->category_title . ' kategorisindeki en iyi ürünler - ' . config('app.name') : 'En iyi ürünler ve fırsatlar - ' . config('app.name') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Güvenli JSON-LD üretimi --}}
    @if(isset($category) || (isset($products) && count($products ?? []) > 0))
        @php
            $isCat = isset($category);
            $listName = $isCat ? $category->category_title : 'Tüm Ürünler';

            // items
            $items = [];
            foreach (($products ?? []) as $index => $product) {
                $isArray = is_array($product);
                $items[] = [
                    '@type'    => 'Product',
                    'position' => $index + 1,
                    'name'     => $isArray ? $product['title']  : $product->title,
                    'description' => $isArray ? ($product['author'] ?? '') : ($product->author ?? ''),
                    'offers'   => [
                        '@type'         => 'Offer',
                        'price'         => (string)($isArray ? ($product['list_price'] ?? 0) : ($product->list_price ?? 0)),
                        'priceCurrency' => 'TRY',
                    ],
                    'brand'    => [
                        '@type' => 'Brand',
                        'name'  => $isArray ? ($product['store_name'] ?? '') : ($product->store->name ?? ''),
                    ],
                ];
            }

            $ld = [
                '@context'   => 'https://schema.org',
                '@type'      => 'CollectionPage',
                'name'       => $listName,
                'description'=> $isCat ? ($category->category_title.' kategorisindeki en iyi ürünler')
                                       : 'En iyi ürünler ve fırsatlar',
                'url'        => url()->current(),
                'mainEntity' => [
                    '@type'          => 'ItemList',
                    'numberOfItems'  => count($products ?? []),
                    'itemListElement'=> $items,
                ],
            ];

            if ($isCat) {
                $ld['breadcrumb'] = [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type'    => 'ListItem',
                            'position' => 1,
                            'name'     => 'Ana Sayfa',
                            'item'     => route('main'),
                        ],
                        [
                            '@type'    => 'ListItem',
                            'position' => 2,
                            'name'     => $category->category_title,
                            'item'     => url()->current(),
                        ],
                    ],
                ];
            }
        @endphp
        <script type="application/ld+json">
            {!! json_encode($ld, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
    
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
        
        /* Header */
        .page-header{background:var(--card);border-bottom:1px solid var(--line);padding:20px 0;margin:-24px -20px 24px;box-shadow:0 4px 20px var(--shadow)}
        .header-content{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center;gap:20px}
        .header-left{flex:1;min-width:200px}
        .header-right{flex:1;min-width:200px;display:flex;justify-content:flex-end;gap:12px}
        h1{font-size:24px;font-weight:600;letter-spacing:-0.01em;margin:0;color:var(--text)}
        .header-subtitle{font-size:14px;color:var(--muted);font-weight:500}
        
        /* Toolbar */
        .toolbar{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin:0 0 20px;background:var(--card);padding:16px 20px;border-radius:8px;box-shadow:0 1px 3px var(--shadow);border:1px solid var(--line)}
        .nav-section{display:flex;gap:6px;align-items:center}
        .btn{border:1px solid var(--primary);background:var(--primary);color:var(--text);padding:8px 16px;border-radius:6px;cursor:pointer;font-weight:500;font-size:14px;transition:all 0.15s ease;text-decoration:none;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 8px rgba(16,185,129,0.2)}
        .btn:hover{background:var(--secondary);border-color:var(--secondary);transform:translateY(-1px);box-shadow:0 4px 12px rgba(16,185,129,0.3)}
        .btn.outline{background:transparent;color:var(--primary);border:1px solid var(--border);box-shadow:none}
        .btn.outline:hover{background:var(--accent);border-color:var(--primary);color:var(--primary)}
        .btn.primary{background:var(--primary);color:var(--text);border:1px solid var(--primary);box-shadow:0 2px 8px rgba(16,185,129,0.2)}
        .btn.primary:hover{background:var(--secondary);border-color:var(--secondary);transform:translateY(-1px);box-shadow:0 4px 12px rgba(16,185,129,0.3)}
        
        /* Notices */
        .notice{padding:12px 16px;border:1px solid var(--line);margin:0 0 16px;border-radius:6px;background:var(--card);font-size:14px}
        .notice.success{border-color:var(--success);background:rgba(0,198,174,0.1);color:var(--success)}
        .notice.error{border-color:var(--danger);background:rgba(255,107,107,0.1);color:var(--danger)}
        
        /* Cards */
        .card{background:var(--card);border:1px solid var(--line);border-radius:8px;padding:20px;box-shadow:0 1px 3px var(--shadow)}
        
        /* Filters */
        .filters{display:grid;grid-template-columns:2fr 0.8fr 0.8fr 0.8fr 1fr auto;gap:8px;align-items:end}
        .field{display:flex;flex-direction:column;gap:8px}
        .field label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;font-weight:600}
        .field input,.field select{padding:8px 12px;border:2px solid var(--border);border-radius:8px;transition:all 0.2s ease;background:var(--card);color:var(--text);font-size:13px;font-weight:500;-webkit-appearance:none;-moz-appearance:none;appearance:none}
        .field select{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23B0B0B0' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");background-position:right 8px center;background-repeat:no-repeat;background-size:16px}
        .field input:hover,.field select:hover{border-color:var(--primary)}
        .field input:focus,.field select:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(16,185,129,0.1)}
        .field input::placeholder{color:var(--muted)}
        
        /* Filter Actions */
        .filter-actions{display:flex;gap:4px;flex-wrap:wrap}
        
        /* Search Group */
        .search-group{display:flex;gap:4px;align-items:end}
        .btn-sm{padding:8px 12px;font-size:11px;border-radius:6px}
        
        /* Custom Dropdown */
        .custom-dropdown{position:relative;display:inline-block;width:200px}
        .dropdown-btn{
            width:100%;padding:10px 16px;background:var(--card);border:2px solid var(--border);
            border-radius:6px;cursor:pointer;display:flex;justify-content:space-between;
            align-items:center;font-size:14px;color:var(--text);transition:all 0.15s ease
        }
        .dropdown-btn:hover{border-color:var(--primary)}
        .dropdown-btn.active{border-color:var(--primary);box-shadow:0 0 0 3px rgba(16,185,129,0.1)}
        .dropdown-content{
            position:absolute;top:100%;left:0;right:0;background:var(--card);
            border:1px solid var(--line);border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,0.1);
            z-index:100;max-height:0;overflow:hidden;transition:all 0.2s ease
        }
        .dropdown-content.show{max-height:300px;overflow-y:auto}
        .dropdown-item{
            padding:10px 16px;cursor:pointer;border-bottom:1px solid var(--border);
            transition:background-color 0.15s ease;font-size:14px;color:var(--text)
        }
        .dropdown-item:last-child{border-bottom:none}
        .dropdown-item:hover{background:var(--accent)}
        .dropdown-item.selected{background:var(--primary);color:var(--text)}
        .dropdown-arrow{
            width:0;height:0;border-left:4px solid transparent;border-right:4px solid transparent;
            border-top:4px solid var(--text);transition:transform 0.2s ease
        }
        .dropdown-btn.active .dropdown-arrow{transform:rotate(180deg)}
        
        /* Account Dropdown Styles */
        .account-dropdown-container{position:relative;display:inline-block;width:120px}
        .account-dropdown-button{width:100%;padding:10px 16px;background:var(--card);border:2px solid var(--border);border-radius:6px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-size:14px;color:var(--text);transition:all 0.2s ease;outline:none}
        .account-dropdown-button:hover{border-color:var(--primary)}
        .account-dropdown-button:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(16,185,129,0.1)}
        .account-dropdown-button.is-open{border-color:var(--primary);box-shadow:0 0 0 3px rgba(16,185,129,0.1)}
        .account-dropdown-menu{position:absolute;top:100%;left:0;right:0;background:var(--card);border:1px solid var(--line);border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,0.1);z-index:1000;opacity:0;transform:translateY(-8px);transition:all 0.2s ease;pointer-events:none}
        .account-dropdown-menu.is-visible{opacity:1;transform:translateY(0);pointer-events:auto}
        .account-dropdown-item{padding:10px 16px;cursor:pointer;border-bottom:1px solid var(--border);transition:all 0.15s ease;font-size:14px;color:var(--text);outline:none}
        .account-dropdown-item:last-child{border-bottom:none}
        .account-dropdown-item:hover{background:var(--accent)}
        .account-dropdown-item:focus{background:var(--accent);box-shadow:inset 0 0 0 2px var(--primary)}
        .account-dropdown-item.is-selected{background:var(--primary);color:var(--text)}
        .account-dropdown-arrow{width:0;height:0;border-left:4px solid transparent;border-right:4px solid transparent;border-top:4px solid var(--text);transition:transform 0.2s ease}
        .account-dropdown-button.is-open .account-dropdown-arrow{transform:rotate(180deg)}
        
        /* Header Search Styles */
        .header-search{flex:3;max-width:900px;margin:0 20px;order:2;min-width:500px;position:relative;}
        .search-input-group{display:flex;background:var(--card);border:2px solid var(--border);border-radius:8px;overflow:hidden;transition:all 0.2s ease;width:100%}
        .search-input-group:focus-within{border-color:var(--primary);box-shadow:0 0 0 3px rgba(16,185,129,0.1)}
        .search-input{flex:1;padding:12px 18px;background:transparent;border:none;color:var(--text);font-size:14px;outline:none;min-width:0}
        .search-input::placeholder{color:var(--muted)}
        .search-btn{padding:12px 18px;background:var(--primary);border:none;color:var(--text);cursor:pointer;transition:all 0.2s ease;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .search-btn:hover{background:var(--secondary)}
        
        /* Header Autocomplete Styles */
        .header-search .autocomplete-container{z-index:1001;top:100%;left:0;right:0;margin-top:4px}
        
        /* Ürün Grid */
        .products-grid{
            display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;margin-top:24px
        }
        .product-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .product-link:hover {
            text-decoration: none;
            color: inherit;
        }
        .product-card{
            background:var(--card);border:1px solid var(--line);border-radius:8px;overflow:hidden;
            transition:all 0.2s ease;position:relative;box-shadow:0 1px 3px var(--shadow)
        }
        .product-card:hover{
            box-shadow:0 8px 24px rgba(0,0,0,0.3);transform:translateY(-4px);
            border-color:var(--primary);
            background:var(--gray-50);
        }
        .product-image{
            width:100%;height:220px;background:var(--gray-50);position:relative;overflow:hidden;
            display:flex;align-items:center;justify-content:center
        }
        .product-image img{
            width:100%;height:100%;object-fit:contain;padding:8px;background:white
        }
        .product-info{padding:16px}
        .product-title{
            font-size:15px;font-weight:600;color:var(--text);margin-bottom:4px;
            line-height:1.4;height:2.8em;display:-webkit-box;-webkit-line-clamp:2;
            -webkit-box-orient:vertical;overflow:hidden
        }
        .product-author{
            font-size:13px;color:var(--muted);margin-bottom:4px;font-weight:400
        }
        .product-store{
            font-size:11px;color:var(--primary);font-weight:500;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px
        }
        .product-price{
            font-size:16px;font-weight:600;color:var(--price-color);margin-bottom:12px
        }
        .product-actions{padding:0 16px 16px}
        .add-btn{
            width:100%;padding:10px 16px;background:var(--primary);color:var(--text);border:none;
            border-radius:6px;font-size:14px;font-weight:500;cursor:pointer;
            transition:all 0.15s ease;text-transform:none;box-shadow:0 2px 8px rgba(16,185,129,0.2)
        }
        .add-btn:hover{background:var(--secondary);transform:translateY(-1px);box-shadow:0 4px 12px rgba(16,185,129,0.3)}
        .add-btn:disabled{background:var(--gray-100);color:var(--muted);cursor:not-allowed;box-shadow:none}
        
        /* Out of Stock Overlay */
        .stock-overlay{
            position:absolute;top:0;left:0;right:0;bottom:0;
            background:rgba(255,255,255,0.95);display:flex;flex-direction:column;
            align-items:center;justify-content:center;color:var(--danger);
            font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em
        }
        
        /* Responsive */
        @media (max-width:1024px){
            .products-grid{grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px}
            .filters{grid-template-columns:1fr;gap:12px}
            .filter-actions{grid-column:1;justify-content:stretch;gap:8px;flex-direction:column}
            .filter-actions .btn{width:100%}
        }
        @media (max-width:768px){
            .shell{padding:24px 16px 60px}
            .page-header{margin:-24px -16px 24px;padding:32px 0}
            h1{font-size:24px}
            .toolbar{padding:16px 20px;flex-direction:column;align-items:stretch}
            .nav-section{justify-content:center;flex-direction:column;gap:12px}
            .header-search{max-width:100%;margin:0}
            .search-group{flex-direction:column;gap:8px}
            .search-group .btn{margin-top:0!important}
            .products-grid{grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px}
            .product-image{height:200px}
            .product-info{padding:16px}
            .product-title{font-size:14px}
            .product-price{font-size:16px}
            .card{padding:20px}
        }
        
        /* Utilities */
        .actions{display:flex;gap:12px;flex-wrap:wrap;align-items:center}
        .right{justify-content:flex-end}
        .center{justify-content:center}
        .muted{color:var(--muted);font-size:13px;font-weight:500}
        .section-title{font-size:20px;font-weight:700;color:var(--text);margin:32px 0 16px;letter-spacing:-0.02em}
        
        /* Empty State */
        .empty-state{
            text-align:center;padding:64px 32px;background:var(--card);
            border-radius:20px;border:2px dashed var(--line);margin:32px 0
        }
        .empty-state svg{margin-bottom:16px;opacity:0.5}
        .empty-state h3{font-size:18px;font-weight:600;color:var(--text);margin-bottom:8px}
        .empty-state p{color:var(--muted);margin-bottom:0}
        
        /* Sepet Bildirimi */
        .cart-notification{
            position:fixed;top:32px;right:-420px;width:380px;background:var(--card);
            border:1px solid var(--line);border-radius:20px;box-shadow:0 16px 64px rgba(0,0,0,0.15);
            z-index:1000;transition:right 0.4s cubic-bezier(0.4,0,0.2,1);font-family:inherit;
            backdrop-filter:blur(8px)
        }
        .cart-notification.show{right:32px}
        .cart-notification-content{
            padding:24px;display:flex;align-items:flex-start;gap:16px;
            border-bottom:1px solid var(--line)
        }
        .cart-icon{
            width:48px;height:48px;background:linear-gradient(135deg, var(--success) 0%, #047857 100%);
            border-radius:16px;display:flex;align-items:center;justify-content:center;
            color:#fff;flex-shrink:0;box-shadow:0 4px 16px rgba(5,150,105,0.3)
        }
        .cart-text{flex:1}
        .cart-title{
            font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;
            color:var(--text);margin-bottom:8px
        }
        .cart-message{font-size:14px;color:var(--muted);font-weight:500}
        .cart-close{
            background:var(--hover);border:none;padding:8px;border-radius:12px;cursor:pointer;
            color:var(--muted);transition:all 0.2s ease;flex-shrink:0
        }
        .cart-close:hover{background:var(--line);transform:scale(1.1)}
        .cart-actions{padding:20px 24px 24px}
        .cart-btn{
            display:inline-block;padding:12px 20px;border-radius:12px;text-decoration:none;
            font-size:13px;font-weight:600;letter-spacing:0.01em;
            transition:all 0.2s cubic-bezier(0.4,0,0.2,1);text-align:center;width:100%
        }
        .cart-btn-outline{
            background:transparent;color:var(--accent);border:2px solid var(--line);
            box-shadow:0 2px 8px var(--shadow)
        }
        .cart-btn-outline:hover{
            background:var(--accent);color:#fff;transform:translateY(-2px);
            box-shadow:0 8px 24px rgba(15,23,42,0.2)
        }
        
        /* Product Info in Notification */
        .cart-product{
            display:flex;align-items:center;gap:16px;margin:12px 0;
            padding:16px;background:var(--bg);border-radius:16px;border:1px solid var(--line)
        }
        .cart-product-image{width:64px;height:64px;flex-shrink:0;border-radius:12px;overflow:hidden}
        .cart-product-image img{
            width:100%;height:100%;object-fit:cover;border:1px solid var(--line)
        }
        .cart-product-info{flex:1;min-width:0}
        .cart-product-title{
            font-size:14px;font-weight:600;color:var(--text);margin-bottom:4px;
            overflow:hidden;text-overflow:ellipsis;white-space:nowrap
        }
        .cart-product-author{font-size:12px;color:var(--muted);margin-bottom:4px;font-weight:500}
        .cart-product-price{font-size:14px;font-weight:700;color:var(--success)}
        
        @media (max-width:480px){
            .cart-notification{width:calc(100vw - 40px);right:-100%}
            .cart-notification.show{right:20px}
        }
        
        @keyframes spin{
            from{transform:rotate(0deg)}
            to{transform:rotate(360deg)}
        }
        
        /* Otomatik Tamamlama Stilleri */
        .autocomplete-container {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--card);
            border: 1px solid var(--line);
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .autocomplete-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid var(--line);
            transition: background-color 0.15s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .autocomplete-item:hover,
        .autocomplete-item.selected {
            background: var(--accent);
            color: #EDEDED;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-product-image {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border-radius: 6px;
            overflow: hidden;
            background: var(--gray-50);
        }

        .autocomplete-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .autocomplete-product-info {
            flex: 1;
            min-width: 0;
        }

        .autocomplete-product-title {
            font-size: 14px;
            font-weight: 500;
            color: inherit;
            margin-bottom: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .autocomplete-product-author {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 2px;
        }

        .autocomplete-product-store {
            font-size: 12px;
            color: var(--primary);
            font-weight: 500;
        }

        .autocomplete-product-price {
            font-size: 14px;
            font-weight: 600;
            color: var(--price-color);
            flex-shrink: 0;
        }

        .search-group {
            position: relative;
        }

        /* Loading animasyonu */
        .autocomplete-loading {
            padding: 16px;
            text-align: center;
            color: var(--muted);
            font-size: 14px;
        }

        .autocomplete-loading::after {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid var(--line);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }

        .category-nav {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 8px;
        padding: 16px 20px;
        margin: 0 0 20px;
        box-shadow: 0 1px 3px var(--shadow);
        overflow-x: auto;
        white-space: nowrap;
    }

    .category-nav-content {
        display: flex;
        gap: 24px;
        align-items: center;
        min-width: max-content;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .category-nav-item {
        display: inline-block;
        flex-shrink: 0;
    }

    .category-nav-item a {
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
        white-space: nowrap;
        display: inline-block;
    }

.category-nav-item.featured a {
    color: var(--primary);
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid var(--primary);
    font-weight: 600;
}

    .category-nav-item.featured a:hover {
        background: rgba(16, 185, 129, 0.2);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .category-nav-item.regular a {
        color: var(--muted);
        background: transparent;
        border: 1px solid transparent;
    }

    .category-nav-item.regular a:hover {
        background: var(--accent);
        color: var(--text);
        border-color: var(--border);
        transform: translateY(-1px);
    }

    /* Active state için */
    .category-nav-item.active a {
        color: var(--primary);
        background: var(--hover);
        border-color: var(--primary);
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .category-nav {
            padding: 12px 16px;
            margin: 0 0 16px;
        }
        
        .category-nav-content {
            gap: 16px;
        }
        
        .category-nav-item a {
            font-size: 13px;
            padding: 6px 10px;
        }
    }
    .autocomplete-container {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--card);
        border: 1px solid var(--line);
        border-top: none;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        display: none;
    }

    .autocomplete-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid var(--line);
        transition: background-color 0.15s ease;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .autocomplete-item:hover,
    .autocomplete-item.selected {
        background: var(--accent);
        color: #EDEDED;
    }

    .autocomplete-item:last-child {
        border-bottom: none;
    }

    .autocomplete-product-image {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border-radius: 6px;
        overflow: hidden;
        background: var(--gray-50);
    }

    .autocomplete-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .autocomplete-product-info {
        flex: 1;
        min-width: 0;
    }

    .autocomplete-product-title {
        font-size: 14px;
        font-weight: 500;
        color: inherit;
        margin-bottom: 2px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .autocomplete-product-author {
        font-size: 12px;
        color: var(--muted);
        margin-bottom: 2px;
    }

    .autocomplete-product-store {
        font-size: 12px;
        color: var(--primary);
        font-weight: 500;
    }

    .autocomplete-product-price {
        font-size: 14px;
        font-weight: 600;
        color: var(--price-color);
        flex-shrink: 0;
    }

    .search-group {
        position: relative;
    }

    /* Loading animasyonu */
    .autocomplete-loading {
        padding: 16px;
        text-align: center;
        color: var(--muted);
        font-size: 14px;
    }

    .autocomplete-loading::after {
        content: '';
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid var(--line);
        border-radius: 50%;
        border-top-color: var(--primary);
        animation: spin 1s linear infinite;
        margin-left: 8px;
    }
    </style>
</head>
<body>
<div class="page-header">
    <div class="header-content">
        <div class="header-left">
            <h1><a href="{{ route('main') }}" style="text-decoration: none; color: inherit;">Omnia</a></h1>
            <div class="header-subtitle"> {{ auth()->user() ? 'Hoş Geldiniz, ' . auth()->user()->username : 'Hoş Geldiniz' }}</div>
        </div>
        <div class="nav-section">
            <form action="{{ route('search') }}" method="GET" class="header-search">
                @csrf
                <div class="search-input-group">
                    <input type="text" name="q" placeholder="Ürün ara..." value="{{ $query ?? request('q') }}" autocomplete="off" class="search-input" id="headerSearchInput">
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="size" value="12">
                    <button type="submit" class="search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                    </button>
                </div>
                <div class="autocomplete-container" id="headerAutocompleteContainer"></div>
            </form>
        </div>
        <div class="header-right">
            @auth('user_web')
                <a href="/bag" class="btn outline" style="color:rgb(255, 255, 255);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/>
                    </svg>
                    Sepetim
                </a>
                <div class="account-dropdown-container" id="accountDropdownContainer">
                    <div class="account-dropdown-button" 
                         role="button" 
                         aria-haspopup="true" 
                         aria-expanded="false" 
                         tabindex="0"
                         id="accountDropdownBtn">
                        <span id="accountSelectedText">Hesabım</span>
                        <div class="account-dropdown-arrow"></div>
                    </div>
                    <div class="account-dropdown-menu" 
                         role="menu" 
                         id="accountDropdownMenu">
                        <div class="account-dropdown-item" 
                             role="menuitem" 
                             tabindex="0"
                             data-value="profile">Hesabım</div>
                        <div class="account-dropdown-item" 
                             role="menuitem" 
                             tabindex="0"
                             data-value="addresses"
                             onclick="window.location.href='{{ route('user.addresses') }}'">Adreslerim</div>
                        <div class="account-dropdown-item" 
                             role="menuitem" 
                             tabindex="0"
                             data-value="orders"
                             onclick="window.location.href='{{ route('myorders') }}'">Siparişlerim</div>
                        <div class="account-dropdown-item" 
                             role="menuitem" 
                             tabindex="0"
                             data-value="logout" 
                             style="color:var(--danger)">Çıkış Yap</div>
                    </div>
                    <input type="hidden" name="accountValue" id="accountHiddenValue" value="">
                </div>
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
                    Kaydol
                </a>
            @endauth
        </div>
    </div>
</div>

<div class="shell">

    @if(session('success')) <div class="notice success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="notice error">{{ session('error') }}</div> @endif

    @if(isset($category))
    <nav aria-label="breadcrumb" style="margin-bottom: 20px;">
        <ol style="display: flex; gap: 8px; align-items: center; list-style: none; margin: 0; padding: 0; font-size: 14px; color: var(--muted);">
            <li><a href="{{ route('main') }}" style="color: var(--primary); text-decoration: none;">Ana Sayfa</a></li>
            <li style="color: var(--muted);">›</li>
            <li style="color: var(--text); font-weight: 500;">{{ $category->category_title }}</li>
        </ol>
    </nav>
    @endif

    <nav class="category-nav" aria-label="Kategoriler">
        <ul class="category-nav-content">
            <li class="category-nav-item {{ request()->routeIs('main') ? 'active' : 'featured' }}">
                <a href="{{ route('main') }}" aria-current="{{ request()->routeIs('main') ? 'page' : 'false' }}">
                    TÜM ÜRÜNLER
                </a>
            </li>
            @foreach($categories as $cat)
                @php $isActive = request()->routeIs('category.filter') && request('category_slug') === $cat->category_slug; @endphp
                <li class="category-nav-item {{ $isActive ? 'active' : 'regular' }}">
                    <a href="{{ route('category.filter', ['category_slug' => $cat->category_slug]) }}"
                       aria-current="{{ $isActive ? 'page' : 'false' }}">
                        {{ $cat->category_title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    @if(empty($query))
        <div class="card" style="margin-top:12px">
            <form action="{{ route('sorting') }}" method="GET" class="actions" style="justify-content:flex-start;align-items:center">
                @csrf
                <div class="custom-dropdown">
                    <div class="dropdown-btn" onclick="toggleDropdown()" id="sortingDropdown">
                        <span id="selectedOption">Önerilen</span>
                        <div class="dropdown-arrow"></div>
                    </div>
                    <div class="dropdown-content" id="dropdownContent">
                        <div class="dropdown-item selected" onclick="selectOption('', 'Önerilen')">Önerilen</div>
                        <div class="dropdown-item" onclick="selectOption('price_asc', 'En Düşük Fiyat')">En Düşük Fiyat</div>
                        <div class="dropdown-item" onclick="selectOption('price_desc', 'En Yüksek Fiyat')">En Yüksek Fiyat</div>
                        <div class="dropdown-item" onclick="selectOption('stock_quantity_asc', 'En Az Stok')">En Az Stok</div>
                        <div class="dropdown-item" onclick="selectOption('stock_quantity_desc', 'En Çok Stok')">En Çok Stok</div>
                    </div>
                    <input type="hidden" name="sorting" id="sortingValue" value="">
                </div>
                <button class="btn" type="submit" style="margin-left:16px">Sırala</button>
            </form>
        </div>
    @endif

    <p class="muted" style="display:none">Gösterilen Ürün Sayısı: {{ count($products ?? []) }}</p>

    @if(!empty($query))
        <div class="muted" style="margin-top:10px"><strong>Arama Sonuçları</strong></div>
        
        <div class="card" style="margin-top:12px">
            <form action="{{ route('search') }}" method="GET" class="filters">
                @csrf
                <input type="hidden" name="q" value="{{ $query }}">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="size" value="12">
                
                <div class="field">
                    <label for="category_title">Kategori</label>
                    <select id="category_title" name="category_title">
                        <option value="">Seçiniz</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_title }}" {{ request('category_title') == $category->category_title ? 'selected' : '' }}>
                                {{ $category->category_title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="min_price">Min Fiyat</label>
                    <input id="min_price" type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                </div>
                <div class="field">
                    <label for="max_price">Max Fiyat</label>
                    <input id="max_price" type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                </div>
                <div class="field">
                    <label for="sorting">Sıralama</label>
                    <select id="sorting" name="sorting">
                        <option value="">Seçiniz</option>
                        <option value="price_asc">Fiyata Göre Artan</option>
                        <option value="price_desc">Fiyata Göre Azalan</option>
                        <option value="stock_quantity_asc">Stoka Göre Artan</option>
                        <option value="stock_quantity_desc">Stoka Göre Azalan</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button class="btn outline btn-sm" type="button" onclick="resetFilters()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-5h5"/>
                        </svg>
                        Sıfırla
                    </button>
                    <button class="btn btn-sm" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                        Uygula
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if(!empty($products) && count($products) > 0)
        <div class="products-grid">
            @foreach($products as $p)
                @php
                    $isOutOfStock = (is_array($p) ? $p['stock_quantity'] : $p->stock_quantity) <= 0;
                    $imageUrl = is_array($p) 
                        ? '/storage/productsImage/' . $p['images'][0]
                        : $p->first_image;
                @endphp
                
                <div class="product-card">
                    <a href="{{ route('product.detail', is_array($p) ? $p['slug'] : $p->slug) }}" class="product-link">
                        <div class="product-image">
                            <img src="{{ $imageUrl }}" 
                                alt="{{ is_array($p) ? $p['title'] : $p->title }}"
                                loading="lazy"
                                width="240" 
                                height="220">
                            @if($isOutOfStock)
                                <div class="stock-overlay">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:4px">
                                        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                    </svg>
                                    Stokta Yok
                                </div>
                            @endif
                        </div>
                        
                        <div class="product-info">
                            <div class="product-title">{{ is_array($p) ? $p['title'] : $p->title }}</div>
                            <div class="product-author">{{ is_array($p) ? $p['author'] : $p->author }}</div>
                            <div class="product-store">{{ is_array($p) ? $p['store_name'] : $p->store->name }}</div>
                            <div class="product-price">{{ number_format(is_array($p) ? $p['list_price'] : $p->list_price, 2) }} TL</div>
                        </div>
                    </a>
                    <div class="product-actions">
                        <form action="{{ route('add') }}" method="POST" style="margin:0" class="add-to-bag-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ is_array($p) ? $p['id'] : $p->id }}">
                            <input type="hidden" name="product_title" value="{{ is_array($p) ? $p['title'] : $p->title }}">
                            <input type="hidden" name="product_author" value="{{ is_array($p) ? $p['author'] : $p->author }}">
                            <input type="hidden" name="product_price" value="{{ is_array($p) ? $p['list_price'] : $p->list_price }}">
                            <input type="hidden" name="product_image" value="{{ basename($imageUrl) }}">
                            <button class="add-btn" type="submit" {{ $isOutOfStock ? 'disabled' : '' }}>
                                {{ $isOutOfStock ? 'STOKTA YOK' : 'SEPETE EKLE' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/>
            </svg>
            <h3>Ürün Bulunamadı</h3>
            <p>Aradığınız kriterlere uygun ürün bulunamadı. Filtreleri değiştirmeyi deneyin.</p>
        </div>
    @endif

    <div class="actions center" style="margin-top:32px">
        <a href="{{ route('main') }}" class="btn outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-5h5"/>
            </svg>
            Tüm Ürünleri Göster
        </a>
    </div>
</div>

<script src="{{ asset('js/main.js') }}"></script>
<script>
// Header arama barı için autocomplete
document.addEventListener('DOMContentLoaded', function() {
    const headerSearchInput = document.getElementById('headerSearchInput');
    const headerAutocompleteContainer = document.getElementById('headerAutocompleteContainer');
    
    if (!headerSearchInput || !headerAutocompleteContainer) return;
    
    let debounceTimer;
    let selectedIndex = -1;
    let suggestions = [];
    let isLoading = false;
    
    // Debounce fonksiyonu
    function debounce(func, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(func, delay);
    }
    
    // Autocomplete verilerini getir
    async function fetchAutocomplete(query) {
        if (query.length < 2) {
            hideAutocomplete();
            return;
        }
        
        isLoading = true;
        showLoadingState();
        
        try {
            const response = await fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Autocomplete response:', data); // Debug için
            suggestions = data.data?.products || data.products || [];
            
            if (suggestions.length > 0) {
                showAutocomplete();
            } else {
                showNoResults();
            }
        } catch (error) {
            console.error('Autocomplete error:', error);
            console.error('Query was:', query);
            showError('Arama sırasında hata oluştu: ' + error.message);
        } finally {
            isLoading = false;
        }
    }
    
    // Loading durumunu göster
    function showLoadingState() {
        headerAutocompleteContainer.innerHTML = '<div class="autocomplete-loading">Aranıyor...</div>';
        headerAutocompleteContainer.style.display = 'block';
    }
    
    // Sonuç bulunamadı mesajı
    function showNoResults() {
        headerAutocompleteContainer.innerHTML = '<div class="autocomplete-loading">Sonuç bulunamadı</div>';
        headerAutocompleteContainer.style.display = 'block';
    }
    
    // Hata mesajı
    function showError(message = 'Bir hata oluştu') {
        headerAutocompleteContainer.innerHTML = `<div class="autocomplete-loading">${message}</div>`;
        headerAutocompleteContainer.style.display = 'block';
    }
    
    // Otomatik tamamlama listesini göster
    function showAutocomplete() {
        headerAutocompleteContainer.innerHTML = '';
        
        suggestions.forEach((product, index) => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            
            const imageUrl = product.images && product.images.length > 0 
                ? `/storage/productsImage/${product.images[0]}`
                : '';
            
            item.innerHTML = `
                <div class="autocomplete-product-image">
                    ${imageUrl ? `<img src="${imageUrl}" alt="${product.title}" onerror="this.style.display='none'">` : ''}
                </div>
                <div class="autocomplete-product-info">
                    <div class="autocomplete-product-title">
                        ${highlightQuery(product.title, headerSearchInput.value)}
                    </div>
                    <div class="autocomplete-product-author">${product.author || ''}</div>
                    <div class="autocomplete-product-store">${product.store_name || ''}</div>
                </div>
                <div class="autocomplete-product-price">
                    ${parseFloat(product.list_price || 0).toFixed(2)} TL
                </div>
            `;
            
            item.addEventListener('click', () => {
                selectSuggestion(product);
            });
            
            item.addEventListener('mouseenter', () => {
                selectedIndex = index;
                updateSelection();
            });
            
            headerAutocompleteContainer.appendChild(item);
        });
        
        headerAutocompleteContainer.style.display = 'block';
        selectedIndex = -1;
    }
    
    // Otomatik tamamlama listesini gizle
    function hideAutocomplete() {
        headerAutocompleteContainer.style.display = 'none';
        selectedIndex = -1;
    }
    
    // Seçili öğeyi vurgula
    function updateSelection() {
        const items = headerAutocompleteContainer.querySelectorAll('.autocomplete-item');
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
    }
    
    // Öneriyi seç
    function selectSuggestion(product) {
        headerSearchInput.value = product.title;
        hideAutocomplete();
        // Formu submit et
        headerSearchInput.closest('form').submit();
    }
    
    // Query'yi vurgula
    function highlightQuery(text, query) {
        if (!query) return text;
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<strong>$1</strong>');
    }
    
    // Input event listener
    headerSearchInput.addEventListener('input', function() {
        const query = this.value.trim();
        debounce(() => fetchAutocomplete(query), 300);
    });
    
    // Klavye navigasyonu
    headerSearchInput.addEventListener('keydown', function(e) {
        if (headerAutocompleteContainer.style.display === 'none') return;
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, suggestions.length - 1);
                updateSelection();
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection();
                break;
                
            case 'Enter':
                e.preventDefault();
                if (selectedIndex >= 0 && suggestions[selectedIndex]) {
                    selectSuggestion(suggestions[selectedIndex]);
                } else {
                    this.closest('form').submit();
                }
                break;
                
            case 'Escape':
                hideAutocomplete();
                break;
        }
    });
    
    // Dışarı tıklandığında gizle
    document.addEventListener('click', function(e) {
        if (!headerSearchInput.contains(e.target) && !headerAutocompleteContainer.contains(e.target)) {
            hideAutocomplete();
        }
    });
});

// Filtreleri sıfırla fonksiyonu
function resetFilters() {
    document.getElementById('category_title').value = '';
    document.getElementById('min_price').value = '';
    document.getElementById('max_price').value = '';
    document.getElementById('sorting').value = '';
    
    // Formu submit et
    const form = document.querySelector('.filters');
    if (form) {
        form.submit();
    }
}
</script>
</body>
</html>