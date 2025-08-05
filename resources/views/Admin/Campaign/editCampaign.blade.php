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
        <label for="condition_logic">Koşul Mantığı:  </label>
        <select name="condition_logic" id="condition_logic">
            <option value="AND" {{ old('condition_logic', $campaigns->condition_logic) == 'AND' ? 'selected' : '' }}>AND</option>
            <option value="OR" {{ old('condition_logic', $campaigns->condition_logic) == 'OR' ? 'selected' : '' }}>OR</option>
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
                        <option value="min_total" {{ $condition->condition_type == 'min_total' ? 'selected' : '' }}>Minimum Fiyat</option>                    </select>
                    <input type="text" name="existing_conditions[{{ $condition->id }}][condition_value]" 
                           value="{{ $condition->condition_value }}" placeholder="Değer">
                    <select name="existing_conditions[{{ $condition->id }}][operator]">
                        <option value="=" {{ $condition->operator == '=' ? 'selected' : '' }}>=</option>
                        <option value="!=" {{ $condition->operator == '!=' ? 'selected' : '' }}>!=</option>
                        <option value=">" {{ $condition->operator == '>' ? 'selected' : '' }}>&gt;</option>
                        <option value="<" {{ $condition->operator == '<' ? 'selected' : '' }}>&lt;</option>
                        <option value=">=" {{ $condition->operator == '>=' ? 'selected' : '' }}>&gt;=</option>
                        <option value="<=" {{ $condition->operator == '<=' ? 'selected' : '' }}>&lt;=</option>
                    </select>
                    <label>
                        <input type="checkbox" name="delete_conditions[]" value="{{ $condition->id }}"> Sil
                    </label>
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
                           value="{{ $discount->discount_value }}" placeholder="Değer">
                    <select name="existing_discounts[{{ $discount->id }}][applies_to]">
                        <option value="product" {{ $discount->applies_to == 'product' ? 'selected' : '' }}>Ürün</option>
                        <option value="bag_total" {{ $discount->applies_to == 'bag_total' ? 'selected' : '' }}>Sepet Toplamı</option>
                        <option value="product_author" {{ $discount->applies_to == 'product_author' ? 'selected' : '' }}>Ürün Yazarı</option>
                    </select>
                    <label>
                        <input type="checkbox" name="delete_discounts[]" value="{{ $discount->id }}"> Sil
                    </label>
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
</body>
</html>