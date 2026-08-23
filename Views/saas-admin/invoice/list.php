<?php include TEMPLATE_DIR . 'header.php'; ?>

<div class="space-y-6">
    <!-- Feedback Alerts -->
    <?php if (!empty($statusMessage)): ?>
        <div class="p-4 rounded-2xl border flex items-center justify-between text-sm font-medium <?= $statusMessage['type'] === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200' ?>">
            <div class="flex items-center gap-2">
                <i class="bi <?= $statusMessage['type'] === 'success' ? 'bi-check-circle-fill text-emerald-500' : 'bi-exclamation-triangle-fill text-rose-500' ?> text-lg"></i>
                <span><?= htmlspecialchars($statusMessage['text']) ?></span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Facturación B2B</h1>
            <p class="text-gray-500 text-sm mt-0.5">Consulta, emisión y control de cobro de facturas por servicios de suscripción SaaS.</p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/superadmin/facturas/crear" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-2xl font-semibold text-sm shadow-md transition-all cursor-pointer">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Factura</span>
        </a>
    </div>

    <!-- Invoices Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Table Card Header & Filters -->
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <form method="GET" action="<?= PROJECT_ROOT ?>/superadmin/facturas" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 max-w-2xl">
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="q" value="<?= htmlspecialchars($filters['q'] ?? '') ?>" placeholder="Nº Factura, empresa, CIF, concepto..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    </div>
                    <select name="estado" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                        <option value="">-- Todos los Estados --</option>
                        <option value="Pagada" <?= ($filters['estado'] ?? '') === 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                        <option value="Pendiente" <?= ($filters['estado'] ?? '') === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="Vencida" <?= ($filters['estado'] ?? '') === 'Vencida' ? 'selected' : '' ?>>Vencida</option>
                        <option value="Cancelada" <?= ($filters['estado'] ?? '') === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                    </select>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-1.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-xl text-xs shadow-sm transition-all cursor-pointer shrink-0">
                            <i class="bi bi-funnel"></i>
                            <span>Filtrar</span>
                        </button>
                        <a href="<?= PROJECT_ROOT ?>/superadmin/facturas" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-semibold transition-colors shrink-0" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>
                <div class="text-xs text-gray-500 font-medium whitespace-nowrap">
                    Total registrado: <span class="font-bold text-gray-900"><?= count($invoices) ?></span> facturas
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold">Nº Factura</th>
                        <th class="py-3 px-4 font-semibold">Clínica</th>
                        <th class="py-3 px-4 font-semibold">Concepto</th>
                        <th class="py-3 px-4 font-semibold">Fecha Emisión</th>
                        <th class="py-3 px-4 font-semibold">Base Imponible</th>
                        <th class="py-3 px-4 font-semibold">Total (+21% IVA)</th>
                        <th class="py-3 px-4 font-semibold">Estado</th>
                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">No se encontraron facturas SaaS emitidas con los filtros seleccionados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($invoices as $inv): ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-4 font-bold text-gray-900 font-mono">
                                    <?= htmlspecialchars($inv['serie']) ?>-<?= sprintf('%04d', $inv['numero']) ?>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($inv['nombre_empresa']) ?></div>
                                    <div class="text-[11px] text-gray-400 font-mono">CIF: <?= htmlspecialchars($inv['nif_cif']) ?></div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($inv['concepto']) ?></div>
                                    <span class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded text-[10px]">Plan <?= htmlspecialchars($inv['plan_suscripcion']) ?></span>
                                </td>
                                <td class="py-4 px-4 text-gray-500 font-medium">
                                    <?= date('d/m/Y', strtotime($inv['fecha_emision'])) ?>
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-700 font-mono">
                                    <?= number_format($inv['base_imponible'], 2, ',', '.') ?> €
                                </td>
                                <td class="py-4 px-4 font-black text-gray-900 font-mono">
                                    <?= number_format($inv['total'], 2, ',', '.') ?> €
                                </td>
                                <td class="py-4 px-4">
                                    <form action="<?= PROJECT_ROOT ?>/superadmin/facturas/estado" method="POST" class="inline-block">
                                        <input type="hidden" name="factura_saas_id" value="<?= $inv['factura_saas_id'] ?>">
                                        <select name="estado" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs font-bold focus:ring-2 focus:ring-primary-500/20 cursor-pointer <?= $inv['estado'] === 'Pagada' ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-amber-700 bg-amber-50 border-amber-200' ?>">
                                            <option value="Pagada" <?= $inv['estado'] === 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                                            <option value="Pendiente" <?= $inv['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                            <option value="Vencida" <?= $inv['estado'] === 'Vencida' ? 'selected' : '' ?>>Vencida</option>
                                            <option value="Cancelada" <?= $inv['estado'] === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <a href="<?= PROJECT_ROOT ?>/superadmin/facturas/pdf?id=<?= $inv['factura_saas_id'] ?>" target="_blank" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all" title="Descargar PDF">
                                        <i class="bi bi-file-earmark-pdf text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>