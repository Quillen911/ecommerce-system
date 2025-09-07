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
        return true;
    } else if (currentStep === 2) {
        return true;
    } else if (currentStep === 3) {
        return validatePaymentForm();
    }
    return true;
}


function validateCreditCardForm() {
    let isValid = true;
    
    clearFieldErrors();
    const cardNumber = document.getElementById('card_number');
    const cardNumberError = document.getElementById('card_number_error');
    if (!cardNumber.value.trim()) {
        showFieldError(cardNumber, cardNumberError, 'Geçerli bir kart numarası girin');
        isValid = false;
    } else if (cardNumber.value.replace(/\D/g, '').length < 16) {
        showFieldError(cardNumber, cardNumberError, 'Geçerli bir kart numarası girin');
        isValid = false;
    }
    const cardHolderName = document.getElementById('card_holder_name');
    const cardHolderNameError = document.getElementById('card_holder_name_error');
    if (!cardHolderName.value.trim()) {
        showFieldError(cardHolderName, cardHolderNameError, 'Ad soyad girin');
        isValid = false;
    }
    const expireDate = document.getElementById('expire_date');
    const expireDateError = document.getElementById('expire_date_error');
    if (!expireDate.value.trim()) {
        showFieldError(expireDate, expireDateError, 'Geçerli bir tarih girin');
        isValid = false;
    } else if (!/^\d{2}\/\d{2}$/.test(expireDate.value)) {
        showFieldError(expireDate, expireDateError, 'Geçerli bir tarih girin');
        isValid = false;
    }
    const cvc = document.getElementById('cvc');
    const cvcError = document.getElementById('cvc_error');
    if (!cvc.value.trim()) {
        showFieldError(cvc, cvcError, 'Geçerli bir güvenlik kodu girin');
        isValid = false;
    } else if (cvc.value.length !== 3) {
        showFieldError(cvc, cvcError, 'Geçerli bir güvenlik kodu girin');
        isValid = false;
    }
    
    const cardName = document.getElementById('card_name');
    const cardNameError = document.getElementById('card_name_error');
    if (!cardName.value.trim()) {
        showFieldError(cardName, cardNameError, 'Kart ismi girin');
        isValid = false;
    }
    
    return isValid;
}

function showFieldError(input, errorElement, message) {
    input.closest('.field').classList.add('error');
    errorElement.textContent = message;
}

function clearFieldErrors() {
    document.querySelectorAll('.field').forEach(field => {
        field.classList.remove('error');
    });
    document.querySelectorAll('.field-error').forEach(error => {
        error.textContent = '';
    });
}


document.addEventListener('DOMContentLoaded', function() {
    const orderForm = document.getElementById('order-form');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            if (!validateCurrentStep()) {
                e.preventDefault();
                return false;
            }
            
            // Expire date'i kontrol et ve ayır (sadece yeni kart için)
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
    const cardNumber = document.getElementById('card_number');
    if (cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) value = value.substr(0, 16);
            e.target.value = value;
            
            if (value.length > 0) {
                clearFieldError(cardNumber);
            }
        });
        
        // Form submit öncesi kart numarasını temizle
        cardNumber.addEventListener('blur', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }
    
    const cvc = document.getElementById('cvc');
    if (cvc) {
        cvc.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 3) value = value.substr(0, 3);
            e.target.value = value;
            
            if (value.length > 0) {
                clearFieldError(cvc);
            }
        });
        
        // Form submit öncesi CVC'yi temizle
        cvc.addEventListener('blur', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }
    const expireDate = document.getElementById('expire_date');
    if (expireDate) {
        expireDate.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substr(0, 2) + '/' + value.substr(2, 2);
            }
            e.target.value = value;
            
            // Ay ve yılı ayrı field'lara yaz
            const parts = value.split('/');
            if (parts.length === 2) {
                const month = parts[0].padStart(2, '0');
                const year = '20' + parts[1];
                document.getElementById('expire_month').value = month;
                document.getElementById('expire_year').value = year;
            }
            
            if (value.length > 0) {
                clearFieldError(expireDate);
            }
        });
    }
    const cardHolderName = document.getElementById('card_holder_name');
    if (cardHolderName) {
        cardHolderName.addEventListener('input', function(e) {
            if (e.target.value.trim().length > 0) {
                clearFieldError(cardHolderName);
            }
        });
    }
    
    const cardName = document.getElementById('card_name');
    if (cardName) {
        cardName.addEventListener('input', function(e) {
            if (e.target.value.trim().length > 0) {
                clearFieldError(cardName);
            }
        });
    }
    
    const saveNewCard = document.getElementById('save_new_card');
    const saveNewCardToggle = document.getElementById('save_new_card_toggle');
    
    if (saveNewCard) {
        saveNewCard.addEventListener('change', function() {
            const label = this.closest('.save-card-label');
            if (this.checked) {
                label.style.borderColor = 'rgba(0, 212, 170, 0.4)';
                label.style.background = 'rgba(0, 212, 170, 0.08)';
            } else {
                label.style.borderColor = 'rgba(0, 212, 170, 0.2)';
                label.style.background = 'rgba(0, 212, 170, 0.05)';
            }
        });
    }
    
    if (saveNewCardToggle) {
        saveNewCardToggle.addEventListener('change', function() {
            const label = this.closest('.save-card-label');
            if (this.checked) {
                label.style.borderColor = 'rgba(0, 212, 170, 0.4)';
                label.style.background = 'rgba(0, 212, 170, 0.08)';
            } else {
                label.style.borderColor = 'rgba(0, 212, 170, 0.2)';
                label.style.background = 'rgba(0, 212, 170, 0.05)';
            }
        });
    }
    
    // Yeni kart toggle functionality
    const newCardToggle = document.getElementById('new_card');
    if (newCardToggle) {
        newCardToggle.addEventListener('change', function() {
            const newCardForm = document.getElementById('new-card-form');
            const creditCardItems = document.querySelectorAll('.credit-card-item');
            
            if (this.checked) {
                // Yeni kart formunu göster
                newCardForm.style.display = 'block';
                // Kayıtlı kartları gizle
                creditCardItems.forEach(item => {
                    item.style.display = 'none';
                });
                // Credit card ID'yi new_card olarak ayarla
                document.getElementById('credit_card_id').value = 'new_card';
            } else {
                // Yeni kart formunu gizle
                newCardForm.style.display = 'none';
                // Kayıtlı kartları göster
                creditCardItems.forEach(item => {
                    item.style.display = 'block';
                });
                // İlk kayıtlı kartı seç
                const firstCard = document.querySelector('input[name="credit_card_id"]:not([value="new_card"])');
                if (firstCard) {
                    firstCard.checked = true;
                    document.getElementById('credit_card_id').value = firstCard.value;
                }
            }
        });
    }
});

function clearFieldError(input) {
    const field = input.closest('.field');
    field.classList.remove('error');
    const errorElement = field.querySelector('.field-error');
    if (errorElement) {
        errorElement.textContent = '';
    }
}

document.querySelectorAll('input[name="credit_card_id"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.getElementById('credit_card_id').value = radio.value;
        
        document.querySelectorAll('.credit-card-item').forEach(item => item.classList.remove('selected'));
        radio.closest('.credit-card-item').classList.add('selected');
        
        // Yeni kart toggle'ını kapat
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

document.getElementById('useNewCardBtn')?.addEventListener('click', function() {
    const newCardOption = document.getElementById('new-card-option');
    if (newCardOption) {
        newCardOption.style.display = 'block';
        
        const newCardRadio = document.getElementById('card_new');
        if (newCardRadio) {
            newCardRadio.checked = true;
            newCardRadio.dispatchEvent(new Event('change'));
        }
        
        this.style.display = 'none';
        
        const backBtn = document.createElement('button');
        backBtn.type = 'button';
        backBtn.className = 'btn ghost';
        backBtn.style.width = '100%';
        backBtn.style.marginTop = '12px';
        backBtn.innerHTML = `
            ← Kayıtlı Kartlarıma Geri Dön
        `;
        backBtn.id = 'backToSavedCards';
        this.parentNode.appendChild(backBtn);
        
        backBtn.addEventListener('click', function() {
            newCardOption.style.display = 'none';
            
            const firstCard = document.querySelector('input[name="credit_card_id"]:not(#card_new)');
            if (firstCard) {
                firstCard.checked = true;
                firstCard.dispatchEvent(new Event('change'));
            }
            
            document.getElementById('useNewCardBtn').style.display = 'block';
            this.remove();
        });
    }
});

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

document.getElementById('save_new_card')?.addEventListener('change', function() {
    const label = this.closest('label');
    
    if (this.checked) {
        label.style.borderColor = 'rgba(0, 212, 170, 0.4)';
        label.style.background = 'rgba(0, 212, 170, 0.08)';
    } else {
        label.style.borderColor = 'rgba(0, 212, 170, 0.2)';
        label.style.background = 'rgba(0, 212, 170, 0.05)';
    }
});

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

document.getElementById('existing_cvv')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 3) value = value.substr(0, 3);
    e.target.value = value;
    
    if (value.length > 0) {
        clearFieldError(e.target);
    }
});

window.addEventListener('DOMContentLoaded', function() {
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
});

