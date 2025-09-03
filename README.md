# E-ticaret Platformu

Modern Laravel tabanlı e-ticaret platformu. Kullanıcılar ve satıcılar için kapsamlı alışveriş deneyimi sunar.

## Teknoloji Stack

### Backend
- **Laravel 12.0** - PHP Framework
- **PHP 8.2+** - Programlama dili
- **PostgreSQL 17** - Veritabanı
- **Elasticsearch 8.11.0** - Arama motoru
- **RabbitMQ** - Queue sistemi
- **Redis** - Cache ve session
- **Laravel Sanctum** - API Authentication

### Frontend
- **Blade Templates** - Server-side rendering
- **Tailwind CSS 4.0** - CSS Framework
- **Vite 6.2.4** - Build tool
- **Vanilla JavaScript** - Client-side logic
- **Axios** - HTTP client

### Ödeme ve Kargo Sistemleri
- **İyzico** - Ana ödeme sistemi
- **MNG Kargo** - Kargo entegrasyonu (Test modu)

### Geliştirme Araçları
- **Docker & Docker Compose** - Containerization
- **Nginx** - Web server
- **PgAdmin** - PostgreSQL yönetimi
- **Kibana** - Elasticsearch monitoring

## Kurulum

### Gereksinimler
- Docker ve Docker Compose
- Git

### Adımlar

1. **Projeyi klonlayın**
```bash
git clone <repository-url>
```

2. **Environment dosyasını ayarlayın**
```bash
cp .env.example .env
# .env dosyasındaki değerleri düzenleyin
```

3. **Docker containerlarını başlatın**
```bash
docker-compose up -d
```

4. **Laravel kurulumunu tamamlayın**
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan storage:link
```

5. **Frontend assetlerini derleyin**
```bash
docker-compose exec app npm install
docker-compose exec app npm run build
```

6. **Uygulamaya erişin**
- Ana uygulama: http://localhost:8000
- PgAdmin: http://localhost:5050
- RabbitMQ Management: http://localhost:15672
- Kibana: http://localhost:5601

## Proje Yapısı

### Controllers
- **Web Controllers**: Kullanıcı arayüzü için
- **API Controllers**: REST API endpoints için
- **Seller Controllers**: Satıcı paneli için

### Models
- **User**: Kullanıcı yönetimi
- **Seller**: Satıcı yönetimi
- **Product**: Ürün yönetimi
- **Order**: Sipariş yönetimi
- **OrderItem**: Sipariş kalemleri
- **Campaign**: Kampanya yönetimi
- **CreditCard**: Ödeme kartları
- **ShippingItems**: Kargo takibi

### Services
- **Auth Services**: Kimlik doğrulama
- **Order Services**: Sipariş işlemleri
- **Payment Services**: İyzico ödeme entegrasyonu
- **Search Services**: Elasticsearch entegrasyonu
- **Campaign Services**: Kampanya hesaplama sistemi
- **Shipping Services**: MNG Kargo entegrasyonu
- **Refund Services**: İade işlemleri

### Views
- **Ana Sayfalar**: main, bag, myorders, order
- **Auth Sayfaları**: login, register, profile
- **Satıcı Paneli**: Ürün, kampanya, sipariş yönetimi

## Özellikler

### Kullanıcı Özellikleri
- Kullanıcı kayıt ve giriş sistemi
- Gelişmiş ürün arama (Elasticsearch)
- Otomatik tamamlama sistemi
- Sepet yönetimi
- Sipariş oluşturma ve takibi
- Güvenli ödeme sistemi (İyzico)
- **Kısmi İade Sistemi**: Ürün bazında iade
- Profil yönetimi
- Kredi kartı kaydetme

### Satıcı Özellikleri
- Satıcı kayıt ve giriş sistemi
- Ürün yönetimi (CRUD)
- Toplu ürün ekleme
- Kampanya oluşturma ve yönetimi
- Sipariş yönetimi
- **Kargo Entegrasyonu**: MNG Kargo ile otomatik kargo oluşturma
- Satış raporları
- Mağaza ayarları

### Kampanya Sistemi
- **Yüzdelik İndirim**: Belirli yüzdede indirim
- **Sabit Tutar İndirim**: Belirli tutarda indirim
- **X Al Y Öde**: Belirli adet al, daha az öde
- **Koşullu Kampanyalar**: 
  - Minimum sepet tutarı
  - Belirli yazarlar
  - Belirli kategoriler
- **Kullanıcı Bazlı Limit**: Kullanıcı başına kullanım limiti
- **Otomatik Hesaplama**: En avantajlı kampanyayı otomatik seçme

### İade Sistemi
- **Kısmi İade**: Sipariş kalemleri bazında iade
- **Adet Bazlı İade**: Ürün adetlerini seçerek iade
- **Otomatik Hesaplama**: İade edilebilir tutar hesaplama
- **Kampanya Geri Alma**: İade durumunda kampanya kullanımını geri alma
- **Stok Güncelleme**: İade edilen ürünlerin stoklarını güncelleme
- **Bildirim Sistemi**: İade işlemleri için SMS/Email bildirimi

### Kargo Sistemi
- **MNG Kargo Entegrasyonu**: Test ve production modları
- **Otomatik Kargo Oluşturma**: Satıcı onayı ile
- **Kargo Takip Numarası**: Her sipariş kalemi için
- **Kargo Durumları**: Beklemede, Hazırlanıyor, Kargoya Verildi, Yolda, Teslim Edildi
- **Kargo Ücretsizlik Eşiği**: 200 TL üzeri ücretsiz kargo

### Sistem Özellikleri
- Elasticsearch ile gelişmiş arama
- Queue sistemi ile asenkron işlemler
- SMS ve email bildirimleri
- Rate limiting
- CSRF koruması
- API endpoints
- Docker containerization
- Redis cache sistemi

## API Endpoints

### Authentication
- `POST /api/register` - Kullanıcı kaydı
- `POST /api/login` - Kullanıcı girişi
- `POST /api/logout` - Çıkış
- `POST /api/seller/login` - Satıcı girişi

### Products
- `GET /api/products` - Ürün listesi
- `GET /api/products/{id}` - Ürün detayı
- `GET /api/search` - Ürün arama
- `GET /api/search/autocomplete` - Otomatik tamamlama

### Orders
- `GET /api/orders` - Sipariş listesi
- `POST /api/orders` - Sipariş oluşturma
- `GET /api/orders/{id}` - Sipariş detayı
- `POST /api/orders/{id}/refund` - Kısmi iade

### Bag
- `GET /api/bag` - Sepet içeriği
- `POST /api/bag/add` - Sepete ürün ekleme
- `PUT /api/bag/update/{id}` - Sepet güncelleme
- `DELETE /api/bag/{id}` - Sepetten ürün çıkarma

### Seller API
- `GET /api/seller/products` - Satıcı ürünleri
- `POST /api/seller/products` - Ürün ekleme
- `PUT /api/seller/products/{id}` - Ürün güncelleme
- `DELETE /api/seller/products/{id}` - Ürün silme
- `GET /api/seller/orders` - Satıcı siparişleri
- `POST /api/seller/orders/{id}/confirm` - Sipariş onaylama

## Docker Servisleri

### Ana Servisler
- **app**: Laravel uygulaması
- **postgres**: PostgreSQL veritabanı
- **redis**: Redis cache
- **nginx**: Web server
- **queue**: Queue worker
- **rabbitmq**: Message broker

### Monitoring Servisleri
- **elasticsearch**: Arama motoru
- **kibana**: Elasticsearch monitoring
- **pgadmin**: PostgreSQL yönetimi

## Konfigürasyon

### Environment Variables
- `DB_CONNECTION=pgsql` - PostgreSQL bağlantısı
- `ELASTICSEARCH_HOST=elasticsearch` - Elasticsearch host
- `IYZICO_API_KEY` - İyzico API anahtarı
- `MNG_API_KEY` - MNG Kargo API anahtarı
- `RABBITMQ_HOST=rabbitmq` - RabbitMQ host
- `REDIS_HOST=redis` - Redis host

### Kargo Ayarları
- `ORDER_CARGO_THRESHOLD=200` - Ücretsiz kargo eşiği (TL)
- `ORDER_CARGO_PRICE=50` - Kargo ücreti (TL)
- `MNG_TEST_MODE=true` - Test modu aktif

## Geliştirme

### Docker Komutları
```bash
# Servisleri başlat
docker-compose up -d

# Logları görüntüle
docker-compose logs -f app

# Container içine gir
docker-compose exec app bash

# Queue worker'ı başlat
docker-compose exec app php artisan queue:work

# Test çalıştır
docker-compose exec app php artisan test
```

### Code Formatting
```bash
docker-compose exec app ./vendor/bin/pint
```

### Database İşlemleri
```bash
# Migration çalıştır
docker-compose exec app php artisan migrate

# Seeder çalıştır
docker-compose exec app php artisan db:seed

# Database sıfırla
docker-compose exec app php artisan migrate:fresh --seed
```

## Güvenlik

- CSRF token koruması
- Rate limiting (giriş/kayıt)
- Input validation
- SQL injection koruması
- XSS koruması
- Secure authentication
- API token authentication