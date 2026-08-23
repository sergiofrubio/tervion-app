<?php
$pageTitle = "Gestión de Contratos";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Contratos Laborales</h1>
            <p class="mt-1 text-sm text-gray-500">Gestión de condiciones salariales del personal.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="<?= PROJECT_ROOT ?>/nominas/contratos/crear" class="inline-flex justify-center items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                <i class="bi bi-plus-lg"></i>
                Nuevo Contrato
            </a>
        </div>
    </div>

    <div class="bg-white overflow-hidden rounded-2xl border border-gray-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Empleado</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Salario Base</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Inicio</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (!empty($contratos)) : ?>
                        <?php foreach ($contratos as $c) : ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <?= $c['nombre'] . ' ' . $c['apellidos'] ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?= $c['tipo_contrato'] ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?= number_format($c['salario_base_mensual'], 2) ?> €
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?= date('d/m/Y', strtotime($c['fecha_inicio'])) ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?= $c['activo'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $c['activo'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="<?= PROJECT_ROOT ?>/nominas/contratos/editar?id=<?= $c['contrato_id'] ?>" class="text-gray-400 hover:text-amber-500 p-1 rounded-lg transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No hay contratos registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>