<?php
$pageTitle = "Pacientes";
$isAdminOrSecretary = isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Administrador' || $_SESSION['rol'] === 'Secretario');
include TEMPLATE_DIR . 'header.php';

$filtro_usuario_id = isset($_GET['usuario_id']) ? trim($_GET['usuario_id']) : '';

// Filter the array if needed
$pacientes_filtrados = [];
if ($filtro_usuario_id !== '') {
    foreach ($patients as $p) {
        if (
            strpos((string)$p['usuario_id'], $filtro_usuario_id) !== false ||
            strpos(strtolower($p['nombre']), strtolower($filtro_usuario_id)) !== false ||
            strpos(strtolower($p['apellidos']), strtolower($filtro_usuario_id)) !== false
        ) {
            $pacientes_filtrados[] = $p;
        }
    }
} else {
    $pacientes_filtrados = $patients;
}

$articulos_x_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$total_pacientes = count($pacientes_filtrados);
$n_botones_paginacion = ceil($total_pacientes / $articulos_x_pagina);

if ($pagina > $n_botones_paginacion && $n_botones_paginacion > 0) {
    $pagina = 1;
}

$iniciar = ($pagina - 1) * $articulos_x_pagina;
$pacientesPaginados = array_slice($pacientes_filtrados, $iniciar, $articulos_x_pagina);
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Pacientes</h1>
            <p class="text-gray-500 text-sm mt-0.5">Gestiona los expedientes e historial clínico de los pacientes.</p>
        </div>
        <div>
            <?php if ($isAdminOrSecretary): ?>
                <a href="<?= PROJECT_ROOT ?>/pacientes/crear" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-2xl font-semibold text-sm shadow-md transition-all cursor-pointer">
                    <i class="bi bi-plus-lg"></i>
                    <span>Nuevo Paciente</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Patients Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Table Card Header & Filters -->
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <form method="get" action="<?= PROJECT_ROOT ?>/pacientes" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="relative flex-1 max-w-md">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="usuario_id" id="usuario_id" value="<?= htmlspecialchars($filtro_usuario_id) ?>" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" placeholder="Buscar por DNI o nombre">
                </div>
                <div class="flex items-center gap-3">
                    <?php if ($filtro_usuario_id !== ''): ?>
                        <a href="<?= PROJECT_ROOT ?>/pacientes" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs transition-colors shrink-0" title="Limpiar búsqueda">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                    <div class="text-xs text-gray-500 font-medium whitespace-nowrap">
                        Total registrado: <span class="font-bold text-gray-900"><?= $total_pacientes ?></span> pacientes
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold">DNI</th>
                        <th class="py-3 px-4 font-semibold">Nombre Completo</th>
                        <th class="py-3 px-4 font-semibold">Email</th>
                        <th class="py-3 px-4 font-semibold">Teléfono</th>
                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($pacientesPaginados)) : ?>
                        <?php foreach ($pacientesPaginados as $paciente) : ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-4 font-bold text-gray-400">#<?= htmlspecialchars($paciente['usuario_id']) ?></td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="h-7 w-7 rounded-full bg-primary-50 flex items-center justify-center text-primary-600 font-bold text-xs">
                                            <?= htmlspecialchars(substr($paciente['nombre'], 0, 1)) ?>
                                        </div>
                                        <div class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?></div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-600">
                                    <a href="mailto:<?= htmlspecialchars($paciente['email']) ?>" class="hover:text-primary-600 hover:underline flex items-center gap-1.5">
                                        <i class="bi bi-envelope text-gray-400 text-xs"></i>
                                        <?= htmlspecialchars($paciente['email']) ?>
                                    </a>
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-600">
                                    <a href="tel:<?= htmlspecialchars($paciente['telefono']) ?>" class="hover:text-primary-600 hover:underline flex items-center gap-1.5">
                                        <i class="bi bi-telephone text-gray-400 text-xs"></i>
                                        <?= htmlspecialchars($paciente['telefono']) ?>
                                    </a>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= $paciente['usuario_id'] ?>" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Ver Historial / Detalle">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>
                                        <?php if ($isAdminOrSecretary): ?>
                                            <a href="<?= PROJECT_ROOT ?>/pacientes/editar?id=<?= $paciente['usuario_id'] ?>" class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Editar">
                                                <i class="bi bi-pencil-square text-sm"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
                                            <form action="<?= PROJECT_ROOT ?>/pacientes/eliminar" method="POST" class="inline-block m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este paciente?');">
                                                <input type="hidden" name="id" value="<?= $paciente['usuario_id'] ?>">
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
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                        <i class="bi bi-people text-xl"></i>
                                    </div>
                                    <h3 class="text-xs font-bold text-gray-900">No hay pacientes</h3>
                                    <p class="mt-0.5 text-xs text-gray-500">No se encontraron pacientes registrados con los filtros aplicados.</p>
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
                            Mostrando <span class="font-bold text-gray-900"><?= min($iniciar + 1, $total_pacientes) ?></span> a <span class="font-bold text-gray-900"><?= min($iniciar + $articulos_x_pagina, $total_pacientes) ?></span> de <span class="font-bold text-gray-900"><?= $total_pacientes ?></span> pacientes
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="?pagina=<?= max(1, $pagina - 1) ?><?= $filtro_usuario_id !== '' ? '&usuario_id=' . urlencode($filtro_usuario_id) : '' ?>" class="relative inline-flex items-center px-3 py-2 rounded-l-xl border border-gray-200 bg-white text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors <?= $pagina <= 1 ? 'pointer-events-none opacity-50' : '' ?>">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </a>
                            <?php for ($i = 0; $i < $n_botones_paginacion; $i++) : ?>
                                <a href="?pagina=<?= $i + 1 ?><?= $filtro_usuario_id !== '' ? '&usuario_id=' . urlencode($filtro_usuario_id) : '' ?>" aria-current="<?= $pagina == $i + 1 ? 'page' : 'false' ?>" class="relative inline-flex items-center px-3 py-2 border text-xs font-semibold transition-colors <?= $pagina == $i + 1 ? 'z-10 bg-primary-50 border-primary-500 text-primary-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                                    <?= $i + 1 ?>
                                </a>
                            <?php endfor; ?>
                            <a href="?pagina=<?= min($n_botones_paginacion, $pagina + 1) ?><?= $filtro_usuario_id !== '' ? '&usuario_id=' . urlencode($filtro_usuario_id) : '' ?>" class="relative inline-flex items-center px-3 py-2 rounded-r-xl border border-gray-200 bg-white text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors <?= $pagina >= $n_botones_paginacion ? 'pointer-events-none opacity-50' : '' ?>">
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