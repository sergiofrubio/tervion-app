<?php
$pageTitle = "Gestión de Nóminas";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Nóminas</h1>
            <p class="mt-1 text-sm text-gray-500">Listado de recibos de salarios emitidos.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <a href="<?= PROJECT_ROOT ?>/nominas/contratos" class="inline-flex justify-center items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all">
                <i class="bi bi-file-earmark-text"></i>
                Gestionar Contratos
            </a>
            <a href="<?= PROJECT_ROOT ?>/nominas/generar" class="inline-flex justify-center items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                <i class="bi bi-plus-lg"></i>
                Generar Nómina
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100">
        <form method="get" action="<?= PROJECT_ROOT ?>/nominas" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="w-full sm:w-auto">
                <label for="mes" class="block text-sm font-medium text-gray-700 mb-1.5">Mes</label>
                <select name="mes" id="mes" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-600 sm:text-sm transition-all">
                    <?php
                    $meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
                    foreach ($meses as $num => $nombre) {
                        $selected = ($num == $mes) ? 'selected' : '';
                        echo "<option value=\"$num\" $selected>$nombre</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <label for="anio" class="block text-sm font-medium text-gray-700 mb-1.5">Año</label>
                <select name="anio" id="anio" class="block w-full rounded-xl border-0 py-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-600 sm:text-sm transition-all">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear; $y >= $currentYear - 2; $y--) {
                        $selected = ($y == $anio) ? 'selected' : '';
                        echo "<option value=\"$y\" $selected>$y</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 transition-all">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white overflow-hidden rounded-2xl border border-gray-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Empleado</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mes/Año</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bruto</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Líquido</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (!empty($nominas)) : ?>
                        <?php foreach ($nominas as $n) : ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <?= $n['nombre'] . ' ' . $n['apellidos'] ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?= $meses[$n['mes']] ?> <?= $n['anio'] ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-semibold">
                                    <?= number_format($n['devengos_total_bruto'], 2) ?> €
                                </td>
                                <td class="px-6 py-4 text-sm text-primary-600 font-bold">
                                    <?= number_format($n['liquido_a_percibir'], 2) ?> €
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?= $n['estado'] === 'Pagada' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' ?>">
                                        <?= $n['estado'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= PROJECT_ROOT ?>/nominas/detalle?id=<?= $n['nomina_id'] ?>" class="text-gray-400 hover:text-blue-600 p-1 rounded-lg transition-colors" title="Ver Detalle">
                                            <i class="bi bi-eye text-lg"></i>
                                        </a>
                                        <a href="<?= PROJECT_ROOT ?>/nominas/pdf?id=<?= $n['nomina_id'] ?>" target="_blank" class="text-gray-400 hover:text-red-600 p-1 rounded-lg transition-colors" title="Descargar PDF">
                                            <i class="bi bi-file-earmark-pdf text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-info-circle text-2xl mb-2 block"></i>
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