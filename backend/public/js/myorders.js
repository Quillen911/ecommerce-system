document.addEventListener('DOMContentLoaded', () => {
    // Her sipariş kartı için davranışları bağla
    document.querySelectorAll('.order-card').forEach(card => {
      const toggleBtn  = card.querySelector('.toggle-refund');
      const cancelBtn  = card.querySelector('.cancel-select');
      const selectAll  = card.querySelector('.select-all');
      const refundForm = card.querySelector('.refund-form');
      const qtyInputs  = card.querySelectorAll('.refund-qty');
  
      // İade modunu aç
      if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
          card.classList.add('select-mode');
        });
      }
  
      // İade modunu kapat + sıfırla
      if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
          card.classList.remove('select-mode');
          qtyInputs.forEach(inp => (inp.value = 0));
          if (selectAll) selectAll.checked = false;
        });
      }
  
      // Tümünü seç / sıfırla
      if (selectAll) {
        selectAll.addEventListener('change', function () {
          const fillMax = !!this.checked;
          qtyInputs.forEach(inp => {
            const max = parseInt(inp.dataset.max || inp.max || '0', 10) || 0;
            inp.value = fillMax ? max : 0;
          });
        });
      }
  
      // Artır/Azalt butonları
      card.querySelectorAll('.qty-box').forEach(box => {
        const dec = box.querySelector('.qty-btn.dec');
        const inc = box.querySelector('.qty-btn.inc');
        const inp = box.querySelector('.refund-qty');
  
        const getMax  = () => parseInt(inp.dataset.max || inp.max || '0', 10) || 0;
        const clamp   = v => Math.max(0, Math.min(getMax(), v | 0));
        const getVal  = () => parseInt(inp.value || '0', 10) || 0;
  
        if (dec) dec.addEventListener('click', () => (inp.value = clamp(getVal() - 1)));
        if (inc) inc.addEventListener('click', () => (inp.value = clamp(getVal() + 1)));
      });
  
      // Form gönderimi: en az bir adet > 0 olmalı, 0'lar disable
      if (refundForm) {
        refundForm.addEventListener('submit', e => {
          const anyPositive = Array.from(qtyInputs).some(inp => (parseInt(inp.value || '0', 10) || 0) > 0);
          if (!anyPositive) {
            e.preventDefault();
            alert('Lütfen iade adedi giriniz.');
            return;
          }
          qtyInputs.forEach(inp => {
            if ((parseInt(inp.value || '0', 10) || 0) === 0) inp.disabled = true;
          });
        });
      }
    });
  });
  