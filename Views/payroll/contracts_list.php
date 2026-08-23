<?php
$pageTitle = "Gestión de Contratos";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Contratos Laborales</h1>
            <p class="text-gray-500 text-sm mt-0.5">Gestión de condiciones salariales del personal.</p>
        </div>
        <div>
            <a href="<?= PROJECT_ROOT ?>/nominas/contratos/crear" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-2xl font-semibold text-sm shadow-md transition-all cursor-pointer">
                <i class="bi bi-plus-lg"></i>
                <span>Nuevo Contrato</span>
            </a>
        </div>
    </div>

    <!-- Contracts Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold">Empleado</th>
                        <th class="py-3 px-4 font-semibold">Tipo</th>
                        <th class="py-3 px-4 font-semibold">Salario Base</th>
                        <th class="py-3 px-4 font-semibold">Inicio</th>
                        <th class="py-3 px-4 font-semibold">Estado</th>
                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($contratos)) : ?>
                        <?php foreach ($contratos as $c) : ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-4 font-bold text-gray-900 text-sm">
                                    <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellidos']) ?>
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-600">
                                    <?= htmlspecialchars($c['tipo_contrato']) ?>
                                </td>
                                <td class="py-4 px-4 font-bold text-gray-900 font-mono">
                                    <?= number_format($c['salario_base_mensual'], 2, ',', '.') ?> €
                                </td>
                                <td class="py-4 px-4 text-gray-500 font-medium">
                                    <?= date('d/m/Y', strtotime($c['fecha_inicio'])) ?>
                                </td>
                                <td class="py-4 px-4">
                                    <?php if ($c['activo']): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <a href="<?= PROJECT_ROOT ?>/nominas/contratos/editar?id=<?= $c['contrato_id'] ?>" class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Editar">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">
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