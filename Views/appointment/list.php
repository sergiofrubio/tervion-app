<?php
$pageTitle = "Citas";
include TEMPLATE_DIR . 'header.php';

$filtro_fecha_hora = isset($_REQUEST['fecha_hora']) ? $_REQUEST['fecha_hora'] : '';
$filtro_estado = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : '';
$filtro_fisioterapeuta = isset($_REQUEST['fisioterapeuta_id']) ? $_REQUEST['fisioterapeuta_id'] : '';

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

$citas_filtradas = [];
if (!empty($appointments)) {
    foreach ($appointments as $cita) {
        $match = true;
        if ($filtro_fecha_hora !== '' && strpos((string)$cita['fecha_hora'], $filtro_fecha_hora) === false) {
            $match = false;
        }
        if ($filtro_estado !== '' && isset($cita['estado']) && $cita['estado'] !== $filtro_estado) {
            $match = false;
        }
        if ($filtro_fisioterapeuta !== '' && isset($cita['fisioterapeuta_id']) && (string)$cita['fisioterapeuta_id'] !== (string)$filtro_fisioterapeuta) {
            $match = false;
        }
        if ($match) {
            $citas_filtradas[] = $cita;
        }
    }
}


$articulos_x_pagina = 5;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$total_citas = count($citas_filtradas);
$n_botones_paginacion = ceil($total_citas / $articulos_x_pagina);

if ($pagina > $n_botones_paginacion && $n_botones_paginacion > 0) {
    $pagina = 1;
}

$iniciar = ($pagina - 1) * $articulos_x_pagina;
$citasPaginadas = array_slice($citas_filtradas, $iniciar, $articulos_x_pagina);

$params = $_GET;
unset($params['pagina']);
$queryString = !empty($params) ? '&' . http_build_query($params) : '';
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Citas</h1>
            <p class="text-gray-500 text-sm mt-0.5">Gestiona las citas programadas de los pacientes.</p>
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
            <form action="<?= PROJECT_ROOT ?>/citas" method="GET" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1 max-w-3xl">
                    <input type="date" id="fecha_hora" name="fecha_hora" value="<?= htmlspecialchars($filtro_fecha_hora) ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <select id="fisioterapeuta_id" name="fisioterapeuta_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                        <option value="" <?= $filtro_fisioterapeuta === '' ? 'selected' : '' ?>>Todas las agendas</option>
                        <?php if (!empty($fisioterapeutas)): ?>
                            <?php foreach ($fisioterapeutas as $fisio): ?>
                                <option value="<?= htmlspecialchars($fisio['usuario_id']) ?>" <?= (string)$filtro_fisioterapeuta === (string)$fisio['usuario_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(($fisio['nombre'] ?? '') . ' ' . ($fisio['apellidos'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <select id="estado" name="estado" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                        <option value="" <?= $filtro_estado === '' ? 'selected' : '' ?>>Todos los estados</option>
                        <option value="Programada" <?= $filtro_estado === 'Programada' ? 'selected' : '' ?>>Programada</option>
                        <option value="Realizada" <?= $filtro_estado === 'Realizada' ? 'selected' : '' ?>>Realizada</option>
                        <option value="Cancelada" <?= $filtro_estado === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        <option value="Pendiente" <?= $filtro_estado === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-xl text-xs shadow-sm transition-all cursor-pointer shrink-0">
                        <i class="bi bi-funnel"></i>
                        <span>Filtrar</span>
                    </button>
                    <?php if ($filtro_fecha_hora !== '' || $filtro_estado !== '' || $filtro_fisioterapeuta !== ''): ?>
                        <a href="<?= PROJECT_ROOT ?>/citas" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs transition-colors shrink-0" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                    <div class="text-xs text-gray-500 font-medium whitespace-nowrap ml-2">
                        Total: <span class="font-bold text-gray-900"><?= $total_citas ?></span> citas
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold">ID Pac.</th>
                        <th class="py-3 px-4 font-semibold">Fecha</th>
                        <th class="py-3 px-4 font-semibold">Tipo</th>
                        <th class="py-3 px-4 font-semibold">Paciente</th>
                        <th class="py-3 px-4 font-semibold">Contacto</th>
                        <th class="py-3 px-4 font-semibold">Fis. Asoc.</th>
                        <th class="py-3 px-4 font-semibold">Estado</th>
                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($citasPaginadas)) : ?>
                        <?php foreach ($citasPaginadas as $cita) : ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-4 font-bold text-gray-400">#<?= htmlspecialchars($cita['paciente_id'] ?? $cita['cita_id'] ?? '') ?></td>
                                <td class="py-4 px-4 font-semibold text-gray-900">
                                    <div class="flex items-center gap-1.5">
                                        <i class="bi bi-calendar2 text-gray-400 text-xs"></i>
                                        <?= !empty($cita['fecha_hora']) ? date('d/m/Y H:i', strtotime($cita['fecha_hora'])) : '' ?>
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-medium text-xs">
                                    <?php if (!empty($cita['tipo_cita_nombre'])): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 border border-gray-200">
                                            <span class="w-2 h-2 rounded-full" style="background-color: <?= htmlspecialchars($cita['tipo_cita_color'] ?? '#3b82f6') ?>"></span>
                                            <?= htmlspecialchars($cita['tipo_cita_nombre']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400 font-normal italic">Estándar</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 font-bold text-gray-900 text-sm">
                                    <?= htmlspecialchars(trim(($cita['paciente_nombre'] ?? '') . " " . ($cita['paciente_apellidos'] ?? ''))) ?>
                                </td>
                                <td class="py-4 px-4 text-gray-600 font-medium">
                                    <a href="tel:<?= htmlspecialchars($cita['paciente_telefono'] ?? '') ?>" class="hover:text-primary-600 transition-colors">
                                        <?= htmlspecialchars($cita['paciente_telefono'] ?? '') ?>
                                    </a>
                                </td>
                                <td class="py-4 px-4 text-gray-600 font-medium">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center text-[10px] font-bold">
                                            <?= htmlspecialchars(substr($cita['fisioterapeuta_nombre'] ?? '', 0, 1)) ?>
                                        </div>
                                        <span><?= htmlspecialchars(trim(($cita['fisioterapeuta_nombre'] ?? '') . " " . substr($cita['fisioterapeuta_apellidos'] ?? '', 0, 1) . ".")) ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <?php
                                    $estado = $cita['estado'];
                                    if ($estado == 'Realizada'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Realizada
                                        </span>
                                    <?php elseif ($estado == 'Cancelada'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Cancelada
                                        </span>
                                    <?php elseif ($estado == 'Programada'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Programada
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> Pendiente
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <?php if (!($cita['estado'] == 'Programada' || $cita['estado'] == 'Pendiente' || $cita['estado'] == 'Cancelada')): ?>
                                            <button onclick="window.location='<?= PROJECT_ROOT ?>/historial?usuario_id=<?= $cita['paciente_id'] ?>'" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Historial Médico">
                                                <i class="bi bi-journal-medical text-sm"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if (!($cita['estado'] == 'Realizada' || $cita['estado'] == 'Cancelada')): ?>
                                            <a href="<?= PROJECT_ROOT ?>/citas/editar?id=<?= $cita['cita_id'] ?>" class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Editar">
                                                <i class="bi bi-pencil-square text-sm"></i>
                                            </a>
                                            <form action="<?= PROJECT_ROOT ?>/citas/eliminar" method="POST" class="inline-block m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                                                <input type="hidden" name="id" value="<?= $cita['cita_id'] ?>">
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all cursor-pointer" title="Eliminar">
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
                                    <p class="mt-0.5 text-xs text-gray-500">No se encontraron citas con los filtros aplicados.</p>
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
                            <a href="?pagina=<?= max(1, $pagina - 1) . $queryString ?>" class="relative inline-flex items-center px-3 py-2 rounded-l-xl border border-gray-200 bg-white text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors <?= $pagina <= 1 ? 'pointer-events-none opacity-50' : '' ?>">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </a>
                            <?php for ($i = 0; $i < $n_botones_paginacion; $i++) : ?>
                                <a href="?pagina=<?= ($i + 1) . $queryString ?>" aria-current="<?= $pagina == $i + 1 ? 'page' : 'false' ?>" class="relative inline-flex items-center px-3 py-2 border text-xs font-semibold transition-colors <?= $pagina == $i + 1 ? 'z-10 bg-primary-50 border-primary-500 text-primary-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                                    <?= $i + 1 ?>
                                </a>
                            <?php endfor; ?>
                            <a href="?pagina=<?= min($n_botones_paginacion, $pagina + 1) . $queryString ?>" class="relative inline-flex items-center px-3 py-2 rounded-r-xl border border-gray-200 bg-white text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors <?= $pagina >= $n_botones_paginacion ? 'pointer-events-none opacity-50' : '' ?>">
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