<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampanya Ekle</title>
</head>
<body>
    <h1>Kampanya Ekle</h1>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('seller.createCampaign') }}" method="POST">
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
                    <option value="min_bag">Minimum Sepet Fiyatı</option>
                </select>

                <input style="width: 30%;" type="text" name="conditions[0][condition_value]" 
                placeholder="Değer (örn: Sabahattin Ali veya Yaşar Kemal, Sabahattin Ali)" required>

                <select name="conditions[0][operator]">
                    <option value="=">=</option>
                    <option value="!=">!=</option>
                    <option value=">">&gt;</option>
                    <option value="<">&lt;</option>
                    <option value=">=">&gt;=</option>
                    <option value="<=">&lt;=</option>
                    <option value="in">in</option>
                    <option value="not_in">not_in</option>
                </select>
                <button type="button" onclick="removeCondition(this)">Kaldır</button>
            </div>
        </div>
        <button type="button" onclick="addCondition()">Koşul Ekle</button>
        
        <h2>İndirimler</h2>
        <p>Değer (örn: {"percentage":15} veya {"amount":100} veya {"x":2, "y":1})</p>
        <div id="discounts">
            <div class="discount-item">
                <select name="discounts[0][discount_type]">
                    <option value="percentage">Yüzde</option>
                    <option value="fixed">Sabit Tutar</option>
                    <option value="x_buy_y_pay">X Al Y Öde</option>
                </select>
                <input type="text" name="discounts[0][discount_value]" 
                placeholder="Değer" required>
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

        // Form submit edildiğinde virgülle ayrılmış yazar değerlerini JSON array'e çevir
        document.querySelector('form').addEventListener('submit', function(e) {
            // Tüm condition itemları bul
            const conditionItems = document.querySelectorAll('.condition-item');
            conditionItems.forEach(function(item) {
                const typeSelect = item.querySelector('select[name*="condition_type"]');
                const valueInput = item.querySelector('input[name*="condition_value"]');
                
                if (typeSelect && valueInput && typeSelect.value === 'author') {
                    const value = valueInput.value.trim();
                    // Eğer virgül varsa ve JSON array değilse
                    if (value.includes(',') && !value.startsWith('[')) {
                        // Virgülle ayır, temizle ve JSON array yap
                        const authors = value.split(',').map(author => author.trim());
                        valueInput.value = JSON.stringify(authors);
                    }
                }
            });
        });
        
        function addCondition() {
            const conditionsDiv = document.getElementById('conditions');
            const newCondition = `
                <div class="condition-item">
                    <select name="conditions[${conditionIndex}][condition_type]">
                        <option value="author">Yazar</option>
                        <option value="category">Kategori</option>
                        <option value="min_bag">Minimum Sepet Fiyatı</option>
                    </select>
                    <input type="text" name="conditions[${conditionIndex}][condition_value]" placeholder="Değer (örn: Sabahattin Ali veya Yaşar Kemal, Sabahattin Ali)" required>
                    <select name="conditions[${conditionIndex}][operator]">
                        <option value="=">=</option>
                        <option value="!=">!=</option>
                        <option value=">">&gt;</option>
                        <option value="<">&lt;</option>
                        <option value=">=">&gt;=</option>
                        <option value="<=">&lt;=</option>
                        <option value="in">in</option>
                        <option value="not_in">not_in</option>
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
                        <option value="x_buy_y_pay">X Al Y Öde</option>
                    </select>
                    <input type="text" name="discounts[${discountIndex}][discount_value]" 
                    placeholder="Değer (örn: {"percentage":15} veya {"amount":100} veya {"x":2, "y":1})" required>
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
    <a href="{{ route('seller.campaign') }}">Geri Dön</a>
</body>
</html>
