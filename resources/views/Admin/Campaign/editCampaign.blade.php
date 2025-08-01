<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampanya Düzenle</title>
</head>
<body>
    <h1>Kampanya Düzenle</h1>
    <form action="{{ route('admin.updateCampaign', $campaigns->id) }}" method="POST">
        @csrf
        <input type="text" name="name" value="{{ old('name', $campaigns->name) }}" required> <br>
        <input type="text" name="type" value="{{ old('type', $campaigns->type) }}" required> <br>
        <input type="text" name="description" value="{{ old('description', $campaigns->description) }}"> <br>
        <input type="text" name="priority" value="{{ old('priority', $campaigns->priority ?? '') }}"> <br> 
        <input type="number" name="usage_limit" value="{{ old('usage_limit', $campaigns->usage_limit) }}" required> <br>
        <input type="number" name="usage_limit_for_user" value="{{ old('usage_limit_for_user', $campaigns->usage_limit_for_user) }}" required> <br>
        <input type="date" name="starts_at" value="{{ old('starts_at', \Carbon\Carbon::parse($campaigns->starts_at)->format('Y-m-d')) }}" required> <br>
        <input type="date" name="ends_at" value="{{ old('ends_at', \Carbon\Carbon::parse($campaigns->ends_at)->format('Y-m-d')) }}" required> <br>
        
        <select name="is_active" id="is_active">
            <option value="1" {{ old('is_active', $campaigns->is_active) ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ old('is_active', $campaigns->is_active) ? '' : 'selected' }}>Pasif</option>
        </select>

        <button type="submit">Güncelle</button>
        
        <a href="{{ route('admin.campaign') }}">Geri Dön</a>
    </form>
</body>
</html>