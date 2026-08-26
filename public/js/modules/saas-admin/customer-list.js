/**
 * Módulo: SaaS Tenant List (Filtrado en vivo de clínicas)
 */

export function filterTenants() {
    const input = document.getElementById('tenantSearch');
    if (!input) return;
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('.tenant-row');

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
}

export function initCustomerList() {
    const input = document.getElementById('tenantSearch');
    if (input) {
        input.addEventListener('input', filterTenants);
    }
}

if (typeof window !== 'undefined') {
    window.filterTenants = filterTenants;
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCustomerList);
} else {
    initCustomerList();
}
window.addEventListener('tervion:navigated', initCustomerList);
