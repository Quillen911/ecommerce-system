# E-Ticaret Platformu

Modern, ölçeklenebilir ve mikroservis benzeri mimariye sahip full-stack e-ticaret platformu. Kullanıcılar ve satıcılar için kapsamlı alışveriş ve yönetim deneyimi sunar.

[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![Next.js](https://img.shields.io/badge/Next.js-15.5.3-000000?style=flat&logo=next.js)](https://nextjs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.9.2-3178C6?style=flat&logo=typescript)](https://www.typescriptlang.org)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=flat&logo=docker)](https://www.docker.com)

---

## İçindekiler

- [Özellikler](#özellikler)
- [Teknoloji Stack](#teknoloji-stack)
- [Mimari](#mimari)
- [Kurulum](#kurulum)
- [Kullanım](#kullanım)
- [API Dokümantasyonu](#api-dokümantasyonu)
- [Proje Yapısı](#proje-yapısı)
- [Veritabanı Şeması](#veritabanı-şeması)
- [Deployment](#deployment)
- [Katkıda Bulunma](#katkıda-bulunma)
- [Lisans](#lisans)

---

## Özellikler

### Kullanıcı Özellikleri
- Kullanıcı kaydı, girişi ve şifre sıfırlama
- Gelişmiş ürün arama ve filtreleme (Elasticsearch powered)
- Gerçek zamanlı sepet yönetimi
- Kampanya ve indirim kodu uygulama
- Çoklu ödeme yöntemi desteği (İyzico, Stripe)
- Sipariş takibi ve geçmişi
- İade ve iade talepleri
- Çoklu adres yönetimi
- Kayıtlı kredi kartı yönetimi
- Profil ve hesap ayarları
- Email ve SMS bildirimleri

### Satıcı Özellikleri
- Kapsamlı ürün yönetimi (CRUD)
- Ürün varyantları (renk, beden, vb.)
- Toplu ürün ekleme
- Görsel yükleme ve sıralama
- Kampanya oluşturma ve yönetimi
- Sipariş yönetimi ve onaylama
- İade işlemleri
- Stok takibi
- Satış raporları

### Sistem Özellikleri
- Asenkron sipariş işleme (RabbitMQ)
- Otomatik bildirim sistemi
- Elasticsearch ile hızlı arama
- Redis ile önbellekleme
- Stok yönetimi ve takibi
- Webhook desteği
- Repository ve Service pattern
- Observer pattern ile model events
- Docker ile kolay deployment

---

## Teknoloji Stack

### Backend

#### Framework & Dil
- **Laravel 12.0** - Modern PHP Framework
- **PHP 8.2+** - Programlama dili

#### Veritabanı & Depolama
- **PostgreSQL 17** - Ana ilişkisel veritabanı
- **Redis** - Cache ve session yönetimi
- **Elasticsearch 8.11.0** - Ürün arama ve filtreleme motoru

#### Queue & Mesajlaşma
- **RabbitMQ 3** - Asenkron işlem kuyruğu
- **Laravel Queue** - Job yönetimi sistemi

#### Kimlik Doğrulama & Güvenlik
- **Laravel Sanctum 4.1** - API token authentication
- Dual authentication (User & Seller)

#### Ödeme Sistemleri
- **İyzico** (iyzipay-php ^2.0.59)
- **Stripe** (stripe-php ^17.5)

#### Diğer Paketler
- **Laravel Tinker** - REPL aracı
- **PHPUnit** - Test framework
- **Laravel Pint** - Code style fixer

### Frontend

#### Framework & Dil
- **Next.js 15.5.3** - React framework (App Router)
- **React 19.1.0** - UI library
- **TypeScript 5.9.2** - Tip güvenli JavaScript
- **Turbopack** - Hızlı build tool

#### State Management
- **Zustand 5.0.8** - Hafif global state yönetimi
- **TanStack React Query 5.89.0** - Server state & caching
- **React Hook Form 7.62.0** - Performanslı form yönetimi

#### UI & Styling
- **Tailwind CSS 4** - Utility-first CSS framework
- **Framer Motion 12.23.13** - Animasyon kütüphanesi
- **Headless UI 2.2.8** - Accessible UI components
- **Radix UI** - Primitive components (Accordion, Slider)
- **Lucide React** - Modern icon library
- **Swiper 11.2.10** - Touch slider

#### Validation & Schema
- **Zod 4.1.8** - TypeScript-first schema validation
- **@hookform/resolvers** - Form validation entegrasyonu

#### HTTP & Utilities
- **Axios 1.12.2** - HTTP client
- **date-fns 4.1.0** - Modern tarih kütüphanesi
- **Sonner 2.0.7** - Toast notifications
- **clsx & tailwind-merge** - Class name utilities

### DevOps & Infrastructure

- **Docker & Docker Compose** - Konteynerizasyon
- **Nginx** - Web server ve reverse proxy
- **Node.js 20** - JavaScript runtime

---

### Backend Mimari Desenleri

#### Repository Pattern
```php
Repositories/
├── Contracts/          # Interface tanımları
│   ├── BaseRepositoryInterface
│   ├── ReadRepositoryInterface
│   └── WriteRepositoryInterface
└── Eloquent/          # Eloquent implementasyonları
    ├── BaseRepository
    ├── UserRepository
    └── ProductRepository
```

#### Service Layer Pattern
```php
Services/
├── Auth/              # Kimlik doğrulama servisleri
├── Bag/               # Sepet işlemleri
├── Checkout/          # Ödeme işlemleri
├── Order/             # Sipariş yönetimi
├── Payment/           # Ödeme entegrasyonları
├── Product/           # Ürün işlemleri
├── Search/            # Arama servisleri
└── Seller/            # Satıcı işlemleri
```

#### Observer Pattern
Model event'leri için observer'lar kullanılır:
- ProductObserver
- OrderObserver
- InventoryObserver

### Frontend Mimari

#### App Router Structure
```
app/
├── [category]/        # Dinamik kategori sayfaları
├── account/           # Kullanıcı hesap yönetimi
├── bag/               # Sepet
├── checkout/          # Ödeme akışı
├── product/           # Ürün detay
└── seller/            # Satıcı paneli
```

#### Custom Hooks
17+ özel hook ile iş mantığı ayrıştırılmıştır:
- `useAuthQuery` - Kimlik doğrulama
- `useBagQuery` - Sepet işlemleri
- `useCheckoutSession` - Ödeme oturumu
- `useOrderQuery` - Sipariş işlemleri
- `useSearchQuery` - Arama işlemleri

---

## Kurulum

### Gereksinimler
- **Docker** 20.10+
- **Docker Compose** 2.0+
- **Git**

1. **Projeyi klonlayın**
```bash
git clone https://github.com/Quillen911/My-Task-2.git
cd myOrders
```

2. **Docker servislerini başlatın**
```bash
docker-compose up -d
```

3. **Backend kurulumu**
```bash
# Laravel container'ına girin
docker exec -it laravel-api bash

# Veritabanı migration'larını çalıştırın
php artisan migrate

# (Opsiyonel) Seed data ekleyin
php artisan db:seed

# Storage link oluşturun
php artisan storage:link

# Elasticsearch index oluşturun
php artisan scout:import "App\Models\Product"
```

4. **Frontend kurulumu**
```bash
# Frontend container'ı zaten çalışıyor olmalı
# Gerekirse yeniden başlatın
docker-compose restart web
```

5. **Uygulamaya erişin**
- Frontend: http://localhost:3000
- Backend API: http://localhost:8000
- RabbitMQ Management: http://localhost:15672 (guest/guest)
- Elasticsearch: http://localhost:9200

### Manuel Kurulum (Docker olmadan)

#### Backend
```bash
cd backend

# Bağımlılıkları yükleyin
composer install

# .env dosyasını oluşturun
cp .env.example .env

# Uygulama anahtarı oluşturun
php artisan key:generate

# Veritabanı migration'larını çalıştırın
php artisan migrate

# Sunucuyu başlatın
php artisan serve
```

#### Frontend
```bash
cd frontend

# Bağımlılıkları yükleyin
npm install

# .env.local dosyasını oluşturun
cp .env.example .env.local

# Development sunucusunu başlatın
npm run dev
```

---

## Kullanım

### Docker Servisleri

#### Tüm servisleri başlatma
```bash
docker-compose up -d
```

#### Belirli bir servisi yeniden başlatma
```bash
docker-compose restart web      # Frontend
docker-compose restart api      # Backend
docker-compose restart queue    # Queue worker
```

#### Logları görüntüleme
```bash
docker-compose logs -f web      # Frontend logs
docker-compose logs -f api      # Backend logs
docker-compose logs -f queue    # Queue logs
```

#### Container'a bağlanma
```bash
docker exec -it laravel-api bash    # Backend
docker exec -it nextjs-app bash     # Frontend
```

### Artisan Komutları

```bash
# Migration çalıştırma
php artisan migrate

# Seed data ekleme
php artisan db:seed

# Cache temizleme
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Queue worker başlatma
php artisan queue:work rabbitmq

# Elasticsearch index oluşturma
php artisan scout:import "App\Models\Product"
```

### NPM Komutları

```bash
# Development mode
npm run dev

# Production build
npm run build

# Production sunucu
npm run start

# Linting
npm run lint
```

---

## Veritabanı Şeması

### Ana Tablolar

#### Users & Authentication
- `users` - Kullanıcı bilgileri
- `sellers` - Satıcı bilgileri
- `stores` - Mağaza bilgileri
- `password_resets` - Şifre sıfırlama tokenları

#### Products
- `products` - Ürün ana bilgileri
- `product_variants` - Ürün varyantları (renk, model)
- `variant_sizes` - Beden/boyut bilgileri
- `product_variant_images` - Ürün görselleri
- `product_categories` - Ürün-kategori ilişkisi
- `categories` - Kategori hiyerarşisi
- `attributes` - Ürün özellikleri
- `attribute_options` - Özellik seçenekleri
- `variant_attributes` - Varyant-özellik ilişkisi
- `genders` - Cinsiyet kategorileri

#### Orders
- `orders` - Sipariş ana bilgileri
- `order_items` - Sipariş kalemleri
- `order_refunds` - İade talepleri
- `order_refund_items` - İade kalemleri
- `shipping_items` - Kargo bilgileri

#### Payments
- `payments` - Ödeme kayıtları
- `payment_methods` - Ödeme yöntemleri
- `payment_providers` - Ödeme sağlayıcıları
- `payment_customer_accounts` - Müşteri ödeme hesapları
- `payment_events` - Ödeme olayları

#### Campaigns
- `campaigns` - Kampanya bilgileri
- `campaign_products` - Kampanya-ürün ilişkisi
- `campaign_categories` - Kampanya-kategori ilişkisi
- `campaign_usages` - Kampanya kullanım kayıtları

#### Cart & Checkout
- `bags` - Sepet ana bilgileri
- `bag_items` - Sepet kalemleri
- `checkout_sessions` - Ödeme oturumları
- `user_addresses` - Kullanıcı adresleri

#### Inventory
- `warehouses` - Depo bilgileri
- `inventories` - Stok kayıtları
- `stock_movements` - Stok hareketleri

#### System
- `jobs` - Queue jobs
- `failed_jobs` - Başarısız jobs
- `cache` - Cache kayıtları
- `notifications` - Bildirimler

---

## Güvenlik

### Implemented Security Features
- Laravel Sanctum token authentication
- CSRF protection
- SQL injection prevention (Eloquent ORM)
- XSS protection
- Rate limiting
- Password hashing (bcrypt)
- Secure payment processing
- HTTPS enforcement (production)
- Environment variable protection

---

## Geliştirici

**İsmail**
- GitHub: [@Quillen911](https://github.com/Quillen911)

---
