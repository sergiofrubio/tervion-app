<?php
$pageTitle = "Gestión de Facturas";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Facturación</h1>
            <p class="mt-1 text-sm text-gray-500">Listado y gestión de facturas de pacientes.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="<?= PROJECT_ROOT ?>/facturas/create" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                <i class="bi bi-plus-lg"></i>
                Nueva Factura
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                    <i class="bi bi-receipt text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Facturas</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count($facturas) ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-2xl">
                    <i class="bi bi-check2-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pagadas</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?php 
                        echo count(array_filter($facturas, function($f) { return $f['estado'] === 'Pagada'; }));
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl">
                    <i class="bi bi-clock text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?php 
                        echo count(array_filter($facturas, function($f) { return $f['estado'] === 'Pendiente'; }));
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <form action="<?= PROJECT_ROOT ?>/facturas" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="space-y-2">
                <label for="paciente_id" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Filtrar por Paciente</label>
                <select name="paciente_id" id="paciente_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm bg-white">
                    <option value="">Todos los pacientes</option>
                    <?php foreach ($pacientes as $paciente) : ?>
                        <option value="<?= $paciente['usuario_id'] ?>" <?= ($filters['paciente_id'] ?? '') == $paciente['usuario_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="space-y-2">
                <label for="estado" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Estado de Pago</label>
                <select name="estado" id="estado" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm bg-white">
                    <option value="">Cualquier estado</option>
                    <option value="Pagada" <?= ($filters['estado'] ?? '') == 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                    <option value="Pendiente" <?= ($filters['estado'] ?? '') == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="q" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Búsqueda rápida</label>
                <div class="relative">
                    <input type="text" name="q" id="q" value="<?= htmlspecialchars($filters['q'] ?? '') ?>" placeholder="ID factura, nombre..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-primary-600 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-primary-200 hover:scale-[1.02] active:scale-95 transition-all">
                    Filtrar
                </button>
                <?php if (!empty($filters['paciente_id']) || !empty($filters['estado']) || !empty($filters['q'])) : ?>
                    <a href="<?= PROJECT_ROOT ?>/facturas" class="p-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition-all" title="Limpiar filtros">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Factura</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Paciente</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (empty($facturas)) : ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 italic">
                                <i class="bi bi-inbox text-4xl block mb-2 opacity-20"></i>
                                No hay facturas registradas.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($facturas as $factura) : ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= str_pad($factura['factura_id'], 5, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center font-bold text-xs">
                                            <?= substr($factura['nombre'], 0, 1) . substr($factura['apellidos'], 0, 1) ?>
                                        </div>
                                        <?= $factura['nombre'] . ' ' . $factura['apellidos'] ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?= date('d/m/Y', strtotime($factura['fecha_emision'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    <?= number_format($factura['total'], 2, ',', '.') ?> €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($factura['estado'] === 'Pagada') : ?>
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            <i class="bi bi-check-circle-fill mr-1"></i> Pagada
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                            <i class="bi bi-clock-history mr-1"></i> Pendiente
                                        </span>
                                    <?php     endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        <a href="<?= PROJECT_ROOT ?>/facturas/pdf?id=<?= $factura['factura_id'] ?>" target="_blank" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Imprimir PDF">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                        <a href="<?= PROJECT_ROOT ?>/facturas/edit?id=<?= $factura['factura_id'] ?>" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="<?= PROJECT_ROOT ?>/facturas/delete" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta factura?');">
                                            <input type="hidden" name="factura_id" value="<?= $factura['factura_id'] ?>">
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
