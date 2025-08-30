# API Toplu Ürün Ekleme

Bu dokümantasyon, API üzerinden toplu ürün ekleme işlemini açıklar.

## Endpoint

```
POST /api/seller/product/bulk
```

## Headers

```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Request Body

```json
{
  "products": [
    {
      "title": "İnce Memed",
      "author": "Yaşar Kemal",
      "category_id": 1,
      "list_price": 259.90,
      "stock_quantity": 10,
      "images": [
        "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
      ]
    },
    {
      "title": "Tutunamayanlar",
      "author": "Oğuz Atay",
      "category_id": 1,
      "list_price": 339.50,
      "stock_quantity": 20,
      "images": [
        "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=="
      ]
    }
  ]
}
```

## Resim Formatı

Resimler **base64 encoded string** olarak gönderilmelidir:

- **Format**: `data:image/{type};base64,{base64_string}`
- **Desteklenen formatlar**: JPEG, PNG, JPG, GIF, SVG
- **Maksimum boyut**: 2MB (base64 encoded)

### Base64 Resim Örneği

```javascript
// JavaScript'te resmi base64'e çevirme
function imageToBase64(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => resolve(reader.result);
    reader.onerror = reject;
    reader.readAsDataURL(file);
  });
}

// Kullanım
const file = document.getElementById('imageInput').files[0];
const base64 = await imageToBase64(file);
```

## Response

### Başarılı Response

```json
{
  "success": true,
  "message": "Ürünler başarıyla oluşturuldu",
  "data": [
    {
      "id": 21,
      "title": "İnce Memed",
      "author": "Yaşar Kemal",
      "category_id": 1,
      "list_price": 259.90,
      "stock_quantity": 10,
      "store_id": 2,
      "store_name": "Ahmet'in Kitap Dünyası",
      "sold_quantity": 0,
      "images": ["1703951234_abc123.jpg"],
      "created_at": "2025-08-30T14:07:15.000000Z",
      "updated_at": "2025-08-30T14:07:15.000000Z"
    }
  ]
}
```

### Hata Response

```json
{
  "success": false,
  "message": "Ürünler oluşturulamadı: Validation hatası",
  "errors": {
    "products.0.title": ["Ürün adı boş bırakılamaz."],
    "products.0.images.0": ["Resimler geçerli base64 formatında olmalıdır."]
  }
}
```

## Validation Kuralları

| Alan | Kural | Açıklama |
|------|-------|----------|
| `products` | `required|array|min:1` | En az bir ürün olmalı |
| `products.*.title` | `required|string|max:255` | Ürün adı zorunlu |
| `products.*.author` | `required|string|max:255` | Yazar adı zorunlu |
| `products.*.category_id` | `nullable|exists:categories,id` | Kategori ID (opsiyonel) |
| `products.*.list_price` | `required|numeric|min:0` | Fiyat zorunlu ve pozitif |
| `products.*.stock_quantity` | `required|integer|min:0` | Stok miktarı zorunlu |
| `products.*.images` | `required|array|min:1` | En az bir resim zorunlu |
| `products.*.images.*` | `string|regex` | Base64 formatında resim |

## Örnek Kullanım

### cURL

```bash
curl -X POST "https://your-domain.com/api/seller/product/bulk" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "products": [
      {
        "title": "Test Kitap",
        "author": "Test Yazar",
        "list_price": 99.99,
        "stock_quantity": 5,
        "images": ["data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="]
      }
    ]
  }'
```

### JavaScript (Fetch)

```javascript
const response = await fetch('/api/seller/product/bulk', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    products: [
      {
        title: 'Test Kitap',
        author: 'Test Yazar',
        list_price: 99.99,
        stock_quantity: 5,
        images: [base64ImageString]
      }
    ]
  })
});

const result = await response.json();
```

### Python (Requests)

```python
import requests
import base64

# Resmi base64'e çevir
with open('image.jpg', 'rb') as image_file:
    encoded_string = base64.b64encode(image_file.read()).decode()

response = requests.post(
    'https://your-domain.com/api/seller/product/bulk',
    headers={
        'Authorization': f'Bearer {token}',
        'Content-Type': 'application/json'
    },
    json={
        'products': [
            {
                'title': 'Test Kitap',
                'author': 'Test Yazar',
                'list_price': 99.99,
                'stock_quantity': 5,
                'images': [f'data:image/jpeg;base64,{encoded_string}']
            }
        ]
    }
)

print(response.json())
```

## Hata Kodları

| HTTP Status | Açıklama |
|-------------|----------|
| 200 | Başarılı |
| 400 | Validation hatası |
| 401 | Yetkilendirme hatası |
| 422 | Validation hatası (detaylı) |
| 500 | Sunucu hatası |

## Notlar

1. **Resim boyutu**: Base64 encoded resimler orijinal dosyadan yaklaşık %33 daha büyüktür
2. **Performans**: Çok sayıda resim gönderirken timeout olmaması için resim boyutlarını optimize edin
3. **Güvenlik**: Sadece güvenilir kaynaklardan gelen resimleri kabul edin
4. **Rate Limiting**: API rate limit'lerine dikkat edin
