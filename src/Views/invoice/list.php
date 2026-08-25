<?php
$pageTitle = "Gestión de Facturas";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Facturación</h1>
            <p class="text-gray-500 text-sm mt-0.5">Listado y gestión de facturas de pacientes.</p>
        </div>
        <div>
            <a href="<?= PROJECT_ROOT ?>/facturas/crear" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-2xl font-semibold text-sm shadow-md transition-all cursor-pointer">
                <i class="bi bi-plus-lg"></i>
                <span>Nueva Factura</span>
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                <i class="bi bi-receipt text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Facturas</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-0.5"><?= count($facturas) ?></p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl">
                <i class="bi bi-check2-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pagadas</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-0.5">
                    <?php
                    echo count(array_filter($facturas, function ($f) {
                        return $f['estado'] === 'Pagada';
                    }));
                    ?>
                </p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl">
                <i class="bi bi-clock text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pendientes</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-0.5">
                    <?php
                    echo count(array_filter($facturas, function ($f) {
                        return $f['estado'] === 'Pendiente';
                    }));
                    ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Invoices Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Table Card Header & Filters -->
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <form action="<?= PROJECT_ROOT ?>/facturas" method="GET" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 max-w-2xl">
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="q" id="q" value="<?= htmlspecialchars($filters['q'] ?? '') ?>" placeholder="Buscar por ID, paciente..."
                            onchange="this.form.submit()" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    </div>
                    <select name="estado" id="estado" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                        <option value="">Cualquier estado</option>
                        <option value="Pagada" <?= ($filters['estado'] ?? '') == 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                        <option value="Pendiente" <?= ($filters['estado'] ?? '') == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    </select>
                    <?php if (!empty($filters['paciente_id']) || !empty($filters['estado']) || !empty($filters['q'])) : ?>
                        <a href="<?= PROJECT_ROOT ?>/facturas" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs transition-colors shrink-0" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="text-xs text-gray-500 font-medium whitespace-nowrap">
                    Total registrado: <span class="font-bold text-gray-900"><?= count($facturas) ?></span> facturas
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold">Factura</th>
                        <th class="py-3 px-4 font-semibold">Paciente</th>
                        <th class="py-3 px-4 font-semibold">Fecha</th>
                        <th class="py-3 px-4 font-semibold">Total</th>
                        <th class="py-3 px-4 font-semibold">Estado Pago</th>
                        <th class="py-3 px-4 font-semibold">Verifactu (AEAT)</th>
                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($facturas)) : ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">
                                <i class="bi bi-inbox text-3xl block mb-2 opacity-30"></i>
                                No hay facturas registradas.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($facturas as $factura) : ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-4 font-bold text-gray-900 font-mono">
                                    <?= htmlspecialchars($factura['serie'] ?? 'A') ?>-<?= str_pad($factura['numero'], 5, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="h-7 w-7 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center font-bold text-xs">
                                            <?= substr($factura['nombre'], 0, 1) . substr($factura['apellidos'], 0, 1) ?>
                                        </div>
                                        <span class="font-bold text-gray-900 text-xs"><?= htmlspecialchars($factura['nombre'] . ' ' . $factura['apellidos']) ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-gray-500 font-medium">
                                    <?= date('d/m/Y', strtotime($factura['fecha_emision'])) ?>
                                </td>
                                <td class="py-4 px-4 font-bold text-gray-900 font-mono">
                                    <?= number_format($factura['total'], 2, ',', '.') ?> €
                                </td>
                                <td class="py-4 px-4">
                                    <?php if ($factura['estado'] === 'Pagada') : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pagada
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Pendiente
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4">
                                    <?php
                                    $vfState = $factura['estado_verifactu'] ?? 'Pendiente';
                                    if ($vfState === 'Aceptado' || $vfState === 'AceptadoConErrores') : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200" title="CSV: <?= htmlspecialchars($factura['csv_verifactu'] ?? 'Generado') ?>">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Remitida
                                        </span>
                                    <?php elseif ($vfState === 'Rechazado') : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200" title="<?= htmlspecialchars($factura['mensaje_verifactu'] ?? 'Error AEAT') ?>">
                                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Rechazada
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Pendiente AEAT
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="<?= PROJECT_ROOT ?>/facturas/reenviar?id=<?= $factura['factura_id'] ?>" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Enviar / Reintentar Verifactu AEAT">
                                            <i class="bi bi-send-fill text-sm"></i>
                                        </a>
                                        <a href="<?= PROJECT_ROOT ?>/facturas/pdf?id=<?= $factura['factura_id'] ?>" target="_blank" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Imprimir PDF">
                                            <i class="bi bi-file-earmark-pdf text-sm"></i>
                                        </a>
                                        <a href="<?= PROJECT_ROOT ?>/facturas/editar?id=<?= $factura['factura_id'] ?>" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all" title="Editar">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </a>
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