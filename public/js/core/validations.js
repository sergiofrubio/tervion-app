/**
 * Core Form Validations
 */

export function validatePassword(passInputId = 'pass', confirmPassInputId = 'confirmPass', errorContainerId = 'passwordError') {
    const password = document.getElementById(passInputId)?.value || '';
    const confirmPassword = document.getElementById(confirmPassInputId)?.value || '';
    const errorEl = document.getElementById(errorContainerId);

    if (password !== confirmPassword) {
        if (errorEl) errorEl.textContent = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
        return false;
    }
    if (errorEl) errorEl.textContent = "";
    return true;
}

export function validarDNI(dni) {
    const dniRegex = /^[0-9]{8}[a-zA-Z]$/;
    if (!dniRegex.test(dni)) {
        return false;
    }
    const letrasDNI = 'TRWAGMYFPDXBNJZSQVHLCKE';
    const numeroDNI = dni.substring(0, 8);
    const letraDNI = dni.substring(8).toUpperCase();
    const resto = numeroDNI % 23;
    const letraCalculada = letrasDNI.charAt(resto);
    return letraDNI === letraCalculada;
}

export function validarInputDNI(inputId = 'usuario_id', errorContainerId = 'idError') {
    const dniInput = document.getElementById(inputId);
    if (!dniInput) return true;
    
    const dni = dniInput.value.trim();
    const idError = document.getElementById(errorContainerId);

    if (!validarDNI(dni)) {
        if (idError) {
            idError.textContent = 'El DNI no es válido.';
            idError.style.color = 'red';
        }
        return false;
    } else {
        if (idError) idError.textContent = '';
        return true;
    }
}

// Compatibilidad global con vistas tradicionales
if (typeof window !== 'undefined') {
    window.validatePassword = validatePassword;
    window.validarDNI = validarDNI;
    window.validarInput = () => validarInputDNI('usuario_id', 'idError');
}
