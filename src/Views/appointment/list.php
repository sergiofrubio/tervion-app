<?php
$pageTitle = "Citas";
$isAdminOrSecretary = isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Administrador' || $_SESSION['rol'] === 'Secretario');
include TEMPLATE_DIR . 'header.php';

// Filtros
$filtro_busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$filtro_estado = isset($_GET['estado']) ? trim($_GET['estado']) : '';
$filtro_fisioterapeuta = isset($_GET['fisioterapeuta_id']) ? trim($_GET['fisioterapeuta_id']) : '';
$filtro_fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';

// Extraer fisioterapeutas únicos si no vinieran definidos
if (empty($fisioterapeutas) && !empty($appointments)) {
    $fisioterapeutas = [];
    $seen = [];
    foreach ($appointments as $cita) {
        if (!empty($cita['fisioterapeuta_id']) && !isset($seen[$cita['fisioterapeuta_id']])) {
            $seen[$cita['fisioterapeuta_id']] = true;
            $fisioterapeutas[] = [
                'usuario_id' => $cita['fisioterapeuta_id'],
                'nombre' => $cita['fisioterapeuta_nombre'] ?? '',
                'apellidos' => $cita['fisioterapeuta_apellidos'] ?? ''
            ];
        }
    }
}

// Filtrar las citas
$citas_filtradas = [];
if (!empty($appointments)) {
    foreach ($appointments as $cita) {
        $match = true;

        // Búsqueda por texto (paciente, fisioterapeuta, o ID)
        if ($filtro_busqueda !== '') {
            $busquedaLower = strtolower($filtro_busqueda);
            $pacienteNombre = strtolower(($cita['paciente_nombre'] ?? '') . ' ' . ($cita['paciente_apellidos'] ?? ''));
            $fisioNombre = strtolower(($cita['fisioterapeuta_nombre'] ?? '') . ' ' . ($cita['fisioterapeuta_apellidos'] ?? ''));
            $tipoNombre = strtolower($cita['tipo_cita_nombre'] ?? '');
            $citaId = (string)($cita['cita_id'] ?? '');

            if (
                strpos($pacienteNombre, $busquedaLower) === false &&
                strpos($fisioNombre, $busquedaLower) === false &&
                strpos($tipoNombre, $busquedaLower) === false &&
                strpos($citaId, $busquedaLower) === false
            ) {
                $match = false;
            }
        }

        // Filtro por Estado
        if ($filtro_estado !== '' && isset($cita['estado']) && $cita['estado'] !== $filtro_estado) {
            $match = false;
        }

        // Filtro por Fisioterapeuta
        if ($filtro_fisioterapeuta !== '' && isset($cita['fisioterapeuta_id']) && (string)$cita['fisioterapeuta_id'] !== (string)$filtro_fisioterapeuta) {
            $match = false;
        }

        // Filtro por Fecha (YYYY-MM-DD)
        if ($filtro_fecha !== '' && isset($cita['fecha_hora'])) {
            $fechaCita = substr($cita['fecha_hora'], 0, 10);
            if ($fechaCita !== $filtro_fecha) {
                $match = false;
            }
        }

        if ($match) {
            $citas_filtradas[] = $cita;
        }
    }
}

// Paginación (10 citas por página, igual que pacientes)
$articulos_x_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$total_citas = count($citas_filtradas);
$n_botones_paginacion = ceil($total_citas / $articulos_x_pagina);

if ($pagina > $n_botones_paginacion && $n_botones_paginacion > 0) {
    $pagina = 1;
}

$iniciar = ($pagina - 1) * $articulos_x_pagina;
$citasPaginadas = array_slice($citas_filtradas, $iniciar, $articulos_x_pagina);

// Helper para mantener parámetros de consulta en paginación
function getPaginationQuery($page, $busqueda, $estado, $fisioterapeuta, $fecha) {
    $params = ['pagina' => $page];
    if ($busqueda !== '') $params['busqueda'] = $busqueda;
    if ($estado !== '') $params['estado'] = $estado;
    if ($fisioterapeuta !== '') $params['fisioterapeuta_id'] = $fisioterapeuta;
    if ($fecha !== '') $params['fecha'] = $fecha;
    return '?' . http_build_query($params);
}

// Helper para colores de badges de estado
$estadoBadgeClasses = [
    'Programada' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20',
    'Confirmada' => 'bg-teal-50 text-teal-700 ring-1 ring-inset ring-teal-600/20',
    'Pendiente'  => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
    'Realizada'  => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20',
    'Cancelada'  => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20',
];
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Citas</h1>
            <p class="text-gray-500 text-sm mt-0.5">Gestiona las citas programadas de los pacientes y asignaciones de terapeutas.</p>
        </div>
        <div>
            <a href="<?= PROJECT_ROOT ?>/citas/crear" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-2xl font-semibold text-sm shadow-md transition-all cursor-pointer">
                <i class="bi bi-plus-lg"></i>
                <span>Asignar Cita</span>
            </a>
        </div>
    </div>

    <?php
    $alert = null;
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
    } elseif (isset($_GET['alert']) && isset($_GET['message'])) {
        $alert = [
            'type' => $_GET['alert'],
            'message' => $_GET['message']
        ];
    }

    if ($alert):
        $alert_type = $alert['type'];
        $alert_message = $alert['message'];
        $bg_color = $alert_type === 'danger' ? 'bg-rose-50 text-rose-800 border-rose-200' : ($alert_type === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-blue-50 text-blue-800 border-blue-200');
    ?>
        <div class="p-4 rounded-2xl border flex items-center justify-between text-sm font-medium <?= $bg_color ?>" role="alert" x-data="{ show: true }" x-show="show">
            <div class="flex items-center gap-2">
                <i class="bi <?= $alert_type === 'success' ? 'bi-check-circle-fill text-emerald-500' : ($alert_type === 'danger' ? 'bi-exclamation-triangle-fill text-rose-500' : 'bi-info-circle-fill text-blue-500') ?> text-lg"></i>
                <span><?= htmlspecialchars($alert_message) ?></span>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- Appointments Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Table Card Header & Filters -->
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <form method="get" action="<?= PROJECT_ROOT ?>/citas" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                    <!-- Search Input -->
                    <div class="relative flex-1 max-w-md">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="busqueda" id="busqueda" value="<?= htmlspecialchars($filtro_busqueda) ?>" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" placeholder="Buscar por paciente, terapeuta o tipo">
                    </div>

                    <!-- Fisioterapeuta Filter -->
                    <select name="fisioterapeuta_id" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer shadow-sm">
                        <option value="" <?= $filtro_fisioterapeuta === '' ? 'selected' : '' ?>>Todos los terapeutas</option>
                        <?php if (!empty($fisioterapeutas)): ?>
                            <?php foreach ($fisioterapeutas as $fisio): ?>
                                <option value="<?= htmlspecialchars($fisio['usuario_id']) ?>" <?= (string)$filtro_fisioterapeuta === (string)$fisio['usuario_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(($fisio['nombre'] ?? '') . ' ' . ($fisio['apellidos'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>

                    <!-- Estado Filter -->
                    <select name="estado" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer shadow-sm">
                        <option value="" <?= $filtro_estado === '' ? 'selected' : '' ?>>Todos los estados</option>
                        <option value="Programada" <?= $filtro_estado === 'Programada' ? 'selected' : '' ?>>Programada</option>
                        <option value="Confirmada" <?= $filtro_estado === 'Confirmada' ? 'selected' : '' ?>>Confirmada</option>
                        <option value="Pendiente" <?= $filtro_estado === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="Realizada" <?= $filtro_estado === 'Realizada' ? 'selected' : '' ?>>Realizada</option>
                        <option value="Cancelada" <?= $filtro_estado === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                    </select>

                    <!-- Date Filter -->
                    <input type="date" name="fecha" value="<?= htmlspecialchars($filtro_fecha) ?>" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer shadow-sm">

                    <button type="submit" class="hidden sm:inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition-colors">
                        Filtrar
                    </button>
                </div>

                <div class="flex items-center gap-3 self-end lg:self-center">
                    <?php if ($filtro_busqueda !== '' || $filtro_estado !== '' || $filtro_fisioterapeuta !== '' || $filtro_fecha !== ''): ?>
                        <a href="<?= PROJECT_ROOT ?>/citas" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs transition-colors shrink-0" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                    <div class="text-xs text-gray-500 font-medium whitespace-nowrap">
                        Total registrado: <span class="font-bold text-gray-900"><?= $total_citas ?></span> citas
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold">ID</th>
                        <th class="py-3 px-4 font-semibold">Fecha y Hora</th>
                        <th class="py-3 px-4 font-semibold">Paciente</th>
                        <th class="py-3 px-4 font-semibold">Fisioterapeuta</th>
                        <th class="py-3 px-4 font-semibold">Tipo / Servicio</th>
                        <th class="py-3 px-4 font-semibold">Estado</th>
                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($citasPaginadas)) : ?>
                        <?php foreach ($citasPaginadas as $cita) : 
                            $timestamp = strtotime($cita['fecha_hora']);
                            $fechaFormateada = date('d/m/Y', $timestamp);
                            $horaFormateada = date('H:i', $timestamp);
                            $estadoActual = $cita['estado'] ?? 'Programada';
                            $badgeClass = $estadoBadgeClasses[$estadoActual] ?? 'bg-gray-100 text-gray-700';
                            $nombrePaciente = trim(($cita['paciente_nombre'] ?? '') . ' ' . ($cita['paciente_apellidos'] ?? ''));
                            if ($nombrePaciente === '') $nombrePaciente = 'Paciente #' . ($cita['paciente_id'] ?? '');
                            $inicialPaciente = strtoupper(substr($cita['paciente_nombre'] ?? 'P', 0, 1));
                            $nombreFisio = trim(($cita['fisioterapeuta_nombre'] ?? '') . ' ' . ($cita['fisioterapeuta_apellidos'] ?? ''));
                            if ($nombreFisio === '') $nombreFisio = 'No asignado';
                        ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-4 font-bold text-gray-400">#<?= htmlspecialchars($cita['cita_id']) ?></td>
                                <td class="py-4 px-4 font-medium text-gray-900 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded-xl bg-blue-50 text-primary-600 flex items-center justify-center font-bold text-xs shrink-0">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-xs"><?= $fechaFormateada ?></div>
                                            <div class="text-[11px] text-gray-500 font-medium flex items-center gap-1">
                                                <i class="bi bi-clock text-[10px]"></i> <?= $horaFormateada ?> h
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="h-7 w-7 rounded-full bg-primary-50 flex items-center justify-center text-primary-600 font-bold text-xs shrink-0">
                                            <?= htmlspecialchars($inicialPaciente) ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($nombrePaciente) ?></div>
                                            <?php if (!empty($cita['paciente_telefono'])): ?>
                                                <div class="text-[11px] text-gray-400 flex items-center gap-1">
                                                    <i class="bi bi-telephone text-[10px]"></i> <?= htmlspecialchars($cita['paciente_telefono']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-700 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <i class="bi bi-person-badge text-gray-400"></i>
                                        <span><?= htmlspecialchars($nombreFisio) ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <?php if (!empty($cita['tipo_cita_nombre'])): ?>
                                        <span class="inline-flex items-center gap-1.5 font-medium text-gray-800">
                                            <?php if (!empty($cita['tipo_cita_color'])): ?>
                                                <span class="w-2.5 h-2.5 rounded-full inline-block shrink-0" style="background-color: <?= htmlspecialchars($cita['tipo_cita_color']) ?>;"></span>
                                            <?php endif; ?>
                                            <span><?= htmlspecialchars($cita['tipo_cita_nombre']) ?></span>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400 italic">Estándar</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <!-- Estado dropdown rápido con selector estético -->
                                    <form action="<?= PROJECT_ROOT ?>/citas/estado" method="POST" class="inline-block m-0">
                                        <input type="hidden" name="cita_id" value="<?= $cita['cita_id'] ?>">
                                        <select name="estado" onchange="this.form.submit()" class="text-[11px] font-bold rounded-full px-2.5 py-1 border-0 cursor-pointer shadow-xs focus:ring-2 focus:ring-primary-500/20 transition-all <?= $badgeClass ?>">
                                            <option value="Programada" <?= $estadoActual === 'Programada' ? 'selected' : '' ?>>Programada</option>
                                            <option value="Confirmada" <?= $estadoActual === 'Confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                            <option value="Pendiente" <?= $estadoActual === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                            <option value="Realizada" <?= $estadoActual === 'Realizada' ? 'selected' : '' ?>>Realizada</option>
                                            <option value="Cancelada" <?= $estadoActual === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1">
                                        <?php if (!empty($cita['paciente_id'])): ?>
                                            <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= $cita['paciente_id'] ?>" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Ver Expediente / Historial del Paciente">
                                                <i class="bi bi-person-lines-fill text-sm"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= PROJECT_ROOT ?>/citas/editar?id=<?= $cita['cita_id'] ?>" class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Editar Cita">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </a>
                                        <?php if ($isAdminOrSecretary): ?>
                                            <form action="<?= PROJECT_ROOT ?>/citas/eliminar" method="POST" class="inline-block m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                                                <input type="hidden" name="id" value="<?= $cita['cita_id'] ?>">
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all cursor-pointer" title="Eliminar Cita">
                                                    <i class="bi bi-trash3 text-sm"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                        <i class="bi bi-calendar-x text-xl"></i>
                                    </div>
                                    <h3 class="text-xs font-bold text-gray-900">No hay citas</h3>
                                    <p class="mt-0.5 text-xs text-gray-500">No se encontraron citas programadas con los filtros aplicados.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($n_botones_paginacion > 1): ?>
            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-medium">
                            Mostrando <span class="font-bold text-gray-900"><?= min($iniciar + 1, $total_citas) ?></span> a <span class="font-bold text-gray-900"><?= min($iniciar + $articulos_x_pagina, $total_citas) ?></span> de <span class="font-bold text-gray-900"><?= $total_citas ?></span> citas
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="<?= getPaginationQuery(max(1, $pagina - 1), $filtro_busqueda, $filtro_estado, $filtro_fisioterapeuta, $filtro_fecha) ?>" class="relative inline-flex items-center px-3 py-2 rounded-l-xl border border-gray-200 bg-white text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors <?= $pagina <= 1 ? 'pointer-events-none opacity-50' : '' ?>">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </a>
                            <?php for ($i = 0; $i < $n_botones_paginacion; $i++) : ?>
                                <a href="<?= getPaginationQuery($i + 1, $filtro_busqueda, $filtro_estado, $filtro_fisioterapeuta, $filtro_fecha) ?>" aria-current="<?= $pagina == $i + 1 ? 'page' : 'false' ?>" class="relative inline-flex items-center px-3 py-2 border text-xs font-semibold transition-colors <?= $pagina == $i + 1 ? 'z-10 bg-primary-50 border-primary-500 text-primary-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                                    <?= $i + 1 ?>
                                </a>
                            <?php endfor; ?>
                            <a href="<?= getPaginationQuery(min($n_botones_paginacion, $pagina + 1), $filtro_busqueda, $filtro_estado, $filtro_fisioterapeuta, $filtro_fecha) ?>" class="relative inline-flex items-center px-3 py-2 rounded-r-xl border border-gray-200 bg-white text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors <?= $pagina >= $n_botones_paginacion ? 'pointer-events-none opacity-50' : '' ?>">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>