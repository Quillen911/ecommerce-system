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
    const editShippingAddressBtn = document.getElementById(`edit-shipping-address-btn-${id}`);
    const editShippingAddressFormContainer = document.getElementById(`edit-shipping-address-form-container-${id}`);
    
    if (!editShippingAddressBtn || !editShippingAddressFormContainer) {
        console.error('Edit button or form container not found for address:', id);
        return;
    }
    
    closeOtherEditForms(id);
    
    editShippingAddressBtn.style.display = 'none';
    editShippingAddressFormContainer.style.display = 'block';
}


function closeAllEditForms() {
    document.querySelectorAll('[id^="edit-shipping-address-form-container-"]').forEach(formContainer => {
        formContainer.style.display = 'none';
    });
    
    document.querySelectorAll('[id^="edit-shipping-address-btn-"]').forEach(btn => {
        btn.style.display = 'inline-block';
    });
}


function closeOtherEditForms(currentId) {
    document.querySelectorAll('[id^="edit-shipping-address-form-container-"]').forEach(formContainer => {
        const formId = formContainer.id.replace('edit-shipping-address-form-container-', '');
        if (formId !== currentId) {
            formContainer.style.display = 'none';
        }
    });
    
    document.querySelectorAll('[id^="edit-shipping-address-btn-"]').forEach(btn => {
        const btnId = btn.id.replace('edit-shipping-address-btn-', '');
        if (btnId !== currentId) {
            btn.style.display = 'inline-block';
        }
    });
}

function closeEditForm(addressId) {
    const formContainer = document.getElementById(`edit-shipping-address-form-container-${addressId}`);
    const editBtn = document.getElementById(`edit-shipping-address-btn-${addressId}`);
    
    if (formContainer) {
        formContainer.style.display = 'none';

    }
    if (editBtn) {
        editBtn.style.display = 'inline-block';

    }
    
    clearValidationErrors(addressId);
}

function clearValidationErrors(addressId) {
    const errorElements = document.querySelectorAll(`[id$="-error-${addressId}"]`);
    errorElements.forEach(element => {
        element.textContent = '';
    });
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

    // Event delegation for edit buttons (works with dynamically added elements)
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id && e.target.id.startsWith('edit-shipping-address-btn-')) {
            const addressId = e.target.id.replace('edit-shipping-address-btn-', '');
            console.log('Edit button clicked for address:', addressId);
            handleEditShippingAddress(addressId);
        }
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

    

    document.getElementById('new_card_number')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 16) value = value.substr(0, 16);
        e.target.value = value;
    });

    document.getElementById('new_cvv')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) value = value.substr(0, 3);
        e.target.value = value;
    });

    document.getElementById('new_expire_date')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        if (value.length > 5) value = value.substring(0, 5);
        e.target.value = value;
    });


    
    // Event delegation for form submissions (works with dynamically added elements)
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.id && e.target.id.startsWith('edit-shipping-address-form-')) {
            console.log('Form submitted:', e.target.id);
            handleUpdateShippingAddress(e);
        }
    });


    initializePaymentPage();
    handleBillingAddressToggle();
});


function handleUpdateShippingAddress(e) {
    e.preventDefault();
    
    const form = e.target;
    const formId = form.id;
    const addressId = formId.replace('edit-shipping-address-form-', '');
    
    const formData = new FormData(form);
    const saveBtn = form.querySelector('button[type="submit"]');
    

    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.textContent = 'Kaydediliyor...';
    }
    

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                           document.querySelector('input[name="_token"]')?.value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {

            const formContainer = document.getElementById(`edit-shipping-address-form-container-${addressId}`);
            if (formContainer) {
                formContainer.style.display = 'none';
            }
            

            const editBtn = document.getElementById(`edit-shipping-address-btn-${addressId}`);
            if (editBtn) {
                editBtn.style.display = 'inline-block';
            }
            

            clearValidationErrors(addressId);
            

            showSuccessMessage('Adres başarıyla güncellendi');
            

            updateAddressDisplay(addressId, formData);
        } else {

            if (data.errors) {
                showValidationErrors(data.errors, addressId);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Bir hata oluştu. Lütfen tekrar deneyin.');
    })
    .finally(() => {
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Adresi Kaydet';
        }
    });
}


function showValidationErrors(errors, addressId) {
    Object.keys(errors).forEach(field => {
        const errorElement = document.getElementById(`edit-shipping-address-${field}-error-${addressId}`);
        if (errorElement) {
            errorElement.textContent = errors[field][0];
        }
    });
}


function updateAddressDisplay(addressId, formData) {

    const addressCard = document.querySelector(`input[name="shipping_address_id"][value="${addressId}"]`).closest('div[style*="border"]');
    
    if (addressCard) {

        const titleElement = addressCard.querySelector('div[style*="font-weight:600"]');
        if (titleElement && formData.get('title')) {
            titleElement.textContent = formData.get('title');
        }
        

        const nameElements = addressCard.querySelectorAll('.address-info');
        if (nameElements.length > 0 && formData.get('first_name') && formData.get('last_name')) {
            nameElements[0].textContent = `${formData.get('first_name')} ${formData.get('last_name')}`;
        }
        

        if (nameElements.length > 1 && formData.get('phone')) {
            nameElements[1].textContent = formData.get('phone');
        }
        
        
        if (nameElements.length > 2 && formData.get('address_line_1')) {
            nameElements[2].textContent = formData.get('address_line_1');
        }
        
        if (nameElements.length > 3) {
            nameElements[3].textContent = formData.get('address_line_2') || '';
        }
        
        
        if (nameElements.length > 4 && formData.get('district') && formData.get('city')) {
            const postalCode = formData.get('postal_code') || '';
            nameElements[4].textContent = `${formData.get('district')} ${formData.get('city')} ${postalCode}`;
        }
        
        
        if (nameElements.length > 5 && formData.get('country')) {
            nameElements[5].textContent = formData.get('country');
        }
        
        
        if (nameElements.length > 6) {
            nameElements[6].textContent = formData.get('notes') || '';
        }
    }
}


function showSuccessMessage(message) {
    showToast(message, 'success');
}


function showErrorMessage(message) {
    showToast(message, 'error');
}


function showToast(message, type = 'info') {
    
    const existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());
    
    
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            pointer-events: none;
        `;
        document.body.appendChild(toastContainer);
    }
    
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        background: ${type === 'success' ? '#00E6B8' : type === 'error' ? '#FF5555' : '#404040'};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        pointer-events: auto;
        max-width: 300px;
        font-size: 14px;
        font-weight: 500;
    `;
    toast.textContent = message;
    
    toastContainer.appendChild(toast);
    
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

