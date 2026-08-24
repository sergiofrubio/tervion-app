<?php
$pageTitle = "Gestión de Nóminas";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Nóminas</h1>
            <p class="text-gray-500 text-sm mt-0.5">Listado de recibos de salarios emitidos.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/nominas/contratos" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-2xl font-semibold text-sm transition-all cursor-pointer">
                <i class="bi bi-file-earmark-text"></i>
                <span>Gestionar Contratos</span>
            </a>
            <a href="<?= PROJECT_ROOT ?>/nominas/generar" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-2xl font-semibold text-sm shadow-md transition-all cursor-pointer">
                <i class="bi bi-plus-lg"></i>
                <span>Generar Nómina</span>
            </a>
        </div>
    </div>

    <!-- Payroll Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Table Card Header & Filters -->
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <form method="get" action="<?= PROJECT_ROOT ?>/nominas" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-1 max-w-md">
                    <select name="mes" id="mes" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                        <?php
                        $meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
                        foreach ($meses as $num => $nombre) {
                            $selected = ($num == $mes) ? 'selected' : '';
                            echo "<option value=\"$num\" $selected>$nombre</option>";
                        }
                        ?>
                    </select>
                    <select name="anio" id="anio" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                        <?php
                        $currentYear = date('Y');
                        for ($y = $currentYear; $y >= $currentYear - 2; $y--) {
                            $selected = ($y == $anio) ? 'selected' : '';
                            echo "<option value=\"$y\" $selected>$y</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-xl text-xs shadow-sm transition-all cursor-pointer shrink-0">
                        <i class="bi bi-funnel"></i>
                        <span>Filtrar</span>
                    </button>
                </div>
                <div class="text-xs text-gray-500 font-medium whitespace-nowrap">
                    Total periodo: <span class="font-bold text-gray-900"><?= count($nominas) ?></span> nóminas
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold">Empleado</th>
                        <th class="py-3 px-4 font-semibold">Mes/Año</th>
                        <th class="py-3 px-4 font-semibold">Bruto</th>
                        <th class="py-3 px-4 font-semibold">Líquido</th>
                        <th class="py-3 px-4 font-semibold">Estado</th>
                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($nominas)) : ?>
                        <?php foreach ($nominas as $n) : ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-4 font-bold text-gray-900 text-sm">
                                    <?= htmlspecialchars($n['nombre'] . ' ' . $n['apellidos']) ?>
                                </td>
                                <td class="py-4 px-4 text-gray-500 font-medium">
                                    <?= $meses[$n['mes']] ?> <?= $n['anio'] ?>
                                </td>
                                <td class="py-4 px-4 text-gray-900 font-bold font-mono">
                                    <?= number_format($n['devengos_total_bruto'], 2, ',', '.') ?> €
                                </td>
                                <td class="py-4 px-4 text-primary-600 font-extrabold font-mono">
                                    <?= number_format($n['liquido_a_percibir'], 2, ',', '.') ?> €
                                </td>
                                <td class="py-4 px-4">
                                    <?php if ($n['estado'] === 'Pagada'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pagada
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Pendiente
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="<?= PROJECT_ROOT ?>/nominas/detalle?id=<?= $n['nomina_id'] ?>" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Ver Detalle">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>
                                        <a href="<?= PROJECT_ROOT ?>/nominas/pdf?id=<?= $n['nomina_id'] ?>" target="_blank" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Descargar PDF">
                                            <i class="bi bi-file-earmark-pdf text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">
                                <i class="bi bi-info-circle text-2xl mb-2 block opacity-30"></i>
                                No se encontraron nóminas para este periodo.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>