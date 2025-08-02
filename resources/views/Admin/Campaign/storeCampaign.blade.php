<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampanya Ekle</title>
</head>
<body>
    <h1>Kampanya Ekle</h1>
    
    <form action="{{ route('admin.createCampaign') }}" method="POST">
        @csrf
        

        <h2>Kampanya Bilgileri</h2>
        <input type="text" name="name" placeholder="Kampanya Adı" required> <br>
        <select name="type" required>
            <option value="">Kampanya Tipi Seçin</option>
            <option value="percentage">Yüzde İndirim</option>
            <option value="fixed">Sabit İndirim</option>
            <option value="x_buy_y_pay">X Al Y Öde</option>
        </select> <br>
        <input type="text" name="description" placeholder="Açıklama"> <br>
        <select name="is_active">
            <option value="1">Aktif</option>
            <option value="0">Pasif</option>
        </select> <br>
        <input type="text" name="priority" placeholder="Öncelik"> <br>
        <input type="number" name="usage_limit" placeholder="Kullanım Limiti" required> <br>
        <input type="number" name="usage_limit_for_user" placeholder="Kullanıcı Limiti" required> <br>
        <input type="date" name="starts_at" required> <br>
        <input type="date" name="ends_at" required> <br>
        

        <h2>Koşullar</h2>
        <div id="conditions">
            <div class="condition-item">
                <select name="conditions[0][condition_type]">
                    <option value="author">Yazar</option>
                    <option value="category">Kategori</option>
                    <option value="min_total">Minimum Fiyat</option>
                    <option value="product">Ürün</option>
                </select>

                <input style="width: 30%;" type="text" name="conditions[0][condition_value]" 
                placeholder="Değer (örn: 'Sabahattin Ali' veya 200 veya ['Yazar1','Yazar2'])" required>

                <select name="conditions[0][operator]">
                    <option value="=">=</option>
                    <option value="!=">!=</option>
                    <option value=">">&gt;</option>
                    <option value="<">&lt;</option>
                    <option value=">=">&gt;=</option>
                    <option value="<=">&lt;=</option>
                </select>
                <button type="button" onclick="removeCondition(this)">Kaldır</button>
            </div>
        </div>
        <button type="button" onclick="addCondition()">Koşul Ekle</button>
        
        <h2>İndirimler</h2>
        <div id="discounts">
            <div class="discount-item">
                <select name="discounts[0][discount_type]">
                    <option value="percentage">Yüzde</option>
                    <option value="fixed">Sabit Tutar</option>
                    <option value="x_buy_y_pay">X Al Y Öde</option>
                </select>
                <input style="width: 30%;" type="text" name="discounts[0][discount_value]" 
                placeholder="Değer (örn: %15 için {'discount':0.15})" required>
                <select name="discounts[0][applies_to]">
                    <option value="product">Ürün</option>
                    <option value="bag_total">Sepet Toplamı</option>
                    <option value="product_author">Ürün Yazarı</option>
                </select>
                <button type="button" onclick="removeDiscount(this)">Kaldır</button>
            </div>
        </div>
        <button type="button" onclick="addDiscount()">İndirim Ekle</button>
        
        <br><br>
        <button type="submit">Kampanya Oluştur</button>
    </form>
    
    <script>
        let conditionIndex = 1;
        let discountIndex = 1;
        
        function addCondition() {
            const conditionsDiv = document.getElementById('conditions');
            const newCondition = `
                <div class="condition-item">
                    <select name="conditions[${conditionIndex}][condition_type]">
                        <option value="author">Yazar</option>
                        <option value="category">Kategori</option>
                        <option value="min_total">Minimum Fiyat</option>
                        <option value="product">Ürün</option>
                    </select>
                    <input type="text" name="conditions[${conditionIndex}][condition_value]" placeholder="Değer" required>
                    <select name="conditions[${conditionIndex}][operator]">
                        <option value="=">=</option>
                        <option value="!=">!=</option>
                        <option value=">">&gt;</option>
                        <option value="<">&lt;</option>
                        <option value=">=">&gt;=</option>
                        <option value="<=">&lt;=</option>
                    </select>
                    <button type="button" onclick="removeCondition(this)">Kaldır</button>
                </div>
            `;
            conditionsDiv.insertAdjacentHTML('beforeend', newCondition);
            conditionIndex++;
        }
        
        function addDiscount() {
            const discountsDiv = document.getElementById('discounts');
            const newDiscount = `
                <div class="discount-item">
                    <select name="discounts[${discountIndex}][discount_type]">
                        <option value="percentage">Yüzde</option>
                        <option value="fixed">Sabit Tutar</option>
                    </select>
                    <input type="number" name="discounts[${discountIndex}][discount_value]" placeholder="Değer" required>
                    <select name="discounts[${discountIndex}][applies_to]">
                        <option value="order">Sipariş</option>
                        <option value="product">Ürün</option>
                        <option value="category">Kategori</option>
                    </select>
                    <button type="button" onclick="removeDiscount(this)">Kaldır</button>
                </div>
            `;
            discountsDiv.insertAdjacentHTML('beforeend', newDiscount);
            discountIndex++;
        }
        
        function removeCondition(button) {
            button.parentElement.remove();
        }
        
        function removeDiscount(button) {
            button.parentElement.remove();
        }
    </script>
    <a href="{{ route('admin.campaign') }}">Geri Dön</a>
</body>
</html>