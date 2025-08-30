# API Toplu Ürün Ekleme - Örnekler

Bu dosya, API üzerinden toplu ürün ekleme için gerçek örnekler içerir.

## 1. Basit Ürün Ekleme

### Request
```json
{
  "products": [
    {
      "title": "İnce Memed",
      "author": "Yaşar Kemal",
      "list_price": 259.90,
      "stock_quantity": 10,
      "images": [
        "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
      ]
    }
  ]
}
```

### cURL
```bash
curl -X POST "https://your-domain.com/api/seller/product/bulk" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "products": [
      {
        "title": "İnce Memed",
        "author": "Yaşar Kemal",
        "list_price": 259.90,
        "stock_quantity": 10,
        "images": [
          "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
        ]
      }
    ]
  }'
```

## 2. Çoklu Ürün Ekleme

### Request
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
    },
    {
      "title": "Kürk Mantolu Madonna",
      "author": "Sabahattin Ali",
      "category_id": 1,
      "list_price": 169.90,
      "stock_quantity": 15,
      "images": [
        "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
      ]
    }
  ]
}
```

## 3. Farklı Resim Formatları

### JPEG Resim
```json
{
  "products": [
    {
      "title": "JPEG Örnek",
      "author": "Test Yazar",
      "list_price": 99.99,
      "stock_quantity": 5,
      "images": [
        "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
      ]
    }
  ]
}
```

### PNG Resim
```json
{
  "products": [
    {
      "title": "PNG Örnek",
      "author": "Test Yazar",
      "list_price": 99.99,
      "stock_quantity": 5,
      "images": [
        "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=="
      ]
    }
  ]
}
```

### GIF Resim
```json
{
  "products": [
    {
      "title": "GIF Örnek",
      "author": "Test Yazar",
      "list_price": 99.99,
      "stock_quantity": 5,
      "images": [
        "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
      ]
    }
  ]
}
```

## 4. Çoklu Resimli Ürün

### Request
```json
{
  "products": [
    {
      "title": "Çoklu Resimli Kitap",
      "author": "Test Yazar",
      "list_price": 199.99,
      "stock_quantity": 8,
      "images": [
        "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k=",
        "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==",
        "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
      ]
    }
  ]
}
```

## 5. JavaScript Örnekleri

### Resmi Base64'e Çevirme
```javascript
// Dosya seçimi
function handleFileSelect(event) {
  const file = event.target.files[0];
  if (file) {
    convertToBase64(file);
  }
}

// Base64'e çevirme
function convertToBase64(file) {
  const reader = new FileReader();
  reader.onload = function(e) {
    const base64 = e.target.result;
    console.log('Base64:', base64);
    // Bu base64 string'i API'ye gönder
  };
  reader.readAsDataURL(file);
}

// Dosya boyutu kontrolü
function validateFileSize(file) {
  const maxSize = 2 * 1024 * 1024; // 2MB
  if (file.size > maxSize) {
    alert('Dosya boyutu 2MB\'dan büyük olamaz!');
    return false;
  }
  return true;
}

// Resim formatı kontrolü
function validateImageFormat(file) {
  const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
  if (!allowedTypes.includes(file.type)) {
    alert('Sadece JPEG, PNG, GIF ve SVG formatları desteklenir!');
    return false;
  }
  return true;
}
```

### API'ye Gönderme
```javascript
async function uploadProducts(products) {
  try {
    const response = await fetch('/api/seller/product/bulk', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + getToken(),
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ products })
    });

    const result = await response.json();
    
    if (result.success) {
      console.log('Ürünler başarıyla eklendi:', result.data);
      return result.data;
    } else {
      console.error('Hata:', result.message);
      throw new Error(result.message);
    }
  } catch (error) {
    console.error('API Hatası:', error);
    throw error;
  }
}

// Kullanım
const products = [
  {
    title: 'Test Kitap',
    author: 'Test Yazar',
    list_price: 99.99,
    stock_quantity: 5,
    images: [base64ImageString]
  }
];

uploadProducts(products)
  .then(result => {
    console.log('Başarılı:', result);
  })
  .catch(error => {
    console.error('Hata:', error);
  });
```

## 6. Python Örnekleri

### Resmi Base64'e Çevirme
```python
import requests
import base64
import os

def image_to_base64(image_path):
    """Resmi base64'e çevir"""
    with open(image_path, 'rb') as image_file:
        encoded_string = base64.b64encode(image_file.read()).decode()
        # Dosya uzantısını al
        file_extension = os.path.splitext(image_path)[1][1:].lower()
        return f"data:image/{file_extension};base64,{encoded_string}"

def upload_products(products, token, base_url):
    """Ürünleri API'ye yükle"""
    url = f"{base_url}/api/seller/product/bulk"
    headers = {
        'Authorization': f'Bearer {token}',
        'Content-Type': 'application/json'
    }
    
    response = requests.post(url, headers=headers, json={'products': products})
    return response.json()

# Kullanım
if __name__ == "__main__":
    # Resimleri base64'e çevir
    image1 = image_to_base64('kitap1.jpg')
    image2 = image_to_base64('kitap2.png')
    
    products = [
        {
            'title': 'Python ile Programlama',
            'author': 'Python Yazar',
            'list_price': 89.99,
            'stock_quantity': 10,
            'images': [image1]
        },
        {
            'title': 'Laravel Web Geliştirme',
            'author': 'Laravel Uzman',
            'list_price': 129.99,
            'stock_quantity': 5,
            'images': [image2]
        }
    ]
    
    # API'ye gönder
    token = "YOUR_API_TOKEN"
    base_url = "https://your-domain.com"
    
    result = upload_products(products, token, base_url)
    print(result)
```

## 7. Test Verileri

### Minimal Test
```json
{
  "products": [
    {
      "title": "Test",
      "author": "Test",
      "list_price": 1.00,
      "stock_quantity": 1,
      "images": [
        "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
      ]
    }
  ]
}
```

### Hata Testi (Resim olmadan)
```json
{
  "products": [
    {
      "title": "Resimsiz Kitap",
      "author": "Test Yazar",
      "list_price": 99.99,
      "stock_quantity": 5
    }
  ]
}
```

Bu örnekler ile API'yi test edebilir ve farklı senaryoları deneyebilirsiniz!
