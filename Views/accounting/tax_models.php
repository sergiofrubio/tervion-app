<?php
$pageTitle = "Borradores de Modelos de Impuestos AEAT";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Simulación de Modelos Tributarios (AEAT)</h1>
            <p class="mt-1 text-sm text-gray-500">Borradores de autoliquidación con datos en tiempo real para rellenar las declaraciones en la sede electrónica.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 flex gap-2">
            <a href="<?= PROJECT_ROOT ?>/contabilidad" class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                <i class="bi bi-arrow-left"></i> Volver a Contabilidad
            </a>
        </div>
    </div>

    <!-- Period Selector -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <form action="<?= PROJECT_ROOT ?>/contabilidad/impuestos" method="GET" class="flex flex-wrap items-center gap-6">
            <div class="flex items-center gap-3">
                <label for="trimestre" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Trimestre</label>
                <select name="trimestre" id="trimestre" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm bg-white font-bold text-gray-700">
                    <option value="1" <?= $quarter == 1 ? 'selected' : '' ?>>T1 (Enero - Marzo)</option>
                    <option value="2" <?= $quarter == 2 ? 'selected' : '' ?>>T2 (Abril - Junio)</option>
                    <option value="3" <?= $quarter == 3 ? 'selected' : '' ?>>T3 (Julio - Septiembre)</option>
                    <option value="4" <?= $quarter == 4 ? 'selected' : '' ?>>T4 (Octubre - Diciembre)</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                <label for="anio" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Año</label>
                <select name="anio" id="anio" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm bg-white font-bold text-gray-700">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear - 2; $y <= $currentYear + 1; $y++) {
                        $selected = ($y == $year) ? 'selected' : '';
                        echo "<option value='$y' $selected>$y</option>";
                    }
                    ?>
                </select>
            </div>
        </form>
    </div>

    <!-- Tax Models Carousels / Tab view (Implemented via premium flex cards for simultaneous readability) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- MODELO 303 - IVA -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 bg-gradient-to-br from-indigo-900 to-indigo-950 text-white">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full bg-indigo-500 text-white font-bold text-xs">AEAT</span>
                        <span class="text-xs font-bold opacity-75">Trimestral</span>
                    </div>
                    <h3 class="text-xl font-black mt-2">Modelo 303</h3>
                    <p class="text-xs opacity-75 mt-1">Autoliquidación del Impuesto sobre el Valor Añadido (IVA)</p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">IVA Devengado (Ingresos)</span>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Base Imponible Repercutida:</span>
                            <span class="font-semibold text-gray-900"><?= number_format($modelo303['total_repercutido_base'], 2, ',', '.') ?> €</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Cuota IVA Repercutido:</span>
                            <span class="font-semibold text-gray-950"><?= number_format($modelo303['total_repercutido_cuota'], 2, ',', '.') ?> €</span>
                        </div>
                    </div>

                    <hr class="border-gray-50">

                    <div class="space-y-1">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">IVA Deducible (Gastos)</span>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Base Imponible Soportada:</span>
                            <span class="font-semibold text-gray-900"><?= number_format($modelo303['total_soportado_base'], 2, ',', '.') ?> €</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Cuota IVA Soportado:</span>
                            <span class="font-semibold text-gray-950"><?= number_format($modelo303['total_soportado_cuota'], 2, ',', '.') ?> €</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-800">Resultado del Modelo:</span>
                    <?php 
                    $resIVA = $modelo303['resultado'];
                    $colorIVA = $resIVA >= 0 ? 'text-indigo-600' : 'text-emerald-600';
                    $lblIVA = $resIVA >= 0 ? 'A ingresar' : 'A compensar / devolver';
                    ?>
                    <div class="text-right">
                        <span class="font-black text-lg <?= $colorIVA ?>"><?= number_format($resIVA, 2, ',', '.') ?> €</span>
                        <div class="text-[10px] text-gray-500 font-bold uppercase"><?= $lblIVA ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODELO 130 - IRPF FRACCIONADO -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 bg-gradient-to-br from-amber-800 to-amber-950 text-white">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-600 text-white font-bold text-xs">AEAT</span>
                        <span class="text-xs font-bold opacity-75">Acumulativo Anual</span>
                    </div>
                    <h3 class="text-xl font-black mt-2">Modelo 130</h3>
                    <p class="text-xs opacity-75 mt-1">Pago fraccionado de IRPF para Autónomos (Directa)</p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Acumulado Año (1 Ene - fin T<?= $quarter ?>)</span>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ingresos Computables:</span>
                            <span class="font-semibold text-gray-900"><?= number_format($modelo130['ingresos'], 2, ',', '.') ?> €</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Gastos Deducibles:</span>
                            <span class="font-semibold text-gray-900"><?= number_format($modelo130['gastos'], 2, ',', '.') ?> €</span>
                        </div>
                        <div class="flex justify-between text-sm bg-gray-50 p-2 rounded-lg mt-1">
                            <span class="text-gray-800 font-bold">Rendimiento Neto:</span>
                            <span class="font-black text-gray-950"><?= number_format($modelo130['rendimiento_neto'], 2, ',', '.') ?> €</span>
                        </div>
                    </div>

                    <hr class="border-gray-50">

                    <div class="space-y-1">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cálculo de la Declaración</span>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Pago Fraccionado (20%):</span>
                            <span class="font-semibold text-gray-900"><?= number_format($modelo130['pago_fraccionado'], 2, ',', '.') ?> €</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Pagos anteriores realizados:</span>
                            <span class="font-semibold text-gray-950"><?= number_format($modelo130['pagos_anteriores'], 2, ',', '.') ?> €</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-800">Cuota a Ingresar:</span>
                    <div class="text-right">
                        <span class="font-black text-lg text-amber-700"><?= number_format($modelo130['cuota_ingresar'], 2, ',', '.') ?> €</span>
                        <div class="text-[10px] text-gray-500 font-bold uppercase">A ingresar en AEAT</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODELO 111 - RETENCIONES IRPF -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 bg-gradient-to-br from-emerald-800 to-emerald-950 text-white">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-600 text-white font-bold text-xs">AEAT</span>
                        <span class="text-xs font-bold opacity-75">Trimestral</span>
                    </div>
                    <h3 class="text-xl font-black mt-2">Modelo 111</h3>
                    <p class="text-xs opacity-75 mt-1">Retenciones e Ingresos a Cuenta (Nóminas y Profesionales)</p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rendimientos del Trabajo (Nóminas)</span>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Nº de Perceptores:</span>
                            <span class="font-semibold text-gray-900"><?= $modelo111['trabajo']['perceptores'] ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Base Retenciones:</span>
                            <span class="font-semibold text-gray-900"><?= number_format($modelo111['trabajo']['base'], 2, ',', '.') ?> €</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Importe Retenciones:</span>
                            <span class="font-semibold text-gray-950 text-emerald-800"><?= number_format($modelo111['trabajo']['retenciones'], 2, ',', '.') ?> €</span>
                        </div>
                    </div>

                    <hr class="border-gray-50">

                    <div class="space-y-1">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rendimientos de Actividades Prof.</span>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Nº de Perceptores:</span>
                            <span class="font-semibold text-gray-900"><?= $modelo111['profesionales']['perceptores'] ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Base Retenciones:</span>
                            <span class="font-semibold text-gray-900"><?= number_format($modelo111['profesionales']['base'], 2, ',', '.') ?> €</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Importe Retenciones:</span>
                            <span class="font-semibold text-gray-950 text-emerald-800"><?= number_format($modelo111['profesionales']['retenciones'], 2, ',', '.') ?> €</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-800">Total a Ingresar:</span>
                    <div class="text-right">
                        <span class="font-black text-lg text-emerald-700"><?= number_format($modelo111['total_retenciones'], 2, ',', '.') ?> €</span>
                        <div class="text-[10px] text-gray-500 font-bold uppercase">Retenciones liquidadas</div>
                    </div>
                </div>
            </div>
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
