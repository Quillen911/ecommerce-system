<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - Omnia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Omnia</h1>
            <p class="text-gray-600">Yeni hesap oluşturun</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Register Form -->
        <form action="{{ route('postregister') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Zorunlu Alanlar -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">
                    <i class="fas fa-asterisk mr-2"></i>Zorunlu Bilgiler
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user mr-2"></i>Kullanıcı Adı *
                        </label>
                        <input 
                            type="text" 
                            id="username"
                            name="username" 
                            value="{{ old('username') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Kullanıcı adınız" 
                            required
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope mr-2"></i>E-posta *
                        </label>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            value="{{ old('email') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="ornek@email.com" 
                            required
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock mr-2"></i>Şifre *
                        </label>
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="En az 8 karakter" 
                            required
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock mr-2"></i>Şifre Tekrar *
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation"
                            name="password_confirmation" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Şifrenizi tekrar girin" 
                            required
                        >
                    </div>
                </div>
            </div>

            <!-- Opsiyonel Alanlar -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">
                    <i class="fas fa-info-circle mr-2"></i>Opsiyonel Bilgiler
                </h3>
                <p class="text-sm text-gray-600 mb-4">Bu bilgileri daha sonra da doldurabilirsiniz.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-phone mr-2"></i>Telefon
                        </label>
                        <input 
                            type="tel" 
                            id="phone"
                            name="phone" 
                            value="{{ old('phone') }}"
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
                            value="{{ old('city') }}"
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
                            value="{{ old('district') }}"
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
                            value="{{ old('postal_code') }}"
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
                    >{{ old('address') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 text-lg font-semibold"
            >
                <i class="fas fa-user-plus mr-2"></i>Hesap Oluştur
            </button>
        </form>

        <!-- Divider -->
        <div class="my-6 flex items-center">
            <div class="flex-1 border-t border-gray-300"></div>
            <span class="px-3 text-gray-500 text-sm">veya</span>
            <div class="flex-1 border-t border-gray-300"></div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-gray-600 mb-2">Zaten hesabınız var mı?</p>
            <a 
                href="{{ route('login') }}" 
                class="inline-block bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>Giriş Yap
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>&copy; 2025 Omnia. Tüm hakları saklıdır.</p>
        </div>
    </div>

    <script>
        // Form validation ve UX improvements
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // Password confirmation check
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            
            function checkPasswordMatch() {
                if (password.value !== passwordConfirmation.value) {
                    passwordConfirmation.setCustomValidity('Şifreler eşleşmiyor');
                } else {
                    passwordConfirmation.setCustomValidity('');
                }
            }
            
            password.addEventListener('change', checkPasswordMatch);
            passwordConfirmation.addEventListener('keyup', checkPasswordMatch);
            
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Hesap oluşturuluyor...';
            });
        });
    </script>
</body>
</html>
