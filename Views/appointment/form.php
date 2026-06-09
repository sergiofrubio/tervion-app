<?php
$a = $appointment ?? [];
$isEdit = !empty($a);
$pageTitle = $isEdit ? "Editar Cita" : "Nueva Cita";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-4xl mx-auto animate-fade-in-up">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Cita" : "Nueva Cita" ?></h1>
            <p class="mt-1 text-sm text-gray-500"><?= $isEdit ? "Modifica los detalles de la sesión programada." : "Programa una nueva sesión para un paciente." ?></p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/citas" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Volver al listado
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/citas/<?= $isEdit ? 'edit' : 'create' ?>" method="POST" class="p-8" id="appointment-form">
            <?php if ($isEdit) : ?>
                <input type="hidden" name="cita_id" value="<?= $a['cita_id'] ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Paciente y Fisio -->
                <div class="space-y-6 md:col-span-2 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-check text-primary-600"></i>
                        1. Asignación de Paciente y Especialista
                    </h2>
                </div>

                <div class="space-y-2 relative group">
                    <label for="paciente_search" class="block text-sm font-medium text-gray-700">Paciente</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="paciente_search" placeholder="Buscar paciente por nombre o ID..." 
                            value="<?= $isEdit ? htmlspecialchars($a['paciente_nombre'] . ' ' . $a['paciente_apellidos']) : '' ?>"
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 pl-10 transition-all bg-white" autocomplete="off">
                        <input type="hidden" name="paciente_id" id="paciente_id" value="<?= $a['paciente_id'] ?? '' ?>" required>
                        <div id="paciente_results" class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 hidden max-h-64 overflow-y-auto py-2 animate-in fade-in slide-in-from-top-2 duration-200">
                        </div>
                    </div>
                </div>

                <div class="space-y-2 relative group">
                    <label for="fisio_search" class="block text-sm font-medium text-gray-700">Fisioterapeuta</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="fisio_search" placeholder="Buscar fisioterapeuta..." 
                            value="<?= $isEdit ? htmlspecialchars($a['fisioterapeuta_nombre'] . ' ' . $a['fisioterapeuta_apellidos']) : '' ?>"
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 pl-10 transition-all bg-white" autocomplete="off">
                        <input type="hidden" name="fisioterapeuta_id" id="fisioterapeuta_id" value="<?= $a['fisioterapeuta_id'] ?? '' ?>" required>
                        <div id="fisio_results" class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 hidden max-h-64 overflow-y-auto py-2 animate-in fade-in slide-in-from-top-2 duration-200">
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="especialidad_id" class="block text-sm font-medium text-gray-700">Especialidad</label>
                    <select name="especialidad_id" id="especialidad_id" required
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all bg-white">
                        <option value="">Seleccionar especialidad...</option>
                        <?php foreach ($especialidades as $e): ?>
                            <option value="<?= $e['especialidad_id'] ?>" <?= ($isEdit && $e['especialidad_id'] == $a['especialidad_id']) ? 'selected' : '' ?>>
                                <?= $e['descripcion'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" id="estado" required
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all bg-white">
                        <option value="Programada" <?= ($isEdit && $a['estado'] == 'Programada') ? 'selected' : '' ?>>Programada</option>
                        <option value="Pendiente" <?= ($isEdit && $a['estado'] == 'Pendiente') ? 'selected' : '' ?>>Pendiente</option>
                        <option value="Realizada" <?= ($isEdit && $a['estado'] == 'Realizada') ? 'selected' : '' ?>>Realizada</option>
                        <option value="Cancelada" <?= ($isEdit && $a['estado'] == 'Cancelada') ? 'selected' : '' ?>>Cancelada</option>
                    </select>
                </div>

                <!-- Selección de Fecha y Hora -->
                <div class="space-y-6 md:col-span-2 pt-4 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-primary-600"></i>
                        2. Fecha y Horas Disponibles
                    </h2>
                </div>

                <div class="space-y-2">
                    <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha de la Cita</label>
                    <input type="date" id="fecha" required
                        value="<?= $isEdit ? date('Y-m-d', strtotime($a['fecha_hora'])) : '' ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all bg-white">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Selecciona una hora disponible</label>
                    <input type="hidden" name="fecha_hora" id="fecha_hora_hidden" value="<?= $isEdit ? $a['fecha_hora'] : '' ?>" required>
                    <div id="slots-container" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                        <div class="col-span-full py-8 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <p class="text-gray-400 text-sm italic">Selecciona un fisioterapeuta y una fecha para ver huecos libres.</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-10 pt-6 border-t border-gray-50 flex items-center justify-end gap-3">
                <a href="<?= PROJECT_ROOT ?>/citas" class="px-6 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    Cancelar
                </a>
                <button type="submit" id="submit-btn" class="inline-flex justify-center items-center gap-2 rounded-xl bg-primary-600 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <?= $isEdit ? "Guardar Cambios" : "Programar Cita" ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fisioIdInput = document.getElementById('fisioterapeuta_id');
    const fechaInput = document.getElementById('fecha');
    const slotsContainer = document.getElementById('slots-container');
    const fechaHoraHidden = document.getElementById('fecha_hora_hidden');
    const submitBtn = document.getElementById('submit-btn');

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
                                div.className = 'px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors flex items-center justify-between border-b border-gray-50 last:border-0';
                                div.innerHTML = `
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-900">${user.nombre} ${user.apellidos}</span>
                                        <span class="text-xs text-gray-500">ID: ${user.usuario_id}</span>
                                    </div>
                                    <i class="bi bi-plus-circle text-primary-500"></i>
                                `;
                                div.addEventListener('click', () => {
                                    input.value = `${user.nombre} ${user.apellidos}`;
                                    hidden.value = user.usuario_id;
                                    results.classList.add('hidden');
                                    input.classList.add('border-primary-500', 'ring-1', 'ring-primary-500');
                                    if (rol === 'Fisioterapeuta') {
                                        updateSlots();
                                    }
                                });
                                results.appendChild(div);
                            });
                            results.classList.remove('hidden');
                        } else {
                            results.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">No se encontraron resultados</div>';
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

    function updateSlots() {
        const fisioId = fisioIdInput.value;
        const fecha = fechaInput.value;

        if (!fisioId || !fecha) return;

        slotsContainer.innerHTML = '<div class="col-span-full text-center py-4 text-gray-500">Cargando huecos disponibles...</div>';

        fetch(`<?= PROJECT_ROOT ?>/citas/slots?fisio_id=${fisioId}&fecha=${fecha}`)
            .then(response => response.json())
            .then(slots => {
                slotsContainer.innerHTML = '';
                if (slots.length > 0) {
                    slots.forEach(slot => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'slot-btn px-4 py-2 text-sm font-medium border-2 border-gray-100 rounded-xl hover:border-primary-500 hover:text-primary-600 transition-all text-gray-600 bg-white';
                        btn.textContent = slot;
                        
                        // Si estamos editando y coincide la hora, marcarlo
                        const currentVal = fechaHoraHidden.value;
                        if (currentVal && currentVal.includes(slot)) {
                            btn.classList.add('bg-primary-50', 'border-primary-500', 'text-primary-600');
                        }

                        btn.addEventListener('click', () => {
                            document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('bg-primary-50', 'border-primary-500', 'text-primary-600'));
                            btn.classList.add('bg-primary-50', 'border-primary-500', 'text-primary-600');
                            fechaHoraHidden.value = `${fecha} ${slot}:00`;
                        });
                        slotsContainer.appendChild(btn);
                    });
                } else {
                    slotsContainer.innerHTML = '<div class="col-span-full py-8 text-center bg-red-50 rounded-2xl border-2 border-dashed border-red-100 text-red-500 text-sm">No hay huecos disponibles para este profesional en la fecha seleccionada.</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching slots:', error);
                slotsContainer.innerHTML = '<div class="col-span-full text-center py-4 text-red-500">Error al cargar disponibilidad</div>';
            });
    }

    setupSearch('paciente_search', 'paciente_results', 'paciente_id', 'Paciente');
    setupSearch('fisio_search', 'fisio_results', 'fisioterapeuta_id', 'Fisioterapeuta');

    fechaInput.addEventListener('change', updateSlots);

    // Initial load if editing
    if (fisioIdInput.value && fechaInput.value) {
        updateSlots();
    }
});
</script>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
