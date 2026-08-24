<?php
$pageTitle = "Generar Nómina";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center gap-4">
        <a href="<?= PROJECT_ROOT ?>/nominas" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-gray-900 transition-all shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Generar Nueva Nómina</h1>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden p-6 sm:p-8">
        <form method="POST" action="<?= PROJECT_ROOT ?>/nominas/generar" class="space-y-6">
            <div>
                <label for="contrato_id" class="block text-sm font-medium text-gray-700 mb-2">Empleado / Contrato</label>
                <select name="contrato_id" id="contrato_id" required class="block w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-primary-600 sm:text-sm py-3 transition-all">
                    <option value="">Seleccione contrato...</option>
                    <?php foreach ($contratos as $c): ?>
                        <?php if ($c['activo']): ?>
                            <option value="<?= $c['contrato_id'] ?>"><?= $c['nombre'] . ' ' . $c['apellidos'] ?> (Base: <?= $c['salario_base_mensual'] ?> €)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="mes" class="block text-sm font-medium text-gray-700 mb-2">Mes</label>
                    <select name="mes" id="mes" class="block w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-primary-600 sm:text-sm py-3 transition-all">
                        <?php
                        $meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
                        $currentMes = date('n');
                        foreach ($meses as $num => $nombre) {
                            $selected = ($num == $currentMes) ? 'selected' : '';
                            echo "<option value=\"$num\" $selected>$nombre</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="anio" class="block text-sm font-medium text-gray-700 mb-2">Año</label>
                    <select name="anio" id="anio" class="block w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-primary-600 sm:text-sm py-3 transition-all">
                        <?php
                        $currentYear = date('Y');
                        for ($y = $currentYear; $y >= $currentYear - 1; $y--) {
                            echo "<option value=\"$y\">$y</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="bg-blue-50 rounded-xl p-4 flex gap-3 border border-blue-100">
                <i class="bi bi-info-circle-fill text-blue-500 text-lg"></i>
                <div class="text-xs text-blue-700 leading-relaxed">
                    Al generar la nómina se aplicarán automáticamente las retenciones de Seguridad Social (6.5%) e IRPF configurado en el contrato para el año 2026.
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-primary-600 px-6 py-3.5 text-base font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all">
                    <i class="bi bi-calculator"></i>
                    Calcular y Generar Nómina
                </button>
            </div>
        </form>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>