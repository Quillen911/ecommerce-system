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

## Mimari

### Genel Mimari
Proje, mikroservis benzeri monolitik bir yapıya sahiptir:

```
┌─────────────────┐      ┌─────────────────┐
│   Next.js App   │◄────►│  Laravel API    │
│   (Frontend)    │      │   (Backend)     │
│   Port: 3000    │      │   Port: 8000    │
└─────────────────┘      └─────────────────┘
                                  │
                    ┌─────────────┼─────────────┐
                    │             │             │
            ┌───────▼──────┐ ┌───▼────┐ ┌─────▼─────┐
            │  PostgreSQL  │ │ Redis  │ │ RabbitMQ  │
            │  Port: 5432  │ │  6379  │ │   5672    │
            └──────────────┘ └────────┘ └───────────┘
                    │
            ┌───────▼──────────┐
            │  Elasticsearch   │
            │   Port: 9200     │
            └──────────────────┘
```

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

### Hızlı Başlangıç

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

## API Dokümantasyonu

### Base URL
- **Development**: `http://localhost:8000/api`
- **Production**: `https://your-domain.com/api`

### Authentication

#### Kullanıcı Kaydı
```http
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Kullanıcı Girişi
```http
POST /login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Satıcı Girişi
```http
POST /seller/login
Content-Type: application/json

{
  "email": "seller@example.com",
  "password": "password123"
}
```

### Ürünler

#### Ürün Arama
```http
GET /search?q=laptop&category=electronics&min_price=1000&max_price=5000
```

#### Kategori Filtreleme
```http
GET /category/{category_slug}?sort=price_asc&page=1
```

#### Ürün Detayı
```http
GET /variant/{variant_slug}
```

### Sepet (Authentication Required)

#### Sepet Görüntüleme
```http
GET /bags
Authorization: Bearer {token}
```

#### Sepete Ürün Ekleme
```http
POST /bags
Authorization: Bearer {token}
Content-Type: application/json

{
  "variant_size_id": 123,
  "quantity": 2
}
```

#### Kampanya Uygulama
```http
POST /bags/campaign
Authorization: Bearer {token}
Content-Type: application/json

{
  "campaign_id": 5
}
```

### Checkout (Authentication Required)

#### Checkout Session Oluşturma
```http
POST /checkout/session
Authorization: Bearer {token}
Content-Type: application/json

{
  "address_id": 1,
  "payment_method_id": 2
}
```

#### Ödeme Intent Oluşturma
```http
POST /checkout/payment-intent
Authorization: Bearer {token}
Content-Type: application/json

{
  "session_id": "abc123",
  "payment_method": "credit_card"
}
```

### Siparişler (Authentication Required)

#### Sipariş Listesi
```http
GET /orders
Authorization: Bearer {token}
```

#### Sipariş Detayı
```http
GET /orders/{order_id}
Authorization: Bearer {token}
```

#### İade Talebi
```http
POST /orders/{order_id}/refunds
Authorization: Bearer {token}
Content-Type: application/json

{
  "items": [
    {
      "order_item_id": 1,
      "quantity": 1,
      "reason": "Ürün hasarlı geldi"
    }
  ]
}
```

### Satıcı API (Seller Authentication Required)

#### Ürün Oluşturma
```http
POST /seller/product
Authorization: Bearer {seller_token}
Content-Type: application/json

{
  "name": "Laptop XYZ",
  "description": "High performance laptop",
  "category_id": 5,
  "gender_id": 1
}
```

#### Varyant Ekleme
```http
POST /seller/product/{product_id}/variants
Authorization: Bearer {seller_token}
Content-Type: application/json

{
  "color": "Black",
  "price": 15000,
  "stock": 50
}
```

#### Kampanya Oluşturma
```http
POST /seller/campaign
Authorization: Bearer {seller_token}
Content-Type: application/json

{
  "name": "Yaz İndirimi",
  "discount_type": "percentage",
  "discount_value": 20,
  "min_purchase_amount": 500,
  "start_date": "2025-06-01",
  "end_date": "2025-08-31"
}
```

---

## Proje Yapısı

### Backend Dizin Yapısı

```
backend/
├── app/
│   ├── Console/              # Artisan komutları
│   ├── Enums/               # Enum sınıfları
│   │   ├── OrderStatus.php
│   │   ├── PaymentStatus.php
│   │   └── ShippingStatus.php
│   ├── Exceptions/          # Exception handlers
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/        # API controllers
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── BagController.php
│   │   │   │   ├── Checkout/
│   │   │   │   ├── Order/
│   │   │   │   ├── Payments/
│   │   │   │   ├── Product/
│   │   │   │   └── Seller/
│   │   │   └── Web/        # Web controllers
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Jobs/               # Queue jobs
│   │   ├── OrderPlacementJob.php
│   │   ├── SellerOrderNotification.php
│   │   ├── IndexProductToElasticsearch.php
│   │   └── Refund/
│   ├── Models/             # Eloquent models (35 adet)
│   │   ├── User.php
│   │   ├── Seller.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   └── ...
│   ├── Notifications/      # Bildirimler
│   │   ├── OrderCreated.php
│   │   ├── OrderItemShipped.php
│   │   └── PasswordResetNotification.php
│   ├── Observers/          # Model observers
│   ├── Providers/          # Service providers
│   ├── Repositories/       # Repository pattern
│   │   ├── Contracts/     # Interfaces
│   │   └── Eloquent/      # Implementations
│   ├── Services/          # Business logic
│   │   ├── Auth/
│   │   ├── Bag/
│   │   ├── Campaigns/
│   │   ├── Checkout/
│   │   ├── Order/
│   │   ├── Payments/
│   │   ├── Product/
│   │   ├── Search/
│   │   ├── Seller/
│   │   └── Shipping/
│   └── Traits/
├── database/
│   ├── factories/
│   ├── migrations/         # 40 migration dosyası
│   └── seeders/
├── routes/
│   ├── api.php            # API routes
│   ├── web.php            # Web routes
│   └── console.php
├── storage/
├── tests/
├── composer.json
├── Dockerfile
└── .env.example
```

### Frontend Dizin Yapısı

```
frontend/
├── public/
│   ├── images/
│   └── icons/
├── src/
│   ├── app/                    # Next.js App Router
│   │   ├── [category]/        # Dinamik kategori
│   │   │   └── page.tsx
│   │   ├── account/           # Hesap yönetimi
│   │   │   ├── addresses/
│   │   │   ├── orders/
│   │   │   └── profile/
│   │   ├── bag/               # Sepet
│   │   │   └── page.tsx
│   │   ├── checkout/          # Ödeme
│   │   │   ├── payment/
│   │   │   ├── shipping/
│   │   │   └── success/
│   │   ├── login/
│   │   ├── product/
│   │   │   └── [slug]/
│   │   ├── register/
│   │   ├── search/
│   │   ├── seller/            # Satıcı paneli
│   │   │   ├── campaign/
│   │   │   ├── order/
│   │   │   └── product/
│   │   ├── layout.tsx
│   │   └── page.tsx
│   ├── components/            # React components
│   │   ├── auth/
│   │   ├── bag/
│   │   ├── checkout/
│   │   ├── footer/
│   │   ├── forms/
│   │   ├── header/
│   │   ├── home/
│   │   ├── order/
│   │   ├── product/
│   │   ├── seller/
│   │   └── ui/               # Reusable UI
│   ├── contexts/             # React contexts
│   ├── hooks/                # Custom hooks
│   │   ├── checkout/
│   │   ├── seller/
│   │   ├── useAuthQuery.ts
│   │   ├── useBagQuery.ts
│   │   ├── useOrderQuery.ts
│   │   └── useSearchQuery.ts
│   ├── lib/
│   │   ├── api/              # API clients
│   │   │   ├── authApi.ts
│   │   │   ├── bagApi.ts
│   │   │   ├── checkoutApi.ts
│   │   │   ├── orderApi.ts
│   │   │   └── seller/
│   │   ├── queryClient.ts
│   │   └── utils.ts
│   ├── providers/            # Context providers
│   ├── schemas/              # Zod schemas
│   ├── styles/
│   ├── types/                # TypeScript types
│   └── middleware.ts         # Next.js middleware
├── package.json
├── tsconfig.json
├── next.config.ts
├── Dockerfile
└── .env.local
```

---

## Veritabanı Şeması

### Ana Tablolar

#### Users & Authentication
- `users` - Kullanıcı bilgileri
- `sellers` - Satıcı bilgileri
- `stores` - Mağaza bilgileri
- `password_resets` - Şifre sıfırlama tokenları
- `personal_access_tokens` - API tokenları

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

## Deployment

### Docker Compose ile Production

1. **Environment değişkenlerini ayarlayın**
```bash
# Backend .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Frontend .env.local
NEXT_PUBLIC_API_URL=https://your-domain.com/api
NODE_ENV=production
```

2. **Production build**
```bash
docker-compose -f docker-compose.prod.yml up -d --build
```

3. **SSL sertifikası ekleyin** (Let's Encrypt ile)
```bash
# Certbot kullanarak
docker-compose run --rm certbot certonly --webroot \
  --webroot-path=/var/www/certbot \
  -d your-domain.com
```

### Manuel Deployment

#### Backend (Laravel)
```bash
# Bağımlılıkları yükle
composer install --optimize-autoloader --no-dev

# Cache oluştur
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migration çalıştır
php artisan migrate --force

# Storage link
php artisan storage:link
```

#### Frontend (Next.js)
```bash
# Bağımlılıkları yükle
npm ci

# Production build
npm run build

# PM2 ile başlat
pm2 start npm --name "ecommerce-frontend" -- start
```

### Environment Variables

#### Backend (.env)
```env
APP_NAME="E-Commerce Platform"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=ecommerce
DB_USERNAME=postgres
DB_PASSWORD=your_password

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

QUEUE_CONNECTION=rabbitmq
RABBITMQ_HOST=rabbitmq
RABBITMQ_PORT=5672
RABBITMQ_USER=guest
RABBITMQ_PASSWORD=guest
RABBITMQ_VHOST=/

ELASTICSEARCH_HOST=elasticsearch:9200

IYZICO_API_KEY=your_api_key
IYZICO_SECRET_KEY=your_secret_key
IYZICO_BASE_URL=https://api.iyzipay.com

STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret

```

#### Frontend (.env.local)
```env
NEXT_PUBLIC_API_URL=https://your-domain.com/api
NEXT_PUBLIC_APP_URL=https://your-domain.com
NODE_ENV=production
```

---

## Testing

### Backend Tests
```bash
# Tüm testleri çalıştır
php artisan test

# Belirli bir test dosyası
php artisan test --filter=OrderTest

# Coverage raporu
php artisan test --coverage
```

### Frontend Tests
```bash
# Unit tests
npm run test

# E2E tests (Playwright)
npm run test:e2e
```

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
- Input validation (Zod schemas)

### Security Best Practices
- API anahtarlarını asla commit etmeyin
- `.env` dosyalarını `.gitignore`'a ekleyin
- Production'da `APP_DEBUG=false` kullanın
- Güçlü şifreler kullanın
- Düzenli güvenlik güncellemeleri yapın

---

## Katkıda Bulunma

Katkılarınızı bekliyoruz! Lütfen şu adımları takip edin:

1. Fork yapın
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Değişikliklerinizi commit edin (`git commit -m 'feat: Add amazing feature'`)
4. Branch'inizi push edin (`git push origin feature/amazing-feature`)
5. Pull Request açın

### Commit Mesaj Formatı
```
feat: Yeni özellik ekleme
fix: Bug düzeltme
docs: Dokümantasyon güncellemesi
style: Kod formatı değişikliği
refactor: Kod refactoring
test: Test ekleme/güncelleme
chore: Build/config değişiklikleri
```

---

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için [LICENSE](LICENSE) dosyasına bakın.

---

## Geliştirici

**İsmail**
- GitHub: [@Quillen911](https://github.com/Quillen911)

---

## İletişim & Destek

Sorularınız veya önerileriniz için:
- GitHub Issues: [Create an issue](https://github.com/Quillen911/My-Task-2/issues)
- Email: your-email@example.com

---

## Teşekkürler

Bu proje aşağıdaki harika açık kaynak projeleri kullanmaktadır:
- [Laravel](https://laravel.com)
- [Next.js](https://nextjs.org)
- [React](https://react.dev)
- [Tailwind CSS](https://tailwindcss.com)
- [PostgreSQL](https://www.postgresql.org)
- [Elasticsearch](https://www.elastic.co)
- [RabbitMQ](https://www.rabbitmq.com)
- [Redis](https://redis.io)

---

<div align="center">
  <p>Bu projeyi beğendiyseniz yıldız vermeyi unutmayın!</p>
  <p>Made with by İsmail</p>
</div>
