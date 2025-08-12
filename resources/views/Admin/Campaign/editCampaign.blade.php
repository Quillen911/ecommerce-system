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
        <label for="name">Kampanya Adı: </label>
        <input type="text" name="name" value="{{ old('name', $campaigns->name) }}" required> <br>
        <label for="type">Kampanya Tipi:  </label>
        <select name="type" id="type">
            <option value="percentage" {{ old('type', $campaigns->type) == 'percentage' ? 'selected' : '' }}>Yüzde</option>
            <option value="fixed" {{ old('type', $campaigns->type) == 'fixed' ? 'selected' : '' }}>Sabit Tutar</option>
            <option value="x_buy_y_pay" {{ old('type', $campaigns->type) == 'x_buy_y_pay' ? 'selected' : '' }}>X Al Y Öde</option>
        </select> <br>
        <label for="description">Kampanya Açıklaması:</label>
        <input type="text" name="description" value="{{ old('description', $campaigns->description) }}"> <br>
        <label for="priority">Kampanya Önceliği:  </label>
        <input type="text" name="priority" value="{{ old('priority', $campaigns->priority ?? '') }}"> <br> 
        <label for="usage_limit">Kullanım Limit:  </label>
        <input type="number" name="usage_limit" value="{{ old('usage_limit', $campaigns->usage_limit) }}" required> <br>
        <label for="usage_limit_for_user">Kullanım Limiti:  </label>
        <input type="number" name="usage_limit_for_user" value="{{ old('usage_limit_for_user', $campaigns->usage_limit_for_user) }}" required> <br>
        <label for="starts_at">Başlangıç Tarihi:  </label>
        <input type="date" name="starts_at" value="{{ old('starts_at', \Carbon\Carbon::parse($campaigns->starts_at)->format('Y-m-d')) }}" required> <br>
        <label for="ends_at">Bitiş Tarihi:  </label>
        <input type="date" name="ends_at" value="{{ old('ends_at', \Carbon\Carbon::parse($campaigns->ends_at)->format('Y-m-d')) }}" required> <br>
        <label for="is_active">Aktiflik Durumu:  </label>
        <select name="is_active" id="is_active">
            <option value="1" {{ old('is_active', $campaigns->is_active) ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ old('is_active', $campaigns->is_active) ? '' : 'selected' }}>Pasif</option>
        </select>
        
        <!-- Mevcut Koşullar -->
        <h2>Mevcut Koşullar</h2>
        @if($campaigns->conditions && $campaigns->conditions->count() > 0)
            @foreach($campaigns->conditions as $index => $condition)
                <div style="border: 1px solid #ccc; padding: 10px; margin: 5px;">
                    <label>Koşul {{ $index + 1 }}:</label>
                    <select name="existing_conditions[{{ $condition->id }}][condition_type]">

                        <option value="author" {{ $condition->condition_type == 'author' ? 'selected' : '' }}>Yazar</option>
                        <option value="category" {{ $condition->condition_type == 'category' ? 'selected' : '' }}>Kategori</option>
                        <option value="min_bag" {{ $condition->condition_type == 'min_bag' ? 'selected' : '' }}>Minimum Sepet Toplamı</option>    
                    
                    </select>

                    <input type="text" name="existing_conditions[{{ $condition->id }}][condition_value]" 
                        value="{{ is_string($condition->condition_value) && str_starts_with($condition->condition_value, '[') ? implode(', ', json_decode($condition->condition_value, true)) : $condition->condition_value }}" 
                        placeholder="Değer (örn: Sabahattin Ali veya Yaşar Kemal, Sabahattin Ali)">
                    <select name="existing_conditions[{{ $condition->id }}][operator]">

                        <option value="=" {{ $condition->operator == '=' ? 'selected' : '' }}>=</option>
                        <option value="!=" {{ $condition->operator == '!=' ? 'selected' : '' }}>!=</option>
                        <option value=">" {{ $condition->operator == '>' ? 'selected' : '' }}>&gt;</option>
                        <option value="<" {{ $condition->operator == '<' ? 'selected' : '' }}>&lt;</option>
                        <option value=">=" {{ $condition->operator == '>=' ? 'selected' : '' }}>&gt;=</option>
                        <option value="<=" {{ $condition->operator == '<=' ? 'selected' : '' }}>&lt;=</option>

                    </select>
                </div>
            @endforeach
        @else
            <p>Koşul bulunmuyor</p>
        @endif

        <!-- Mevcut İndirimler -->
        <h2>Mevcut İndirimler</h2>
        @if($campaigns->discounts && $campaigns->discounts->count() > 0)
            @foreach($campaigns->discounts as $index => $discount)
                <div style="border: 1px solid #ccc; padding: 10px; margin: 5px;">
                    <label>İndirim {{ $index + 1 }}:</label>
                    <select name="existing_discounts[{{ $discount->id }}][discount_type]">
                        
                        <option value="percentage" {{ $discount->discount_type == 'percentage' ? 'selected' : '' }}>Yüzde</option>
                        <option value="fixed" {{ $discount->discount_type == 'fixed' ? 'selected' : '' }}>Sabit Tutar</option>
                        <option value="x_buy_y_pay" {{ $discount->discount_type == 'x_buy_y_pay' ? 'selected' : '' }}>X Al Y Öde</option>
                    
                    </select>

                    <input type="text" name="existing_discounts[{{ $discount->id }}][discount_value]" 
                        value="{{ is_string($discount->discount_value) && str_starts_with($discount->discount_value, '"') ? json_decode($discount->discount_value, true) : $discount->discount_value }}" 
                        placeholder="Değer">

                </div>
            @endforeach
        @else
            <p>İndirim bulunmuyor</p>
        @endif
        <br>
        <br>
        <button type="submit">Güncelle</button>
        <br>
        <br>
        <a href="{{ route('admin.campaign') }}">Geri Dön</a>
    </form>

    <script>
        // Form submit edildiğinde virgülle ayrılmış yazar değerlerini JSON array'e çevir
        document.querySelector('form').addEventListener('submit', function(e) {
            // Mevcut koşullar için kontrol
            const existingConditions = document.querySelectorAll('[name*="existing_conditions"]');
            const conditionGroups = {};
            
            // Koşulları gruplandır
            existingConditions.forEach(function(element) {
                const match = element.name.match(/existing_conditions\[(\d+)\]\[(\w+)\]/);
                if (match) {
                    const id = match[1];
                    const field = match[2];
                    if (!conditionGroups[id]) conditionGroups[id] = {};
                    conditionGroups[id][field] = element;
                }
            });
            
            // Her grup için kontrol et
            Object.values(conditionGroups).forEach(function(group) {
                if (group.condition_type && group.condition_value && 
                    group.condition_type.value === 'author') {
                    const value = group.condition_value.value.trim();
                    // Eğer virgül varsa ve JSON array değilse
                    if (value.includes(',') && !value.startsWith('[')) {
                        // Virgülle ayır, temizle ve JSON array yap
                        const authors = value.split(',').map(author => author.trim());
                        group.condition_value.value = JSON.stringify(authors);
                    }
                }
            });
        });
    </script>
</body>
</html>