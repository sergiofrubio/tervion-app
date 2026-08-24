<?php
$pageTitle = "Expediente Clínico — " . ($usuario['nombre'] . ' ' . $usuario['apellidos']);
include TEMPLATE_DIR . 'header.php';

function calcularEdad($fechaNacimiento)
{
    if (empty($fechaNacimiento)) return 'N/D';
    try {
        $nacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($nacimiento);
        return $edad->y;
    } catch (Exception $e) {
        return 'N/D';
    }
}

$edadCalculada = calcularEdad($usuario['fecha_nacimiento'] ?? null);
$iniciales = strtoupper(substr($usuario['nombre'] ?? '', 0, 1) . substr($usuario['apellidos'] ?? '', 0, 1));
$totalInformes = count($informes ?? []);
$totalCitas = count($citas ?? []);
$totalDocumentos = count($documentos ?? []);
$totalFacturas = count($facturas ?? []);

// Última consulta
$ultimaConsulta = !empty($informes[0]['fecha_consulta'])
    ? date('d/m/Y', strtotime($informes[0]['fecha_consulta']))
    : (!empty($citas[0]['fecha_hora']) ? date('d/m/Y', strtotime($citas[0]['fecha_hora'])) : 'Sin registros');
?>

<div class="space-y-6 animate-fade-in" x-data="{ activeTab: 'history' }">

    <!-- Top Action & Navigation Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/pacientes"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Volver a Pacientes</span>
            </a>
            <div class="h-4 w-px bg-gray-300 hidden sm:block"></div>
            <span class="text-xs font-medium text-gray-400 hidden sm:inline-block">Expediente Clínico Digital</span>
        </div>

        <div class="flex items-center gap-3">
            <?php if ($rol !== "Paciente") : ?>
                <a href="<?= PROJECT_ROOT ?>/citas/crear?paciente_id=<?= $usuario['usuario_id'] ?>"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-600 text-white text-xs font-semibold hover:bg-primary-500 transition-all shadow-sm hover:scale-105 active:scale-95">
                    <i class="bi bi-calendar-plus"></i>
                    <span>Agendar Cita</span>
                </a>
                <a href="<?= PROJECT_ROOT ?>/historial/crear?paciente_id=<?= $usuario['usuario_id'] ?>"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition-all shadow-sm hover:scale-105 active:scale-95">
                    <i class="bi bi-file-earmark-plus"></i>
                    <span>Nuevo Informe</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Clinical Profile Card (Header Clínico Principal) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-200 shadow-sm relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">

            <!-- Patient Identity -->
            <div class="flex items-start sm:items-center gap-3">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gray-900 text-white flex items-center justify-center font-bold text-xl sm:text-2xl shrink-0 shadow-md">
                    <?= $iniciales ?: 'P' ?>
                </div>
                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                            <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?>
                        </h1>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 gap-1 text-xs text-gray-500 font-medium">
                        <span><strong class="text-gray-700">NHC:</strong> #<?= htmlspecialchars($usuario['usuario_id']) ?></span>
                        <span>•</span>
                        <span><strong class="text-gray-700">Género:</strong> <?= htmlspecialchars($usuario['genero'] ?? 'No especificado') ?></span>
                        <span>•</span>
                        <span><strong class="text-gray-700">Edad:</strong> <?= $edadCalculada !== 'N/D' ? $edadCalculada . ' años' : 'N/D' ?></span>
                    </div>
                </div>
            </div>

            <!-- Quick Stat Counters -->
            <div class="flex items-center gap-3 sm:gap-6 pt-4 md:pt-0 border-t md:border-t-0 border-gray-100">
                <div class="text-left md:text-right">
                    <span class="block text-[11px] uppercase tracking-wider font-bold text-gray-400">Total Consultas</span>
                    <span class="text-2xl font-light text-gray-900"><?= $totalInformes ?></span>
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <div class="text-left md:text-right">
                    <span class="block text-[11px] uppercase tracking-wider font-bold text-gray-400">Citas Totales</span>
                    <span class="text-2xl font-light text-gray-900"><?= $totalCitas ?></span>
                </div>
            </div>

        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Patient Details & Administrative Info -->
        <div class="space-y-6">

            <!-- Administrative Contact Card -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-lines-fill text-primary-600"></i>
                        Datos de Contacto
                    </h3>
                    <a href="<?= PROJECT_ROOT ?>/pacientes/editar?id=<?= $usuario['usuario_id'] ?>" class="text-xs font-semibold text-primary-600 hover:text-primary-700">
                        Editar
                    </a>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <span class="block font-bold text-gray-400 uppercase tracking-wider text-[10px] mb-1">Teléfono</span>
                        <?php if (!empty($usuario['telefono'])): ?>
                            <a href="tel:<?= htmlspecialchars($usuario['telefono']) ?>" class="font-medium text-sm text-gray-900 hover:text-primary-600 inline-flex items-center gap-1.5">
                                <i class="bi bi-telephone text-gray-400"></i>
                                <?= htmlspecialchars($usuario['telefono']) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-gray-400 italic">No registrado</span>
                        <?php endif; ?>
                    </div>

                    <div>
                        <span class="block font-bold text-gray-400 uppercase tracking-wider text-[10px] mb-1">Correo Electrónico</span>
                        <a href="mailto:<?= htmlspecialchars($usuario['email']) ?>" class="font-medium text-sm text-primary-600 hover:text-primary-700 break-all inline-flex items-center gap-1.5">
                            <i class="bi bi-envelope text-gray-400"></i>
                            <?= htmlspecialchars($usuario['email']) ?>
                        </a>
                    </div>

                    <div>
                        <span class="block font-bold text-gray-400 uppercase tracking-wider text-[10px] mb-1">Dirección Postal</span>
                        <div class="text-gray-700 text-sm leading-relaxed">
                            <?= !empty($usuario['direccion']) ? htmlspecialchars($usuario['direccion']) : 'Sin dirección especificada' ?>
                            <?php if (!empty($usuario['cp']) || !empty($usuario['municipio'])): ?>
                                <br><span class="text-gray-500 text-xs"><?= htmlspecialchars(($usuario['cp'] ?? '') . ' ' . ($usuario['municipio'] ?? '')) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($usuario['provincia'])): ?>
                                <br><span class="text-gray-500 text-xs"><?= htmlspecialchars($usuario['provincia']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-gray-400 text-[11px]">
                        <span>Registro en clínica:</span>
                        <span class="font-semibold text-gray-700">
                            <?= !empty($usuario['fecha_creacion']) ? date('d/m/Y', strtotime($usuario['fecha_creacion'])) : '—' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Tabs (Historial Médico / Citas) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden flex flex-col min-h-[480px]">

                <!-- Modern Tab Navigation -->
                <div class="border-b border-gray-200 bg-gray-50/60 px-6 pt-3">
                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                        <button
                            @click="activeTab = 'history'"
                            :class="activeTab === 'history' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300 font-medium'"
                            class="whitespace-nowrap py-3.5 px-1 border-b-2 text-sm transition-all flex items-center gap-2">
                            <i class="bi bi-journal-medical text-base"></i>
                            <span>Historial Médico</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                                :class="activeTab === 'history' ? 'bg-primary-50 text-primary-700' : 'bg-gray-200/70 text-gray-600'">
                                <?= $totalInformes ?>
                            </span>
                        </button>

                        <button
                            @click="activeTab = 'appointments'"
                            :class="activeTab === 'appointments' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300 font-medium'"
                            class="whitespace-nowrap py-3.5 px-1 border-b-2 text-sm transition-all flex items-center gap-2">
                            <i class="bi bi-calendar3 text-base"></i>
                            <span>Citas</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                                :class="activeTab === 'appointments' ? 'bg-primary-50 text-primary-700' : 'bg-gray-200/70 text-gray-600'">
                                <?= $totalCitas ?>
                            </span>
                        </button>

                        <button
                            @click="activeTab = 'documents'"
                            :class="activeTab === 'documents' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300 font-medium'"
                            class="whitespace-nowrap py-3.5 px-1 border-b-2 text-sm transition-all flex items-center gap-2">
                            <i class="bi bi-file-earmark-text text-base"></i>
                            <span>Documentos</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                                :class="activeTab === 'documents' ? 'bg-primary-50 text-primary-700' : 'bg-gray-200/70 text-gray-600'">
                                <?= $totalDocumentos ?>
                            </span>
                        </button>

                        <button
                            @click="activeTab = 'invoices'"
                            :class="activeTab === 'invoices' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300 font-medium'"
                            class="whitespace-nowrap py-3.5 px-1 border-b-2 text-sm transition-all flex items-center gap-2">
                            <i class="bi bi-receipt text-base"></i>
                            <span>Facturas</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                                :class="activeTab === 'invoices' ? 'bg-primary-50 text-primary-700' : 'bg-gray-200/70 text-gray-600'">
                                <?= $totalFacturas ?>
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content Body -->
                <div class="p-6 flex-1">

                    <!-- TAB 1: Historial Médico -->
                    <div x-show="activeTab === 'history'" x-cloak>
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Evolutivos e Informes Clínicos</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Historial cronológico de consultas, diagnósticos y tratamientos.</p>
                            </div>
                            <?php if ($rol !== "Paciente") : ?>
                                <a href="<?= PROJECT_ROOT ?>/historial/crear?paciente_id=<?= $usuario['usuario_id'] ?>"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-primary-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-500 transition-all">
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Añadir Informe</span>
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if (empty($informes)) : ?>
                            <div class="flex flex-col items-center justify-center py-16 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                <div class="w-14 h-14 bg-white rounded-2xl border border-gray-200 flex items-center justify-center mb-3 text-gray-400 text-2xl shadow-sm">
                                    <i class="bi bi-journal-x"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900">Sin historial médico registrado</h4>
                                <p class="text-xs text-gray-500 max-w-sm mt-1 mb-4 font-light">Este paciente aún no tiene informes clínicos o notas de evolución registradas.</p>
                                <?php if ($rol !== "Paciente") : ?>
                                    <a href="<?= PROJECT_ROOT ?>/historial/crear?paciente_id=<?= $usuario['usuario_id'] ?>"
                                        class="inline-flex items-center gap-2 rounded-full bg-primary-600 text-white px-4 py-2 text-xs font-semibold hover:bg-primary-500 transition-colors">
                                        <i class="bi bi-plus-lg"></i>
                                        <span>Redactar primera consulta</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="space-y-4">
                                <?php foreach ($informes as $informe) :
                                    $fechaInforme = date('d/m/Y', strtotime($informe['fecha_consulta']));
                                    $horaInforme = date('H:i', strtotime($informe['fecha_consulta']));
                                ?>
                                    <div class="p-5 rounded-2xl border border-gray-200 hover:border-primary-200 hover:bg-slate-50/50 transition-all group">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="space-y-2 flex-1">
                                                <div class="flex items-center gap-2.5">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-50 text-primary-700 border border-primary-100">
                                                        <i class="bi bi-calendar2-event text-[11px]"></i>
                                                        <?= $fechaInforme ?> a las <?= $horaInforme ?>
                                                    </span>
                                                    <?php if (!empty($informe['creado_por'])): ?>
                                                        <span class="text-[11px] text-gray-400">
                                                            Atendido por: <?= htmlspecialchars($informe['creado_por']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if (!empty($informe['motivo_consulta'])): ?>
                                                    <div>
                                                        <span class="text-xs font-bold text-gray-900">Motivo:</span>
                                                        <span class="text-xs text-gray-700"><?= htmlspecialchars($informe['motivo_consulta']) ?></span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!empty($informe['diagnostico'])): ?>
                                                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs">
                                                        <span class="font-bold text-gray-900 block mb-0.5">Diagnóstico / Evaluación:</span>
                                                        <p class="text-gray-600 line-clamp-2"><?= htmlspecialchars($informe['diagnostico']) ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <a href="<?= PROJECT_ROOT ?>/historial/detalle?id=<?= $informe['historial_id'] ?>"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-primary-600 bg-primary-50 hover:bg-primary-100 transition-colors shrink-0">
                                                <span>Ver informe</span>
                                                <i class="bi bi-chevron-right text-[10px]"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- TAB 2: Agenda de Citas -->
                    <div x-show="activeTab === 'appointments'" x-cloak>
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Citas Programadas y Pasadas</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Listado de sesiones de tratamiento y revisiones del paciente.</p>
                            </div>
                            <?php if ($rol !== "Paciente") : ?>
                                <a href="<?= PROJECT_ROOT ?>/citas/crear?paciente_id=<?= $usuario['usuario_id'] ?>"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-primary-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-500 transition-all">
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Nueva Cita</span>
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if (empty($citas)) : ?>
                            <div class="flex flex-col items-center justify-center py-16 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                <div class="w-14 h-14 bg-white rounded-2xl border border-gray-200 flex items-center justify-center mb-3 text-gray-400 text-2xl shadow-sm">
                                    <i class="bi bi-calendar-x"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900">No hay citas registradas</h4>
                                <p class="text-xs text-gray-500 max-w-sm mt-1 mb-4 font-light">Este paciente no tiene citas agendadas actualmente.</p>
                                <?php if ($rol !== "Paciente") : ?>
                                    <a href="<?= PROJECT_ROOT ?>/citas/crear?paciente_id=<?= $usuario['usuario_id'] ?>"
                                        class="inline-flex items-center gap-2 rounded-full bg-primary-600 text-white px-4 py-2 text-xs font-semibold hover:bg-primary-500 transition-colors">
                                        <i class="bi bi-calendar-plus"></i>
                                        <span>Programar primera cita</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="overflow-x-auto rounded-2xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Motivo / Servicio</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        <?php foreach ($citas as $cita) :
                                            $estadoCita = $cita['estado'] ?? 'Programada';
                                            $citaStatusClass = 'bg-blue-50 text-blue-700 border-blue-100';
                                            if ($estadoCita === 'Realizada' || $estadoCita === 'Completada') {
                                                $citaStatusClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                            } elseif ($estadoCita === 'Cancelada') {
                                                $citaStatusClass = 'bg-rose-50 text-rose-700 border-rose-100';
                                            } elseif ($estadoCita === 'Pendiente') {
                                                $citaStatusClass = 'bg-amber-50 text-amber-700 border-amber-100';
                                            }
                                        ?>
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3.5 text-xs font-bold text-gray-900 whitespace-nowrap">
                                                    <i class="bi bi-clock mr-1.5 text-gray-400"></i>
                                                    <?= date('d/m/Y H:i', strtotime($cita['fecha_hora'])) ?>
                                                </td>
                                                <td class="px-4 py-3.5 text-xs text-gray-700">
                                                    <?= htmlspecialchars($cita['motivo_consulta'] ?? ($cita['descripcion'] ?? 'Consulta de Fisioterapia')) ?>
                                                </td>
                                                <td class="px-4 py-3.5 text-xs whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border <?= $citaStatusClass ?>">
                                                        <?= htmlspecialchars($estadoCita) ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3.5 text-right text-xs whitespace-nowrap">
                                                    <a href="<?= PROJECT_ROOT ?>/citas?id=<?= $cita['cita_id'] ?>"
                                                        class="p-1.5 rounded-lg text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-colors"
                                                        title="Ver / Editar Cita">
                                                        <i class="bi bi-pencil-square text-sm"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- TAB 3: Documentos -->
                    <div x-show="activeTab === 'documents'" x-cloak>
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Documentos y Consentimientos Informados</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Plantillas de la clínica, estado de firma del paciente y emisión de documentos.</p>
                            </div>
                        </div>

                        <?php if (empty($documentos)) : ?>
                            <div class="flex flex-col items-center justify-center py-16 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                <div class="w-14 h-14 bg-white rounded-2xl border border-gray-200 flex items-center justify-center mb-3 text-gray-400 text-2xl shadow-sm">
                                    <i class="bi bi-file-earmark-x"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900">No hay documentos registrados</h4>
                                <p class="text-xs text-gray-500 mt-1 max-w-xs">No se han encontrado plantillas de documentos configuradas en la clínica.</p>
                                <?php if ($rol !== "Paciente") : ?>
                                    <a href="<?= PROJECT_ROOT ?>/documentos/crear"
                                        class="mt-4 inline-flex items-center gap-2 rounded-full bg-primary-600 text-white px-4 py-2 text-xs font-semibold hover:bg-primary-500 transition-colors">
                                        <i class="bi bi-plus-lg"></i>
                                        <span>Crear primera plantilla</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="overflow-x-auto rounded-2xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Documento</th>
                                            <!-- <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Descripción</th> -->
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado de Firma</th>
                                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        <?php foreach ($documentos as $doc) :
                                            $isFirmado = !empty($doc['firmado']);
                                        ?>
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3.5 text-xs font-bold text-gray-900 whitespace-nowrap">
                                                    <div class="flex items-center gap-2.5">
                                                        <div class="w-8 h-8 rounded-xl <?= $isFirmado ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' ?> flex items-center justify-center font-bold shrink-0 text-sm">
                                                            <i class="bi <?= $isFirmado ? 'bi-file-earmark-check-fill' : 'bi-file-earmark-text' ?>"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-bold text-gray-900 block"><?= htmlspecialchars($doc['titulo']) ?></span>
                                                            <!-- <span class="text-[10px] text-gray-400 font-normal">ID Doc: #<?= $doc['documento_id'] ?></span> -->
                                                        </div>
                                                    </div>
                                                </td>
                                                <!-- <td class="px-4 py-3.5 text-xs text-gray-600 max-w-xs truncate">
                                                    <?= htmlspecialchars($doc['descripcion'] ?? 'Sin descripción') ?>
                                                </td> -->
                                                <td class="px-4 py-3.5 text-xs whitespace-nowrap">
                                                    <?php if ($isFirmado) : ?>
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border bg-emerald-50 text-emerald-700 border-emerald-100">
                                                            <i class="bi bi-check-circle-fill text-emerald-500 text-[9px]"></i>
                                                            Firmado <?= !empty($doc['fecha_firma']) ? '(' . date('d/m/Y', strtotime($doc['fecha_firma'])) . ')' : '' ?>
                                                        </span>
                                                    <?php else : ?>
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border bg-amber-50 text-amber-700 border-amber-100">
                                                            <i class="bi bi-clock-history text-amber-500 text-[9px]"></i>
                                                            Pendiente de firma
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3.5 text-right text-xs whitespace-nowrap">
                                                    <div class="flex items-center justify-end gap-1.5">
                                                        <a href="<?= PROJECT_ROOT ?>/pacientes/documentos/firmar?paciente_id=<?= $usuario['usuario_id'] ?>&documento_id=<?= $doc['documento_id'] ?>"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold text-xs shadow-sm transition-all hover:scale-105 active:scale-95"
                                                            title="<?= $isFirmado ? 'Editar o Refirmar Documento' : 'Rellenar y Firmar Documento' ?>">
                                                            <i class="bi <?= $isFirmado ? 'bi-pencil-square' : 'bi-pen' ?>"></i>
                                                            <span><?= $isFirmado ? 'Editar / Refirmar' : 'Rellenar y Firmar' ?></span>
                                                        </a>
                                                        <?php if ($isFirmado) : ?>
                                                            <a href="<?= PROJECT_ROOT ?>/pacientes/documentos/ver?paciente_id=<?= $usuario['usuario_id'] ?>&documento_id=<?= $doc['documento_id'] ?>"
                                                                class="p-1.5 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors"
                                                                title="Ver documento firmado">
                                                                <i class="bi bi-eye text-sm"></i>
                                                            </a>
                                                            <a href="<?= PROJECT_ROOT ?>/pacientes/documentos/pdf?paciente_id=<?= $usuario['usuario_id'] ?>&documento_id=<?= $doc['documento_id'] ?>"
                                                                class="p-1.5 rounded-lg text-gray-500 hover:text-primary-600 hover:bg-primary-50 transition-colors"
                                                                title="Descargar PDF">
                                                                <i class="bi bi-file-earmark-pdf text-sm"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- TAB 4: Facturas -->
                    <div x-show="activeTab === 'invoices'" x-cloak>
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Facturas Emitidas</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Historial de facturación, estado de pago y registro fiscal Verifactu.</p>
                            </div>
                            <?php if ($rol !== "Paciente") : ?>
                                <a href="<?= PROJECT_ROOT ?>/facturas/crear?paciente_id=<?= $usuario['usuario_id'] ?>"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-primary-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-500 transition-all hover:scale-105 active:scale-95">
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Emitir Factura</span>
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if (empty($facturas)) : ?>
                            <div class="flex flex-col items-center justify-center py-16 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                <div class="w-14 h-14 bg-white rounded-2xl border border-gray-200 flex items-center justify-center mb-3 text-gray-400 text-2xl shadow-sm">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900">No hay facturas registradas</h4>
                                <p class="text-xs text-gray-500 mt-1 max-w-xs">Aún no se ha emitido ninguna factura para este paciente.</p>
                                <?php if ($rol !== "Paciente") : ?>
                                    <a href="<?= PROJECT_ROOT ?>/facturas/crear?paciente_id=<?= $usuario['usuario_id'] ?>"
                                        class="mt-4 inline-flex items-center gap-2 rounded-full bg-primary-600 text-white px-4 py-2 text-xs font-semibold hover:bg-primary-500 transition-colors">
                                        <i class="bi bi-plus-lg"></i>
                                        <span>Emitir primera factura</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="overflow-x-auto rounded-2xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nº Factura</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado Pago</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Verifactu</th>
                                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        <?php foreach ($facturas as $factura) : ?>
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3.5 text-xs font-bold text-gray-900 font-mono whitespace-nowrap">
                                                    <?= htmlspecialchars($factura['serie'] ?? 'A') ?>-<?= str_pad($factura['numero'], 5, '0', STR_PAD_LEFT) ?>
                                                </td>
                                                <td class="px-4 py-3.5 text-xs text-gray-600 whitespace-nowrap">
                                                    <?= date('d/m/Y', strtotime($factura['fecha_emision'])) ?>
                                                </td>
                                                <td class="px-4 py-3.5 text-xs font-bold text-gray-900 font-mono whitespace-nowrap">
                                                    <?= number_format($factura['total'], 2, ',', '.') ?> €
                                                </td>
                                                <td class="px-4 py-3.5 text-xs whitespace-nowrap">
                                                    <?php if ($factura['estado'] === 'Pagada') : ?>
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Pagada
                                                        </span>
                                                    <?php else : ?>
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pendiente
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3.5 text-xs whitespace-nowrap">
                                                    <?php
                                                    $vfState = $factura['estado_verifactu'] ?? 'Pendiente';
                                                    if ($vfState === 'Aceptado' || $vfState === 'AceptadoConErrores') : ?>
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200" title="CSV: <?= htmlspecialchars($factura['csv_verifactu'] ?? 'Generado') ?>">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Remitida
                                                        </span>
                                                    <?php elseif ($vfState === 'Rechazado') : ?>
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200" title="<?= htmlspecialchars($factura['mensaje_verifactu'] ?? 'Error AEAT') ?>">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Rechazada
                                                        </span>
                                                    <?php else : ?>
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Pendiente AEAT
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3.5 text-right text-xs whitespace-nowrap">
                                                    <div class="flex items-center justify-end gap-1.5">
                                                        <a href="<?= PROJECT_ROOT ?>/facturas/pdf?id=<?= $factura['factura_id'] ?>" target="_blank" class="p-1.5 rounded-lg text-gray-500 hover:text-primary-600 hover:bg-primary-50 transition-colors" title="Descargar PDF">
                                                            <i class="bi bi-file-earmark-pdf text-sm"></i>
                                                        </a>
                                                        <?php if ($rol !== "Paciente") : ?>
                                                            <a href="<?= PROJECT_ROOT ?>/facturas/editar?id=<?= $factura['factura_id'] ?>" class="p-1.5 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Editar Factura">
                                                                <i class="bi bi-pencil-square text-sm"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>