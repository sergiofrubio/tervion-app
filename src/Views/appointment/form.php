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
        <a href="<?= PROJECT_ROOT ?>/citas" onclick="if (history.length > 1) { history.back(); return false; }" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/citas/<?= $isEdit ? 'editar' : 'crear' ?>" method="POST" class="p-8 space-y-10" id="appointment-form">
            <?php if ($isEdit) : ?>
                <input type="hidden" name="cita_id" id="cita_id" value="<?= $a['cita_id'] ?>">
            <?php endif; ?>

            <!-- 1. Asignación de Paciente y Profesional -->
            <div class="space-y-6">
                <div class="pb-3 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center text-sm font-black">1</span>
                        Participantes de la Cita
                    </h2>
                    <span class="text-xs text-gray-400 font-medium">Paciente y terapeuta asignado</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Paciente -->
                    <div class="space-y-2 relative group">
                        <label for="paciente_search" class="block text-sm font-semibold text-gray-700">Paciente <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 pointer-events-none">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="paciente_search" placeholder="Buscar por nombre, apellidos o DNI..."
                                value="<?= $isEdit ? htmlspecialchars(($a['paciente_nombre'] ?? '') . ' ' . ($a['paciente_apellidos'] ?? '')) : '' ?>"
                                class="block w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 pl-10 transition-all bg-gray-50/50 hover:bg-white focus:bg-white" autocomplete="off">
                            <input type="hidden" name="paciente_id" id="paciente_id" value="<?= $a['paciente_id'] ?? '' ?>" required>
                            <div id="paciente_results" class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 hidden max-h-64 overflow-y-auto py-2"></div>
                        </div>
                        <p class="text-xs text-gray-400">Escribe al menos 2 caracteres para desplegar la lista de pacientes.</p>
                    </div>

                    <!-- Tipo de Sesión / Especialidad -->
                    <div class="space-y-2">
                        <label for="tipo_cita_id" class="block text-sm font-semibold text-gray-700">Tipo de Sesión <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="tipo_cita_id" id="tipo_cita_id" class="block w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 pr-10 bg-gray-50/50 hover:bg-white focus:bg-white appearance-none transition-all">
                                <option value="">-- Seleccionar Tipo de Sesión --</option>
                                <?php if (!empty($tiposCitas)): ?>
                                    <?php foreach ($tiposCitas as $tc): ?>
                                        <?php $selected = ($isEdit && isset($a['tipo_cita_id']) && $a['tipo_cita_id'] == $tc['tipo_cita_id']) ? 'selected' : ''; ?>
                                        <option value="<?= $tc['tipo_cita_id'] ?>" data-duracion="<?= $tc['duracion_minutos'] ?? 60 ?>" data-precio="<?= $tc['precio'] ?? 0 ?>" <?= $selected ?>>
                                            <?= htmlspecialchars($tc['nombre']) ?> (<?= $tc['duracion_minutos'] ?> min - <?= number_format($tc['precio'], 2) ?>€)
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="1">Sesión Fisioterapia General (50 min - 50.00€)</option>
                                    <option value="2">Primera Consulta y Diagnóstico (60 min - 65.00€)</option>
                                    <option value="3">Rehabilitación Funcional (45 min - 45.00€)</option>
                                    <option value="4">Sesión Osteopatía (55 min - 60.00€)</option>
                                <?php endif; ?>
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                <i class="bi bi-chevron-down"></i>
                            </span>
                        </div>
                        <p class="text-xs text-gray-400">Define los tiempos recomendados y la tarifa base.</p>
                    </div>

                    <!-- Terapeuta Selector -->
                    <div class="space-y-3 md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700">Terapeuta Responsable <span class="text-red-500">*</span></label>
                        <input type="hidden" name="terapeuta_id" id="terapeuta_id" value="<?= $isEdit ? ($a['terapeuta_id'] ?? '') : '' ?>" required>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="fisios-cards-container">
                            <?php if (!empty($fisioterapeutas)): ?>
                                <?php foreach ($fisioterapeutas as $f): ?>
                                    <?php
                                    $isSelected = ($isEdit && isset($a['terapeuta_id']) && $f['usuario_id'] == $a['terapeuta_id']);
                                    $avatarId = (intval(preg_replace('/[^0-9]/', '', $f['usuario_id'])) % 70) + 1;
                                    $avatarUrl = (isset($f['genero']) && $f['genero'] === 'Mujer')
                                        ? "https://randomuser.me/api/portraits/women/{$avatarId}.jpg"
                                        : "https://randomuser.me/api/portraits/men/{$avatarId}.jpg";
                                    ?>
                                    <button type="button" data-id="<?= $f['usuario_id'] ?>"
                                        class="fisio-card flex flex-col items-center p-4 bg-white border-2 rounded-2xl hover:border-primary-400 hover:bg-primary-50/10 active:scale-95 transition-all text-center focus:outline-none group <?= $isSelected ? 'border-primary-500 bg-primary-50/30 ring-2 ring-primary-500/20' : 'border-gray-100' ?>">
                                        <div class="w-14 h-14 rounded-full overflow-hidden mb-2.5 border-2 border-white shadow-sm group-hover:scale-105 transition-transform">
                                            <img src="<?= $avatarUrl ?>" alt="<?= htmlspecialchars($f['nombre']) ?>" class="w-full h-full object-cover">
                                        </div>
                                        <span class="block text-sm font-bold text-gray-900 leading-tight"><?= htmlspecialchars($f['nombre'] . ' ' . $f['apellidos']) ?></span>
                                        <span class="block text-[11px] text-primary-600 font-medium mt-0.5"><?= htmlspecialchars($f['especialidad'] ?? 'Terapeuta') ?></span>
                                    </button>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Programación: Fecha, Horarios y Ubicación -->
            <div class="space-y-6">
                <div class="pb-3 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center text-sm font-black">2</span>
                        Fecha, Horarios y Modalidad
                    </h2>
                    <span class="text-xs text-gray-400 font-medium">Planificación temporal y lugar</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Fecha de la Sesión -->
                    <div class="space-y-2">
                        <label for="fecha_sesion" class="block text-sm font-semibold text-gray-700">Fecha de la Cita <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" name="fecha_sesion" id="fecha_sesion"
                                value="<?= $isEdit && !empty($a['fecha_hora']) ? date('Y-m-d', strtotime($a['fecha_hora'])) : date('Y-m-d') ?>"
                                class="block w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-gray-50/50 hover:bg-white focus:bg-white transition-all" required>
                        </div>
                    </div>

                    <!-- Hora de Inicio -->
                    <div class="space-y-2">
                        <label for="hora_inicio" class="block text-sm font-semibold text-gray-700">Hora de Inicio <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="time" name="hora_inicio" id="hora_inicio"
                                value="<?= $isEdit && !empty($a['fecha_hora']) ? date('H:i', strtotime($a['fecha_hora'])) : '10:00' ?>"
                                class="block w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-gray-50/50 hover:bg-white focus:bg-white transition-all" required>
                        </div>
                    </div>

                    <!-- Hora de Finalización -->
                    <div class="space-y-2">
                        <label for="hora_fin" class="block text-sm font-semibold text-gray-700">Hora de Finalización <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="time" name="hora_fin" id="hora_fin"
                                value="<?= $isEdit && !empty($a['fecha_hora_fin']) ? date('H:i', strtotime($a['fecha_hora_fin'])) : '11:00' ?>"
                                class="block w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-gray-50/50 hover:bg-white focus:bg-white transition-all" required>
                        </div>
                    </div>

                    <input type="hidden" name="fecha_hora" id="fecha_hora_hidden" value="<?= $isEdit ? ($a['fecha_hora'] ?? '') : '' ?>">
                </div>

                <!-- Ubicación / Modalidad de la Cita -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700">Ubicación de la Sesión <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Presencial -->
                        <label class="relative flex items-center p-4 rounded-2xl border-2 border-gray-200 hover:border-primary-400 bg-white cursor-pointer transition-all has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50/20 has-[:checked]:ring-2 has-[:checked]:ring-primary-500/20">
                            <input type="radio" name="ubicacion_tipo" id="ubicacion_presencial" value="presencial" class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500" <?= (!$isEdit || ($a['ubicacion_tipo'] ?? 'presencial') === 'presencial') ? 'checked' : '' ?>>
                            <div class="ml-3 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-gray-900">Presencial en Clínica</span>
                                    <span class="block text-xs text-gray-500">Gabinete / Sala de tratamiento física</span>
                                </div>
                            </div>
                        </label>

                        <!-- Telemática / Online -->
                        <label class="relative flex items-center p-4 rounded-2xl border-2 border-gray-200 hover:border-primary-400 bg-white cursor-pointer transition-all has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50/20 has-[:checked]:ring-2 has-[:checked]:ring-primary-500/20">
                            <input type="radio" name="ubicacion_tipo" id="ubicacion_telematica" value="telematica" class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500" <?= ($isEdit && ($a['ubicacion_tipo'] ?? '') === 'telematica') ? 'checked' : '' ?>>
                            <div class="ml-3 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                                    <i class="bi bi-camera-video-fill"></i>
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-gray-900">Telemática (Online)</span>
                                    <span class="block text-xs text-gray-500">Videollamada con enlace virtual automático</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Panel informativo de Asignación Automática de Despachos -->
                    <div id="despacho-auto-info" class="p-4 rounded-2xl border border-indigo-100 bg-indigo-50/40 text-indigo-900 flex items-start gap-3 transition-all">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 text-base">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                        <div class="text-xs space-y-1">
                            <div class="flex items-center gap-2 font-bold text-indigo-950">
                                <span>Asignación Automática de Despacho</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-indigo-100 text-indigo-700 uppercase font-black tracking-wider">Automático</span>
                            </div>
                            <p class="text-indigo-800/80">
                                El sistema cuadra en tiempo real la disponibilidad del profesional con los despachos libres de la clínica, asignando automáticamente una sala disponible.
                            </p>
                            <?php if ($isEdit && !empty($a['despacho_nombre'])): ?>
                                <div class="pt-1 flex items-center gap-2 font-medium text-gray-700">
                                    <span class="text-xs text-gray-500">Despacho actual asignado:</span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-xs font-bold border" style="background-color: <?= htmlspecialchars($a['despacho_color'] ?? '#6366f1') ?>15; border-color: <?= htmlspecialchars($a['despacho_color'] ?? '#6366f1') ?>40; color: <?= htmlspecialchars($a['despacho_color'] ?? '#6366f1') ?>;">
                                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($a['despacho_nombre']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($despachos)): ?>
                                <div class="pt-1.5 flex flex-wrap items-center gap-1.5 text-[11px] text-gray-500">
                                    <span class="font-semibold text-gray-600">Salas activas en clínica:</span>
                                    <?php foreach ($despachos as $d): ?>
                                        <?php if (($d['estado'] ?? 'Activo') === 'Activo'): ?>
                                            <span class="px-2 py-0.5 rounded-md bg-white border border-gray-200 text-gray-700 font-medium">
                                                <?= htmlspecialchars($d['nombre']) ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Bloques dinámicos de días y slots recomendados del terapeuta -->
                <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                            <i class="bi bi-magic text-primary-500"></i> Asistente de disponibilidad del profesional
                        </span>
                        <span class="text-xs text-gray-400">Clic en un slot para autocompletar horas</span>
                    </div>

                    <!-- Días disponibles -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600">Días con disponibilidad en agenda:</label>
                        <div id="dias-container" class="flex gap-3 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-200">
                            <div class="text-xs text-gray-400 italic p-3 bg-white rounded-xl w-full text-center border border-gray-100">
                                Selecciona un profesional para consultar su calendario de días disponibles.
                            </div>
                        </div>
                    </div>

                    <!-- Horas disponibles -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600">Horas de inicio sugeridas:</label>
                        <div id="slots-container" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                            <div class="col-span-full text-xs text-gray-400 italic p-3 bg-white rounded-xl text-center border border-gray-100">
                                Selecciona un día primero para ver los huecos disponibles.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Precio, Cobro y Gestión de Bonos -->
            <div class="space-y-6">
                <div class="pb-3 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center text-sm font-black">3</span>
                        Tarificación y Condiciones de Cobro
                    </h2>
                    <span class="text-xs text-gray-400 font-medium">Honorarios, bonos y plazos de facturación</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Precio de la sesión -->
                    <div class="space-y-2">
                        <label for="precio_sesion" class="block text-sm font-semibold text-gray-700">Precio de la Sesión (€) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 pointer-events-none">
                                <i class="bi bi-currency-euro"></i>
                            </span>
                            <input type="number" step="0.01" min="0" name="precio" id="precio_sesion"
                                value="<?= $isEdit ? ($a['precio'] ?? '50.00') : '50.00' ?>"
                                placeholder="0.00"
                                class="block w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 pl-10 bg-gray-50/50 hover:bg-white focus:bg-white transition-all font-semibold text-gray-800">
                        </div>
                    </div>

                    <!-- Utilizar Bono -->
                    <div class="space-y-2 flex flex-col justify-end">
                        <div class="p-3.5 rounded-2xl border border-gray-200 bg-gray-50/50 hover:bg-white transition-all flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base">
                                    <i class="bi bi-ticket-perforated-fill"></i>
                                </div>
                                <div>
                                    <label for="usar_bono" class="text-sm font-bold text-gray-800 cursor-pointer">Canjear sesión de bono</label>
                                    <p class="text-xs text-gray-500">Descontar del bono activo del paciente</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="usar_bono" id="usar_bono" value="1"
                                    <?= (!empty($a['usar_bono']) || !empty($a['bono_id'])) ? 'checked' : '' ?>
                                    class="w-5 h-5 rounded-lg text-primary-600 border-gray-300 focus:ring-primary-500 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- Momento del Cobro -->
                    <div class="space-y-3 md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700">Momento del Cobro <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <!-- Al finalizar -->
                            <label class="relative flex flex-col p-3.5 rounded-2xl border-2 border-gray-200 hover:border-primary-400 bg-white cursor-pointer transition-all text-center has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50/30 has-[:checked]:ring-1 has-[:checked]:ring-primary-500">
                                <input type="radio" name="momento_cobro" value="finalizar" class="sr-only" <?= (!$isEdit || ($a['momento_cobro'] ?? 'finalizar') === 'finalizar') ? 'checked' : '' ?>>
                                <i class="bi bi-clock-history text-lg text-gray-600 mb-1"></i>
                                <span class="text-sm font-bold text-gray-900">Al finalizar</span>
                                <span class="text-[11px] text-gray-500 mt-0.5">En recepción tras la sesión</span>
                            </label>

                            <!-- 24 horas antes -->
                            <label class="relative flex flex-col p-3.5 rounded-2xl border-2 border-gray-200 hover:border-primary-400 bg-white cursor-pointer transition-all text-center has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50/30 has-[:checked]:ring-1 has-[:checked]:ring-primary-500">
                                <input type="radio" name="momento_cobro" value="24h_antes" class="sr-only" <?= ($isEdit && ($a['momento_cobro'] ?? '') === '24h_antes') ? 'checked' : '' ?>>
                                <i class="bi bi-hourglass-split text-lg text-gray-600 mb-1"></i>
                                <span class="text-sm font-bold text-gray-900">24 horas antes</span>
                                <span class="text-[11px] text-gray-500 mt-0.5">Cobro anticipado (1 día)</span>
                            </label>

                            <!-- 48 horas antes -->
                            <label class="relative flex flex-col p-3.5 rounded-2xl border-2 border-gray-200 hover:border-primary-400 bg-white cursor-pointer transition-all text-center has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50/30 has-[:checked]:ring-1 has-[:checked]:ring-primary-500">
                                <input type="radio" name="momento_cobro" value="48h_antes" class="sr-only" <?= ($isEdit && ($a['momento_cobro'] ?? '') === '48h_antes') ? 'checked' : '' ?>>
                                <i class="bi bi-hourglass-top text-lg text-gray-600 mb-1"></i>
                                <span class="text-sm font-bold text-gray-900">48 horas antes</span>
                                <span class="text-[11px] text-gray-500 mt-0.5">Cobro anticipado (2 días)</span>
                            </label>

                            <!-- 72 horas antes -->
                            <label class="relative flex flex-col p-3.5 rounded-2xl border-2 border-gray-200 hover:border-primary-400 bg-white cursor-pointer transition-all text-center has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50/30 has-[:checked]:ring-1 has-[:checked]:ring-primary-500">
                                <input type="radio" name="momento_cobro" value="72h_antes" class="sr-only" <?= ($isEdit && ($a['momento_cobro'] ?? '') === '72h_antes') ? 'checked' : '' ?>>
                                <i class="bi bi-calendar2-check text-lg text-gray-600 mb-1"></i>
                                <span class="text-sm font-bold text-gray-900">72 horas antes</span>
                                <span class="text-[11px] text-gray-500 mt-0.5">Cobro anticipado (3 días)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Notificaciones y Recordatorios -->
            <div class="space-y-6">
                <div class="pb-3 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center text-sm font-black">4</span>
                        Notificaciones y Avisos al Paciente
                    </h2>
                    <span class="text-xs text-gray-400 font-medium">Canales y programación de alertas</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Canales de Notificación -->
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">Canales de Notificación</label>
                        <div class="space-y-2.5">
                            <!-- WhatsApp -->
                            <label class="flex items-center justify-between p-3 rounded-2xl border border-gray-200 bg-white hover:border-emerald-300 transition-all cursor-pointer has-[:checked]:bg-emerald-50/20 has-[:checked]:border-emerald-500">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                                        <i class="bi bi-whatsapp"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-800">WhatsApp</span>
                                        <span class="block text-xs text-gray-500">Mensaje directo interactivo</span>
                                    </div>
                                </div>
                                <input type="checkbox" name="notificar_whatsapp" value="1" <?= (!isset($a['notificar_whatsapp']) || $a['notificar_whatsapp']) ? 'checked' : '' ?> class="w-5 h-5 rounded-lg text-emerald-600 border-gray-300 focus:ring-emerald-500 cursor-pointer">
                            </label>

                            <!-- Email -->
                            <label class="flex items-center justify-between p-3 rounded-2xl border border-gray-200 bg-white hover:border-blue-300 transition-all cursor-pointer has-[:checked]:bg-blue-50/20 has-[:checked]:border-blue-500">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg">
                                        <i class="bi bi-envelope-fill"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-800">Correo Electrónico (Email)</span>
                                        <span class="block text-xs text-gray-500">Confirmación formal con archivo ICS</span>
                                    </div>
                                </div>
                                <input type="checkbox" name="notificar_email" value="1" <?= (!isset($a['notificar_email']) || $a['notificar_email']) ? 'checked' : '' ?> class="w-5 h-5 rounded-lg text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                            </label>

                            <!-- SMS -->
                            <label class="flex items-center justify-between p-3 rounded-2xl border border-gray-200 bg-white hover:border-purple-300 transition-all cursor-pointer has-[:checked]:bg-purple-50/20 has-[:checked]:border-purple-500">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-lg">
                                        <i class="bi bi-chat-dots-fill"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-800">Mensaje SMS</span>
                                        <span class="block text-xs text-gray-500">Aviso rápido al móvil</span>
                                    </div>
                                </div>
                                <input type="checkbox" name="notificar_sms" value="1" <?= (!empty($a['notificar_sms'])) ? 'checked' : '' ?> class="w-5 h-5 rounded-lg text-purple-600 border-gray-300 focus:ring-purple-500 cursor-pointer">
                            </label>
                        </div>
                    </div>

                    <!-- Programación del envío -->
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">Momentos de Envío</label>
                        <div class="space-y-3">
                            <!-- Inmediato -->
                            <label class="flex items-center justify-between p-3.5 rounded-2xl border border-gray-200 bg-white hover:border-primary-300 transition-all cursor-pointer has-[:checked]:bg-primary-50/20 has-[:checked]:border-primary-500">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-base">
                                        <i class="bi bi-lightning-charge-fill"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-800">Notificar ahora mismo</span>
                                        <span class="block text-xs text-gray-500">Al guardar o confirmar la cita</span>
                                    </div>
                                </div>
                                <input type="checkbox" name="notificar_inmediato" id="notificar_inmediato" value="1" <?= (!isset($a['notificar_inmediato']) || $a['notificar_inmediato']) ? 'checked' : '' ?> class="w-5 h-5 rounded-lg text-primary-600 border-gray-300 focus:ring-primary-500 cursor-pointer">
                            </label>

                            <!-- Recordatorio previo a la sesión -->
                            <div class="p-3.5 rounded-2xl border border-gray-200 bg-white space-y-3">
                                <label class="flex items-center justify-between cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-base">
                                            <i class="bi bi-bell-fill"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm font-bold text-gray-800">Recordatorio antes de la sesión</span>
                                            <span class="block text-xs text-gray-500">Aviso preventivo para reducir absentismo</span>
                                        </div>
                                    </div>
                                    <input type="checkbox" name="notificar_previo" id="notificar_previo" value="1" <?= (!isset($a['notificar_previo']) || $a['notificar_previo']) ? 'checked' : '' ?> class="w-5 h-5 rounded-lg text-teal-600 border-gray-300 focus:ring-teal-500 cursor-pointer">
                                </label>

                                <!-- Selector de antelación del recordatorio -->
                                <div id="contenedor_antelacion_recordatorio" class="pt-2 border-t border-gray-100 space-y-1.5">
                                    <label class="block text-xs font-semibold text-gray-600">Antelación del recordatorio:</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 hover:border-primary-400 cursor-pointer has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50 has-[:checked]:text-primary-700">
                                            <input type="radio" name="antelacion_recordatorio" value="24h" class="sr-only" <?= (!$isEdit || ($a['antelacion_recordatorio'] ?? '24h') === '24h') ? 'checked' : '' ?>>
                                            <span>24 horas</span>
                                        </label>
                                        <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 hover:border-primary-400 cursor-pointer has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50 has-[:checked]:text-primary-700">
                                            <input type="radio" name="antelacion_recordatorio" value="48h" class="sr-only" <?= ($isEdit && ($a['antelacion_recordatorio'] ?? '') === '48h') ? 'checked' : '' ?>>
                                            <span>48 horas</span>
                                        </label>
                                        <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 hover:border-primary-400 cursor-pointer has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50 has-[:checked]:text-primary-700">
                                            <input type="radio" name="antelacion_recordatorio" value="72h" class="sr-only" <?= ($isEdit && ($a['antelacion_recordatorio'] ?? '') === '72h') ? 'checked' : '' ?>>
                                            <span>72 horas</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="<?= PROJECT_ROOT ?>/citas" class="px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" id="submit-btn" class="inline-flex justify-center items-center gap-2 rounded-xl bg-primary-600 px-8 py-3 text-sm font-semibold text-white shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="bi bi-check2-circle text-base"></i>
                    <?= $isEdit ? "Guardar Cambios" : "Programar Cita" ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script type="module" src="<?= PROJECT_ROOT ?>/public/js/modules/appointment/appointment-form.js"></script>

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