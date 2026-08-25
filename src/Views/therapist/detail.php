<?php
$t = $therapist ?? [];
$pageTitle = "Datos del Facultativo — " . ($t['nombre'] . ' ' . $t['apellidos']);
include TEMPLATE_DIR . 'header.php';

$avatarId = (intval(preg_replace('/[^0-9]/', '', $t['usuario_id'])) % 70) + 1;
$avatarUrl = (isset($t['genero']) && $t['genero'] === 'Mujer')
    ? "https://randomuser.me/api/portraits/women/{$avatarId}.jpg"
    : "https://randomuser.me/api/portraits/men/{$avatarId}.jpg";

$totalNominas = count($nominas ?? []);
$totalCitas = count($citas ?? []);
$totalHorarios = count($horarios ?? []);
$totalAusencias = count($ausencias ?? []);
?>

<div class="space-y-6 animate-fade-in" x-data="{ activeTab: 'nominas' }">

    <!-- Top Navigation Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/terapeutas" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Volver a Facultativos</span>
            </a>
            <div class="h-4 w-px bg-gray-300 hidden sm:block"></div>
            <span class="text-xs font-medium text-gray-400 hidden sm:inline-block">Ficha Profesional</span>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/terapeutas/editar?id=<?= $t['usuario_id'] ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-600 text-white text-xs font-semibold hover:bg-primary-500 transition-all shadow-sm">
                <i class="bi bi-pencil-square"></i>
                <span>Editar Perfil</span>
            </a>
        </div>
    </div>

    <!-- Professional Profile Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-200 shadow-sm relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">

            <!-- Specialist Identity -->
            <div class="flex items-center gap-4">
                <img src="<?= $avatarUrl ?>" alt="Avatar" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-white shadow-md shrink-0">
                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                            <?= htmlspecialchars($t['nombre'] . ' ' . $t['apellidos']) ?>
                        </h1>
                        <?php if (($t['rol'] ?? '') === 'Administrador'): ?>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200">
                                Administrador
                            </span>
                        <?php elseif (($t['rol'] ?? '') === 'Fisioterapeuta'): ?>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Fisioterapeuta
                            </span>
                        <?php else: ?>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                <?= htmlspecialchars($t['rol'] ?? '') ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 font-medium">
                        <span><strong class="text-gray-700">DNI:</strong> #<?= htmlspecialchars($t['usuario_id']) ?></span>
                        <span>•</span>
                        <span><strong class="text-gray-700">Email:</strong> <?= htmlspecialchars($t['email']) ?></span>
                        <span>•</span>
                        <span><strong class="text-gray-700">Teléfono:</strong> <?= htmlspecialchars($t['telefono'] ?: 'N/D') ?></span>
                    </div>
                </div>
            </div>

            <!-- Quick Stat Counters -->
            <div class="flex items-center gap-4 sm:gap-6 pt-4 md:pt-0 border-t md:border-t-0 border-gray-100">
                <div class="text-left md:text-right">
                    <span class="block text-[11px] uppercase tracking-wider font-bold text-gray-400">Citas Asignadas</span>
                    <span class="text-2xl font-light text-gray-900"><?= $totalCitas ?></span>
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <div class="text-left md:text-right">
                    <span class="block text-[11px] uppercase tracking-wider font-bold text-gray-400">Nóminas Emitidas</span>
                    <span class="text-2xl font-light text-gray-900"><?= $totalNominas ?></span>
                </div>
            </div>

        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex gap-6 overflow-x-auto" aria-label="Tabs">
            <button @click="activeTab = 'nominas'" :class="{ 'border-primary-600 text-primary-600 font-bold': activeTab === 'nominas', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium': activeTab !== 'nominas' }" class="py-3 px-1 border-b-2 text-xs transition-colors flex items-center gap-2 cursor-pointer whitespace-nowrap">
                <i class="bi bi-file-earmark-spreadsheet text-base"></i>
                <span>Nóminas (<?= $totalNominas ?>)</span>
            </button>
            <button @click="activeTab = 'horarios'" :class="{ 'border-primary-600 text-primary-600 font-bold': activeTab === 'horarios', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium': activeTab !== 'horarios' }" class="py-3 px-1 border-b-2 text-xs transition-colors flex items-center gap-2 cursor-pointer whitespace-nowrap">
                <i class="bi bi-clock text-base"></i>
                <span>Horarios (<?= $totalHorarios ?>)</span>
            </button>
            <button @click="activeTab = 'ausencias'" :class="{ 'border-primary-600 text-primary-600 font-bold': activeTab === 'ausencias', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium': activeTab !== 'ausencias' }" class="py-3 px-1 border-b-2 text-xs transition-colors flex items-center gap-2 cursor-pointer whitespace-nowrap">
                <i class="bi bi-calendar-x text-base"></i>
                <span>Ausencias (<?= $totalAusencias ?>)</span>
            </button>
            <button @click="activeTab = 'contrato'" :class="{ 'border-primary-600 text-primary-600 font-bold': activeTab === 'contrato', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium': activeTab !== 'contrato' }" class="py-3 px-1 border-b-2 text-xs transition-colors flex items-center gap-2 cursor-pointer whitespace-nowrap">
                <i class="bi bi-file-earmark-text text-base"></i>
                <span>Datos Laborales</span>
            </button>
            <!-- <button @click="activeTab = 'citas'" :class="{ 'border-primary-600 text-primary-600 font-bold': activeTab === 'citas', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium': activeTab !== 'citas' }" class="py-3 px-1 border-b-2 text-xs transition-colors flex items-center gap-2 cursor-pointer whitespace-nowrap">
                <i class="bi bi-calendar-event text-base"></i>
                <span>Citas del Especialista (<?= $totalCitas ?>)</span>
            </button> -->
        </nav>
    </div>

    <!-- Tab Content: Nóminas -->
    <div x-show="activeTab === 'nominas'" class="space-y-6">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Histórico de Nóminas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Recibos de salario procesados para este empleado.</p>
                </div>
                <a href="<?= PROJECT_ROOT ?>/nominas/generar?usuario_id=<?= $t['usuario_id'] ?>" class="px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl text-xs font-semibold transition-colors">
                    <i class="bi bi-plus-lg mr-1"></i> Generar Nueva Nómina
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                            <th class="py-3 px-4 font-semibold">Periodo</th>
                            <th class="py-3 px-4 font-semibold">Salario Bruto</th>
                            <th class="py-3 px-4 font-semibold">Desc. SS</th>
                            <th class="py-3 px-4 font-semibold">Desc. IRPF</th>
                            <th class="py-3 px-4 font-semibold">Líquido a Percibir</th>
                            <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!empty($nominas)): ?>
                            <?php
                            $mesesNombres = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
                            foreach ($nominas as $nom):
                            ?>
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-4 font-bold text-gray-900">
                                        <?= ($mesesNombres[(int)$nom['mes']] ?? $nom['mes']) . ' ' . $nom['anio'] ?>
                                    </td>
                                    <td class="py-4 px-4 font-medium text-gray-700">
                                        <?= number_format($nom['bruto'] ?? $nom['devengos_total_bruto'] ?? 0, 2) ?> €
                                    </td>
                                    <td class="py-4 px-4 text-rose-600 font-medium">
                                        -<?= number_format($nom['deduccion_ss'] ?? $nom['deduccion_seguridad_social_trabajador'] ?? 0, 2) ?> €
                                    </td>
                                    <td class="py-4 px-4 text-rose-600 font-medium">
                                        -<?= number_format($nom['deduccion_irpf'] ?? 0, 2) ?> €
                                    </td>
                                    <td class="py-4 px-4 font-bold text-emerald-600 text-sm">
                                        <?= number_format($nom['liquido_percepcion'] ?? $nom['liquido_a_percibir'] ?? 0, 2) ?> €
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <a href="<?= PROJECT_ROOT ?>/nominas/detalle?id=<?= $nom['nomina_id'] ?>" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all" title="Ver Detalle">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>
                                        <a href="<?= PROJECT_ROOT ?>/nominas/pdf?id=<?= $nom['nomina_id'] ?>" target="_blank" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Descargar PDF">
                                            <i class="bi bi-file-earmark-pdf text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-400 italic">
                                    <i class="bi bi-receipt text-3xl block mb-2 text-gray-300"></i>
                                    No hay nóminas registradas para este facultativo.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Content: Horarios de Atención -->
    <div x-show="activeTab === 'horarios'" class="space-y-6">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Horarios de Atención</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Franjas horarias y días de consulta configurados para este facultativo.</p>
                </div>
                <a href="<?= PROJECT_ROOT ?>/configuracion/horarios/crear" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                    <i class="bi bi-plus-circle"></i>
                    <span>Nuevo Horario</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                            <th class="py-3 px-4 font-semibold">Día de la semana</th>
                            <th class="py-3 px-4 font-semibold">Hora Inicio</th>
                            <th class="py-3 px-4 font-semibold">Hora Fin</th>
                            <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!empty($horarios)): ?>
                            <?php foreach ($horarios as $h): ?>
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-4 font-bold text-gray-900">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-primary-50 text-primary-700 border border-primary-100">
                                            <i class="bi bi-calendar3 text-xs"></i>
                                            <?= htmlspecialchars($h['dia_semana']) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-medium text-gray-700">
                                        <?= date('H:i', strtotime($h['hora_inicio'])) ?>
                                    </td>
                                    <td class="py-4 px-4 font-medium text-gray-700">
                                        <?= date('H:i', strtotime($h['hora_fin'])) ?>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <a href="<?= PROJECT_ROOT ?>/configuracion/horarios/editar?id=<?= $h['horario_id'] ?>" class="text-gray-400 hover:text-amber-500 hover:bg-amber-50 p-1.5 rounded-lg transition-all inline-block" title="Editar">
                                            <i class="bi bi-pencil text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-400 italic">
                                    <i class="bi bi-clock-history text-3xl block mb-2 text-gray-300"></i>
                                    No hay horarios registrados para este facultativo.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Content: Ausencias -->
    <div x-show="activeTab === 'ausencias'" class="space-y-6">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Ausencias y Vacaciones</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Periodos de inactividad, bajas médicas o vacaciones de este facultativo.</p>
                </div>
                <a href="<?= PROJECT_ROOT ?>/configuracion/ausencias/crear" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                    <i class="bi bi-plus-circle"></i>
                    <span>Registrar Ausencia</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                            <th class="py-3 px-4 font-semibold">Fecha Inicio</th>
                            <th class="py-3 px-4 font-semibold">Fecha Fin</th>
                            <th class="py-3 px-4 font-semibold">Motivo</th>
                            <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!empty($ausencias)): ?>
                            <?php foreach ($ausencias as $a): ?>
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-4 font-semibold text-gray-900">
                                        <?= date('d/m/Y', strtotime($a['fecha_inicio'])) ?>
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-gray-900">
                                        <?= date('d/m/Y', strtotime($a['fecha_fin'])) ?>
                                    </td>
                                    <td class="py-4 px-4 text-gray-600 font-medium">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                            <?= htmlspecialchars($a['motivo'] ?: 'Sin especificar') ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <a href="<?= PROJECT_ROOT ?>/configuracion/ausencias/editar?id=<?= $a['ausencia_id'] ?>" class="text-gray-400 hover:text-amber-500 hover:bg-amber-50 p-1.5 rounded-lg transition-all inline-block" title="Editar">
                                            <i class="bi bi-pencil text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-400 italic">
                                    <i class="bi bi-calendar-x text-3xl block mb-2 text-gray-300"></i>
                                    No hay ausencias registradas para este facultativo.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Content: Contrato & Datos Laborales -->
    <div x-show="activeTab === 'contrato'" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Ficha Laboral -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <i class="bi bi-shield-check text-primary-600"></i>
                    Afiliación y Cuenta Bancaria
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-gray-50">
                        <span class="text-gray-500">NSS (Seguridad Social):</span>
                        <span class="font-bold text-gray-900"><?= htmlspecialchars($t['nss'] ?: 'No registrado') ?></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-gray-50">
                        <span class="text-gray-500">IBAN para Pago:</span>
                        <span class="font-bold text-gray-900"><?= htmlspecialchars($t['iban'] ?: 'No registrado') ?></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-gray-50">
                        <span class="text-gray-500">Grupo de Cotización:</span>
                        <span class="font-bold text-gray-900">Grupo <?= htmlspecialchars($t['grupo_cotizacion'] ?? 1) ?></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-gray-50">
                        <span class="text-gray-500">Fecha Nacimiento:</span>
                        <span class="font-bold text-gray-900"><?= !empty($t['fecha_nacimiento']) ? date('d/m/Y', strtotime($t['fecha_nacimiento'])) : 'No especificada' ?></span>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-gray-500">Dirección Completa:</span>
                        <span class="font-bold text-gray-900 text-right"><?= htmlspecialchars(trim(($t['direccion'] ?? '') . ' ' . ($t['municipio'] ?? '') . ' ' . ($t['cp'] ?? ''))) ?: 'No registrada' ?></span>
                    </div>
                </div>
            </div>

            <!-- Datos del Contrato Vigente -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-file-earmark-text text-primary-600"></i>
                        Contrato Vigente
                    </h3>
                    <?php if (!empty($contrato)): ?>
                        <a href="<?= PROJECT_ROOT ?>/nominas/contratos/editar?id=<?= $contrato['contrato_id'] ?>" class="text-xs font-semibold text-primary-600 hover:text-primary-700">
                            Editar Contrato
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($contrato)): ?>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500">Tipo de Contrato:</span>
                            <span class="font-bold text-gray-900"><?= htmlspecialchars($contrato['tipo_contrato']) ?></span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500">Salario Base Mensual:</span>
                            <span class="font-bold text-gray-900"><?= number_format($contrato['salario_base_mensual'], 2) ?> €</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500">Complementos Mensuales:</span>
                            <span class="font-bold text-gray-900"><?= number_format($contrato['complementos_mensuales'], 2) ?> €</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500">Retención IRPF:</span>
                            <span class="font-bold text-emerald-600"><?= number_format($contrato['irpf_porcentaje'], 2) ?> %</span>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <span class="text-gray-500">Fecha de Inicio:</span>
                            <span class="font-bold text-gray-900"><?= date('d/m/Y', strtotime($contrato['fecha_inicio'])) ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="py-8 text-center text-gray-400 italic">
                        <i class="bi bi-file-earmark-x text-3xl block mb-2 text-gray-300"></i>
                        No hay ningún contrato activo registrado.
                        <div class="mt-3">
                            <a href="<?= PROJECT_ROOT ?>/nominas/contratos/crear?usuario_id=<?= $t['usuario_id'] ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-600 text-white font-semibold rounded-xl text-xs shadow-sm hover:bg-primary-700 transition-colors">
                                <i class="bi bi-plus-lg"></i> Registrar Contrato
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Tab Content: Citas del Especialista -->
    <!-- <div x-show="activeTab === 'citas'" class="space-y-6">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-900">Agenda & Citas del Especialista</h3>
                <p class="text-xs text-gray-500 mt-0.5">Histórico de sesiones asignadas a este facultativo.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                            <th class="py-3 px-4 font-semibold">Fecha y Hora</th>
                            <th class="py-3 px-4 font-semibold">Paciente</th>
                            <th class="py-3 px-4 font-semibold">Tipo Cita</th>
                            <th class="py-3 px-4 font-semibold">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!empty($citas)): ?>
                            <?php foreach ($citas as $c): ?>
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-4 font-semibold text-gray-900">
                                        <?= date('d/m/Y H:i', strtotime($c['fecha_hora'])) ?>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-gray-900">
                                        <?= htmlspecialchars(trim(($c['paciente_nombre'] ?? '') . ' ' . ($c['paciente_apellidos'] ?? ''))) ?>
                                    </td>
                                    <td class="py-4 px-4 font-medium text-xs">
                                        <?php if (!empty($c['tipo_cita_nombre'])): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 border border-gray-200">
                                                <span class="w-2 h-2 rounded-full" style="background-color: <?= htmlspecialchars($c['tipo_cita_color'] ?? '#3b82f6') ?>"></span>
                                                <?= htmlspecialchars($c['tipo_cita_nombre']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic">Estándar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-4">
                                        <?php
                                        $est = $c['estado'];
                                        if ($est == 'Realizada'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Realizada
                                            </span>
                                        <?php elseif ($est == 'Cancelada'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Cancelada
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Programada
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-400 italic">
                                    <i class="bi bi-calendar-x text-3xl block mb-2 text-gray-300"></i>
                                    No hay citas registradas para este facultativo.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->

</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>