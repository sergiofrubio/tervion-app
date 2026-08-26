/**
 * Módulo: Auth / Login
 */

export function initLoginForm() {
    const form = document.querySelector('form');
    const btn = document.getElementById('loginButton');
    const spinner = document.getElementById('loginSpinner');
    const btnText = document.getElementById('loginButtonText');

    if (form && btn && spinner) {
        form.addEventListener('submit', function() {
            spinner.classList.remove('hidden');
            btn.classList.add('opacity-80');
            btn.disabled = true;
            if (btnText) btnText.textContent = 'Iniciando...';
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLoginForm);
} else {
    initLoginForm();
}
