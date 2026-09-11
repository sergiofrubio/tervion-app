<?php
$pageTitle = "Citas";
$isAdminOrSecretary = isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Administrador' || $_SESSION['rol'] === 'Secretario');
include TEMPLATE_DIR . 'header.php';

// Filtros
$filtro_busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$filtro_estado = isset($_GET['estado']) ? trim($_GET['estado']) : '';
$filtro_fisioterapeuta = isset($_GET['terapeuta_id']) ? trim($_GET['terapeuta_id']) : '';
$filtro_fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';
$filtro_paciente = isset($_GET['paciente']) ? trim($_GET['paciente']) : '';
$filtro_servicio = isset($_GET['servicio']) ? trim($_GET['servicio']) : '';
$filtro_despacho = isset($_GET['despacho']) ? trim($_GET['despacho']) : '';

// Extraer fisioterapeutas, servicios y despachos únicos
$lista_terapeutas = [];
$lista_servicios = [];
$lista_despachos = [];
$seen_fisios = [];
$seen_servicios = [];
$seen_despachos = [];

if (!empty($appointments)) {
    foreach ($appointments as $cita) {
        if (!empty($cita['terapeuta_id']) && !isset($seen_fisios[$cita['terapeuta_id']])) {
            $seen_fisios[$cita['terapeuta_id']] = true;
            $lista_terapeutas[] = [
                'usuario_id' => $cita['terapeuta_id'],
                'nombre' => trim(($cita['fisioterapeuta_nombre'] ?? '') . ' ' . ($cita['fisioterapeuta_apellidos'] ?? ''))
            ];
        }
        if (!empty($cita['tipo_cita_nombre']) && !isset($seen_servicios[$cita['tipo_cita_nombre']])) {
            $seen_servicios[$cita['tipo_cita_nombre']] = true;
            $lista_servicios[] = [
                'nombre' => $cita['tipo_cita_nombre'],
                'color'  => $cita['tipo_cita_color'] ?? '#6366f1'
            ];
        }
        if (!empty($cita['despacho_nombre']) && !isset($seen_despachos[$cita['despacho_nombre']])) {
            $seen_despachos[$cita['despacho_nombre']] = true;
            $lista_despachos[] = [
                'nombre' => $cita['despacho_nombre'],
                'color'  => $cita['despacho_color'] ?? '#6366f1'
            ];
        }
    }
}

// Si $fisioterapeutas venía del controller pero no estaba poblada en appointments
if (empty($lista_terapeutas) && !empty($fisioterapeutas)) {
    foreach ($fisioterapeutas as $f) {
        $lista_terapeutas[] = [
            'usuario_id' => $f['usuario_id'],
            'nombre' => trim(($f['nombre'] ?? '') . ' ' . ($f['apellidos'] ?? ''))
        ];
    }
}

// Filtrar las citas
$citas_filtradas = [];
if (!empty($appointments)) {
    foreach ($appointments as $cita) {
        $match = true;

        // Búsqueda global por texto
        if ($filtro_busqueda !== '') {
            $busquedaLower = strtolower($filtro_busqueda);
            $pacienteNombre = strtolower(($cita['paciente_nombre'] ?? '') . ' ' . ($cita['paciente_apellidos'] ?? ''));
            $fisioNombre = strtolower(($cita['fisioterapeuta_nombre'] ?? '') . ' ' . ($cita['fisioterapeuta_apellidos'] ?? ''));
            $tipoNombre = strtolower($cita['tipo_cita_nombre'] ?? '');
            $despachoNombre = strtolower($cita['despacho_nombre'] ?? '');
            $citaId = (string)($cita['cita_id'] ?? '');

            if (
                strpos($pacienteNombre, $busquedaLower) === false &&
                strpos($fisioNombre, $busquedaLower) === false &&
                strpos($tipoNombre, $busquedaLower) === false &&
                strpos($despachoNombre, $busquedaLower) === false &&
                strpos($citaId, $busquedaLower) === false
            ) {
                $match = false;
            }
        }

        // Filtro por Paciente
        if ($filtro_paciente !== '') {
            $pacienteLower = strtolower($filtro_paciente);
            $nombreComp = strtolower(($cita['paciente_nombre'] ?? '') . ' ' . ($cita['paciente_apellidos'] ?? ''));
            $tel = strtolower($cita['paciente_telefono'] ?? '');
            if (strpos($nombreComp, $pacienteLower) === false && strpos($tel, $pacienteLower) === false) {
                $match = false;
            }
        }

        // Filtro por Estado
        if ($filtro_estado !== '' && isset($cita['estado']) && $cita['estado'] !== $filtro_estado) {
            $match = false;
        }

        // Filtro por Fisioterapeuta
        if ($filtro_fisioterapeuta !== '' && isset($cita['terapeuta_id']) && (string)$cita['terapeuta_id'] !== (string)$filtro_fisioterapeuta) {
            $match = false;
        }

        // Filtro por Fecha (YYYY-MM-DD)
        if ($filtro_fecha !== '' && isset($cita['fecha_hora'])) {
            $fechaCita = substr($cita['fecha_hora'], 0, 10);
            if ($fechaCita !== $filtro_fecha) {
                $match = false;
            }
        }

        // Filtro por Servicio / Tipo de cita
        if ($filtro_servicio !== '') {
            $servicioCita = $cita['tipo_cita_nombre'] ?? '';
            if ($servicioCita !== $filtro_servicio) {
                $match = false;
            }
        }

        // Filtro por Despacho
        if ($filtro_despacho !== '') {
            if ($filtro_despacho === 'online') {
                if (!empty($cita['despacho_id'])) {
                    $match = false;
                }
            } else {
                $despachoCita = $cita['despacho_nombre'] ?? '';
                if ($despachoCita !== $filtro_despacho) {
                    $match = false;
                }
            }
        }

        if ($match) {
            $citas_filtradas[] = $cita;
        }
    }
}

// Paginación (10 citas por página)
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
function getPaginationQuery($page, $busqueda, $estado, $fisioterapeuta, $fecha, $paciente = '', $servicio = '', $despacho = '')
{
    $params = ['pagina' => $page];
    if ($busqueda !== '') $params['busqueda'] = $busqueda;
    if ($estado !== '') $params['estado'] = $estado;
    if ($fisioterapeuta !== '') $params['terapeuta_id'] = $fisioterapeuta;
    if ($fecha !== '') $params['fecha'] = $fecha;
    if ($paciente !== '') $params['paciente'] = $paciente;
    if ($servicio !== '') $params['servicio'] = $servicio;
    if ($despacho !== '') $params['despacho'] = $despacho;
    return '?' . http_build_query($params);
}

// Helper para generar URL con un parámetro modificado o eliminado
function getFilterUrl($changes = [])
{
    $params = $_GET;
    unset($params['pagina']); // Resetear a página 1 al cambiar filtros
    foreach ($changes as $key => $val) {
        if ($val === null || $val === '') {
            unset($params[$key]);
        } else {
            $params[$key] = $val;
        }
    }
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

$hayFiltrosActivos = ($filtro_busqueda !== '' || $filtro_estado !== '' || $filtro_fisioterapeuta !== '' || $filtro_fecha !== '' || $filtro_paciente !== '' || $filtro_servicio !== '' || $filtro_despacho !== '');
?>

<div class="space-y-6 animate-fade-in-up" x-data="{ openFilter: null }">
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
        <!-- Table Card Header & Filters Bar -->
        <div class="p-4 sm:p-6 border-b border-gray-100 bg-white">
            <form method="get" action="<?= PROJECT_ROOT ?>/citas" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <!-- Conservar filtros de columnas en caso de búsqueda rápida -->
                <?php if ($filtro_fisioterapeuta !== ''): ?><input type="hidden" name="terapeuta_id" value="<?= htmlspecialchars($filtro_fisioterapeuta) ?>"><?php endif; ?>
                <?php if ($filtro_estado !== ''): ?><input type="hidden" name="estado" value="<?= htmlspecialchars($filtro_estado) ?>"><?php endif; ?>
                <?php if ($filtro_fecha !== ''): ?><input type="hidden" name="fecha" value="<?= htmlspecialchars($filtro_fecha) ?>"><?php endif; ?>
                <?php if ($filtro_paciente !== ''): ?><input type="hidden" name="paciente" value="<?= htmlspecialchars($filtro_paciente) ?>"><?php endif; ?>
                <?php if ($filtro_servicio !== ''): ?><input type="hidden" name="servicio" value="<?= htmlspecialchars($filtro_servicio) ?>"><?php endif; ?>
                <?php if ($filtro_despacho !== ''): ?><input type="hidden" name="despacho" value="<?= htmlspecialchars($filtro_despacho) ?>"><?php endif; ?>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                    <!-- Search Input -->
                    <div class="relative flex-1 max-w-md">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="busqueda" id="busqueda" value="<?= htmlspecialchars($filtro_busqueda) ?>" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" placeholder="Buscar citas por cualquier término...">
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-xl text-xs font-semibold transition-colors cursor-pointer shadow-sm">
                        <i class="bi bi-search text-xs"></i>
                        <span>Buscar</span>
                    </button>

                    <?php if ($hayFiltrosActivos): ?>
                        <a href="<?= PROJECT_ROOT ?>/citas" class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold transition-colors" title="Restablecer todos los filtros">
                            <i class="bi bi-x-circle-fill"></i>
                            <span>Limpiar filtros</span>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-3 self-end lg:self-center">
                    <div class="text-xs text-gray-500 font-medium whitespace-nowrap">
                        Total registrado: <span class="font-bold text-gray-900"><?= $total_citas ?></span> citas
                        <?php if ($hayFiltrosActivos && count($appointments) !== $total_citas): ?>
                            <span class="text-gray-400 text-[11px]">(de <?= count($appointments) ?>)</span>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto min-h-[360px] pb-48">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-500 uppercase text-[10px] tracking-wider border-b border-gray-100 select-none">

                        <!-- Columna: Fecha y Hora -->
                        <th class="py-3 px-4 font-semibold relative">
                            <div class="flex items-center justify-between gap-1.5">
                                <span>Fecha y Hora</span>
                                <button type="button"
                                    @click="openFilter = (openFilter === 'fecha' ? null : 'fecha')"
                                    class="p-1 rounded-lg transition-colors cursor-pointer <?= $filtro_fecha !== '' ? 'bg-primary-100 text-primary-700 ring-1 ring-primary-400' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-200/70' ?>"
                                    title="Filtrar por fecha">
                                    <i class="bi bi-funnel-fill text-xs"></i>
                                </button>
                            </div>
                            <!-- Dropdown Fecha -->
                            <div x-show="openFilter === 'fecha'"
                                @click.outside="openFilter = null"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute left-2 top-full mt-1.5 z-40 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 p-3 text-gray-800 normal-case tracking-normal">
                                <div class="flex items-center justify-between pb-2 mb-2 border-b border-gray-100">
                                    <span class="font-bold text-xs text-gray-900 flex items-center gap-1.5">
                                        <i class="bi bi-calendar3 text-primary-600"></i> Filtrar por Fecha
                                    </span>
                                    <?php if ($filtro_fecha !== ''): ?>
                                        <a href="<?= getFilterUrl(['fecha' => null]) ?>" class="text-[11px] text-rose-600 hover:underline font-semibold">Limpiar</a>
                                    <?php endif; ?>
                                </div>
                                <form method="get" action="<?= PROJECT_ROOT ?>/citas" class="space-y-2.5">
                                    <?php if ($filtro_busqueda !== ''): ?><input type="hidden" name="busqueda" value="<?= htmlspecialchars($filtro_busqueda) ?>"><?php endif; ?>
                                    <?php if ($filtro_paciente !== ''): ?><input type="hidden" name="paciente" value="<?= htmlspecialchars($filtro_paciente) ?>"><?php endif; ?>
                                    <?php if ($filtro_fisioterapeuta !== ''): ?><input type="hidden" name="terapeuta_id" value="<?= htmlspecialchars($filtro_fisioterapeuta) ?>"><?php endif; ?>
                                    <?php if ($filtro_servicio !== ''): ?><input type="hidden" name="servicio" value="<?= htmlspecialchars($filtro_servicio) ?>"><?php endif; ?>
                                    <?php if ($filtro_despacho !== ''): ?><input type="hidden" name="despacho" value="<?= htmlspecialchars($filtro_despacho) ?>"><?php endif; ?>
                                    <?php if ($filtro_estado !== ''): ?><input type="hidden" name="estado" value="<?= htmlspecialchars($filtro_estado) ?>"><?php endif; ?>

                                    <input type="date" name="fecha" value="<?= htmlspecialchars($filtro_fecha) ?>" class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">

                                    <div class="flex items-center justify-end gap-2 pt-1">
                                        <button type="button" @click="openFilter = null" class="px-2.5 py-1 text-xs text-gray-500 hover:text-gray-700">Cerrar</button>
                                        <button type="submit" class="px-3 py-1 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-xs cursor-pointer">Aplicar</button>
                                    </div>
                                </form>
                            </div>
                        </th>

                        <!-- Columna: Paciente -->
                        <th class="py-3 px-4 font-semibold relative">
                            <div class="flex items-center justify-between gap-1.5">
                                <span>Paciente</span>
                                <button type="button"
                                    @click="openFilter = (openFilter === 'paciente' ? null : 'paciente')"
                                    class="p-1 rounded-lg transition-colors cursor-pointer <?= $filtro_paciente !== '' ? 'bg-primary-100 text-primary-700 ring-1 ring-primary-400' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-200/70' ?>"
                                    title="Filtrar por paciente">
                                    <i class="bi bi-funnel-fill text-xs"></i>
                                </button>
                            </div>
                            <!-- Dropdown Paciente -->
                            <div x-show="openFilter === 'paciente'"
                                @click.outside="openFilter = null"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute left-2 top-full mt-1.5 z-40 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 p-3 text-gray-800 normal-case tracking-normal">
                                <div class="flex items-center justify-between pb-2 mb-2 border-b border-gray-100">
                                    <span class="font-bold text-xs text-gray-900 flex items-center gap-1.5">
                                        <i class="bi bi-person text-primary-600"></i> Filtrar Paciente
                                    </span>
                                    <?php if ($filtro_paciente !== ''): ?>
                                        <a href="<?= getFilterUrl(['paciente' => null]) ?>" class="text-[11px] text-rose-600 hover:underline font-semibold">Limpiar</a>
                                    <?php endif; ?>
                                </div>
                                <form method="get" action="<?= PROJECT_ROOT ?>/citas" class="space-y-2.5">
                                    <?php if ($filtro_busqueda !== ''): ?><input type="hidden" name="busqueda" value="<?= htmlspecialchars($filtro_busqueda) ?>"><?php endif; ?>
                                    <?php if ($filtro_fecha !== ''): ?><input type="hidden" name="fecha" value="<?= htmlspecialchars($filtro_fecha) ?>"><?php endif; ?>
                                    <?php if ($filtro_fisioterapeuta !== ''): ?><input type="hidden" name="terapeuta_id" value="<?= htmlspecialchars($filtro_fisioterapeuta) ?>"><?php endif; ?>
                                    <?php if ($filtro_servicio !== ''): ?><input type="hidden" name="servicio" value="<?= htmlspecialchars($filtro_servicio) ?>"><?php endif; ?>
                                    <?php if ($filtro_despacho !== ''): ?><input type="hidden" name="despacho" value="<?= htmlspecialchars($filtro_despacho) ?>"><?php endif; ?>
                                    <?php if ($filtro_estado !== ''): ?><input type="hidden" name="estado" value="<?= htmlspecialchars($filtro_estado) ?>"><?php endif; ?>

                                    <input type="text" name="paciente" value="<?= htmlspecialchars($filtro_paciente) ?>" placeholder="Nombre o teléfono..." class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">

                                    <div class="flex items-center justify-end gap-2 pt-1">
                                        <button type="button" @click="openFilter = null" class="px-2.5 py-1 text-xs text-gray-500 hover:text-gray-700">Cerrar</button>
                                        <button type="submit" class="px-3 py-1 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-xs cursor-pointer">Filtrar</button>
                                    </div>
                                </form>
                            </div>
                        </th>

                        <!-- Columna: Terapeuta -->
                        <th class="py-3 px-4 font-semibold relative">
                            <div class="flex items-center justify-between gap-1.5">
                                <span>Terapeuta</span>
                                <button type="button"
                                    @click="openFilter = (openFilter === 'terapeuta' ? null : 'terapeuta')"
                                    class="p-1 rounded-lg transition-colors cursor-pointer <?= $filtro_fisioterapeuta !== '' ? 'bg-primary-100 text-primary-700 ring-1 ring-primary-400' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-200/70' ?>"
                                    title="Filtrar por terapeuta">
                                    <i class="bi bi-funnel-fill text-xs"></i>
                                </button>
                            </div>
                            <!-- Dropdown Terapeuta -->
                            <div x-show="openFilter === 'terapeuta'"
                                @click.outside="openFilter = null"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute left-2 top-full mt-1.5 z-40 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 text-gray-800 normal-case tracking-normal">
                                <div class="flex items-center justify-between p-2 mb-1 border-b border-gray-100">
                                    <span class="font-bold text-xs text-gray-900 flex items-center gap-1.5">
                                        <i class="bi bi-person-badge text-primary-600"></i> Terapeuta
                                    </span>
                                    <?php if ($filtro_fisioterapeuta !== ''): ?>
                                        <a href="<?= getFilterUrl(['terapeuta_id' => null]) ?>" class="text-[11px] text-rose-600 hover:underline font-semibold">Todos</a>
                                    <?php endif; ?>
                                </div>
                                <div class="max-h-52 overflow-y-auto space-y-0.5">
                                    <a href="<?= getFilterUrl(['terapeuta_id' => null]) ?>"
                                        class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs transition-colors <?= $filtro_fisioterapeuta === '' ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' ?>">
                                        <span>Todos los terapeutas</span>
                                        <?php if ($filtro_fisioterapeuta === ''): ?><i class="bi bi-check text-primary-600 text-sm"></i><?php endif; ?>
                                    </a>
                                    <?php foreach ($lista_terapeutas as $fisio): ?>
                                        <a href="<?= getFilterUrl(['terapeuta_id' => $fisio['usuario_id']]) ?>"
                                            class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs transition-colors <?= (string)$filtro_fisioterapeuta === (string)$fisio['usuario_id'] ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' ?>">
                                            <span class="truncate"><?= htmlspecialchars($fisio['nombre']) ?></span>
                                            <?php if ((string)$filtro_fisioterapeuta === (string)$fisio['usuario_id']): ?>
                                                <i class="bi bi-check text-primary-600 text-sm"></i>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </th>

                        <!-- Columna: Servicio -->
                        <th class="py-3 px-4 font-semibold relative">
                            <div class="flex items-center justify-between gap-1.5">
                                <span>Servicio</span>
                                <button type="button"
                                    @click="openFilter = (openFilter === 'servicio' ? null : 'servicio')"
                                    class="p-1 rounded-lg transition-colors cursor-pointer <?= $filtro_servicio !== '' ? 'bg-primary-100 text-primary-700 ring-1 ring-primary-400' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-200/70' ?>"
                                    title="Filtrar por servicio">
                                    <i class="bi bi-funnel-fill text-xs"></i>
                                </button>
                            </div>
                            <!-- Dropdown Servicio -->
                            <div x-show="openFilter === 'servicio'"
                                @click.outside="openFilter = null"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute left-2 top-full mt-1.5 z-40 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 text-gray-800 normal-case tracking-normal">
                                <div class="flex items-center justify-between p-2 mb-1 border-b border-gray-100">
                                    <span class="font-bold text-xs text-gray-900 flex items-center gap-1.5">
                                        <i class="bi bi-tag text-primary-600"></i> Servicio
                                    </span>
                                    <?php if ($filtro_servicio !== ''): ?>
                                        <a href="<?= getFilterUrl(['servicio' => null]) ?>" class="text-[11px] text-rose-600 hover:underline font-semibold">Todos</a>
                                    <?php endif; ?>
                                </div>
                                <div class="max-h-52 overflow-y-auto space-y-0.5">
                                    <a href="<?= getFilterUrl(['servicio' => null]) ?>"
                                        class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs transition-colors <?= $filtro_servicio === '' ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' ?>">
                                        <span>Todos los servicios</span>
                                        <?php if ($filtro_servicio === ''): ?><i class="bi bi-check text-primary-600 text-sm"></i><?php endif; ?>
                                    </a>
                                    <?php foreach ($lista_servicios as $serv): ?>
                                        <a href="<?= getFilterUrl(['servicio' => $serv['nombre']]) ?>"
                                            class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs transition-colors <?= $filtro_servicio === $serv['nombre'] ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' ?>">
                                            <span class="flex items-center gap-2 truncate">
                                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= htmlspecialchars($serv['color']) ?>;"></span>
                                                <span class="truncate"><?= htmlspecialchars($serv['nombre']) ?></span>
                                            </span>
                                            <?php if ($filtro_servicio === $serv['nombre']): ?>
                                                <i class="bi bi-check text-primary-600 text-sm"></i>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </th>

                        <!-- Columna: Despacho -->
                        <th class="py-3 px-4 font-semibold relative">
                            <div class="flex items-center justify-between gap-1.5">
                                <span>Despacho</span>
                                <button type="button"
                                    @click="openFilter = (openFilter === 'despacho' ? null : 'despacho')"
                                    class="p-1 rounded-lg transition-colors cursor-pointer <?= $filtro_despacho !== '' ? 'bg-primary-100 text-primary-700 ring-1 ring-primary-400' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-200/70' ?>"
                                    title="Filtrar por despacho">
                                    <i class="bi bi-funnel-fill text-xs"></i>
                                </button>
                            </div>
                            <!-- Dropdown Despacho -->
                            <div x-show="openFilter === 'despacho'"
                                @click.outside="openFilter = null"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 top-full mt-1.5 z-40 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 text-gray-800 normal-case tracking-normal">
                                <div class="flex items-center justify-between p-2 mb-1 border-b border-gray-100">
                                    <span class="font-bold text-xs text-gray-900 flex items-center gap-1.5">
                                        <i class="bi bi-door-open text-primary-600"></i> Despacho / Sala
                                    </span>
                                    <?php if ($filtro_despacho !== ''): ?>
                                        <a href="<?= getFilterUrl(['despacho' => null]) ?>" class="text-[11px] text-rose-600 hover:underline font-semibold">Todos</a>
                                    <?php endif; ?>
                                </div>
                                <div class="max-h-52 overflow-y-auto space-y-0.5">
                                    <a href="<?= getFilterUrl(['despacho' => null]) ?>"
                                        class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs transition-colors <?= $filtro_despacho === '' ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' ?>">
                                        <span>Todos los despachos</span>
                                        <?php if ($filtro_despacho === ''): ?><i class="bi bi-check text-primary-600 text-sm"></i><?php endif; ?>
                                    </a>
                                    <a href="<?= getFilterUrl(['despacho' => 'online']) ?>"
                                        class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs transition-colors <?= $filtro_despacho === 'online' ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' ?>">
                                        <span class="flex items-center gap-1.5 text-gray-600">
                                            <i class="bi bi-camera-video text-xs"></i>
                                            <span>Online / Sin despacho</span>
                                        </span>
                                        <?php if ($filtro_despacho === 'online'): ?><i class="bi bi-check text-primary-600 text-sm"></i><?php endif; ?>
                                    </a>
                                    <?php foreach ($lista_despachos as $desp): ?>
                                        <a href="<?= getFilterUrl(['despacho' => $desp['nombre']]) ?>"
                                            class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs transition-colors <?= $filtro_despacho === $desp['nombre'] ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' ?>">
                                            <span class="flex items-center gap-2 truncate">
                                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= htmlspecialchars($desp['color']) ?>;"></span>
                                                <span class="truncate"><?= htmlspecialchars($desp['nombre']) ?></span>
                                            </span>
                                            <?php if ($filtro_despacho === $desp['nombre']): ?>
                                                <i class="bi bi-check text-primary-600 text-sm"></i>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </th>

                        <!-- Columna: Estado -->
                        <th class="py-3 px-4 font-semibold relative">
                            <div class="flex items-center justify-between gap-1.5">
                                <span>Estado</span>
                                <button type="button"
                                    @click="openFilter = (openFilter === 'estado' ? null : 'estado')"
                                    class="p-1 rounded-lg transition-colors cursor-pointer <?= $filtro_estado !== '' ? 'bg-primary-100 text-primary-700 ring-1 ring-primary-400' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-200/70' ?>"
                                    title="Filtrar por estado">
                                    <i class="bi bi-funnel-fill text-xs"></i>
                                </button>
                            </div>
                            <!-- Dropdown Estado -->
                            <div x-show="openFilter === 'estado'"
                                @click.outside="openFilter = null"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 top-full mt-1.5 z-40 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 text-gray-800 normal-case tracking-normal">
                                <div class="flex items-center justify-between p-2 mb-1 border-b border-gray-100">
                                    <span class="font-bold text-xs text-gray-900 flex items-center gap-1.5">
                                        <i class="bi bi-flag text-primary-600"></i> Estado
                                    </span>
                                    <?php if ($filtro_estado !== ''): ?>
                                        <a href="<?= getFilterUrl(['estado' => null]) ?>" class="text-[11px] text-rose-600 hover:underline font-semibold">Todos</a>
                                    <?php endif; ?>
                                </div>
                                <div class="space-y-0.5">
                                    <a href="<?= getFilterUrl(['estado' => null]) ?>"
                                        class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs transition-colors <?= $filtro_estado === '' ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' ?>">
                                        <span>Todos los estados</span>
                                        <?php if ($filtro_estado === ''): ?><i class="bi bi-check text-primary-600 text-sm"></i><?php endif; ?>
                                    </a>
                                    <?php foreach (['Programada', 'Confirmada', 'Pendiente', 'Realizada', 'Cancelada'] as $est): ?>
                                        <a href="<?= getFilterUrl(['estado' => $est]) ?>"
                                            class="flex items-center justify-between px-2.5 py-1.5 rounded-xl text-xs transition-colors <?= $filtro_estado === $est ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' ?>">
                                            <span class="inline-flex items-center gap-1.5">
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold <?= $estadoBadgeClasses[$est] ?? 'bg-gray-100 text-gray-700' ?>"><?= $est ?></span>
                                            </span>
                                            <?php if ($filtro_estado === $est): ?>
                                                <i class="bi bi-check text-primary-600 text-sm"></i>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </th>

                        <!-- Columna: Acciones -->
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
                            if ($nombrePaciente === '') {
                                $nombrePaciente = !empty($cita['paciente_id']) ? 'Paciente ' . $cita['paciente_id'] : 'Sin asignar';
                            }
                            $inicialPaciente = strtoupper(substr(!empty($cita['paciente_nombre']) ? $cita['paciente_nombre'] : $nombrePaciente, 0, 1));
                            $nombreFisio = trim(($cita['fisioterapeuta_nombre'] ?? '') . ' ' . ($cita['fisioterapeuta_apellidos'] ?? ''));
                            if ($nombreFisio === '') $nombreFisio = 'No asignado';
                            $pacienteEnlaceId = !empty($cita['paciente_usuario_id']) ? $cita['paciente_usuario_id'] : ($cita['paciente_id'] ?? '');
                        ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <!-- <td class="py-4 px-4 font-bold text-gray-400">#<?= htmlspecialchars($cita['cita_id']) ?></td> -->
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
                                    <?php if (!empty($cita['despacho_nombre'])): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-bold border" style="background-color: <?= htmlspecialchars($cita['despacho_color'] ?? '#6366f1') ?>15; border-color: <?= htmlspecialchars($cita['despacho_color'] ?? '#6366f1') ?>40; color: <?= htmlspecialchars($cita['despacho_color'] ?? '#6366f1') ?>;">
                                            <i class="bi bi-door-open-fill text-[10px]"></i>
                                            <span><?= htmlspecialchars($cita['despacho_nombre']) ?></span>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-semibold text-gray-400 bg-gray-50 border border-gray-100">
                                            <i class="bi bi-camera-video text-[9px]"></i> Online / Sin asignar
                                        </span>
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
                                        <?php if (!empty($pacienteEnlaceId)): ?>
                                            <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= htmlspecialchars($pacienteEnlaceId) ?>" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Ver Expediente / Historial del Paciente">
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
                            <a href="<?= getPaginationQuery(max(1, $pagina - 1), $filtro_busqueda, $filtro_estado, $filtro_fisioterapeuta, $filtro_fecha, $filtro_paciente, $filtro_servicio, $filtro_despacho) ?>" class="relative inline-flex items-center px-3 py-2 rounded-l-xl border border-gray-200 bg-white text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors <?= $pagina <= 1 ? 'pointer-events-none opacity-50' : '' ?>">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </a>
                            <?php for ($i = 0; $i < $n_botones_paginacion; $i++) : ?>
                                <a href="<?= getPaginationQuery($i + 1, $filtro_busqueda, $filtro_estado, $filtro_fisioterapeuta, $filtro_fecha, $filtro_paciente, $filtro_servicio, $filtro_despacho) ?>" aria-current="<?= $pagina == $i + 1 ? 'page' : 'false' ?>" class="relative inline-flex items-center px-3 py-2 border text-xs font-semibold transition-colors <?= $pagina == $i + 1 ? 'z-10 bg-primary-50 border-primary-500 text-primary-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                                    <?= $i + 1 ?>
                                </a>
                            <?php endfor; ?>
                            <a href="<?= getPaginationQuery(min($n_botones_paginacion, $pagina + 1), $filtro_busqueda, $filtro_estado, $filtro_fisioterapeuta, $filtro_fecha, $filtro_paciente, $filtro_servicio, $filtro_despacho) ?>" class="relative inline-flex items-center px-3 py-2 rounded-r-xl border border-gray-200 bg-white text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors <?= $pagina >= $n_botones_paginacion ? 'pointer-events-none opacity-50' : '' ?>">
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