document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.order-card').forEach(function(card){
        const toggleBtn = card.querySelector('.toggle-refund');
        const cancelBtn = card.querySelector('.cancel-select');
        const selectAll = card.querySelector('.select-all');
        const refundForm = card.querySelector('.refund-form');
        const qtyInputs = card.querySelectorAll('.refund-qty');
        
        if(toggleBtn){
            toggleBtn.addEventListener('click', function(){
                card.classList.add('select-mode');
            });
        }
        
        if(cancelBtn){
            cancelBtn.addEventListener('click', function(){
                card.classList.remove('select-mode');
                qtyInputs.forEach(inp => inp.value = 0);
                if(selectAll){ selectAll.checked = false; }
            });
        }
        
        if(selectAll){
            selectAll.addEventListener('change', function(){
                const fillMax = !!this.checked;
                qtyInputs.forEach(function(inp){
                    inp.value = fillMax ? (inp.dataset.max || inp.max || 0) : 0;
                });
            });
        }
        
        card.querySelectorAll('.qty-box').forEach(function(box){
            const dec = box.querySelector('.qty-btn.dec');
            const inc = box.querySelector('.qty-btn.inc');
            const inp = box.querySelector('.refund-qty');
            const getMax = () => parseInt(inp.dataset.max || inp.max || '0', 10) || 0;
            const clamp = v => Math.max(0, Math.min(getMax(), v|0));
            
            if(dec){ 
                dec.addEventListener('click', ()=>{ 
                    inp.value = clamp((parseInt(inp.value||'0',10)||0) - 1); 
                }); 
            }
            if(inc){ 
                inc.addEventListener('click', ()=>{ 
                    inp.value = clamp((parseInt(inp.value||'0',10)||0) + 1); 
                }); 
            }
        });
        
        if(refundForm){
            refundForm.addEventListener('submit', function(e){
                const anyPositive = Array.from(qtyInputs).some(inp => (parseInt(inp.value||'0',10)||0) > 0);
                if(!anyPositive){
                    e.preventDefault();
                    alert('Lütfen iade adedi giriniz.');
                    return;
                }
                
                qtyInputs.forEach(function(inp) {
                    if (parseInt(inp.value || '0', 10) === 0) {
                        inp.disabled = true;
                    }
                });
            });
        }
    });
});
