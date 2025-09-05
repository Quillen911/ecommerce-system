/**
 * Sepet Sayfası JavaScript Fonksiyonları
 * Miktar güncelleme ve form işlemleri
 */

function updateQuantity(itemId, change) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const quantityElement = document.querySelector(`[data-item-id="${itemId}"] .quantity-number`);
    const currentQuantity = parseInt(quantityElement.textContent);
    const newQuantity = currentQuantity + change;
    
    // Miktar 0'dan küçükse ürünü sil
    if (newQuantity < 1) {
        if (confirm('Bu ürünü sepetten tamamen silmek istediğinizden emin misiniz?')) {
            deleteItem(itemId, token);
        }
        return;
    }
    
    // Yükleme göstergesi
    quantityElement.textContent = '...';
    
    // Form oluştur ve gönder
    submitQuantityUpdate(itemId, newQuantity, token);
}

/**
 * Ürünü sepetten sil
 */
function deleteItem(itemId, token) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/bag/${itemId}`;
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = token;
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    
    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}

/**
 * Miktar güncelleme formunu gönder
 */
function submitQuantityUpdate(itemId, newQuantity, token) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/bag/update/${itemId}`;
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = token;
    
    const quantityInput = document.createElement('input');
    quantityInput.type = 'hidden';
    quantityInput.name = 'quantity';
    quantityInput.value = newQuantity;
    
    form.appendChild(csrfInput);
    form.appendChild(quantityInput);
    document.body.appendChild(form);
    form.submit();
}

/**
 * Sayfa yüklendiğinde çalışacak fonksiyonlar
 */
document.addEventListener('DOMContentLoaded', function() {
    // CSRF token kontrolü
    const token = document.querySelector('meta[name="csrf-token"]');
    if (!token) {
        console.warn('CSRF token bulunamadı');
    }
    
    // Sepet boşsa özel işlemler
    const emptyState = document.querySelector('.empty-state');
    if (emptyState) {
        console.log('Sepet boş');
    }
});
