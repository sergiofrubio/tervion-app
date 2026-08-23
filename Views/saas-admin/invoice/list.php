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

    <!-- Filters Bar -->
    <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm">
        <form method="GET" action="<?= PROJECT_ROOT ?>/superadmin/facturas" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Buscar Factura</label>
                <input type="text" name="q" value="<?= htmlspecialchars($filters['q'] ?? '') ?>" placeholder="Nº Factura, empresa, CIF, concepto..." class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>

            <!-- <div>
                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Clínica Cliente</label>
                <select name="cuenta_id" class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    <option value="">-- Todas las Clínicas --</option>
                    <?php foreach ($tenants as $t): ?>
                        <option value="<?= $t['cuenta_id'] ?>" <?= (string)($filters['cuenta_id'] ?? '') === (string)$t['cuenta_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['nombre_empresa']) ?> (<?= htmlspecialchars($t['nif_cif']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div> -->

            <div>
                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Estado de Cobro</label>
                <select name="estado" class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    <option value="">-- Todos los Estados --</option>
                    <option value="Pagada" <?= ($filters['estado'] ?? '') === 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                    <option value="Pendiente" <?= ($filters['estado'] ?? '') === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="Vencida" <?= ($filters['estado'] ?? '') === 'Vencida' ? 'selected' : '' ?>>Vencida</option>
                    <option value="Cancelada" <?= ($filters['estado'] ?? '') === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-bold py-1.5 px-3 rounded-xl text-xs transition-colors">
                    Filtrar
                </button>
                <a href="<?= PROJECT_ROOT ?>/superadmin/facturas" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-1.5 px-3 rounded-xl text-xs transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
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
                                <td class="py-3.5 px-4 font-bold text-gray-900 font-mono">
                                    <?= htmlspecialchars($inv['serie']) ?>-<?= sprintf('%04d', $inv['numero']) ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-900"><?= htmlspecialchars($inv['nombre_empresa']) ?></div>
                                    <div class="text-[11px] text-gray-400 font-mono">CIF: <?= htmlspecialchars($inv['nif_cif']) ?></div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($inv['concepto']) ?></div>
                                    <span class="text-[10px] text-gray-400">Plan <?= htmlspecialchars($inv['plan_suscripcion']) ?></span>
                                </td>
                                <td class="py-3.5 px-4 text-gray-500">
                                    <?= date('d/m/Y', strtotime($inv['fecha_emision'])) ?>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-gray-700 font-mono">
                                    <?= number_format($inv['base_imponible'], 2, ',', '.') ?> €
                                </td>
                                <td class="py-3.5 px-4 font-black text-gray-900 font-mono">
                                    <?= number_format($inv['total'], 2, ',', '.') ?> €
                                </td>
                                <td class="py-3.5 px-4">
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
                                <td class="py-3.5 px-4 text-right">
                                    <a href="<?= PROJECT_ROOT ?>/superadmin/facturas/pdf?id=<?= $inv['factura_saas_id'] ?>" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 hover:text-primary-800 hover:underline">
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