<?php include TEMPLATE_DIR . 'header.php'; ?>

<div class="space-y-6">
    <!-- Header Page Title & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-6 rounded-3xl shadow-xl border border-slate-700/50 text-white">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-500/20 text-primary-300 border border-primary-500/30 text-xs font-semibold uppercase tracking-wider mb-2">
                <i class="bi bi-shield-check text-primary-400"></i> SaaS Control Center
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Panel de Control SaaS Global</h1>
            <p class="text-slate-400 text-sm mt-1">Supervisión general de la plataforma Velion, métricas de negocio e inquilinos.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/superadmin/clientes/create" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-500 text-white px-5 py-2.5 rounded-2xl font-semibold text-sm shadow-lg shadow-primary-600/30 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                <i class="bi bi-plus-lg"></i>
                <span>Alta Nueva Clínica</span>
            </a>
        </div>
    </div>

    <!-- Key Metrics Grid (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card MRR -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between text-gray-500 mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">MRR (Recurrente Mensual)</span>
                    <div class="w-10 h-10 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center font-bold">
                        <i class="bi bi-currency-euro text-lg"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-gray-900 tracking-tight">
                    <?= number_format($metrics['mrr'], 2, ',', '.') ?> €
                </div>
                <div class="flex items-center gap-1.5 text-xs text-primary-600 font-semibold mt-2">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Ingresos recurrentes activos</span>
                </div>
            </div>
        </div>

        <!-- Card Clientes Activos -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between text-gray-500 mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Clientes (Tenants)</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                        <i class="bi bi-building text-lg"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-gray-900 tracking-tight">
                    <?= $metrics['activeTenants'] ?> <span class="text-sm font-medium text-gray-400">/ <?= $metrics['totalTenants'] ?></span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500 mt-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span><?= $metrics['activeTenants'] ?> activos</span>
                    <?php if ($metrics['suspendedTenants'] > 0): ?>
                        <span class="inline-block w-2 h-2 rounded-full bg-amber-500 ml-1"></span>
                        <span><?= $metrics['suspendedTenants'] ?> susp.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Card Usuarios Globales -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between text-gray-500 mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Usuarios Totales</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                        <i class="bi bi-people text-lg"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-gray-900 tracking-tight">
                    <?= number_format($metrics['totalUsers'], 0, ',', '.') ?>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500 mt-2">
                    <span><?= number_format($metrics['totalPatients'], 0, ',', '.') ?> Pacientes</span>
                    <span>•</span>
                    <span><?= number_format($metrics['totalPhysios'], 0, ',', '.') ?> Fisioterapeutas</span>
                </div>
            </div>
        </div>

        <!-- Card Citas Procesadas -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-sky-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between text-gray-500 mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Citas en Sistema</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold">
                        <i class="bi bi-calendar-check text-lg"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-gray-900 tracking-tight">
                    <?= number_format($metrics['totalAppointments'], 0, ',', '.') ?>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-sky-600 font-semibold mt-2">
                    <i class="bi bi-activity"></i>
                    <span>Actividad global alta</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Grid: Planes & Recientes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Distribución de Planes -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-pie-chart text-primary-600"></i> Planes de Suscripción
                    </h3>
                    <span class="text-xs text-gray-400 font-medium">Distribución</span>
                </div>
                <div class="space-y-4 mt-5">
                    <!-- Plan Básico -->
                    <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Plan Básico</h4>
                                <span class="text-xs text-gray-400">29.00 €/mes por clínica</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-black text-gray-900"><?= $planDistribution['Basico'] ?></span>
                            <span class="text-xs text-gray-400 block">clientes</span>
                        </div>
                    </div>

                    <!-- Plan Profesional -->
                    <div class="p-3.5 rounded-2xl bg-primary-50 border border-primary-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-primary-600"></div>
                            <div>
                                <h4 class="text-sm font-semibold text-primary-950">Plan Profesional</h4>
                                <span class="text-xs text-primary-600 font-medium">79.00 €/mes por clínica</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-black text-primary-950"><?= $planDistribution['Profesional'] ?></span>
                            <span class="text-xs text-primary-500 block">clientes</span>
                        </div>
                    </div>

                    <!-- Plan Premium -->
                    <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                            <div>
                                <h4 class="text-sm font-semibold text-amber-950">Plan Premium</h4>
                                <span class="text-xs text-amber-600 font-medium">199.00 €/mes por clínica</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-black text-amber-950"><?= $planDistribution['Premium'] ?></span>
                            <span class="text-xs text-amber-600 block">clientes</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                <a href="<?= PROJECT_ROOT ?>/superadmin/planes" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors inline-flex items-center gap-1">
                    <span>Gestionar todos los planes</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Clientes Recientes -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-buildings text-primary-600"></i> Últimas Clínicas Registradas
                </h3>
                <a href="<?= PROJECT_ROOT ?>/superadmin/clientes" class="text-xs font-semibold text-primary-600 hover:underline">Ver Directorio Completo</a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                            <th class="pb-3 font-semibold">Empresa / Clínica</th>
                            <th class="pb-3 font-semibold">Plan</th>
                            <th class="pb-3 font-semibold">Usuarios</th>
                            <th class="pb-3 font-semibold">Estado</th>
                            <th class="pb-3 font-semibold text-right">Alta</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (empty($recentTenants)): ?>
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400">No hay clientes registrados en la plataforma.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentTenants as $t): ?>
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-3.5 pr-3">
                                        <div class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($t['nombre_empresa']) ?></div>
                                        <div class="text-gray-400 text-[11px]"><?= htmlspecialchars($t['nombre_comercial'] ?? $t['slug']) ?> • CIF: <?= htmlspecialchars($t['nif_cif']) ?></div>
                                    </td>
                                    <td class="py-3.5 px-2">
                                        <?php
                                        $planBadge = match ($t['plan_suscripcion']) {
                                            'Premium' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'Profesional' => 'bg-primary-100 text-primary-800 border-primary-200',
                                            default => 'bg-blue-100 text-blue-800 border-blue-200'
                                        };
                                        ?>
                                        <span class="inline-block px-2.5 py-1 rounded-full border text-[11px] font-semibold <?= $planBadge ?>">
                                            <?= htmlspecialchars($t['plan_suscripcion']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-2 font-semibold text-gray-700">
                                        <?= $t['total_usuarios'] ?> u. (<?= $t['total_pacientes'] ?> pac.)
                                    </td>
                                    <td class="py-3.5 px-2">
                                        <?php if ($t['estado_cuenta'] === 'Activo'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> <?= htmlspecialchars($t['estado_cuenta']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 pl-2 text-right text-gray-400 font-medium">
                                        <?= date('d/m/Y', strtotime($t['fecha_alta'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../Templates/footer.php'; ?>