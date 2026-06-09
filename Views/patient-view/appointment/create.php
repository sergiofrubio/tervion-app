<?php
$pageTitle = "Solicitar Cita - Velion";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-3xl mx-auto animate-fade-in pb-12">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Nueva Cita</h1>
            <p class="mt-1 text-gray-500">Completa los detalles para programar tu próxima sesión.</p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/vista-pacientes/citas" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-primary-600 transition-colors group">
            <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i>
            Volver
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/vista-pacientes/citas/nueva" method="POST" class="p-8 md:p-12">
            <div class="space-y-10">
                
                <!-- Sección: Profesional y Servicio -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center">
                            <i class="bi bi-person-badge text-xl"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Profesional y Servicio</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2" id="container-fisio">
                            <label for="fisio_search" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Fisioterapeuta</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" id="fisio_search" placeholder="Buscar profesional..." 
                                    class="block w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-4 pl-11 transition-all bg-gray-50/50" autocomplete="off" required>
                                <input type="hidden" name="fisioterapeuta_id" id="fisioterapeuta_id" required>
                                <div id="fisio_results" class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 hidden max-h-64 overflow-y-auto overflow-x-hidden py-2 animate-in fade-in slide-in-from-top-2 duration-200">
                                    <!-- Results -->
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="especialidad_id" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Especialidad</label>
                            <select name="especialidad_id" id="especialidad_id" required
                                class="block w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-4 transition-all bg-gray-50/50 appearance-none cursor-pointer">
                                <option value="">Seleccionar especialidad...</option>
                                <?php foreach ($especialidades as $e): ?>
                                    <option value="<?= $e['especialidad_id'] ?>"><?= $e['descripcion'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sección: Fecha y Hora -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i class="bi bi-calendar-check text-xl"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Fecha y Hora</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2 md:col-span-2">
                            <label for="fecha_hora" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Selecciona el momento</label>
                            <input type="datetime-local" name="fecha_hora" id="fecha_hora" required
                                class="block w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-4 transition-all bg-gray-50/50">
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end gap-6">
                    <a href="<?= PROJECT_ROOT ?>/vista-pacientes/citas" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-2xl bg-primary-600 px-10 py-4 text-sm font-bold text-white shadow-xl shadow-primary-200/50 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all hover:scale-105 active:scale-95">
                        Confirmar Cita
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupSearch(inputId, resultsId, hiddenId, rol) {
        const input = document.getElementById(inputId);
        const results = document.getElementById(resultsId);
        const hidden = document.getElementById(hiddenId);
        let debounceTimer;

        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 2) {
                results.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`<?= PROJECT_ROOT ?>/usuarios/search?rol=${rol}&q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        results.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(user => {
                                const div = document.createElement('div');
                                div.className = 'px-5 py-4 hover:bg-primary-50 cursor-pointer transition-colors flex items-center justify-between border-b border-gray-50 last:border-0';
                                div.innerHTML = `
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] font-black text-gray-500 uppercase">
                                            ${user.nombre.substring(0, 1)}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 text-sm">${user.nombre} ${user.apellidos}</span>
                                            <span class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">Fisioterapeuta Especialista</span>
                                        </div>
                                    </div>
                                    <i class="bi bi-plus-circle text-primary-400"></i>
                                `;
                                div.addEventListener('click', () => {
                                    input.value = `${user.nombre} ${user.apellidos}`;
                                    hidden.value = user.usuario_id;
                                    results.classList.add('hidden');
                                    input.classList.remove('border-gray-200');
                                    input.classList.add('border-primary-500', 'bg-primary-50/30');
                                });
                                results.appendChild(div);
                            });
                            results.classList.remove('hidden');
                        } else {
                            results.innerHTML = '<div class="px-5 py-4 text-xs text-gray-400 italic">No se encontraron profesionales</div>';
                            results.classList.remove('hidden');
                        }
                    });
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.classList.add('hidden');
            }
        });
    }

    setupSearch('fisio_search', 'fisio_results', 'fisioterapeuta_id', 'Fisioterapeuta');
});
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
