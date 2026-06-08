<?php
$pageTitle = "Usuarios";
include TEMPLATE_DIR . 'header.php';

$filtro_usuario_id = isset($_POST['usuario_id']) ? trim($_POST['usuario_id']) : '';

// Filter the array if needed
$usuarios_filtrados = [];
if ($filtro_usuario_id !== '') {
    foreach ($users as $u) {
        if (strpos((string)$u['usuario_id'], $filtro_usuario_id) !== false) {
            $usuarios_filtrados[] = $u;
        }
    }
} else {
    $usuarios_filtrados = $users;
}

$articulos_x_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$total_usuarios = count($usuarios_filtrados);
$n_botones_paginacion = ceil($total_usuarios / $articulos_x_pagina);

if ($pagina > $n_botones_paginacion && $n_botones_paginacion > 0) {
    $pagina = 1;
}

$iniciar = ($pagina - 1) * $articulos_x_pagina;
$usuariosPaginados = array_slice($usuarios_filtrados, $iniciar, $articulos_x_pagina);
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Usuarios</h1>
            <p class="mt-1 text-sm text-gray-500">Gestiona los pacientes y usuarios del sistema.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
            <form action="<?= PROJECT_ROOT ?>/usuarios/pdf" method="POST" class="w-full sm:w-auto">
                <input type="hidden" value="pdf" id="action" name="action">
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all">
                    <i class="bi bi-file-earmark-pdf text-red-500 text-lg"></i>
                    Exportar PDF
                </button>
            </form>
            <a href="<?= PROJECT_ROOT ?>/usuarios/create" class="inline-flex justify-center items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                <i class="bi bi-plus-lg"></i>
                Agregar Usuario
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['alert'])): ?>
        <?php 
        $alert_type = $_SESSION['alert']['type'];
        $alert_message = $_SESSION['alert']['message'];
        $bg_color = $alert_type === 'danger' ? 'bg-red-50 text-red-800 border-red-200' : ($alert_type === 'success' ? 'bg-green-50 text-green-800 border-green-200' : 'bg-blue-50 text-blue-800 border-blue-200');
        ?>
        <div class="rounded-lg border p-4 <?= $bg_color ?>" role="alert" x-data="{ show: true }" x-show="show">
            <div class="flex justify-between items-center">
                <div class="text-sm font-medium"><?= $alert_message ?></div>
                <button @click="show = false" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <!-- Filter -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100">
        <form method="post" action="<?= PROJECT_ROOT ?>/usuarios/search" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="w-full sm:w-auto flex-1 max-w-sm">
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1.5">Buscar por ID</label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="bi bi-search text-gray-400"></i>
                    </div>
                    <input type="text" name="usuario_id" id="usuario_id" value="<?= htmlspecialchars($filtro_usuario_id) ?>" class="block w-full rounded-xl border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 transition-all" placeholder="Ej. 123">
                </div>
            </div>
            <div class="w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 transition-all">
                    Buscar
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
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</th>
                        <th scope="col" class="sticky right-0 bg-gray-50/90 backdrop-blur-sm px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider border-l border-gray-100 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.02)]">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (!empty($usuariosPaginados)) : ?>
                        <?php foreach ($usuariosPaginados as $usuario) : ?>
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-500">#<?= $usuario['usuario_id'] ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold mr-3 shadow-sm">
                                            <?= substr($usuario['nombre'], 0, 1) ?>
                                        </div>
                                        <div class="font-medium text-gray-900"><?= $usuario['nombre'] . ' ' . $usuario['apellidos'] ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <a href="mailto:<?= $usuario['email'] ?>" class="hover:text-primary-600 transition-colors flex items-center gap-1.5">
                                        <i class="bi bi-envelope"></i>
                                        <?= $usuario['email'] ?>
                                    </a>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    <a href="tel:<?= $usuario['telefono'] ?>" class="hover:text-primary-600 transition-colors flex items-center gap-1.5">
                                        <i class="bi bi-telephone"></i>
                                        <?= $usuario['telefono'] ?>
                                    </a>
                                </td>
                                <td class="sticky right-0 bg-white group-hover:bg-gray-50/50 px-6 py-4 whitespace-nowrap text-right text-sm font-medium border-l border-gray-100/50 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.02)] transition-colors">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="<?= PROJECT_ROOT ?>/usuarios/detail?usuario_id=<?= $usuario['usuario_id'] ?>" class="text-gray-400 hover:text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200" title="Ver detalle">
                                            <i class="bi bi-eye text-lg"></i>
                                        </a>
                                        <a href="<?= PROJECT_ROOT ?>/usuarios/edit?id=<?= $usuario['usuario_id'] ?>" class="text-gray-400 hover:text-amber-500 hover:bg-amber-50 p-2 rounded-lg transition-all duration-200" title="Editar">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </a>
                                        <form action="<?= PROJECT_ROOT ?>/usuarios/delete" method="POST" class="inline-block m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                            <input type="hidden" name="id" value="<?= $usuario['usuario_id'] ?>">
                                            <button type="submit" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-all duration-200" title="Eliminar">
                                                <i class="bi bi-trash3 text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="bi bi-people text-2xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900">No hay usuarios</h3>
                                    <p class="mt-1 text-sm text-gray-500">No se encontraron usuarios con los filtros aplicados.</p>
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
                        Mostrando <span class="font-semibold text-gray-900"><?= min($iniciar + 1, $total_usuarios) ?></span> a <span class="font-semibold text-gray-900"><?= min($iniciar + $articulos_x_pagina, $total_usuarios) ?></span> de <span class="font-semibold text-gray-900"><?= $total_usuarios ?></span> usuarios
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
