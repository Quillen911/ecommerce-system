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

function validateCurrentStep() {
    if (currentStep === 1) {
        const shippingAddress = document.querySelector('input[name="shipping_address_id"]:checked');
        
        if (!shippingAddress) {
            alert('Lütfen teslimat adresini seçiniz!');
            return false;
        }
        document.getElementById('shipping_address_id').value = shippingAddress.value;

    } else if (currentStep === 2) {
        return true;
    } else if (currentStep === 3) {
        const billingSameCheckbox = document.getElementById('billing_same_as_shipping');
        const newBillingCheckbox = document.getElementById('new_billing_address');
        
        console.log('billingSameCheckbox checked:', billingSameCheckbox?.checked);
        console.log('newBillingCheckbox checked:', newBillingCheckbox?.checked);
        
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
            console.log('Billing address set to: new_billing_address');
        } else {
            const billingAddress = document.querySelector('input[name="billing_address_selection"]:checked');
            if (!billingAddress) {
                alert('Lütfen fatura adresini seçiniz!');
                return false;
            }
            document.getElementById('billing_address_id').value = billingAddress.value;
        }
        
        console.log('Final billing_address_id value:', document.getElementById('billing_address_id').value);
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
        const fields = [
            {id: 'new_card_holder_name', name: 'Kart sahibi adı'},
            {id: 'new_card_name', name: 'Kart ismi'},
            {id: 'new_card_number', name: 'Kart numarası', length: 16},
            {id: 'new_expire_month', name: 'Son kullanma ayı'},
            {id: 'new_expire_year', name: 'Son kullanma yılı'},
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
    } else {
        const existingCvvDiv = document.getElementById('existing-card-cvv');
        if (existingCvvDiv && existingCvvDiv.style.display !== 'none') {
            const cvv = document.getElementById('existing_cvv').value;
            
            if (!cvv || cvv.length !== 3) {
                alert('Lütfen 3 haneli CVV kodunu giriniz!');
                document.getElementById('existing_cvv').focus();
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

    if (checkbox.checked) {
        billingAddressSection.style.display = 'none';
        newBillingToggle.style.display = 'none';
        newBillingAddressForm.style.display = 'none';
        
        if (newBillingCheckbox) {
            newBillingCheckbox.checked = false;
        }
        
        updateBillingAddressFromShipping();
    } else {
        billingAddressSection.style.display = 'block';
        newBillingToggle.style.display = 'block';
        
        newBillingAddressForm.style.display = 'none';
        
        if (newBillingCheckbox) {
            newBillingCheckbox.checked = false;
        }
    }
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



function handleCardSelection(cardValue) {
    const newCardForm = document.getElementById('new-card-form');
    const existingCardCvv = document.getElementById('existing-card-cvv');
    
    newCardForm.style.display = 'none';
    existingCardCvv.style.display = 'none';
    
    clearFormValidation();
    
    if (cardValue === 'new_card') {
        newCardForm.style.display = 'block';
        setNewCardValidation();
    } else {
        checkExistingCard(cardValue);
    }
}

function checkExistingCard(cardId) {
    try {
        const cards = window.creditCards || {};
        const selectedCard = cards[cardId];
        const existingCardCvv = document.getElementById('existing-card-cvv');

        if (selectedCard && !selectedCard.iyzico_card_token) {
            existingCardCvv.style.display = 'block';
            document.getElementById('existing_cvv').required = true;
        } else {
            existingCardCvv.style.display = 'none';
            document.getElementById('existing_cvv').required = false;
        }
    } catch (error) {
        console.error('Kart kontrolü hatası:', error);
    }
}

function clearFormValidation() {
    document.getElementById('new_card_holder_name').required = false;
    document.getElementById('new_card_name').required = false;
    document.getElementById('new_card_number').required = false;
    document.getElementById('new_expire_month').required = false;
    document.getElementById('new_expire_year').required = false;
    document.getElementById('new_cvv').required = false;
    
    document.getElementById('existing_cvv').required = false;
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
                const expireDateInput = document.getElementById('expire_date');
                if (expireDateInput && expireDateInput.value) {
                    const parts = expireDateInput.value.split('/');
                    if (parts.length === 2) {
                        const month = parts[0].padStart(2, '0');
                        const year = '20' + parts[1];
                        document.getElementById('expire_month').value = month;
                        document.getElementById('expire_year').value = year;
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
                const firstCard = document.querySelector('input[name="credit_card_id"]:not([value="new_card"])');
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

    document.getElementById('existing_cvv')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) value = value.substr(0, 3);
        e.target.value = value;
    });

    // İlk kart seçimi
    if (typeof window.creditCardsData !== 'undefined') {
        window.creditCards = window.creditCardsData;
    }
    
    const hasCreditCards = document.querySelectorAll('input[name="credit_card_id"]:not(#card_new)').length > 0;
    
    if (hasCreditCards) {
        const firstCard = document.querySelector('input[name="credit_card_id"]:not(#card_new)');
        if (firstCard) {
            firstCard.checked = true;
            firstCard.dispatchEvent(new Event('change'));
        }
    } else {
        const newCardRadio = document.getElementById('card_new');
        if (newCardRadio) {
            newCardRadio.checked = true;
            document.getElementById('credit_card_id').value = 'new_card';
            handleCardSelection('new_card');
        }
    }
    handleBillingAddressToggle();
});