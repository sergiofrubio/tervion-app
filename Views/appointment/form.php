<?php
$a = $appointment ?? [];
$isEdit = !empty($a);
$pageTitle = $isEdit ? "Editar Cita" : "Nueva Cita";
include TEMPLATE_DIR . 'header.php';
?>

<div class="w-full animate-fade-in-up">
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

                <!-- Tipo de Cita -->
                <div class="space-y-2">
                    <label for="tipo_cita_id" class="block text-sm font-medium text-gray-700">Tipo de Cita</label>
                    <div class="relative">
                        <select name="tipo_cita_id" id="tipo_cita_id" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 bg-white appearance-none transition-all">
                            <option value="">-- Seleccionar Tipo de Cita --</option>
                            <?php if (!empty($tiposCitas)): ?>
                                <?php foreach ($tiposCitas as $tc): ?>
                                    <?php $selected = ($isEdit && isset($a['tipo_cita_id']) && $a['tipo_cita_id'] == $tc['tipo_cita_id']) ? 'selected' : ''; ?>
                                    <option value="<?= $tc['tipo_cita_id'] ?>" <?= $selected ?>>
                                        <?= htmlspecialchars($tc['nombre']) ?> (<?= $tc['duracion_minutos'] ?> min - <?= number_format($tc['precio'], 2) ?>€)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </div>
                </div>

                <div class="space-y-3 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Fisioterapeuta</label>
                    <input type="hidden" name="fisioterapeuta_id" id="fisioterapeuta_id" value="<?= $isEdit ? $a['fisioterapeuta_id'] : '' ?>" required>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" id="fisios-cards-container">
                        <?php foreach ($fisioterapeutas as $f): ?>
                            <?php
                            $firstLetter = mb_substr($f['nombre'], 0, 1, 'UTF-8');
                            $isSelected = ($isEdit && $f['usuario_id'] == $a['fisioterapeuta_id']);
                            $avatarId = (intval(preg_replace('/[^0-9]/', '', $f['usuario_id'])) % 70) + 1;
                            $avatarUrl = (isset($f['genero']) && $f['genero'] === 'Mujer')
                                ? "https://randomuser.me/api/portraits/women/{$avatarId}.jpg"
                                : "https://randomuser.me/api/portraits/men/{$avatarId}.jpg";
                            ?>
                            <button type="button" data-id="<?= $f['usuario_id'] ?>"
                                class="fisio-card flex flex-col items-center p-5 bg-white border-2 rounded-3xl hover:border-primary-400 hover:bg-primary-50/10 active:scale-95 transition-all text-center focus:outline-none group <?= $isSelected ? 'border-primary-500 bg-primary-50/30 ring-2 ring-primary-500/20' : 'border-gray-100' ?>">
                                <div class="w-16 h-16 rounded-full overflow-hidden mb-3 border-2 border-white shadow-md group-hover:scale-105 transition-transform duration-200">
                                    <img src="<?= $avatarUrl ?>" alt="<?= htmlspecialchars($f['nombre']) ?>" class="w-full h-full object-cover">
                                </div>
                                <span class="block text-sm font-extrabold text-gray-900 leading-tight"><?= htmlspecialchars($f['nombre'] . ' ' . $f['apellidos']) ?></span>
                                <span class="block text-[10px] text-gray-400 uppercase font-black tracking-wider mt-1">Fisioterapeuta</span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Selección de Fecha y Hora -->
                <div class="space-y-6 md:col-span-2 pt-4 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-primary-600"></i>
                        2. Fecha y Horas Disponibles
                    </h2>
                </div>

                <input type="hidden" name="fecha_hora" id="fecha_hora_hidden" value="<?= $isEdit ? $a['fecha_hora'] : '' ?>" required>

                <!-- Días disponibles -->
                <div class="space-y-3 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">1. Días Disponibles</label>
                    <div id="dias-container" class="flex gap-3 overflow-x-auto pb-4 pt-1 px-1 scrollbar-thin scrollbar-thumb-gray-200">
                        <div class="text-sm text-gray-400 italic p-6 bg-gray-50/50 rounded-2xl w-full text-center border border-gray-100">
                            Selecciona un profesional para consultar su agenda de días disponibles.
                        </div>
                    </div>
                </div>

                <!-- Horas disponibles -->
                <div class="space-y-3 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">2. Horas Disponibles</label>
                    <div id="slots-container" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                        <div class="col-span-full text-sm text-gray-400 italic p-6 bg-gray-50/50 rounded-2xl text-center border border-gray-100">
                            Selecciona un día primero para ver las horas.
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
        const hiddenFisioInput = document.getElementById('fisioterapeuta_id');
        const fisioCards = document.querySelectorAll('.fisio-card');
        const diasContainer = document.getElementById('dias-container');
        const slotsContainer = document.getElementById('slots-container');
        const fechaHoraHidden = document.getElementById('fecha_hora_hidden');
        const submitBtn = document.getElementById('submit-btn');

        function generateCalendarHtml(year, month, availableDaysMap, selectedDateStr) {
            const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            const daysOfWeek = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];

            const firstDayDate = new Date(year, month, 1);
            let startDayIndex = firstDayDate.getDay() - 1;
            if (startDayIndex < 0) startDayIndex = 6;

            const totalDays = new Date(year, month + 1, 0).getDate();

            let html = `
            <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm flex-1">
                <h3 class="text-sm font-extrabold text-gray-900 mb-4 text-center capitalize">${monthNames[month]} ${year}</h3>
                <div class="grid grid-cols-7 gap-2 text-center text-[10px] font-black text-gray-400 uppercase tracking-wider mb-3">
        `;

            daysOfWeek.forEach(day => {
                html += `<div class="py-1">${day}</div>`;
            });

            for (let i = 0; i < startDayIndex; i++) {
                html += `<div></div>`;
            }

            for (let day = 1; day <= totalDays; day++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const dayInfo = availableDaysMap[dateStr];
                const isAvailable = !!dayInfo;
                const isSelected = dateStr === selectedDateStr;

                let cellClass = "";
                let btnDisabled = "";

                if (isAvailable) {
                    if (isSelected) {
                        cellClass = "bg-primary-600 text-white font-extrabold shadow-md hover:bg-primary-700";
                    } else {
                        cellClass = "border-2 border-primary-500/20 text-primary-700 bg-primary-50/20 font-bold hover:border-primary-500 hover:bg-primary-50 hover:text-primary-800 transition-all";
                    }
                } else {
                    cellClass = "text-gray-300 cursor-not-allowed pointer-events-none";
                    btnDisabled = "disabled";
                }

                html += `
                <button type="button" ${btnDisabled} data-date="${dateStr}"
                    class="w-full aspect-square flex items-center justify-center text-xs rounded-xl focus:outline-none transition-all active:scale-95 ${cellClass}">
                    ${day}
                </button>
            `;
            }

            html += `
                </div>
            </div>
        `;

            return html;
        }

        const tipoCitaSelect = document.getElementById('tipo_cita_id');

        function loadAvailableDays(fisioId, initialDateTime = '') {
            diasContainer.innerHTML = '<div class="text-sm text-gray-500 py-4 w-full text-center">Cargando días disponibles...</div>';
            slotsContainer.innerHTML = '<div class="col-span-full text-sm text-gray-400 italic p-6 bg-gray-50/50 rounded-2xl text-center border border-gray-100">Selecciona un día primero para ver las horas.</div>';

            let initialDate = '';
            let initialTime = '';
            if (initialDateTime) {
                const parts = initialDateTime.split(' ');
                initialDate = parts[0];
                if (parts[1]) {
                    initialTime = parts[1].substring(0, 5);
                }
            }

            const tipoCitaId = tipoCitaSelect ? tipoCitaSelect.value : '';
            fetch(`<?= PROJECT_ROOT ?>/citas/dias-disponibles?fisio_id=${encodeURIComponent(fisioId)}&tipo_cita_id=${encodeURIComponent(tipoCitaId)}`)
                .then(response => response.json())
                .then(days => {
                    diasContainer.innerHTML = '';

                    const availableDaysMap = {};
                    days.forEach(d => {
                        availableDaysMap[d.fecha] = d;
                    });

                    if (initialDate && !availableDaysMap[initialDate]) {
                        availableDaysMap[initialDate] = {
                            fecha: initialDate,
                            total_slots: 1
                        };
                    }

                    if (days.length === 0 && !initialDate) {
                        diasContainer.className = "flex w-full";
                        diasContainer.innerHTML = '<div class="text-sm text-red-500 py-4 w-full text-center font-bold">No hay días disponibles programados para este profesional en los próximos 60 días.</div>';
                        return;
                    }

                    const today = new Date();
                    const currentYear = today.getFullYear();
                    const currentMonth = today.getMonth();

                    const nextMonthDate = new Date(currentYear, currentMonth + 1, 1);
                    const nextYear = nextMonthDate.getFullYear();
                    const nextMonth = nextMonthDate.getMonth();

                    const currentCalHtml = generateCalendarHtml(currentYear, currentMonth, availableDaysMap, initialDate);
                    const nextCalHtml = generateCalendarHtml(nextYear, nextMonth, availableDaysMap, initialDate);

                    diasContainer.className = "grid grid-cols-1 md:grid-cols-2 gap-6 w-full";
                    diasContainer.innerHTML = currentCalHtml + nextCalHtml;

                    diasContainer.querySelectorAll('button[data-date]').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const dateStr = this.dataset.date;
                            selectDate(dateStr);
                        });
                    });

                    function selectDate(dateStr) {
                        diasContainer.querySelectorAll('button[data-date]').forEach(b => {
                            const d = b.dataset.date;
                            const isAv = !!availableDaysMap[d];
                            const isSel = d === dateStr;

                            if (isAv) {
                                if (isSel) {
                                    b.className = "w-full aspect-square flex items-center justify-center text-xs rounded-xl focus:outline-none transition-all active:scale-95 bg-primary-600 text-white font-extrabold shadow-md hover:bg-primary-700";
                                } else {
                                    b.className = "w-full aspect-square flex items-center justify-center text-xs rounded-xl focus:outline-none transition-all active:scale-95 border-2 border-primary-500/20 text-primary-700 bg-primary-50/20 font-bold hover:border-primary-500 hover:bg-primary-50 hover:text-primary-800 transition-all";
                                }
                            }
                        });

                        loadAvailableHours(fisioId, dateStr, dateStr === initialDate ? initialTime : '');
                    }

                    if (initialDate) {
                        selectDate(initialDate);
                    }
                })
                .catch(err => {
                    console.error(err);
                    diasContainer.innerHTML = '<div class="text-sm text-red-500 py-4 w-full text-center">Error al cargar la agenda.</div>';
                });
        }

        function loadAvailableHours(fisioId, dateStr, initialTime = '') {
            slotsContainer.innerHTML = '<div class="col-span-full text-sm text-gray-500 py-4 text-center">Cargando horas disponibles...</div>';

            const tipoCitaId = tipoCitaSelect ? tipoCitaSelect.value : '';
            fetch(`<?= PROJECT_ROOT ?>/citas/slots?fisio_id=${encodeURIComponent(fisioId)}&fecha=${encodeURIComponent(dateStr)}&tipo_cita_id=${encodeURIComponent(tipoCitaId)}`)
                .then(response => response.json())
                .then(slots => {
                    slotsContainer.innerHTML = '';

                    if (initialTime && !slots.includes(initialTime)) {
                        slots.push(initialTime);
                        slots.sort();
                    }

                    if (slots.length === 0) {
                        slotsContainer.innerHTML = '<div class="col-span-full text-sm text-red-500 py-4 text-center">No hay horas libres para este día.</div>';
                        return;
                    }

                    slots.forEach(slot => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'slot-btn px-4 py-3 text-sm font-bold border-2 border-gray-100 rounded-xl hover:border-primary-500 hover:text-primary-600 transition-all text-gray-700 bg-white';
                        btn.textContent = slot;

                        const selectHour = () => {
                            document.querySelectorAll('.slot-btn').forEach(b => {
                                b.classList.remove('border-primary-500', 'bg-primary-50', 'text-primary-600');
                                b.classList.add('border-gray-100', 'text-gray-700');
                            });
                            btn.classList.remove('border-gray-100', 'text-gray-700');
                            btn.classList.add('border-primary-500', 'bg-primary-50', 'text-primary-600');
                            fechaHoraHidden.value = `${dateStr} ${slot}:00`;
                        };

                        btn.addEventListener('click', selectHour);
                        slotsContainer.appendChild(btn);

                        if (slot === initialTime) {
                            selectHour();
                        }
                    });
                })
                .catch(err => {
                    console.error(err);
                    slotsContainer.innerHTML = '<div class="col-span-full text-sm text-red-500 py-4 text-center">Error al cargar las horas.</div>';
                });
        }

        if (tipoCitaSelect) {
            tipoCitaSelect.addEventListener('change', function() {
                if (hiddenFisioInput.value) {
                    loadAvailableDays(hiddenFisioInput.value);
                }
            });
        }

        function setupSearch(inputId, resultsId, hiddenId, rol, onSelect) {
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

                const searchUrl = rol === 'Paciente' ? `<?= PROJECT_ROOT ?>/pacientes/buscar?q=${encodeURIComponent(query)}` : `<?= PROJECT_ROOT ?>/trabajadores/buscar?q=${encodeURIComponent(query)}`;
                debounceTimer = setTimeout(() => {
                    fetch(searchUrl)
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
                                        if (typeof onSelect === 'function') {
                                            onSelect(user.usuario_id);
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

        setupSearch('paciente_search', 'paciente_results', 'paciente_id', 'Paciente');

        fisioCards.forEach(card => {
            card.addEventListener('click', function() {
                const fisioId = this.dataset.id;

                fisioCards.forEach(c => {
                    c.classList.remove('border-primary-500', 'bg-primary-50/30', 'ring-2', 'ring-primary-500/20');
                    c.classList.add('border-gray-100', 'bg-white');
                });

                this.classList.remove('border-gray-100', 'bg-white');
                this.classList.add('border-primary-500', 'bg-primary-50/30', 'ring-2', 'ring-primary-500/20');

                hiddenFisioInput.value = fisioId;
                loadAvailableDays(fisioId);
            });
        });

        // Carga inicial
        if (hiddenFisioInput.value) {
            loadAvailableDays(hiddenFisioInput.value, fechaHoraHidden.value);
        }
    });
</script>

<style>
    .scrollbar-thin::-webkit-scrollbar {
        height: 6px;
    }

    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>