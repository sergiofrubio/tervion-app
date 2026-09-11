/**
 * Módulo: Landing Registration (Alpine Component simplificado)
 */

function registrationForm(initialData = null) {
    const data = initialData || (typeof window !== 'undefined' ? window.registrationInitialData : {}) || {};

    return {
        formData: {
            nombre: data.nombre || '',
            email: data.email || '',
            pass: '',
            confirm_pass: ''
        },
        errors: {},
        validate() {
            this.errors = {};

            if (!this.formData.nombre || this.formData.nombre.trim() === '') {
                this.errors.nombre = 'Por favor, introduce tu nombre.';
            }

            if (!this.formData.email || !this.formData.email.includes('@')) {
                this.errors.email = 'Introduce un correo electrónico válido.';
            }

            if (!this.formData.pass || this.formData.pass.length < 8) {
                this.errors.pass = 'La contraseña debe tener al menos 8 caracteres.';
            }

            if (this.formData.pass !== this.formData.confirm_pass) {
                this.errors.confirm_pass = 'Las contraseñas no coinciden.';
            }

            return Object.keys(this.errors).length === 0;
        },
        submitForm(e) {
            if (!this.validate()) {
                e.preventDefault();
            }
        }
    };
}

if (typeof window !== 'undefined') {
    window.registrationForm = registrationForm;
}

