/**
 * Módulo: Medical Report List (Búsqueda reactiva de pacientes para historias clínicas)
 */

export function historiasClinicas(options = {}) {
    const root = options.rootUrl || (window.PROJECT_ROOT || '');
    return {
        searchQuery: '',
        results: [],
        isOpen: false,
        isLoading: false,

        async searchPatients() {
            const q = this.searchQuery.trim();
            if (q.length < 2) {
                this.results = [];
                this.isOpen = false;
                return;
            }

            this.isLoading = true;
            this.isOpen = true;

            try {
                const response = await fetch(`${root}/pacientes/buscar?q=` + encodeURIComponent(q));
                if (response.ok) {
                    this.results = await response.json();
                } else {
                    this.results = [];
                }
            } catch (err) {
                console.error('Error al buscar pacientes:', err);
                this.results = [];
            } finally {
                this.isLoading = false;
            }
        },

        selectPatient(p) {
            this.searchQuery = p.nombre + ' ' + (p.apellidos || '');
            this.isOpen = false;
            window.location.href = `${root}/historial/crear?paciente_id=` + encodeURIComponent(p.usuario_id);
        }
    };
}

if (typeof window !== 'undefined') {
    window.historiasClinicas = historiasClinicas;
}
