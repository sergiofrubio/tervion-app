<?php
$pageTitle = "Cuadro de Mando Contable";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Contabilidad y Fiscalidad</h1>
            <p class="mt-1 text-sm text-gray-500">Gestión de balances, cuenta de pérdidas y ganancias, y obligaciones fiscales.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 flex flex-wrap gap-2">
            <!-- <a href="<?= PROJECT_ROOT ?>/facturas" class="inline-flex items-center gap-2 rounded-xl bg-white border border-gray-200 text-gray-700 px-4 py-2.5 text-sm font-semibold shadow-sm hover:bg-gray-50 transition-all hover:scale-105 active:scale-95">
                <i class="bi bi-file-earmark-text text-primary-600"></i>
                Facturas Emitidas
            </a> -->
            <a href="<?= PROJECT_ROOT ?>/contabilidad/gastos" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-gray-800 transition-all hover:scale-105 active:scale-95">
                <i class="bi bi-receipt"></i>
                Libro de Gastos
            </a>
            <a href="<?= PROJECT_ROOT ?>/contabilidad/impuestos" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-primary-700 transition-all hover:scale-105 active:scale-95">
                <i class="bi bi-calculator"></i>
                Modelos Impuestos (303/130/111)
            </a>
        </div>
    </div>

    <!-- Mensajes de Estado (Notificaciones de Automatización) -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm font-bold flex items-center gap-2 animate-fade-in-up">
            <i class="bi bi-check-circle-fill text-lg"></i>
            <?= $_SESSION['flash_success'];
            unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="p-4 rounded-2xl bg-rose-50 text-rose-800 border border-rose-200 text-sm font-bold flex items-center gap-2 animate-fade-in-up">
            <i class="bi bi-exclamation-triangle-fill text-lg"></i>
            <?= $_SESSION['flash_error'];
            unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <!-- Automatización Contable en 1 Clic (Sin Intervención Manual) -->
    <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center gap-2 text-indigo-700">
                    <i class="bi bi-lightning-charge-fill text-xl"></i>
                    <h3 class="font-bold text-gray-900">Lector de Facturas Inteligente (OCR / IA)</h3>
                </div>
                <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                    Sube las facturas de tus proveedores en formato PDF o imagen. El sistema extraerá automáticamente el NIF, el nombre del proveedor, las bases imponibles y pre-categorizará el gasto sin que teclees nada.
                </p>
            </div>
            <form action="<?= PROJECT_ROOT ?>/contabilidad/gastos/auto-import" method="POST" enctype="multipart/form-data" class="space-y-3">
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-primary-500 hover:bg-white transition-all cursor-pointer relative group">
                    <input type="file" name="invoice_file" id="invoice_file" onchange="this.form.submit()" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <i class="bi bi-cloud-arrow-up text-2xl text-gray-400 group-hover:text-primary-500 transition-colors"></i>
                    <p class="text-xs font-bold text-gray-600 mt-1">Arrastra tu factura aquí o haz clic para subir</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Soporta PDF, PNG, JPG (Ej: Factura_Iberdrola.pdf)</p>
                </div>
            </form>
        </div>

        <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center gap-2 text-emerald-700">
                    <i class="bi bi-bank2 text-xl"></i>
                    <h3 class="font-bold text-gray-900">Conciliación Bancaria Automática</h3>
                </div>
                <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                    Tervion se conecta a tus cuentas bancarias. Analiza los cargos y abonos del extracto, concilia los cobros de tus pacientes de forma automática y crea los gastos correspondientes aplicando reglas fiscales de deducibilidad.
                </p>
            </div>
            <form action="<?= PROJECT_ROOT ?>/contabilidad/banco/importar" method="POST">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-md hover:bg-emerald-700 transition-all hover:scale-[1.01] active:scale-95">
                    <i class="bi bi-arrow-repeat"></i>
                    Sincronizar y Conciliar Banco Ahora
                </button>
            </form>
        </div>
    </div> -->

    <!-- Filters & Actions -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form action="<?= PROJECT_ROOT ?>/contabilidad" method="GET" class="flex items-center gap-3">
            <label for="anio" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Año Fiscal</label>
            <select name="anio" id="anio" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm bg-white font-bold text-gray-700">
                <?php
                $currentYear = date('Y');
                for ($y = $currentYear - 2; $y <= $currentYear + 1; $y++) {
                    $selected = ($y == $year) ? 'selected' : '';
                    echo "<option value='$y' $selected>$y</option>";
                }
                ?>
            </select>
        </form>

        <div class="flex flex-wrap gap-2">
            <a href="<?= PROJECT_ROOT ?>/facturas" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gray-100 text-gray-700 font-bold text-xs hover:bg-gray-200 transition-all border border-gray-200">
                <i class="bi bi-file-earmark-text text-primary-600"></i> Facturas Emitidas
            </a>
            <a href="<?= PROJECT_ROOT ?>/contabilidad/exportar/emitidas?anio=<?= $year ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-50 text-blue-700 font-bold text-xs hover:bg-blue-100 transition-all">
                <i class="bi bi-file-earmark-spreadsheet"></i> Libro Emitidas (CSV)
            </a>
            <a href="<?= PROJECT_ROOT ?>/contabilidad/exportar/recibidas?anio=<?= $year ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-green-50 text-green-700 font-bold text-xs hover:bg-green-100 transition-all">
                <i class="bi bi-file-earmark-spreadsheet"></i> Libro Recibidas (CSV)
            </a>
        </div>
    </div>

    <!-- Financial KPI Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Ingresos -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm group">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ingresos Brutos</p>
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-arrow-up-right text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900 mt-2 tracking-tight"><?= number_format($report['ingresos'], 2, ',', '.') ?> €</p>
            <p class="text-xs text-gray-500 mt-2">Facturación total expedida</p>
        </div>

        <!-- Gastos -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm group">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Gastos Deducibles</p>
                <div class="p-2 bg-red-50 text-red-600 rounded-xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-arrow-down-left text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900 mt-2 tracking-tight"><?= number_format($report['total_gastos'], 2, ',', '.') ?> €</p>
            <p class="text-xs text-gray-500 mt-2">Compras, suministros y personal</p>
        </div>

        <!-- Margen de Explotación (EBITDA) -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm group">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rendimiento Neto</p>
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-graph-up text-lg"></i>
                </div>
            </div>
            <?php $colorClass = $report['beneficio_antes_impuestos'] >= 0 ? 'text-indigo-600' : 'text-rose-600'; ?>
            <p class="text-3xl font-black <?= $colorClass ?> mt-2 tracking-tight"><?= number_format($report['beneficio_antes_impuestos'], 2, ',', '.') ?> €</p>
            <p class="text-xs text-gray-500 mt-2">Beneficio antes de impuestos</p>
        </div>

        <!-- Impuestos Directos Estimados -->
        <div class="bg-gradient-to-br from-indigo-900 to-indigo-950 p-6 rounded-3xl text-white shadow-md relative overflow-hidden">
            <i class="bi bi-percent absolute -bottom-6 -right-6 text-7xl text-white/5 rotate-12"></i>
            <p class="text-xs font-bold opacity-75 uppercase tracking-wider">Sociedades Est. (25%)</p>
            <p class="text-3xl font-black mt-2 tracking-tight"><?= number_format($report['impuesto_sociedades_est'], 2, ',', '.') ?> €</p>
            <p class="text-xs opacity-75 mt-2">Para S.L. (Autónomo tributa IRPF)</p>
        </div>
    </div>

    <!-- P&L details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cuenta de Pérdidas y Ganancias (P&L) -->
        <div class="lg:col-span-2 space-y-4">
            <h3 class="text-xl font-bold text-gray-900 tracking-tight">Cuenta de Pérdidas y Ganancias (P&L) - <?= $year ?></h3>
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                    <span class="text-sm font-bold text-gray-700">Conceptos Contables</span>
                    <span class="text-sm font-bold text-gray-700">Importe (€)</span>
                </div>
                <div class="divide-y divide-gray-50 text-sm">
                    <!-- Ingresos -->
                    <div class="p-5 flex justify-between items-center bg-emerald-50/20">
                        <div class="font-bold text-emerald-800 flex items-center gap-2">
                            <i class="bi bi-plus-circle-fill"></i> (+) INGRESOS DE EXPLOTACIÓN (Ventas/Citas)
                        </div>
                        <span class="font-black text-emerald-700"><?= number_format($report['ingresos'], 2, ',', '.') ?> €</span>
                    </div>

                    <!-- Gastos de Personal -->
                    <div class="p-5 flex justify-between items-center hover:bg-gray-50/50 transition-colors">
                        <div class="text-gray-700 font-medium pl-6">
                            (-) Gastos de Personal (Nóminas y SS Empresa)
                        </div>
                        <span class="text-gray-900 font-semibold"><?= number_format($report['gastos_detalle']['Personal'], 2, ',', '.') ?> €</span>
                    </div>

                    <!-- Alquileres -->
                    <div class="p-5 flex justify-between items-center hover:bg-gray-50/50 transition-colors">
                        <div class="text-gray-700 font-medium pl-6">
                            (-) Arrendamientos y Cánones (Alquiler de local)
                        </div>
                        <span class="text-gray-900 font-semibold"><?= number_format($report['gastos_detalle']['Alquileres'], 2, ',', '.') ?> €</span>
                    </div>

                    <!-- Suministros -->
                    <div class="p-5 flex justify-between items-center hover:bg-gray-50/50 transition-colors">
                        <div class="text-gray-700 font-medium pl-6">
                            (-) Suministros (Luz, Agua, Internet)
                        </div>
                        <span class="text-gray-900 font-semibold"><?= number_format($report['gastos_detalle']['Suministros'], 2, ',', '.') ?> €</span>
                    </div>

                    <!-- Servicios Profesionales -->
                    <div class="p-5 flex justify-between items-center hover:bg-gray-50/50 transition-colors">
                        <div class="text-gray-700 font-medium pl-6">
                            (-) Servicios Profesionales Externos (Gestoría, Limpieza)
                        </div>
                        <span class="text-gray-900 font-semibold"><?= number_format($report['gastos_detalle']['Servicios profesionales'], 2, ',', '.') ?> €</span>
                    </div>

                    <!-- Bienes de inversión -->
                    <div class="p-5 flex justify-between items-center hover:bg-gray-50/50 transition-colors">
                        <div class="text-gray-700 font-medium pl-6">
                            (-) Amortización / Bienes de Inversión (Aparatos médicos)
                        </div>
                        <span class="text-gray-900 font-semibold"><?= number_format($report['gastos_detalle']['Bienes de inversión'], 2, ',', '.') ?> €</span>
                    </div>

                    <!-- Otros Gastos -->
                    <div class="p-5 flex justify-between items-center hover:bg-gray-50/50 transition-colors">
                        <div class="text-gray-700 font-medium pl-6">
                            (-) Otros Gastos de Explotación
                        </div>
                        <span class="text-gray-900 font-semibold"><?= number_format($report['gastos_detalle']['Otros'], 2, ',', '.') ?> €</span>
                    </div>

                    <!-- EBITDA -->
                    <div class="p-5 flex justify-between items-center bg-gray-50 font-bold border-t border-gray-200">
                        <div class="text-gray-900 flex items-center gap-2">
                            (=) RESULTADO ANTES DE IMPUESTOS (Rendimiento Neto)
                        </div>
                        <span class="text-gray-900 font-black text-base"><?= number_format($report['beneficio_antes_impuestos'], 2, ',', '.') ?> €</span>
                    </div>

                    <!-- Impuesto sociedades -->
                    <div class="p-5 flex justify-between items-center hover:bg-gray-50/50 transition-colors text-gray-500">
                        <div class="pl-6">
                            (-) Impuesto sobre Sociedades Estimado
                        </div>
                        <span><?= number_format($report['impuesto_sociedades_est'], 2, ',', '.') ?> €</span>
                    </div>

                    <!-- Net profit -->
                    <div class="p-5 flex justify-between items-center bg-primary-50/30 font-bold border-t border-primary-100">
                        <div class="text-primary-950 flex items-center gap-2">
                            (=) RESULTADO NETO ESTIMADO (Para S.L.)
                        </div>
                        <span class="text-primary-700 font-black text-lg"><?= number_format($report['beneficio_neto_est'], 2, ',', '.') ?> €</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorías de Gastos -->
        <div class="space-y-4">
            <h3 class="text-xl font-bold text-gray-900 tracking-tight">Distribución de Gastos</h3>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                <?php if ($report['total_gastos'] <= 0) : ?>
                    <p class="text-xs text-gray-500 italic text-center py-12">No hay gastos registrados para este año.</p>
                <?php else : ?>
                    <?php
                    $colors = [
                        'Personal' => 'bg-amber-500',
                        'Alquileres' => 'bg-blue-500',
                        'Suministros' => 'bg-indigo-500',
                        'Servicios profesionales' => 'bg-purple-500',
                        'Bienes de inversión' => 'bg-rose-500',
                        'Otros' => 'bg-gray-400'
                    ];
                    foreach ($report['gastos_detalle'] as $cat => $val) :
                        if ($val <= 0) continue;
                        $pct = ($val / $report['total_gastos']) * 100;
                        $color = $colors[$cat] ?? 'bg-gray-400';
                    ?>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs font-bold text-gray-600 uppercase tracking-wide">
                                <span><?= $cat ?></span>
                                <span><?= number_format($val, 2, ',', '.') ?> € (<?= number_format($pct, 1) ?>%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="<?= $color ?> h-2 rounded-full" style="width: <?= $pct ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- <div class="bg-gradient-to-br from-indigo-900 to-indigo-950 rounded-3xl p-6 text-white shadow-md relative overflow-hidden">
                <h4 class="font-bold mb-2">Información Contable</h4>
                <p class="text-xs leading-relaxed opacity-85">
                    Como S.L., estás obligado a depositar las <strong>Cuentas Anuales</strong> (Balance y P&L) en el Registro Mercantil.
                    Si eres Autónomo, debes registrar todas las operaciones en los <strong>Libros Registro Oficiales de la AEAT</strong> y declarar trimestralmente el pago fraccionado del IRPF (Modelo 130).
                </p>
            </div> -->
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