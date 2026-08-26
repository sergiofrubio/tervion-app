/**
 * Módulo: SaaS Invoices (Cálculo dinámico de totales)
 */

export function initSaasInvoiceForm() {
    const baseInput = document.getElementById('base_imponible');
    const ivaInput = document.getElementById('tipo_iva');
    const totalInput = document.getElementById('total_factura');

    if (!baseInput || !ivaInput || !totalInput) return;

    function calculateTotal() {
        const base = parseFloat(baseInput.value) || 0;
        const iva = parseFloat(ivaInput.value) || 0;
        const cuotaIva = base * (iva / 100);
        const total = base + cuotaIva;
        totalInput.value = total.toFixed(2) + ' €';
    }

    baseInput.addEventListener('input', calculateTotal);
    ivaInput.addEventListener('change', calculateTotal);
    calculateTotal();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSaasInvoiceForm);
} else {
    initSaasInvoiceForm();
}
window.addEventListener('tervion:navigated', initSaasInvoiceForm);
