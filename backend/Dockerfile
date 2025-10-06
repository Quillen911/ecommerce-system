FROM php:8.2-fpm

# Kullanıcı ve grup değişkenlerini tanımla
ARG user=www-data
ARG uid=1000

# Sistem bağımlılıklarını yükle
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev

# Önbelleği temizle
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP eklentilerini yükle
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip sockets

# Redis eklentisini yükle
RUN pecl install redis && docker-php-ext-enable redis

# Composer'ı yükle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Kullanıcı UID'sini güncelle
RUN usermod -u ${uid} ${user}

# Çalışma dizinini ayarla
WORKDIR /var/www

# Uygulama dosyalarını kopyala
COPY --chown=${user}:${user} . /var/www

# Gerekli dizinleri oluştur
RUN mkdir -p /var/www/storage/app/public \
    && mkdir -p /var/www/bootstrap/cache

# İzinleri ayarla
RUN chown -R ${user}:${user} /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Composer bağımlılıklarını yükle
RUN composer install --optimize-autoloader

# Storage linkini oluştur
RUN php artisan storage:link

# Son izin düzeltmeleri
RUN chown -R ${user}:${user} \
        /var/www/storage \
        /var/www/bootstrap/cache \
        /var/www/public

# www-data kullanıcısına geç
USER ${user}

# Ortam değişkenlerini ayarla
ENV APP_ENV=local
ENV LOG_CHANNEL=single
ENV LOG_LEVEL=debug

# Port 9000'i aç ve php-fpm sunucusunu başlat
EXPOSE 9000
CMD ["php-fpm"]