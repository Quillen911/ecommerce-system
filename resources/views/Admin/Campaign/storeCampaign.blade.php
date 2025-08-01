<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampanya Ekle</title>
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
    <h1>Kampanya Ekle</h1>
    
    <form action="{{ route('admin.createCampaign') }}" method="POST">
        @csrf
        
            <input type="text" name="name" placeholder="Kampanya Adı" value="{{ old('name') }}" required> <br>
       
            <input type="text" name="type" placeholder="Kampanya Tipi" value="{{ old('type') }}" required> <br>
            
            <input type="text" name="description" placeholder="Kampanya Açıklaması" value="{{ old('description') }}" nullable> <br>

            <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}> Kampanya Aktif <br>
            
            <input type="text" name="priority" placeholder="Kampanya Önceliği" value="{{ old('priority') }}" nullable> <br>
        
            <input type="number" name="usage_limit" placeholder="Kullanım Limit" value="{{ old('usage_limit') }}" nullable> <br>

            <input type="number" name="usage_limit_for_user" placeholder="Kullanıcı Kullanım Limit" value="{{ old('usage_limit_for_user') }}" nullable> <br>

            <input type="date" name="starts_at" placeholder="Kampanya Başlangıç Tarihi" value="{{ old('starts_at') }}" nullable> <br>

            <input type="date" name="ends_at" placeholder="Kampanya Bitiş Tarihi" value="{{ old('ends_at') }}" nullable> <br> <br>
        
        
        <button type="submit">Kampanya Ekle</button>
        
    </form>
    <br>
    <a href="{{ route('admin.campaign') }}">Geri Dön</a> 
</body>
</html> 