function resetFilters(){
    const searchQuery = document.getElementById('q')?.value || '';
    const url = new URL(window.location.origin + '/search');
    url.searchParams.set('q', searchQuery);
    url.searchParams.set('page', '1');
    url.searchParams.set('size', '12');
    window.location.href = url.toString();
}

function resetFilter(){
    const url = new URL(window.location.origin + '/filter');
    url.searchParams.set('page', '1');
    url.searchParams.set('size', '12');
    window.location.href = url.toString();
}

// Dropdown functionality
function toggleDropdown() {
    const dropdown = document.getElementById('sortingDropdown');
    const content = document.getElementById('dropdownContent');
    
    dropdown.classList.toggle('active');
    content.classList.toggle('show');
}

function selectOption(value, text) {
    document.getElementById('selectedOption').textContent = text;
    document.getElementById('sortingValue').value = value;
    
    // Update selected state
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.classList.remove('selected');
    });
    event.target.classList.add('selected');
    
    // Close dropdown
    document.getElementById('sortingDropdown').classList.remove('active');
    document.getElementById('dropdownContent').classList.remove('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.custom-dropdown');
    if (!dropdown.contains(event.target)) {
        document.getElementById('sortingDropdown').classList.remove('active');
        document.getElementById('dropdownContent').classList.remove('show');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.add-to-bag-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = form.querySelector('button');
            const originalText = button.textContent;
            button.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;">
                    <path d="M21 12a9 9 0 11-6.219-8.56"/>
                </svg>
                Ekleniyor
            `;
            button.disabled = true;
            
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Form verilerini al
                const productData = {
                    title: formData.get('product_title'),
                    author: formData.get('product_author'),
                    price: formData.get('product_price'),
                    image: formData.get('product_image')
                };
                
                if(data.success) {
                    showNotification(data.message, 'success', productData);
                } else {
                    showNotification(data.message, 'error', productData);
                }
                
                button.innerHTML = originalText;
                button.disabled = false;
            })
            .catch(error => {
                showNotification('Bir hata oluştu!', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
    });
});

function showNotification(message, type, productData = null) {
    // Mevcut bildirimleri kaldır
    const existingNotification = document.querySelector('.cart-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const isSuccess = type === 'success';
    const iconBg = isSuccess ? 'var(--success)' : 'var(--danger)';
    const title = isSuccess ? 'SEPETİNİZE EKLENDİ' : 'ÜRÜN SEPETE EKLENEMEDİ';
    const iconSvg = isSuccess ? 
        `<path d="M6 2l1 7h10l1-7"/><path d="M5 9h14l-1 11H6L5 9z"/><path d="M9 13h6"/>` :
        `<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>`;
    
    // Ürün bilgisi HTML'i
    let productHtml = '';
    if (productData && isSuccess) {
        productHtml = `
            <div class="cart-product">
                <div class="cart-product-image">
                    <img src="/storage/productsImages/${productData.image}" alt="${productData.title}">
                </div>
                <div class="cart-product-info">
                    <div class="cart-product-title">${productData.title}</div>
                    <div class="cart-product-author">${productData.author}</div>
                    <div class="cart-product-price">${parseFloat(productData.price).toFixed(2)} TL</div>
                </div>
            </div>
        `;
    }
    
    // Sepet bildirimi oluştur
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `
        <div class="cart-notification-content">
            <div class="cart-icon" style="background: ${iconBg}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    ${iconSvg}
                </svg>
            </div>
            <div class="cart-text">
                <div class="cart-title">${title}</div>
                ${productHtml}
                <div class="cart-message">${message}</div>
            </div>
            <button class="cart-close" onclick="this.parentElement.parentElement.remove()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        ${isSuccess ? '<div class="cart-actions"><a href="/bag" class="cart-btn cart-btn-outline">SEPETE GİT</a></div>' : ''}
    `;
    
    // Body'ye ekle
    document.body.appendChild(notification);
    
    // Animasyonla göster
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // 5 saniye sonra kaldır
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Otomatik Tamamlama Sistemi
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('q');
    const searchGroup = document.querySelector('.search-group');
    
    if (!searchInput || !searchGroup) return;
    
    // Otomatik tamamlama container'ı oluştur
    const autocompleteContainer = document.createElement('div');
    autocompleteContainer.className = 'autocomplete-container';
    searchGroup.appendChild(autocompleteContainer);
    
    let debounceTimer;
    let selectedIndex = -1;
    let suggestions = [];
    let isLoading = false;
    
    // Debounce fonksiyonu
    function debounce(func, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(func, delay);
    }
    
    // Otomatik tamamlama verilerini getir
    async function fetchAutocomplete(query) {
        if (query.length < 2) {
            hideAutocomplete();
            return;
        }
        
        isLoading = true;
        showLoadingState();
        
        try {
            // Web route kullanıyoruz (/search/autocomplete)
            const response = await fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success && data.data && data.data.products && data.data.products.length > 0) {
                suggestions = data.data.products;
                showAutocomplete();
            } else {
                showNoResults();
            }
        } catch (error) {
            console.error('Otomatik tamamlama hatası:', error);
            showError(error.message);
        } finally {
            isLoading = false;
        }
    }
    
    // Loading durumunu göster
    function showLoadingState() {
        autocompleteContainer.innerHTML = '<div class="autocomplete-loading">Aranıyor...</div>';
        autocompleteContainer.style.display = 'block';
    }
    
    // Sonuç bulunamadı mesajı
    function showNoResults() {
        autocompleteContainer.innerHTML = '<div class="autocomplete-loading">Sonuç bulunamadı</div>';
        autocompleteContainer.style.display = 'block';
    }
    
    // Hata mesajı
    function showError(message = 'Bir hata oluştu') {
        autocompleteContainer.innerHTML = `<div class="autocomplete-loading">${message}</div>`;
        autocompleteContainer.style.display = 'block';
    }
    
    // Otomatik tamamlama listesini göster
    function showAutocomplete() {
        autocompleteContainer.innerHTML = '';
        
        suggestions.forEach((product, index) => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            
            const imageUrl = product.images && product.images.length > 0 
                ? `/storage/productsImages/${product.images[0]}`
                : '';
            
            item.innerHTML = `
                <div class="autocomplete-product-image">
                    ${imageUrl ? `<img src="${imageUrl}" alt="${product.title}" onerror="this.style.display='none'">` : ''}
                </div>
                <div class="autocomplete-product-info">
                    <div class="autocomplete-product-title">
                        ${highlightQuery(product.title, searchInput.value)}
                    </div>
                    <div class="autocomplete-product-author">${product.author || ''}</div>
                    <div class="autocomplete-product-store">${product.store_name || ''}</div>
                </div>
                <div class="autocomplete-product-price">
                    ${parseFloat(product.list_price || 0).toFixed(2)} TL
                </div>
            `;
            
            item.addEventListener('click', () => {
                selectSuggestion(product);
            });
            
            item.addEventListener('mouseenter', () => {
                selectedIndex = index;
                updateSelection();
            });
            
            autocompleteContainer.appendChild(item);
        });
        
        autocompleteContainer.style.display = 'block';
        selectedIndex = -1;
    }
    
    // Otomatik tamamlama listesini gizle
    function hideAutocomplete() {
        autocompleteContainer.style.display = 'none';
        selectedIndex = -1;
    }
    
    // Seçili öğeyi vurgula
    function updateSelection() {
        const items = autocompleteContainer.querySelectorAll('.autocomplete-item');
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
    }
    
    // Öneriyi seç
    function selectSuggestion(product) {
        searchInput.value = product.title;
        hideAutocomplete();
        
        // Arama formunu otomatik gönder
        const form = searchInput.closest('form');
        if (form) {
            form.submit();
        }
    }
    
    // Arama terimini vurgula
    function highlightQuery(text, query) {
        if (!query || !text) return text;
        const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        return text.replace(regex, '<mark style="background: var(--primary); color: white; padding: 1px 2px; border-radius: 2px;">$1</mark>');
    }
    
    // Input event listener
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        debounce(() => fetchAutocomplete(query), 300);
    });
    
    // Klavye navigasyonu
    searchInput.addEventListener('keydown', function(e) {
        if (autocompleteContainer.style.display === 'none') return;
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, suggestions.length - 1);
                updateSelection();
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection();
                break;
                
            case 'Enter':
                e.preventDefault();
                if (selectedIndex >= 0 && suggestions[selectedIndex]) {
                    selectSuggestion(suggestions[selectedIndex]);
                } else {
                    // Form gönder
                    const form = this.closest('form');
                    if (form) form.submit();
                }
                break;
                
            case 'Escape':
                hideAutocomplete();
                break;
        }
    });
    
    // Dışarı tıklandığında gizle
    document.addEventListener('click', function(e) {
        if (!searchGroup.contains(e.target)) {
            hideAutocomplete();
        }
    });
    
    // Focus olduğunda mevcut değer varsa önerileri göster
    searchInput.addEventListener('focus', function() {
        const query = this.value.trim();
        if (query.length >= 2) {
            fetchAutocomplete(query);
        }
    });
});
