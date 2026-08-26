/**
 * Módulo: Landing Registration Wizard (Multi-step Alpine Component)
 */

export function registrationForm(initialData = {}) {
    return {
        currentStep: 1,
        maxStepReached: 1,
        steps: [
            { name: 'Credenciales' },
            { name: 'Autónomo - Personales' },
            { name: 'Autónomo - Dirección' },
            { name: 'Clínica - Generales' },
            { name: 'Clínica - Ubicación' },
            { name: 'Suscripción' },
            { name: 'Resumen' }
        ],
        formData: {
            email: initialData.email || '',
            pass: '',
            confirm_pass: '',
            usuario_id: initialData.usuario_id || '',
            nombre: initialData.nombre || '',
            apellidos: initialData.apellidos || '',
            telefono: initialData.telefono || '',
            fecha_nacimiento: initialData.fecha_nacimiento || '',
            genero: initialData.genero || 'Hombre',
            direccion: initialData.direccion || '',
            municipio: initialData.municipio || '',
            provincia: initialData.provincia || '',
            cp: initialData.cp || '',
            nss: initialData.nss || '',
            iban: initialData.iban || '',
            nombre_comercial: initialData.nombre_comercial || '',
            razon_social: initialData.razon_social || '',
            telefono_contacto: initialData.telefono_contacto || '',
            email_contacto: initialData.email_contacto || '',
            sitio_web: initialData.sitio_web || '',
            direccion_calle: initialData.direccion_calle || '',
            ciudad: initialData.ciudad || '',
            provincia_estado: initialData.provincia_estado || '',
            codigo_postal: initialData.codigo_postal || '',
            pais: initialData.pais || 'España',
            plan_suscripcion: 'Premium',
            card_holder: '',
            card_number: '',
            card_expiry: '',
            card_cvv: ''
        },
        errors: {},
        goToStep(step) {
            if (step <= this.maxStepReached) {
                this.currentStep = step;
            }
        },
        validateStep(step) {
            this.errors = {};

            if (step === 1) {
                if (!this.formData.email || !this.formData.email.includes('@')) {
                    this.errors.email = 'Introduce un email de administrador válido.';
                }
                if (!this.formData.pass || this.formData.pass.length < 8) {
                    this.errors.pass = 'La contraseña debe tener al menos 8 caracteres.';
                }
                if (this.formData.pass !== this.formData.confirm_pass) {
                    this.errors.confirm_pass = 'Las contraseñas no coinciden.';
                }
            }

            if (step === 2) {
                if (!this.formData.usuario_id || this.formData.usuario_id.trim().length !== 9) {
                    this.errors.usuario_id = 'El DNI / NIF debe tener exactamente 9 caracteres.';
                }
                if (!this.formData.nombre || this.formData.nombre.trim() === '') {
                    this.errors.nombre = 'El nombre es obligatorio.';
                }
                if (!this.formData.apellidos || this.formData.apellidos.trim() === '') {
                    this.errors.apellidos = 'Los apellidos son obligatorios.';
                }
                if (!this.formData.fecha_nacimiento) {
                    this.errors.fecha_nacimiento = 'La fecha de nacimiento es obligatoria.';
                }
            }

            if (step === 4) {
                if (!this.formData.nombre_comercial || this.formData.nombre_comercial.trim() === '') {
                    this.errors.nombre_comercial = 'El nombre comercial de la clínica es obligatorio.';
                }
                if (!this.formData.telefono_contacto || this.formData.telefono_contacto.trim() === '') {
                    this.errors.telefono_contacto = 'El teléfono de contacto de la clínica es obligatorio.';
                }
            }

            if (step === 5) {
                if (!this.formData.direccion_calle || this.formData.direccion_calle.trim() === '') {
                    this.errors.direccion_calle = 'La dirección de la clínica es obligatoria.';
                }
                if (!this.formData.ciudad || this.formData.ciudad.trim() === '') {
                    this.errors.ciudad = 'La ciudad es obligatoria.';
                }
            }

            return Object.keys(this.errors).length === 0;
        },
        nextStep() {
            if (this.validateStep(this.currentStep)) {
                this.currentStep++;
                if (this.currentStep > this.maxStepReached) {
                    this.maxStepReached = this.currentStep;
                }
            }
        },
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        submitForm(e) {
            if (!this.validateStep(1) || !this.validateStep(2) || !this.validateStep(4) || !this.validateStep(5)) {
                e.preventDefault();
                alert('Por favor, compruebe que todos los campos obligatorios están rellenos correctamente.');
            }
        }
    };
}

if (typeof window !== 'undefined') {
    window.registrationForm = registrationForm;
}
