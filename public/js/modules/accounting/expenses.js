/**
 * Módulo: Accounting Expenses List (Modal toggle)
 */

export function toggleExpenseModal(show) {
    const modal = document.getElementById('expenseModal');
    if (!modal) return;
    if (show) {
        modal.classList.remove('hidden');
    } else {
        modal.classList.add('hidden');
    }
}

if (typeof window !== 'undefined') {
    window.toggleModal = toggleExpenseModal;
}
