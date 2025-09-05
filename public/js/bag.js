/**
 * Sepet Sayfası JavaScript Fonksiyonları
 * Optimized ama okunabilir versiyon
 */

// CSRF token'ı cache'le
let csrfToken;

function updateQuantity(itemId, change) {
    const token = csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const quantityElement = document.querySelector(`[data-item-id="${itemId}"] .quantity-number`);
    const currentQuantity = parseInt(quantityElement.textContent);
    const newQuantity = currentQuantity + change;
    
    if (newQuantity < 1) {
        if (confirm('Bu ürünü sepetten tamamen silmek istediğinizden emin misiniz?')) {
            deleteItem(itemId, token);
        }
        return;
    }
    
    quantityElement.textContent = '...';
    
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
    // CSRF token'ı cache'le
    csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.warn('CSRF token bulunamadı');
    }
});
