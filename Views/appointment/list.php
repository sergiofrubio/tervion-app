<?php
$pageTitle = "Citas";
include TEMPLATE_DIR . 'header.php';

$filtro_fecha_hora = isset($_POST['fecha_hora']) ? $_POST['fecha_hora'] : '';
$filtro_estado = isset($_POST['estado']) ? $_POST['estado'] : '';

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
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Citas</h1>
            <p class="mt-1 text-sm text-gray-500">Gestiona las citas programadas de los pacientes.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="<?= PROJECT_ROOT ?>/citas/create" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                <i class="bi bi-plus-lg"></i>
                Asignar Cita
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
        $bg_color = $alert_type === 'danger' ? 'bg-red-50 text-red-800 border-red-200' : ($alert_type === 'success' ? 'bg-green-50 text-green-800 border-green-200' : 'bg-blue-50 text-blue-800 border-blue-200');
    ?>
        <div class="rounded-lg border p-4 <?= $bg_color ?>" role="alert" x-data="{ show: true }" x-show="show">
            <div class="flex justify-between items-center">
                <div class="text-sm font-medium"><?= htmlspecialchars($alert_message) ?></div>
                <button @click="show = false" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100">
        <form method="post" action="" class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="w-full lg:w-auto flex-1">
                <label for="fecha_hora" class="block text-sm font-medium text-gray-700 mb-1.5">Fecha</label>
                <input type="date" id="fecha_hora" name="fecha_hora" value="<?= htmlspecialchars($filtro_fecha_hora) ?>" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2.5 transition-colors">
            </div>
            <div class="w-full lg:w-auto flex-1">
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
                <select id="estado" name="estado" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2.5 bg-white transition-colors">
                    <option value="" <?= $filtro_estado === '' ? 'selected' : '' ?>>Todos los estados</option>
                    <option value="Programada" <?= $filtro_estado === 'Programada' ? 'selected' : '' ?>>Programada</option>
                    <option value="Realizada" <?= $filtro_estado === 'Realizada' ? 'selected' : '' ?>>Realizada</option>
                    <option value="Cancelada" <?= $filtro_estado === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                    <option value="Pendiente" <?= $filtro_estado === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                </select>
            </div>
            <div class="w-full lg:w-auto">
                <button type="submit" class="w-full lg:w-auto inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 transition-all">
                    <i class="bi bi-funnel"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white overflow-hidden rounded-2xl border border-gray-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID Pac.</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Paciente</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacto</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fis. Asoc.</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="sticky right-0 bg-gray-50/90 backdrop-blur-sm px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider border-l border-gray-100 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.02)]">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (!empty($citasPaginadas)) : ?>
                        <?php foreach ($citasPaginadas as $cita) : ?>
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 font-medium">#<?= htmlspecialchars($cita['paciente_id'] ?? $cita['cita_id'] ?? '') ?></td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-calendar2 text-gray-400"></i>
                                        <?= !empty($cita['fecha_hora']) ? date('d/m/Y H:i', strtotime($cita['fecha_hora'])) : '' ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars(trim(($cita['paciente_nombre'] ?? '') . " " . ($cita['paciente_apellidos'] ?? ''))) ?></div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    <a href="tel:<?= htmlspecialchars($cita['paciente_telefono'] ?? '') ?>" class="hover:text-primary-600 transition-colors">
                                        <?= htmlspecialchars($cita['paciente_telefono'] ?? '') ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-xs font-bold">
                                            <?= htmlspecialchars(substr($cita['fisioterapeuta_nombre'] ?? '', 0, 1)) ?>
                                        </div>
                                        <?= htmlspecialchars(trim(($cita['fisioterapeuta_nombre'] ?? '') . " " . substr($cita['fisioterapeuta_apellidos'] ?? '', 0, 1) . ".")) ?>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <?php
                                    $estado = $cita['estado'];
                                    $bg = 'bg-gray-100 text-gray-700 ring-gray-600/20';
                                    $dot = 'bg-gray-500';
                                    if ($estado == 'Realizada') {
                                        $bg = 'bg-green-50 text-green-700 ring-green-600/20';
                                        $dot = 'bg-green-500';
                                    } elseif ($estado == 'Cancelada') {
                                        $bg = 'bg-red-50 text-red-700 ring-red-600/10';
                                        $dot = 'bg-red-500';
                                    } elseif ($estado == 'Programada') {
                                        $bg = 'bg-amber-50 text-amber-700 ring-amber-600/20';
                                        $dot = 'bg-amber-500';
                                    } elseif ($estado == 'Pendiente') {
                                        $bg = 'bg-blue-50 text-blue-700 ring-blue-700/10';
                                        $dot = 'bg-blue-500';
                                    }
                                    ?>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset <?= $bg ?>">
                                        <svg class="h-1.5 w-1.5 fill-current <?= $dot ?>" viewBox="0 0 6 6" aria-hidden="true">
                                            <circle cx="3" cy="3" r="3" />
                                        </svg>
                                        <?= $estado ?>
                                    </span>
                                </td>
                                <td class="sticky right-0 bg-white group-hover:bg-gray-50/50 px-6 py-4 whitespace-nowrap text-right text-sm font-medium border-l border-gray-100/50 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.02)] transition-colors">
                                    <div class="flex items-center justify-end gap-1">
                                        <?php if (!($cita['estado'] == 'Programada' || $cita['estado'] == 'Pendiente' || $cita['estado'] == 'Cancelada')): ?>
                                            <button onclick="window.location='<?= PROJECT_ROOT ?>/historial?usuario_id=<?= $cita['paciente_id'] ?>'" class="text-gray-400 hover:text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200" title="Historial Médico">
                                                <i class="bi bi-journal-medical text-lg"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if (!($cita['estado'] == 'Realizada' || $cita['estado'] == 'Cancelada')): ?>
                                            <a href="<?= PROJECT_ROOT ?>/citas/edit?id=<?= $cita['cita_id'] ?>" class="text-gray-400 hover:text-amber-500 hover:bg-amber-50 p-2 rounded-lg transition-all duration-200" title="Editar">
                                                <i class="bi bi-pencil-square text-lg"></i>
                                            </a>
                                            <form action="<?= PROJECT_ROOT ?>/citas/delete" method="POST" class="inline-block m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                                                <input type="hidden" name="id" value="<?= $cita['cita_id'] ?>">
                                                <button type="submit" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-all duration-200" title="Eliminar">
                                                    <i class="bi bi-trash3 text-lg"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="bi bi-calendar-x text-2xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900">No hay citas</h3>
                                    <p class="mt-1 text-sm text-gray-500">No se encontraron citas con los filtros aplicados.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($n_botones_paginacion > 1): ?>
        <div class="bg-white px-4 py-3 border-t border-gray-100 sm:px-6 flex items-center justify-between">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Mostrando <span class="font-semibold text-gray-900"><?= min($iniciar + 1, $total_citas) ?></span> a <span class="font-semibold text-gray-900"><?= min($iniciar + $articulos_x_pagina, $total_citas) ?></span> de <span class="font-semibold text-gray-900"><?= $total_citas ?></span> citas
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="?pagina=<?= max(1, $pagina - 1) ?>" class="relative inline-flex items-center px-3 py-2 rounded-l-lg border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors <?= $pagina <= 1 ? 'pointer-events-none opacity-50' : '' ?>">
                            <span class="sr-only">Anterior</span>
                            <i class="bi bi-chevron-left text-xs"></i>
                        </a>
                        <?php for ($i = 0; $i < $n_botones_paginacion; $i++) : ?>
                            <a href="?pagina=<?= $i + 1 ?>" aria-current="<?= $pagina == $i + 1 ? 'page' : 'false' ?>" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors <?= $pagina == $i + 1 ? 'z-10 bg-primary-50 border-primary-500 text-primary-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                                <?= $i + 1 ?>
                            </a>
                        <?php endfor; ?>
                        <a href="?pagina=<?= min($n_botones_paginacion, $pagina + 1) ?>" class="relative inline-flex items-center px-3 py-2 rounded-r-lg border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors <?= $pagina >= $n_botones_paginacion ? 'pointer-events-none opacity-50' : '' ?>">
                            <span class="sr-only">Siguiente</span>
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
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>