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
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Directorio de Clientes</h1>
        </div>
        <a href="<?= PROJECT_ROOT ?>/superadmin/clientes/crear" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-2xl font-semibold text-sm shadow-md transition-all cursor-pointer">
            <i class="bi bi-plus-lg"></i>
            <span>Alta de Clínica</span>
        </a>
    </div>

    <!-- Tenants Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="tenantSearch" onkeyup="filterTenants()" placeholder="Buscar por empresa, NIF o email admin..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
            </div>
            <div class="text-xs text-gray-500 font-medium">
                Total registrado: <span class="font-bold text-gray-900"><?= count($tenants) ?></span> clientes
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="tenantsTable">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold">ID</th>
                        <th class="py-3 px-4 font-semibold">Empresa</th>
                        <th class="py-3 px-4 font-semibold">Contacto</th>
                        <th class="py-3 px-4 font-semibold">Plan Actual</th>
                        <th class="py-3 px-4 font-semibold">Estado</th>
                        <th class="py-3 px-4 font-semibold">Usuarios / Citas</th>
                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($tenants)): ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">No hay clientes registrados en el sistema.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tenants as $t): ?>
                            <tr class="hover:bg-gray-50/80 transition-colors tenant-row">
                                <td class="py-4 px-4 font-bold text-gray-400">
                                    #<?= $t['cuenta_id'] ?>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($t['nombre_empresa']) ?></div>
                                    <div class="text-gray-500 text-[11px]">
                                        CIF: <span class="font-mono"><?= htmlspecialchars($t['nif_cif']) ?></span>
                                        • Slug: <span class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded font-mono text-[10px]"><?= htmlspecialchars($t['slug']) ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-700">
                                    <a href="mailto:<?= htmlspecialchars($t['email_admin']) ?>" class="hover:text-primary-600 hover:underline">
                                        <?= htmlspecialchars($t['email_admin']) ?>
                                    </a>
                                </td>
                                <td class="py-4 px-4">
                                    <form action="<?= PROJECT_ROOT ?>/superadmin/clientes/plan" method="POST" class="inline-flex items-center gap-1.5">
                                        <input type="hidden" name="cuenta_id" value="<?= $t['cuenta_id'] ?>">
                                        <select name="plan_suscripcion" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-primary-500/20 cursor-pointer">nter">
                                            <option value="Basico" <?= $t['plan_suscripcion'] === 'Basico' ? 'selected' : '' ?>>Básico (29€)</option>
                                            <option value="Profesional" <?= $t['plan_suscripcion'] === 'Profesional' ? 'selected' : '' ?>>Profesional (79€)</option>
                                            <option value="Premium" <?= $t['plan_suscripcion'] === 'Premium' ? 'selected' : '' ?>>Premium (199€)</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-4 px-4">
                                    <?php if ($t['estado_cuenta'] === 'Activo'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Activo
                                        </span>
                                    <?php elseif ($t['estado_cuenta'] === 'Suspendido'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Suspendido
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Cancelado
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 text-gray-600 font-medium">
                                    <div><span class="font-bold text-gray-900"><?= $t['total_usuarios'] ?></span> usuarios</div>
                                    <div class="text-[11px] text-gray-400"><?= $t['total_citas'] ?> citas totales</div>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <form action="<?= PROJECT_ROOT ?>/superadmin/clientes/estado" method="POST" class="inline-block">
                                        <input type="hidden" name="cuenta_id" value="<?= $t['cuenta_id'] ?>">
                                        <?php if ($t['estado_cuenta'] === 'Activo'): ?>
                                            <input type="hidden" name="estado_cuenta" value="Suspendido">
                                            <button type="submit" onclick="return confirm('¿Suspender la cuenta cliente <?= htmlspecialchars($t['nombre_empresa']) ?>?')" class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg text-xs font-semibold transition-colors cursor-pointer" title="Suspender Cuenta">
                                                <i class="bi bi-pause-circle"></i> Suspender
                                            </button>
                                        <?php else: ?>
                                            <input type="hidden" name="estado_cuenta" value="Activo">
                                            <button type="submit" class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold transition-colors cursor-pointer" title="Activar Cuenta">
                                                <i class="bi bi-play-circle"></i> Activar
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function filterTenants() {
        const input = document.getElementById('tenantSearch');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('.tenant-row');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }
</script>

<?php include TEMPLATE_DIR . 'footer.php'; ?>