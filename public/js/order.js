let currentStep = 1;

function nextStep(step) {
    if (validateCurrentStep()) {
        hideStep(currentStep);
        currentStep = step;
        showStep(currentStep);
        updateStepProgress();
    }
}

function prevStep(step) {
    hideStep(currentStep);
    currentStep = step;
    showStep(currentStep);
    updateStepProgress();
}

function showStep(step) {
    document.getElementById(`step-${step}`).style.display = 'block';
}

function hideStep(step) {
    document.getElementById(`step-${step}`).style.display = 'none';
}

function updateStepProgress() {
    document.querySelectorAll('.step-item').forEach((item, index) => {
        const stepNumber = index + 1;
        item.classList.remove('active', 'completed');
        
        if (stepNumber < currentStep) {
            item.classList.add('completed');
        } else if (stepNumber === currentStep) {
            item.classList.add('active');
        }
    });
}

function handleEditShippingAddress(id) {
    const editShippingAddressForm = document.getElementById(`edit-shipping-address-form-${id}`);
    const editShippingAddressBtn = document.getElementById(`edit-shipping-address-btn-${id}`);
    
    if (editShippingAddressBtn) {
        editShippingAddressBtn.style.display = 'none';
    }
    if (editShippingAddressForm) {
        editShippingAddressForm.style.display = 'block';
    }
}

function handleShippingAddressToggle() {
    const newShippingAddressCheckbox = document.getElementById('new_shipping_address');
    const addressItemSelected = document.getElementById('shipping-address-item');
    const newShippingAddressForm = document.getElementById('new-shipping-address-form');
    const saveShippingAddressBtn = document.getElementById('save-shipping-address-btn');

    const fields = [
        'new_shipping_address_first_name',
        'new_shipping_address_last_name',
        'new_shipping_address_phone',
        'new_shipping_address_address_line_1',
        'new_shipping_address_district',
        'new_shipping_address_city',
        'new_shipping_address_country',
    ];

    function updateNewShippingFieldsRequired() {
        const isNewShippingSelected = newShippingAddressCheckbox && newShippingAddressCheckbox.checked;
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.required = isNewShippingSelected;
            }
        });
    }

    if (newShippingAddressCheckbox.checked) {
        addressItemSelected.style.display = 'none';
        newShippingAddressForm.style.display = 'block';
        saveShippingAddressBtn.style.display = 'block';
        updateNewShippingFieldsRequired();
    }
    else {
        addressItemSelected.style.display = 'block';
        newShippingAddressForm.style.display = 'none';
        saveShippingAddressBtn.style.display = 'none';
    }
}

function validateCurrentStep() {
    // Ödeme sayfasında olduğumuzu kontrol et
    const step3Element = document.getElementById('step-3');
    const isOnPaymentStep = step3Element && step3Element.style.display !== 'none';
    
    if (currentStep === 1 || !isOnPaymentStep) {
        const shippingAddress = document.querySelector('input[name="shipping_address_id"]:checked');
        
        if (!shippingAddress) {
            alert('Lütfen teslimat adresini seçiniz!');
            return false;
        }
        document.getElementById('shipping_address_id').value = shippingAddress.value;

    } else if (currentStep === 2) {
        return true;
    } else if (currentStep === 3 || isOnPaymentStep) {
        const billingSameCheckbox = document.getElementById('billing_same_as_shipping');
        const newBillingCheckbox = document.getElementById('new_billing_address');
        
        
        if (billingSameCheckbox && billingSameCheckbox.checked) {
            const shippingAddress = document.querySelector('input[name="shipping_address_id"]:checked');
            if (shippingAddress) {
                document.getElementById('billing_address_id').value = shippingAddress.value;
            }
        } else if (newBillingCheckbox && newBillingCheckbox.checked) {
            if (!validateNewBillingAddress()) {
                return false;
            }
            document.getElementById('billing_address_id').value = 'new_billing_address';
        } else {
            const billingAddress = document.querySelector('input[name="billing_address_selection"]:checked');
            if (!billingAddress) {
                alert('Lütfen fatura adresini seçiniz!');
                return false;
            }
            document.getElementById('billing_address_id').value = billingAddress.value;
        }
        
        return validatePaymentForm();
    }
    return true;
}

function validatePaymentForm() {
    const selectedCard = document.getElementById('credit_card_id').value;
    
    if (!selectedCard) {
        alert('Lütfen bir ödeme yöntemi seçiniz!');
        return false;
    }
    
    if (selectedCard === 'new_card') {
        // Önce expire_date'i expire_month ve expire_year'a dönüştür
        const expireDateInput = document.getElementById('new_expire_date');
        if (expireDateInput && expireDateInput.value) {
            const parts = expireDateInput.value.split('/');
            if (parts.length === 2) {
                const month = parts[0].padStart(2, '0');
                const year = '20' + parts[1];
                document.getElementById('new_expire_month').value = month;
                document.getElementById('new_expire_year').value = year;
            }
        }
        
        const fields = [
            {id: 'new_card_holder_name', name: 'Kart sahibi adı'},
            {id: 'new_card_name', name: 'Kart ismi'},
            {id: 'new_card_number', name: 'Kart numarası', length: 16},
            {id: 'new_expire_date', name: 'Son kullanma tarihi'},
            {id: 'new_cvv', name: 'CVV kodu', length: 3}
        ];
        
        for (let field of fields) {
            const element = document.getElementById(field.id);
            const value = element.value.trim();
            
            if (!value) {
                alert(`Lütfen ${field.name} giriniz!`);
                element.focus();
                return false;
            }
            
            if (field.length && value.length !== field.length) {
                alert(`${field.name} ${field.length} haneli olmalıdır!`);
                element.focus();
                return false;
            }
        }
    }
    
    return true;
}
function handleBillingAddressToggle() {
    const checkbox = document.getElementById('billing_same_as_shipping');
    const billingAddressSection = document.getElementById('billing-address-item');
    const newBillingToggle = document.getElementById('new-billing-address-toggle');
    const newBillingAddressForm = document.getElementById('new-billing-address-form');
    const newBillingCheckbox = document.getElementById('new_billing_address');

    // Yeni fatura adresi alanlarının required attribute'unu yönet
    const newBillingFields = [
        'new_billing_address_title',
        'new_billing_address_first_name', 
        'new_billing_address_last_name',
        'new_billing_address_phone',
        'new_billing_address_address',
        'new_billing_address_district',
        'new_billing_address_city',
        'new_billing_address_postal_code',
        'new_billing_address_country'
    ];
    
    function updateNewBillingFieldsRequired() {
        const isNewBillingSelected = newBillingCheckbox && newBillingCheckbox.checked;
        newBillingFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.required = isNewBillingSelected;
            }
        });
    }

    if (checkbox.checked) {
        billingAddressSection.style.display = 'none';
        newBillingToggle.style.display = 'none';
        newBillingAddressForm.style.display = 'none';
        
        if (newBillingCheckbox) {
            newBillingCheckbox.checked = false;
        }
        
        updateBillingAddressFromShipping();
        updateNewBillingFieldsRequired();
    } else {
        billingAddressSection.style.display = 'block';
        newBillingToggle.style.display = 'block';
        
        newBillingAddressForm.style.display = 'none';
        
        if (newBillingCheckbox) {
            newBillingCheckbox.checked = false;
        }
        
        updateNewBillingFieldsRequired();
    }
    
    // Sayfa yüklendiğinde başlangıç durumunu ayarla
    updateNewBillingFieldsRequired();
}

function updateBillingAddressFromShipping() {
    const shippingAddress = document.querySelector('input[name="shipping_address_id"]:checked');
    if (shippingAddress) {
        const billingAddresses = document.querySelectorAll('input[name="billing_address_id"]');
        billingAddresses.forEach(address => {
            address.checked = false;
        });
        const billingAddressId = document.getElementById('billing_address_id');
        if (billingAddressId) {
            billingAddressId.value = shippingAddress.value;
        }
    }
}

function onShippingAddressChange() {
    const checkbox = document.getElementById('billing_same_as_shipping');
    if (checkbox && checkbox.checked) {
        updateBillingAddressFromShipping();
    }
}

function handleNewBillingAddressToggle() {
    const checkbox = document.getElementById('new_billing_address');
    const newBillingForm = document.getElementById('new-billing-address-form');
    const existingBillingSection = document.getElementById('billing-address-item');
    if (checkbox.checked) {
        newBillingForm.style.display = 'block';
        existingBillingSection.style.display = 'none';
    } else {
        newBillingForm.style.display = 'none';
        const billingSameCheckbox = document.getElementById('billing_same_as_shipping');
        if (billingSameCheckbox && !billingSameCheckbox.checked) {
            existingBillingSection.style.display = 'block';
        }
    }
}



function initializePaymentPage() {
    // Kayıtlı kartlar varsa ilk kartı seç
    const firstCard = document.querySelector('input[name="credit_card_selection"]');
    if (firstCard) {
        firstCard.checked = true;
        document.getElementById('credit_card_id').value = firstCard.value;
    }
}

function handleCardSelection(cardValue) {
    const newCardForm = document.getElementById('new-card-form');
    
    if (newCardForm) {
        newCardForm.style.display = 'none';
        clearFormValidation();
        
        if (cardValue === 'new_card') {
            newCardForm.style.display = 'block';
            setNewCardValidation();
        }
    }
}


function clearFormValidation() {
    document.getElementById('new_card_holder_name').required = false;
    document.getElementById('new_card_name').required = false;
    document.getElementById('new_card_number').required = false;
    document.getElementById('new_expire_month').required = false;
    document.getElementById('new_expire_year').required = false;
    document.getElementById('new_cvv').required = false;
}

function setNewCardValidation() {
    document.getElementById('new_card_holder_name').required = true;
    document.getElementById('new_card_name').required = true;
    document.getElementById('new_card_number').required = true;
    document.getElementById('new_expire_month').required = true;
    document.getElementById('new_expire_year').required = true;
    document.getElementById('new_cvv').required = true;
}

function validateNewBillingAddress() {
    const requiredFields = [
        {id: 'new_billing_address_title', name: 'Başlık', maxLength: 255},
        {id: 'new_billing_address_first_name', name: 'Ad', maxLength: 255},
        {id: 'new_billing_address_last_name', name: 'Soyad', maxLength: 255},
        {id: 'new_billing_address_phone', name: 'Telefon', maxLength: 255},
        {id: 'new_billing_address_address', name: 'Adres', maxLength: 255},
        {id: 'new_billing_address_address_2', name: 'Adres 2', maxLength: 255},
        {id: 'new_billing_address_district', name: 'İlçe', maxLength: 255},
        {id: 'new_billing_address_city', name: 'İl', maxLength: 255},
        {id: 'new_billing_address_postal_code', name: 'Posta Kodu', maxLength: 10},
        {id: 'new_billing_address_country', name: 'Ülke', maxLength: 255}
    ];
    
    for (let field of requiredFields) {
        const element = document.getElementById(field.id);
        
        if (!element.value.trim()) {
            alert(`Lütfen ${field.name} giriniz!`);
            element.focus();
            return false;
        }
        
        if (element.value.length > field.maxLength) {
            alert(`${field.name} en fazla ${field.maxLength} karakter olabilir!`);
            element.focus();
            return false;
        }
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const newShippingAddressCheckbox = document.getElementById('new_shipping_address');
    if (newShippingAddressCheckbox) {
        newShippingAddressCheckbox.addEventListener('change', handleShippingAddressToggle);
    }

    // Her adres için düzenle butonuna event listener ekle
    document.querySelectorAll('[id^="edit-shipping-address-btn-"]').forEach(btn => {
        const addressId = btn.id.replace('edit-shipping-address-btn-', '');
        btn.addEventListener('click', () => handleEditShippingAddress(addressId));
    });

    const inputLimits = {
        'new_billing_address_title': 255,
        'new_billing_address_first_name': 255,
        'new_billing_address_last_name': 255,
        'new_billing_address_phone': 255,
        'new_billing_address_address': 255,
        'new_billing_address_address_2': 255,
        'new_billing_address_district': 255,
        'new_billing_address_city': 255,
        'new_billing_address_postal_code': 10,
        'new_billing_address_country': 255
    };
    
    Object.keys(inputLimits).forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', function() {
                if (this.value.length > inputLimits[id]) {
                    this.value = this.value.substring(0, inputLimits[id]);
                }
            });
        }
    });

    // Form submit
    const orderForm = document.getElementById('order-form');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            if (!validateCurrentStep()) {
                e.preventDefault();
                return false;
            }
            
            const selectedCard = document.getElementById('credit_card_id').value;
            
            if (selectedCard === 'new_card') {
                const expireDateInput = document.getElementById('new_expire_date');
                if (expireDateInput && expireDateInput.value) {
                    const parts = expireDateInput.value.split('/');
                    if (parts.length === 2) {
                        const month = parts[0].padStart(2, '0');
                        const year = '20' + parts[1];
                        document.getElementById('new_expire_month').value = month;
                        document.getElementById('new_expire_year').value = year;
                    }
                }
            }
        });
    }

    // Yeni kart toggle
    const newCardToggle = document.getElementById('new_card');
    if (newCardToggle) {
        newCardToggle.addEventListener('change', function() {
            const newCardForm = document.getElementById('new-card-form');
            const creditCardItems = document.querySelectorAll('.credit-card-item');
            
            if (this.checked) {
                newCardForm.style.display = 'block';
                creditCardItems.forEach(item => {
                    item.style.display = 'none';
                });
                document.getElementById('credit_card_id').value = 'new_card';
            } else {
                newCardForm.style.display = 'none';
                creditCardItems.forEach(item => {
                    item.style.display = 'block';
                });
                const firstCard = document.querySelector('input[name="credit_card_selection"]:not([value="new_card"])');
                if (firstCard) {
                    firstCard.checked = true;
                    document.getElementById('credit_card_id').value = firstCard.value;
                }
            }
        });
    }

    // Kart seçimi
    document.querySelectorAll('input[name="credit_card_id"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.getElementById('credit_card_id').value = radio.value;
            
            document.querySelectorAll('.credit-card-item').forEach(item => item.classList.remove('selected'));
            radio.closest('.credit-card-item').classList.add('selected');
            
            const newCardToggle = document.getElementById('new_card');
            if (newCardToggle && radio.value !== 'new_card') {
                newCardToggle.checked = false;
                const newCardForm = document.getElementById('new-card-form');
                if (newCardForm) {
                    newCardForm.style.display = 'none';
                }
            }
            
            handleCardSelection(radio.value);
        });
    });

    // Adres seçimi
    const billingSameCheckbox = document.getElementById('billing_same_as_shipping');
    if (billingSameCheckbox) {
        billingSameCheckbox.addEventListener('change', handleBillingAddressToggle);
    }

    const newBillingCheckbox = document.getElementById('new_billing_address');
    if (newBillingCheckbox) {
        newBillingCheckbox.addEventListener('change', handleNewBillingAddressToggle);
    }

    document.querySelectorAll('input[name="shipping_address_id"]').forEach(radio => {
        radio.addEventListener('change', function(){
            document.getElementById('shipping_address_id').value = this.value;
            onShippingAddressChange();
        });
    });
    document.querySelectorAll('input[name="billing_address_selection"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('billing_address_id').value = this.value;
        });
    });

    

    // Kart numarası formatı
    document.getElementById('new_card_number')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 16) value = value.substr(0, 16);
        e.target.value = value;
    });

    // CVC formatı
    document.getElementById('new_cvv')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) value = value.substr(0, 3);
        e.target.value = value;
    });

    // Tarih formatı (MM/YY)
    document.getElementById('new_expire_date')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        if (value.length > 5) value = value.substring(0, 5);
        e.target.value = value;
    });


    // Sayfa yüklendiğinde kart durumunu kontrol et
    initializePaymentPage();
    handleBillingAddressToggle();
});

