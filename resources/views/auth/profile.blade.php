<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim - Omnia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
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
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-user-circle mr-3"></i>Profilim
                    </h2>
                    <p class="text-gray-600">Kişisel bilgilerinizi görüntüleyin ve güncelleyin</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Üye olma tarihi</div>
                    <div class="font-semibold">{{ $user->created_at->format('d.m.Y') }}</div>
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
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3">
                        <i class="fas fa-asterisk mr-2"></i>Zorunlu Bilgiler
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
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">
                        <i class="fas fa-edit mr-2"></i>Düzenlenebilir Bilgiler
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

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-city mr-2"></i>Şehir
                            </label>
                            <input 
                                type="text" 
                                id="city"
                                name="city" 
                                value="{{ old('city', $user->city) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="İstanbul"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-map-marker-alt mr-2"></i>İlçe
                            </label>
                            <input 
                                type="text" 
                                id="district"
                                name="district" 
                                value="{{ old('district', $user->district) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Kadıköy"
                            >
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-mail-bulk mr-2"></i>Posta Kodu
                            </label>
                            <input 
                                type="text" 
                                id="postal_code"
                                name="postal_code" 
                                value="{{ old('postal_code', $user->postal_code) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="34710"
                            >
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-home mr-2"></i>Adres
                        </label>
                        <textarea 
                            id="address"
                            name="address" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Tam adres bilginiz"
                        >{{ old('address', $user->address) }}</textarea>
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
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-bar mr-2"></i>Profil İstatistikleri
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $user->created_at->diffForHumans() }}</div>
                    <div class="text-sm text-gray-600">Üye olalı</div>
                </div>
                
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">
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
                    <div class="text-sm text-gray-600">Profil tamamlanma</div>
                </div>
                
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">
                        {{ $user->updated_at->diffForHumans() }}
                    </div>
                    <div class="text-sm text-gray-600">Son güncelleme</div>
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
