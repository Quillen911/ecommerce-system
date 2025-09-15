<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim - Omnia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #0F0F0F;
            --text: #FFFFFF;
            --muted: #B0B0B0;
            --line: #2A2A2A;
            --accent: #3A3A3A;
            --success: #00D4AA;
            --danger: #FF4444;
            --card: #1A1A1A;
            --primary: #00D4AA;
            --shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        * { 
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
        }
        
        body {
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .bg-white {
            background: var(--card) !important;
            border: 1px solid var(--line) !important;
            border-radius: 12px !important;
            box-shadow: var(--shadow) !important;
        }
        
        .text-gray-800, .text-gray-700 {
            color: var(--text) !important;
        }
        
        .text-gray-600 {
            color: var(--muted) !important;
        }
        
        .text-gray-500 {
            color: var(--muted) !important;
        }
        
        .border-gray-300 {
            border-color: var(--line) !important;
        }
        
        .bg-gray-100 {
            background: var(--accent) !important;
            border-radius: 8px !important;
        }
        
        .bg-gray-50 {
            background: var(--accent) !important;
            border-radius: 8px !important;
        }
        
        .bg-blue-50 {
            background: rgba(0, 212, 170, 0.1) !important;
            border: 1px solid var(--primary) !important;
            border-radius: 8px !important;
        }
        
        .text-blue-800, .text-blue-600 {
            color: var(--primary) !important;
        }
        
        .bg-green-50 {
            background: rgba(0, 212, 170, 0.1) !important;
            border-radius: 8px !important;
        }
        
        .text-green-600 {
            color: var(--success) !important;
        }
        
        .bg-purple-50 {
            background: rgba(139, 92, 246, 0.1) !important;
            border-radius: 8px !important;
        }
        
        .text-purple-600 {
            color: #8b5cf6 !important;
        }
        
        .bg-green-100 {
            background: rgba(0, 212, 170, 0.1) !important;
            border: 1px solid var(--success) !important;
            color: var(--success) !important;
            border-radius: 8px !important;
        }
        
        .bg-red-100 {
            background: rgba(255, 68, 68, 0.1) !important;
            border: 1px solid var(--danger) !important;
            color: var(--danger) !important;
            border-radius: 8px !important;
        }
        
        .text-red-600 {
            color: var(--danger) !important;
        }
        
        .hover\:bg-gray-50:hover {
            background: var(--accent) !important;
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
        
        .hover\:text-gray-800:hover {
            color: var(--text) !important;
        }
        
        .hover\:text-red-800:hover {
            color: var(--danger) !important;
        }
        
        .bg-blue-600 {
            background: var(--primary) !important;
            border: 1px solid var(--primary) !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
        }
        
        .hover\:bg-blue-700:hover {
            background: var(--success) !important;
            border-color: var(--success) !important;
            transform: translateY(-1px) !important;
        }
        
        .focus\:ring-blue-500:focus {
            --tw-ring-color: var(--primary) !important;
        }
        
        .shadow-lg {
            box-shadow: var(--shadow) !important;
        }
        
        input {
            border-radius: 8px !important;
            border: 1px solid var(--line) !important;
            transition: all 0.2s ease !important;
            background: var(--card) !important;
            color: var(--text) !important;
        }
        
        input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.1) !important;
            background: var(--accent) !important;
        }
        
        button:focus {
            outline: none !important;
        }
        
        nav {
            background: var(--card) !important;
            border-bottom: 1px solid var(--line);
        }
        
        .rounded-lg {
            border-radius: 12px !important;
        }
        
        .rounded-md {
            border-radius: 8px !important;
        }
        
        .stat-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.2s ease;
            box-shadow: var(--shadow);
        }
        
        .stat-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: var(--primary);
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .section-header .icon-wrapper {
            margin-bottom: 0;
            margin-right: 0.75rem;
        }
        
        .form-section {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .form-section h3 {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            color: var(--text);
        }
        
        .form-section h3 .icon-wrapper {
            margin-right: 0.5rem;
            margin-bottom: 0;
        }
        
        /* Header Styles */
        .page-header{background:var(--card);border-bottom:1px solid var(--line);padding:20px 0;margin:-24px -20px 24px;box-shadow:0 4px 20px var(--shadow)}
        .header-content{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center}
        .header-content h1{font-size:24px;font-weight:600;letter-spacing:-0.01em;margin:0;color:var(--text)}
        .header-subtitle{font-size:14px;color:var(--muted);font-weight:500}
        
        /* Toolbar */
        .nav-section{display:flex;gap:6px;align-items:center}
        .btn{border:1px solid var(--primary);background:var(--primary);color:var(--text);padding:8px 16px;border-radius:6px;cursor:pointer;font-weight:500;font-size:14px;transition:all 0.15s ease;text-decoration:none;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 8px rgba(16,185,129,0.2)}
        .btn:hover{background:var(--secondary);border-color:var(--secondary);transform:translateY(-1px);box-shadow:0 4px 12px rgba(16,185,129,0.3)}
        .btn.outline{background:transparent;color:var(--primary);border:1px solid var(--border);box-shadow:none}
        .btn.outline:hover{background:var(--accent);border-color:var(--primary);color:var(--primary)}
        
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
        
        /* Shell */
        .shell{max-width:1200px;margin:0 auto;padding:24px 20px 80px}
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div>
                <h1><a href="{{ route('main') }}" style="text-decoration: none; color: inherit;">Omnia</a></h1>
                <div class="header-subtitle">Hoş geldiniz, {{ auth()->user()->username }}</div>
            </div>
            <div class="nav-section">
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
                                 data-value="profile"
                                 onclick="window.location.href='{{ route('profile') }}'">Hesabım</div>
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
                                 style="color:var(--danger)"
                                 onclick="document.getElementById('logoutForm').submit()">Çıkış Yap</div>
                        </div>
                        <input type="hidden" name="accountValue" id="accountHiddenValue" value="">
                    </div>
            </div>
        </div>
    </div>
    
    <!-- Hidden logout form -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="shell">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="section-header">
                    <div class="icon-wrapper">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Profilim</h2>
                        <p class="text-gray-600">Kişisel bilgilerinizi görüntüleyin ve güncelleyin</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Üye olma tarihi</div>
                    <div class="font-semibold text-lg">{{ $user->created_at->format('d.m.Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                @foreach($errors->all() as $error)
                    <p><i class="fas fa-exclamation-triangle mr-2"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Profile Form -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Zorunlu Alanlar (Salt Okunur) -->
                <div class="form-section bg-blue-50">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3">
                        <div class="icon-wrapper">
                            <i class="fas fa-lock"></i>
                        </div>
                        Zorunlu Bilgiler
                    </h3>
                    <p class="text-sm text-blue-600 mb-4">Bu bilgiler değiştirilemez.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user mr-2"></i>Kullanıcı Adı
                            </label>
                            <input 
                                type="text" 
                                value="{{ $user->username }}" 
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-600"
                                disabled
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-envelope mr-2"></i>E-posta
                            </label>
                            <input 
                                type="email" 
                                value="{{ $user->email }}" 
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-600"
                                disabled
                            >
                        </div>
                    </div>
                </div>

                <!-- Düzenlenebilir Alanlar -->
                <div class="form-section bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">
                        <div class="icon-wrapper">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        Düzenlenebilir Bilgiler
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user mr-2"></i>Ad
                            </label>
                            <input 
                                type="text" 
                                id="first_name"
                                name="first_name" 
                                value="{{ old('first_name', $user->first_name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Adınız"
                            >
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user mr-2"></i>Soyad
                            </label>
                            <input 
                                type="text" 
                                id="last_name"
                                name="last_name" 
                                value="{{ old('last_name', $user->last_name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Soyadınız"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-phone mr-2"></i>Telefon
                            </label>
                            <input 
                                type="tel" 
                                id="phone"
                                name="phone" 
                                value="{{ old('phone', $user->phone) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="+90 555 123 4567"
                            >
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a 
                        href="{{ route('main') }}" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>İptal
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
                    >
                        <i class="fas fa-check mr-2"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>

        <!-- Profile Stats -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <div class="section-header">
                <div class="icon-wrapper">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Profil İstatistikleri</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="stat-card bg-blue-50">
                    <div class="stat-number">{{ $user->created_at->diffForHumans() }}</div>
                    <div class="stat-label">Üye olalı</div>
                </div>
                
                <div class="stat-card bg-green-50">
                    <div class="stat-number">
                        @php
                            $filledFields = 0;
                            $totalFields = 5; // username, email, first_name, last_name, phone
                            if($user->first_name) $filledFields++;
                            if($user->last_name) $filledFields++;
                            if($user->phone) $filledFields++;
                            $filledFields += 2; // username ve email her zaman dolu
                            $completionRate = round(($filledFields / $totalFields) * 100);
                        @endphp
                        %{{ $completionRate }}
                    </div>
                    <div class="stat-label">Profil tamamlanma</div>
                </div>
                
                <div class="stat-card bg-purple-50">
                    <div class="stat-number">{{ $user->updated_at->diffForHumans() }}</div>
                    <div class="stat-label">Son güncelleme</div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Form validation ve UX improvements
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Kaydediliyor...';
            });
        });
    </script>
</body>
</html>
