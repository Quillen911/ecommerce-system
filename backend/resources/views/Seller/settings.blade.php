<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayarlar</title>
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
    <h1>Satıcı Ayarları</h1>
    <form action="{{ route('settings.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">Mağaza Adı</label>
        <input type="text" name="name" value="{{ $store->name ?? '' }}" required>
    </div>
    
    <div>
        <label for="iban">IBAN</label>
        <input type="text" name="iban" value="{{ $store->iban ?? '' }}" required>
    </div>
    
    <div>
        <label for="tax_number">Vergi Numarası</label>
        <input type="text" name="tax_number" value="{{ $store->tax_number ?? '' }}" required>
    </div>
    
    <div>
        <label for="tax_office">Vergi Dairesi</label>
        <input type="text" name="tax_office" value="{{ $store->tax_office ?? '' }}" required>
    </div>
    
    <div>
        <label for="identity_number">TC Kimlik No</label>
        <input type="text" name="identity_number" value="{{ $store->identity_number ?? '' }}" required>
    </div>
    
    <button type="submit">Kaydet</button>
</form>
</body>
</html>