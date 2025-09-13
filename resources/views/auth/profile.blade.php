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
    </style>
</head>
<body class="min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-800">Omnia</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('main') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-home mr-2"></i>Anasayfa
                    </a>
                    <a href="{{ route('bag') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-shopping-bag mr-2"></i>Sepetim
                    </a>
                    <a href="{{ route('myorders') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-box mr-2"></i>Siparişlerim
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt mr-2"></i>Çıkış
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-8 px-4">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="section-header">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-circle"></i>
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
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                @foreach($errors->all() as $error)
                    <p><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
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
                            <i class="fas fa-asterisk"></i>
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
                            <i class="fas fa-edit"></i>
                        </div>
                        Düzenlenebilir Bilgiler
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a 
                        href="{{ route('main') }}" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200"
                    >
                        <i class="fas fa-times mr-2"></i>İptal
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
                    >
                        <i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>

        <!-- Profile Stats -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <div class="section-header">
                <div class="icon-wrapper">
                    <i class="fas fa-chart-bar"></i>
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
                            $totalFields = 7; // username, email, phone, address, city, district, postal_code
                            if($user->phone) $filledFields++;
                            if($user->address) $filledFields++;
                            if($user->city) $filledFields++;
                            if($user->district) $filledFields++;
                            if($user->postal_code) $filledFields++;
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

    <script>
        // Form validation ve UX improvements
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Kaydediliyor...';
            });
        });
    </script>
</body>
</html>
