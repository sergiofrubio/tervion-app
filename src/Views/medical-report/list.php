<?php
$pageTitle = "Historias Clínicas";
$paciente = $paciente ?? null;
$informes = $informes ?? [];
$citas = $citas ?? [];

include TEMPLATE_DIR . 'header.php';

// Calcular edad si hay paciente
$edad = null;
if ($paciente && !empty($paciente['fecha_nacimiento'])) {
    $fechaNac = new DateTime($paciente['fecha_nacimiento']);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNac)->y;
}

// Citas pasadas y próximas para recordatorios / estado
$proximaCita = null;
// $ahora = date('Y-m-d H:i:s');
// foreach ($citas as $c) {
//     if ($c['fecha_cita'] >= $ahora && ($c['estado'] ?? '') !== 'cancelada') {
//         $proximaCita = $c;
//         break;
//     }
// }
?>

<div class="max-w-7xl mx-auto space-y-6" x-data="historiasClinicas()">

    <!-- Header y Buscador de Pacientes -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Historias Clínicas</h1>
                <p class="text-xs text-slate-500 mt-0.5">Búsqueda y consulta del expediente médico del paciente</p>
            </div>

            <!-- Buscador Dinámico con Autocompletado -->
            <div class="relative w-full md:w-96" @click.away="isOpen = false">
                <label for="search-input" class="sr-only">Buscar paciente</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="bi bi-search text-xs"></i>
                    </div>
                    <input type="text"
                        id="search-input"
                        x-model="searchQuery"
                        @input.debounce.250ms="searchPatients()"
                        @focus="if(results.length > 0) isOpen = true"
                        placeholder="Buscar paciente por nombre, DNI o teléfono..."
                        class="w-full pl-8 pr-8 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                        autocomplete="off">
                    <button x-show="searchQuery.length > 0"
                        @click="searchQuery = ''; results = []; isOpen = false"
                        type="button"
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-circle-fill text-xs"></i>
                    </button>
                </div>

                <!-- Dropdown de Resultados -->
                <div x-show="isOpen && results.length > 0"
                    x-cloak
                    class="absolute left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-50 max-h-72 overflow-y-auto divide-y divide-slate-100">
                    <template x-for="item in results" :key="item.usuario_id">
                        <a :href="'<?= PROJECT_ROOT ?>/historial?paciente_id=' + encodeURIComponent(item.usuario_id)"
                            class="block p-3 hover:bg-slate-50 transition-colors text-left group">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-slate-900 group-hover:text-primary-600" x-text="item.nombre + ' ' + (item.apellidos || '')"></span>
                                <span class="text-[11px] font-mono text-slate-400" x-text="item.usuario_id"></span>
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-[11px] text-slate-500">
                                <span x-show="item.telefono" class="flex items-center gap-1">
                                    <i class="bi bi-telephone text-[10px]"></i>
                                    <span x-text="item.telefono"></span>
                                </span>
                                <span x-show="item.email" class="truncate max-w-[180px]" x-text="item.email"></span>
                            </div>
                        </a>
                    </template>
                </div>

                <div x-show="isOpen && isLoading"
                    x-cloak
                    class="absolute left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-xl shadow-lg p-3 text-center text-xs text-slate-500 z-50">
                    Buscando pacientes...
                </div>

                <div x-show="isOpen && !isLoading && searchQuery.length >= 2 && results.length === 0"
                    x-cloak
                    class="absolute left-0 right-0 mt-1.5 bg-white border border-slate-200 rounded-xl shadow-lg p-3 text-center text-xs text-slate-500 z-50">
                    No se encontraron pacientes coincidentes.
                </div>
            </div>
        </div>
    </div>

    <?php if (!$paciente): ?>
        <!-- Estado Vacío / Prompt inicial para buscar paciente -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center shadow-sm">
            <div class="max-w-md mx-auto space-y-3">
                <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-xl">
                    <i class="bi bi-person-lines-fill"></i>
                </div>
                <h3 class="text-base font-semibold text-slate-900">Selecciona un paciente</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Utiliza el buscador superior para localizar al paciente por su nombre, DNI/NIE o número de teléfono para acceder a su expediente clínico completo, informes y antecedentes.
                </p>
                <div class="pt-2">
                    <a href="<?= PROJECT_ROOT ?>/pacientes" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-700 bg-primary-50 hover:bg-primary-100 px-3.5 py-2 rounded-lg transition-colors">
                        <i class="bi bi-people"></i>
                        Ver directorio de pacientes
                    </a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Vista Completa del Expediente Clínico -->

        <!-- 1. Tarjeta Resumen del Paciente & Alertas Clínicas -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-sm shrink-0">
                        <?= strtoupper(substr($paciente['nombre'], 0, 1) . substr($paciente['apellidos'] ?? '', 0, 1)) ?>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-lg font-bold text-slate-900 leading-tight">
                                <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?>
                            </h2>
                            <span class="px-2 py-0.5 rounded text-[11px] font-mono font-medium bg-slate-100 text-slate-700">
                                <?= htmlspecialchars($paciente['usuario_id']) ?>
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-3 flex-wrap">
                            <span><?= htmlspecialchars($paciente['genero'] ?? 'No especificado') ?></span>
                            <?php if ($edad !== null): ?>
                                <span>•</span>
                                <span><?= $edad ?> años (<?= date('d/m/Y', strtotime($paciente['fecha_nacimiento'])) ?>)</span>
                            <?php endif; ?>
                            <span>•</span>
                            <span><?= htmlspecialchars($paciente['telefono'] ?? 'Sin teléfono') ?></span>
                            <span>•</span>
                            <span><?= htmlspecialchars($paciente['email'] ?? 'Sin email') ?></span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="<?= PROJECT_ROOT ?>/historial/crear?paciente_id=<?= urlencode($paciente['usuario_id']) ?>"
                        class="inline-flex items-center gap-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                        <i class="bi bi-plus-lg"></i>
                        Nuevo Informe
                    </a>
                    <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= urlencode($paciente['usuario_id']) ?>"
                        class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-2 rounded-lg transition-colors"
                        title="Ver ficha personal completa">
                        <i class="bi bi-person-bounding-box"></i>
                        Ficha Paciente
                    </a>
                </div>
            </div>

            <!-- Fila de Datos Clínicos Rápidos: Alergias, Recordatorios y Próxima Cita -->
            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100 bg-slate-50/50 text-xs">

                <!-- Alergias y Advertencias Médicas -->
                <div class="p-4 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold uppercase tracking-wider text-[10px] text-slate-500 flex items-center gap-1.5">
                            <i class="bi bi-shield-exclamation text-amber-500 text-xs"></i>
                            Alergias y Alertas
                        </span>
                    </div>
                    <div class="p-2.5 rounded-lg border bg-amber-50/60 border-amber-200/80 text-amber-900">
                        <p class="font-medium text-[11px]">Sin alergias farmacológicas conocidas registradas.</p>
                        <p class="text-[10px] text-amber-700/80 mt-0.5">Verificar tolerancia previa en tratamientos manuales y electroterapia.</p>
                    </div>
                </div>

                <!-- Recordatorios Clínicos / Notas de Seguimiento -->
                <div class="p-4 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold uppercase tracking-wider text-[10px] text-slate-500 flex items-center gap-1.5">
                            <i class="bi bi-pin-angle text-blue-500 text-xs"></i>
                            Recordatorios de Seguimiento
                        </span>
                    </div>
                    <div class="p-2.5 rounded-lg border bg-blue-50/60 border-blue-200/80 text-blue-900">
                        <p class="font-medium text-[11px]">Control de evolución de rango articular</p>
                        <p class="text-[10px] text-blue-700/80 mt-0.5">Solicitar actualización de pauta de ejercicios domiciliarios.</p>
                    </div>
                </div>

                <!-- Próxima Cita / Estado de Asistencia -->
                <div class="p-4 space-y-1.5">
                    <span class="font-bold uppercase tracking-wider text-[10px] text-slate-500 flex items-center gap-1.5">
                        <i class="bi bi-calendar-event text-emerald-500 text-xs"></i>
                        Próxima Sesión Programada
                    </span>
                    <?php if ($proximaCita): ?>
                        <div class="p-2.5 rounded-lg border bg-white border-slate-200 text-slate-800">
                            <p class="font-semibold text-slate-900 text-[11px]"><?= date('d/m/Y \a \l\a\s H:i', strtotime($proximaCita['fecha_cita'])) ?> h</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">Estado: <span class="font-medium capitalize text-emerald-600"><?= htmlspecialchars($proximaCita['estado']) ?></span></p>
                        </div>
                    <?php else: ?>
                        <div class="p-2.5 rounded-lg border border-dashed border-slate-200 bg-white text-slate-500 text-[11px]">
                            No hay citas futuras programadas actualmente.
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <!-- 2. Cronología de Informes Médicos (Timeline) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="bi bi-clock-history text-primary-600"></i>
                        Evolución e Informes Médicos (<?= count($informes) ?>)
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Historial cronológico ordenado por fecha de consulta</p>
                </div>
            </div>

            <?php if (empty($informes)): ?>
                <div class="bg-white rounded-2xl border border-slate-200/80 p-8 text-center shadow-sm">
                    <p class="text-xs text-slate-500">Este paciente aún no tiene informes clínicos registrados en su historia.</p>
                    <div class="mt-3">
                        <a href="<?= PROJECT_ROOT ?>/historial/crear?paciente_id=<?= urlencode($paciente['usuario_id']) ?>"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-700 bg-primary-50 px-3 py-1.5 rounded-lg">
                            <i class="bi bi-plus-lg"></i>
                            Crear primer informe
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($informes as $index => $inf): ?>
                        <article class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden hover:border-slate-300 transition-colors">

                            <!-- Cabecera de la consulta -->
                            <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full <?= $index === 0 ? 'bg-primary-600 ring-4 ring-primary-100' : 'bg-slate-400' ?>"></div>
                                    <span class="text-xs font-bold text-slate-900">
                                        <?= date('d/m/Y \a \l\a\s H:i', strtotime($inf['fecha_consulta'])) ?> h
                                    </span>
                                    <span class="text-slate-300">|</span>
                                    <span class="text-xs text-slate-600 font-medium">
                                        Motivo: <strong class="text-slate-900 font-semibold"><?= htmlspecialchars($inf['motivo_consulta']) ?></strong>
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 self-end sm:self-auto">
                                    <a href="<?= PROJECT_ROOT ?>/historial/detalle?id=<?= $inf['historial_id'] ?>"
                                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary-700 hover:text-primary-800 bg-primary-50 hover:bg-primary-100 border border-primary-200 px-2.5 py-1 rounded-md transition-colors"
                                        title="Ver en Document Studio">
                                        <i class="bi bi-file-earmark-text text-primary-600"></i>
                                        Ver Informe
                                    </a>
                                    <a href="<?= PROJECT_ROOT ?>/historial/pdf?id=<?= $inf['historial_id'] ?>"
                                        target="_blank"
                                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-md transition-colors"
                                        title="Exportar informe a PDF">
                                        <i class="bi bi-file-earmark-pdf text-rose-500"></i>
                                        PDF
                                    </a>
                                </div>
                            </div>

                            <!-- Contenido Clínico Estructurado -->
                            <div class="p-5 grid grid-cols-1 lg:grid-cols-12 gap-5 text-xs">

                                <!-- Diagnóstico Clínico -->
                                <div class="lg:col-span-6 space-y-1">
                                    <span class="font-bold text-[10px] uppercase tracking-wider text-slate-400">Diagnóstico</span>
                                    <div class="text-slate-800 leading-relaxed bg-slate-50/50 p-3 rounded-xl border border-slate-100">
                                        <?= nl2br(htmlspecialchars($inf['diagnostico'])) ?>
                                    </div>
                                </div>

                                <!-- Tratamiento Aplicado -->
                                <div class="lg:col-span-6 space-y-1">
                                    <span class="font-bold text-[10px] uppercase tracking-wider text-slate-400">Tratamiento / Pauta</span>
                                    <div class="text-slate-800 leading-relaxed bg-slate-50/50 p-3 rounded-xl border border-slate-100">
                                        <?= nl2br(htmlspecialchars($inf['tratamiento'])) ?>
                                    </div>
                                </div>

                                <!-- Observaciones (si las hay) -->
                                <?php if (!empty($inf['observaciones'])): ?>
                                    <div class="lg:col-span-12 space-y-1 pt-1 border-t border-slate-100">
                                        <span class="font-bold text-[10px] uppercase tracking-wider text-slate-400">Observaciones Adicionales</span>
                                        <div class="text-slate-600 italic bg-amber-50/30 p-2.5 rounded-lg border border-amber-100/60">
                                            <?= nl2br(htmlspecialchars($inf['observaciones'])) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</div>

<script type="module" src="<?= PROJECT_ROOT ?>/public/js/modules/medical-report/report-list.js"></script>

<?php include TEMPLATE_DIR . 'footer.php'; ?>