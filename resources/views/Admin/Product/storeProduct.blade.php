<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Ekle</title>
</head>
<body>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <h1>Ürün Ekle</h1>
    
    <form action="{{ route('admin.createProduct') }}" method="post">
        @csrf
        <div>
            <input type="text" name="title" placeholder="Ürün Adı" value="{{ old('title') }}">
            @error('title')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            <input type="text" name="category_id" placeholder="Kategori" value="{{ old('category_id') }}">
            @error('category_id')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            <input type="text" name="author" placeholder="Yazar" value="{{ old('author') }}">
            @error('author')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            <input type="number" step="0.01" name="list_price" placeholder="Liste Fiyatı" value="{{ old('list_price') }}">
            @error('list_price')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            <input type="number" name="stock_quantity" placeholder="Stok Miktarı" value="{{ old('stock_quantity') }}">
            @error('stock_quantity')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit">Ürün Ekle</button>
    </form>
    
    <a href="{{ route('admin.product') }}">Geri Dön</a>
</body>
</html>