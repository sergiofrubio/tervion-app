<?php
$pageTitle = "Facultativos";
$isAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador';
include TEMPLATE_DIR . 'header.php';

$filtro_busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';
$filtro_rol = isset($_GET['rol']) ? trim($_GET['rol']) : '';

// Filtrar trabajadores
$trabajadores_filtrados = [];
if (!empty($workers)) {
    foreach ($workers as $w) {
        $match = true;
        if ($filtro_busqueda !== '') {
            $nombreCompleto = strtolower($w['nombre'] . ' ' . $w['apellidos'] . ' ' . $w['usuario_id']);
            if (strpos($nombreCompleto, strtolower($filtro_busqueda)) === false) {
                $match = false;
            }
        }
        if ($filtro_rol !== '' && ($w['rol'] ?? '') !== $filtro_rol) {
            $match = false;
        }
        if ($match) {
            $trabajadores_filtrados[] = $w;
        }
    }
}

$articulos_x_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$total_trabajadores = count($trabajadores_filtrados);
$n_botones_paginacion = ceil($total_trabajadores / $articulos_x_pagina);

if ($pagina > $n_botones_paginacion && $n_botones_paginacion > 0) {
    $pagina = 1;
}

$iniciar = ($pagina - 1) * $articulos_x_pagina;
$trabajadoresPaginados = array_slice($trabajadores_filtrados, $iniciar, $articulos_x_pagina);
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Facultativos</h1>
            <p class="text-gray-500 text-sm mt-0.5">Gestión del personal de la clínica.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <?php if ($isAdmin): ?>
                <a href="<?= PROJECT_ROOT ?>/terapeutas/crear" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-2xl font-semibold text-sm shadow-md transition-all cursor-pointer">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Nuevo Facultativo</span>
                </a>
            <?php endif; ?>
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

    <!-- Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Filters -->
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <form method="get" action="<?= PROJECT_ROOT ?>/terapeutas" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-center gap-3 flex-1 max-w-xl">
                    <div class="relative w-full">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="q" value="<?= htmlspecialchars($filtro_busqueda) ?>" onchange="this.form.submit()" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" placeholder="Buscar por DNI o nombre...">
                    </div>
                    <select name="rol" onchange="this.form.submit()" class="w-full sm:w-48 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                        <option value="">Todos los roles</option>
                        <option value="Fisioterapeuta" <?= $filtro_rol === 'Fisioterapeuta' ? 'selected' : '' ?>>Fisioterapeuta</option>
                        <option value="Secretario" <?= $filtro_rol === 'Secretario' ? 'selected' : '' ?>>Secretario</option>
                        <option value="Administrador" <?= $filtro_rol === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                    <?php if ($filtro_busqueda !== '' || $filtro_rol !== ''): ?>
                        <a href="<?= PROJECT_ROOT ?>/terapeutas" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs transition-colors shrink-0" title="Limpiar filtro">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="text-xs text-gray-500 font-medium whitespace-nowrap">
                    Total registrado: <span class="font-bold text-gray-900"><?= $total_trabajadores ?></span> facultativos
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold">DNI / ID</th>
                        <th class="py-3 px-4 font-semibold">Nombre Completo</th>
                        <th class="py-3 px-4 font-semibold">Rol / Cargo</th>
                        <th class="py-3 px-4 font-semibold">Email</th>
                        <th class="py-3 px-4 font-semibold">Teléfono</th>
                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($trabajadoresPaginados)) : ?>
                        <?php foreach ($trabajadoresPaginados as $w) : ?>
                            <?php
                            $avatarId = (intval(preg_replace('/[^0-9]/', '', $w['usuario_id'])) % 70) + 1;
                            $avatarUrl = (isset($w['genero']) && $w['genero'] === 'Mujer')
                                ? "https://randomuser.me/api/portraits/women/{$avatarId}.jpg"
                                : "https://randomuser.me/api/portraits/men/{$avatarId}.jpg";
                            ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-4 font-bold text-gray-400">#<?= htmlspecialchars($w['usuario_id']) ?></td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="<?= $avatarUrl ?>" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200 shadow-sm">
                                        <div>
                                            <a href="<?= PROJECT_ROOT ?>/terapeutas/detalle?id=<?= $w['usuario_id'] ?>" class="font-bold text-gray-900 text-sm hover:text-primary-600 transition-colors">
                                                <?= htmlspecialchars($w['nombre'] . ' ' . $w['apellidos']) ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <?php if ($w['rol'] === 'Administrador'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200">
                                            <i class="bi bi-shield-lock-fill text-purple-500"></i> Administrador
                                        </span>
                                    <?php elseif ($w['rol'] === 'Fisioterapeuta'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="bi bi-heart-pulse-fill text-emerald-500"></i> Fisioterapeuta
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="bi bi-person-badge-fill text-blue-500"></i> <?= htmlspecialchars($w['rol']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 text-gray-600 font-medium">
                                    <?= htmlspecialchars($w['email']) ?>
                                </td>
                                <td class="py-4 px-4 text-gray-600 font-medium">
                                    <?= htmlspecialchars($w['telefono'] ?: 'N/D') ?>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="<?= PROJECT_ROOT ?>/terapeutas/detalle?id=<?= $w['usuario_id'] ?>" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all" title="Ver Expediente">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>
                                        <a href="<?= PROJECT_ROOT ?>/nominas?usuario_id=<?= $w['usuario_id'] ?>" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Ver Nóminas">
                                            <i class="bi bi-file-earmark-spreadsheet text-sm"></i>
                                        </a>
                                        <?php if ($isAdmin): ?>
                                            <a href="<?= PROJECT_ROOT ?>/terapeutas/editar?id=<?= $w['usuario_id'] ?>" class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Editar">
                                                <i class="bi bi-pencil-square text-sm"></i>
                                            </a>
                                            <form action="<?= PROJECT_ROOT ?>/terapeutas/eliminar" method="POST" class="inline-block m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este facultativo?');">
                                                <input type="hidden" name="id" value="<?= $w['usuario_id'] ?>">
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
                            <td colspan="6" class="py-12 text-center text-gray-400 italic">
                                <i class="bi bi-people text-4xl block mb-2 text-gray-300"></i>
                                No se encontraron facultativos o empleados registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <?php if ($n_botones_paginacion > 1): ?>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="text-xs text-gray-500 font-medium">
                    Página <span class="font-bold text-gray-900"><?= $pagina ?></span> de <span class="font-bold text-gray-900"><?= $n_botones_paginacion ?></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <?php if ($pagina > 1): ?>
                        <a href="<?= PROJECT_ROOT ?>/terapeutas?pagina=<?= $pagina - 1 ?>&q=<?= urlencode($filtro_busqueda) ?>&rol=<?= urlencode($filtro_rol) ?>" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl text-xs hover:bg-gray-50 transition-colors">
                            Anterior
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $n_botones_paginacion; $i++): ?>
                        <a href="<?= PROJECT_ROOT ?>/terapeutas?pagina=<?= $i ?>&q=<?= urlencode($filtro_busqueda) ?>&rol=<?= urlencode($filtro_rol) ?>" class="w-8 h-8 flex items-center justify-center font-bold text-xs rounded-xl transition-colors <?= $i === $pagina ? 'bg-primary-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($pagina < $n_botones_paginacion): ?>
                        <a href="<?= PROJECT_ROOT ?>/terapeutas?pagina=<?= $pagina + 1 ?>&q=<?= urlencode($filtro_busqueda) ?>&rol=<?= urlencode($filtro_rol) ?>" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl text-xs hover:bg-gray-50 transition-colors">
                            Siguiente
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>