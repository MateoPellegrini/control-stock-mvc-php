
function setupTotalCalculator(formSelector) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    const cantidadInput = form.querySelector('input[name="cantidad"]');
    const precioInput   = form.querySelector('input[name="precio_unitario"]');
    const totalInput    = form.querySelector('input[name="total"]');

    function recalcularTotal() {
        const cantidad = parseFloat(cantidadInput.value) || 0;
        const precio   = parseFloat(precioInput.value) || 0;
        const total    = cantidad * precio;
        totalInput.value = total > 0 ? total.toFixed(2) : '';
    }

    if (cantidadInput && precioInput && totalInput) {
        cantidadInput.addEventListener('input', recalcularTotal);
        precioInput.addEventListener('input', recalcularTotal);
    }
}
