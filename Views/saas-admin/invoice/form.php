<?php include TEMPLATE_DIR . 'header.php'; ?>

<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Emitir Factura SaaS a Clínica</h1>
            <p class="text-gray-500 text-sm mt-0.5">Genera una factura oficial de suscripción B2B emitida a una clínica cliente.</p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/superadmin/facturas" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 px-3.5 py-2 rounded-xl transition-colors">
            <i class="bi bi-arrow-left"></i>
            <span>Volver a Facturas</span>
        </a>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($errorMessage)): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm font-medium flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-rose-500 text-lg"></i>
            <span><?= htmlspecialchars($errorMessage) ?></span>
        </div>
    <?php endif; ?>

    <!-- Invoice Creation Form -->
    <form action="<?= PROJECT_ROOT ?>/superadmin/facturas/crear" method="POST" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Clínica Cliente (Organización Facturada) *</label>
            <select name="cuenta_id" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none font-semibold text-gray-900">
                <option value="">-- Seleccionar Clínica Cliente --</option>
                <?php foreach ($tenants as $t): ?>
                    <option value="<?= $t['cuenta_id'] ?>" <?= ($selectedCuentaId ?? 0) === (int)$t['cuenta_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['nombre_empresa']) ?> (CIF: <?= htmlspecialchars($t['nif_cif']) ?>) - Plan <?= htmlspecialchars($t['plan_suscripcion']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Concepto de la Factura *</label>
                <input type="text" name="concepto" required value="Suscripción Mensual Velion SaaS - Plan Profesional" placeholder="Ej: Suscripción Mensual Velion SaaS" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Plan de Suscripción Facturado</label>
                <select name="plan_suscripcion" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none font-medium">
                    <option value="Basico">Plan Básico (29.00 €/mes)</option>
                    <option value="Profesional" selected>Plan Profesional (79.00 €/mes)</option>
                    <option value="Premium">Plan Premium (199.00 €/mes)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Base Imponible (€) *</label>
                <input type="number" step="0.01" name="base_imponible" id="base_imponible" required value="79.00" oninput="calculateTotal()" class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Tipo IVA (%)</label>
                <input type="number" step="0.01" name="tipo_iva" id="tipo_iva" value="21.00" oninput="calculateTotal()" class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Total Factura con IVA (€)</label>
                <input type="text" id="total_factura" readonly value="95.59 €" class="w-full px-3.5 py-2 bg-gray-100 border border-gray-200 rounded-xl text-xs font-black text-primary-600 focus:outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Fecha de Emisión</label>
                <input type="date" name="fecha_emision" value="<?= date('Y-m-d') ?>" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Fecha de Vencimiento</label>
                <input type="date" name="fecha_vencimiento" value="<?= date('Y-m-d', strtotime('+15 days')) ?>" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Estado de Pago Inicial</label>
                <select name="estado" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none font-semibold">
                    <option value="Pendiente" selected>Pendiente</option>
                    <option value="Pagada">Pagada</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Notas Internas o Instrucciones de Pago</label>
            <textarea name="notas" rows="2" placeholder="Ej: Pago procesado vía recibo domiciliado / Tarjeta crédito." class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none"></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="<?= PROJECT_ROOT ?>/superadmin/facturas" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-primary-600/30 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                <i class="bi bi-file-earmark-plus mr-1"></i> Emitir Factura B2B
            </button>
        </div>
    </form>
</div>

<script>
    function calculateTotal() {
        const base = parseFloat(document.getElementById('base_imponible').value) || 0;
        const iva = parseFloat(document.getElementById('tipo_iva').value) || 0;
        const cuotaIva = base * (iva / 100);
        const total = base + cuotaIva;
        document.getElementById('total_factura').value = total.toFixed(2) + ' €';
    }
    calculateTotal();
</script>

<?php include TEMPLATE_DIR . 'footer.php'; ?>