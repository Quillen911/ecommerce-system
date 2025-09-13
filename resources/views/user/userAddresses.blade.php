<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adreslerim - {{ config('app.name') }}</title>
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
        .header-content{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center}
        h1{font-size:24px;font-weight:600;letter-spacing:-0.01em;margin:0;color:var(--text)}
        .header-subtitle{font-size:14px;color:var(--muted);font-weight:500}
        
        /* Toolbar */
        .toolbar{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin:0 0 20px;background:var(--card);padding:16px 20px;border-radius:8px;box-shadow:0 1px 3px var(--shadow);border:1px solid var(--line)}
        .nav-section{display:flex;gap:6px;align-items:center}
        .btn{border:1px solid var(--primary);background:var(--primary);color:var(--text);padding:8px 16px;border-radius:6px;cursor:pointer;font-weight:500;font-size:14px;transition:all 0.15s ease;text-decoration:none;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 8px rgba(16,185,129,0.2)}
        .btn:hover{background:var(--secondary);border-color:var(--secondary);transform:translateY(-1px);box-shadow:0 4px 12px rgba(16,185,129,0.3)}
        .btn.outline{background:transparent;color:var(--primary);border:1px solid var(--border);box-shadow:none}
        .btn.outline:hover{background:var(--accent);border-color:var(--primary);color:var(--primary)}
        
        /* Cards */
        .card{background:var(--card);border:1px solid var(--line);border-radius:8px;padding:20px;box-shadow:0 1px 3px var(--shadow)}
        
        /* Account Dropdown Styles */
        .account-dropdown-container{position:relative;display:inline-block;width:120px}
        .account-dropdown-button{width:100%;padding:10px 16px;background:var(--card);border:2px solid var(--border);border-radius:6px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-size:14px;color:var(--text);transition:all 0.15s ease}
        .account-dropdown-button:hover{border-color:var(--primary)}
        .account-dropdown-button.is-open{border-color:var(--primary);box-shadow:0 0 0 3px rgba(16,185,129,0.1)}
        .account-dropdown-menu{position:absolute;top:100%;left:0;right:0;background:var(--card);border:1px solid var(--line);border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,0.1);z-index:1000;max-height:0;overflow:hidden;transition:all 0.2s ease}
        .account-dropdown-menu.is-visible{max-height:300px;overflow-y:auto}
        .account-dropdown-item{padding:10px 16px;cursor:pointer;border-bottom:1px solid var(--border);transition:background-color 0.15s ease;font-size:14px;color:var(--text)}
        .account-dropdown-item:last-child{border-bottom:none}
        .account-dropdown-item:hover{background:var(--accent)}
        .account-dropdown-item.is-selected{background:var(--primary);color:var(--text)}
        .account-dropdown-arrow{width:0;height:0;border-left:4px solid transparent;border-right:4px solid transparent;border-top:4px solid var(--text);transition:transform 0.2s ease}
        .account-dropdown-button.is-open .account-dropdown-arrow{transform:rotate(180deg)}
        
        /* Address Grid */
        .addresses-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px;margin-top:24px}
        .address-card{background:var(--card);border:1px solid var(--line);border-radius:8px;padding:20px;box-shadow:0 1px 3px var(--shadow);transition:all 0.2s ease}
        .address-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px var(--shadow)}
        .address-title{font-size:16px;font-weight:600;color:var(--text);margin:0 0 12px;display:flex;justify-content:space-between;align-items:center}
        .address-info{color:var(--muted);font-size:14px;line-height:1.5}
        .address-info p{margin:4px 0}
        .address-actions{display:flex;gap:8px;margin-top:16px}
        .btn-sm{padding:6px 12px;font-size:12px;border-radius:4px}
        .btn-danger{background:var(--danger);border-color:var(--danger)}
        .btn-danger:hover{background:#dc2626;border-color:#dc2626}
        .btn-warning{background:var(--warn);border-color:var(--warn)}
        .btn-warning:hover{background:#d97706;border-color:#d97706}
        
        /* Empty State */
        .empty-state{text-align:center;padding:60px 20px;color:var(--muted)}
        .empty-state h3{font-size:18px;margin:0 0 8px;color:var(--text)}
        .empty-state p{margin:0 0 20px}
        
        /* Modal Styles */
        .modal{display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5)}
        .modal.show{display:flex;align-items:center;justify-content:center}
        .modal-content{background:var(--card);border:1px solid var(--line);border-radius:8px;padding:24px;width:90%;max-width:500px;max-height:90vh;overflow-y:auto}
        .modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--line)}
        .modal-title{font-size:18px;font-weight:600;color:var(--text);margin:0}
        .modal-close{background:none;border:none;color:var(--muted);font-size:24px;cursor:pointer;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center}
        .modal-close:hover{color:var(--text)}
        .form-group{margin-bottom:16px}
        .form-group label{display:block;margin-bottom:6px;font-size:14px;font-weight:500;color:var(--text)}
        .form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 12px;border:2px solid var(--border);border-radius:6px;background:var(--card);color:var(--text);font-size:14px;transition:border-color 0.2s ease}
        .form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:var(--primary)}
        .form-group textarea{resize:vertical;min-height:80px}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .modal-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:16px;border-top:1px solid var(--line)}
        .btn-secondary{background:var(--accent);border:1px solid var(--border);color:var(--text)}
        .btn-secondary:hover{background:var(--hover-accent)}
    </style>
</head>
<body>
<div class="page-header">
    <div class="header-content">
        <div>
            <h1>Omnia</h1>
            <div class="header-subtitle">Hoş geldiniz, {{ auth()->user()->username }}</div>
        </div>
        <div class="nav-section">
            <a href="/bag" class="btn outline" style="color:rgb(255, 255, 255);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/>
                </svg>
                Sepetim
            </a>
            <a href="/myorders" class="btn outline" style="color:rgb(255, 255, 255);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3h18v18H3zM8 7h8M8 11h8M8 15h5"/>
                </svg>
                Siparişlerim
            </a>
            <div class="account-dropdown-container" id="accountDropdownContainer">
                <div class="account-dropdown-button" onclick="toggleAccountDropdown()" id="accountDropdownBtn">
                    <span id="accountSelectedText">Hesabım</span>
                    <div class="account-dropdown-arrow"></div>
                </div>
                <div class="account-dropdown-menu" id="accountDropdownMenu">
                    <div class="account-dropdown-item" onclick="selectAccountOption('profile', 'Hesabım', event)">Hesabım</div>
                    <div class="account-dropdown-item" onclick="selectAccountOption('addresses', 'Adreslerim', event)">Adreslerim</div>
                    <div class="account-dropdown-item" style="color:var(--danger)" onclick="selectAccountOption('logout', 'Çıkış Yap', event)">Çıkış Yap</div>
                </div>
                <input type="hidden" name="accountValue" id="accountHiddenValue" value="">
            </div>
        </div>
    </div>
</div>
<div class="shell">
    <div class="toolbar">
        <div>
            <h1>Adreslerim</h1>
            <div class="header-subtitle">Kayıtlı adreslerinizi yönetin</div>
        </div>
        <div class="nav-section">
            <button onclick="openAddressModal()" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Yeni Adres Ekle
            </button>
        </div>
    </div>

    @if($addresses->count() > 0)
        <div class="addresses-grid">
            @foreach($addresses as $address)
                <div class="address-card">
                    <div class="address-title">
                        <span>{{ $address->title }}</span>
                        <div class="address-actions">
                            <button class="btn btn-warning btn-sm" onclick="editAddress({{ $address->id }}, '{{ $address->title }}', '{{ $address->first_name }}', '{{ $address->last_name }}', '{{ $address->phone }}', '{{ $address->address_line_1 }}', '{{ $address->address_line_2 }}', '{{ $address->district }}', '{{ $address->city }}', '{{ $address->postal_code }}', '{{ $address->country }}', '{{ $address->notes }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                Düzenle
                            </button>
                            <form action="{{ route('user.addresses.destroy', $address->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bu adresi silmek istediğinizden emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3,6 5,6 21,6"/>
                                        <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"/>
                                    </svg>
                                    Sil
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="address-info">
                        <p><strong>{{ $address->first_name }} {{ $address->last_name }}</strong></p>
                        <p>{{ $address->phone }}</p>
                        <p>{{ $address->address_line_1 }}</p>
                        @if($address->address_line_2)
                            <p>{{ $address->address_line_2 }}</p>
                        @endif
                        <p>{{ $address->district }}, {{ $address->city }} {{ $address->postal_code }}</p>
                        <p>{{ $address->country }}</p>
                        @if($address->notes)
                            <p><em>{{ $address->notes }}</em></p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <h3>Henüz adres eklenmemiş</h3>
                <p>İlk adresinizi ekleyerek alışverişe başlayabilirsiniz.</p>
                <button onclick="openAddressModal()" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    İlk Adresimi Ekle
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Address Modal -->
<div id="addressModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Yeni Adres Ekle</h3>
            <button class="modal-close" onclick="closeAddressModal()">&times;</button>
        </div>
        
        <form id="addressForm" method="POST">
            @csrf
            <input type="hidden" id="addressId" name="address_id">
            <input type="hidden" id="formMethod" name="_method" value="POST">
            
            <div class="form-group">
                <label for="title">Adres Başlığı *</label>
                <input type="text" id="title" name="title" maxlength="50" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">Ad *</label>
                    <input type="text" id="first_name" name="first_name" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Soyad *</label>
                    <input type="text" id="last_name" name="last_name" maxlength="50" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="phone">Telefon *</label>
                <input type="tel" id="phone" name="phone" maxlength="15" pattern="[0-9+\-\s()]*" required>
            </div>
            
            <div class="form-group">
                <label for="address_line_1">Adres Satırı 1 *</label>
                <input type="text" id="address_line_1" name="address_line_1" maxlength="200" required>
            </div>
            
            <div class="form-group">
                <label for="address_line_2">Adres Satırı 2</label>
                <input type="text" id="address_line_2" name="address_line_2" maxlength="200">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="district">İlçe *</label>
                    <input type="text" id="district" name="district" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label for="city">Şehir *</label>
                    <input type="text" id="city" name="city" maxlength="50" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="postal_code">Posta Kodu</label>
                    <input type="text" id="postal_code" name="postal_code" maxlength="10" pattern="[0-9]*">
                </div>
                <div class="form-group">
                    <label for="country">Ülke *</label>
                    <input type="text" id="country" name="country" value="Türkiye" maxlength="50" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="notes">Notlar</label>
                <textarea id="notes" name="notes" maxlength="500" placeholder="Teslimat için özel notlar..."></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeAddressModal()">İptal</button>
                <button type="submit" class="btn" id="submitBtn">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/main.js') }}"></script>
<script>
let currentAddressId = null;

function openAddressModal() {
    currentAddressId = null;
    document.getElementById('modalTitle').textContent = 'Yeni Adres Ekle';
    document.getElementById('addressForm').action = '{{ route("user.addresses.store") }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('submitBtn').textContent = 'Kaydet';
    document.getElementById('addressForm').reset();
    document.getElementById('addressModal').classList.add('show');
}

function editAddress(id, title, firstName, lastName, phone, address1, address2, district, city, postalCode, country, notes) {
    currentAddressId = id;
    document.getElementById('modalTitle').textContent = 'Adres Düzenle';
    document.getElementById('addressForm').action = '{{ route("user.addresses.update", ":id") }}'.replace(':id', id);
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('submitBtn').textContent = 'Güncelle';
    
    // Form alanlarını doldur
    document.getElementById('addressId').value = id;
    document.getElementById('title').value = title;
    document.getElementById('first_name').value = firstName;
    document.getElementById('last_name').value = lastName;
    document.getElementById('phone').value = phone;
    document.getElementById('address_line_1').value = address1;
    document.getElementById('address_line_2').value = address2 || '';
    document.getElementById('district').value = district;
    document.getElementById('city').value = city;
    document.getElementById('postal_code').value = postalCode || '';
    document.getElementById('country').value = country;
    document.getElementById('notes').value = notes || '';
    
    document.getElementById('addressModal').classList.add('show');
}

function closeAddressModal() {
    document.getElementById('addressModal').classList.remove('show');
    document.getElementById('addressForm').reset();
    currentAddressId = null;
}

// Modal dışına tıklandığında kapat
document.getElementById('addressModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddressModal();
    }
});

// ESC tuşu ile kapat
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddressModal();
    }
});
</script>
</body>
</html>