<?php
$pageTitle = "Detalle de Nómina";
include TEMPLATE_DIR . 'header.php';
$meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
?>

<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="<?= PROJECT_ROOT ?>/nominas" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-gray-900 transition-all shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Recibo de Salarios</h1>
        </div>
        <a href="<?= PROJECT_ROOT ?>/nominas/pdf?id=<?= $nomina['nomina_id'] ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
            <i class="bi bi-file-earmark-pdf"></i>
            Imprimir PDF
        </a>
    </div>

    <!-- Payroll Document UI -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-2xl overflow-hidden divide-y divide-gray-100">
        <!-- Header Section -->
        <div class="p-8 bg-gray-50/50">
            <div class="flex flex-col md:flex-row justify-between gap-8">
                <div class="space-y-2">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Empresa</h2>
                    <p class="text-lg font-bold text-gray-900">Tervion Physiotherapy Clinic S.L.</p>
                    <p class="text-sm text-gray-500">CIF: B12345678<br>Calle Falsa 123, 28001 Madrid</p>
                </div>
                <div class="space-y-2 text-right">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Trabajador</h2>
                    <p class="text-lg font-bold text-gray-900"><?= $nomina['nombre'] . ' ' . $nomina['apellidos'] ?></p>
                    <p class="text-sm text-gray-500">DNI: <?= $nomina['dni'] ?><br>NSS: <?= $nomina['nss'] ?? 'N/A' ?></p>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase">Periodo</p>
                    <p class="text-sm font-semibold text-gray-900"><?= $meses[$nomina['mes']] ?> <?= $nomina['anio'] ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase">Grupo Cotización</p>
                    <p class="text-sm font-semibold text-gray-900"><?= $nomina['grupo_cotizacion'] ?? '1' ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase">IBAN</p>
                    <p class="text-sm font-semibold text-gray-900"><?= $nomina['iban'] ?? 'N/A' ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase">Fecha Emisión</p>
                    <p class="text-sm font-semibold text-gray-900"><?= date('d/m/Y', strtotime($nomina['fecha_emision'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Body Section -->
        <div class="p-0">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-3 text-left text-xs font-bold text-gray-500 uppercase">Concepto</th>
                        <th class="px-8 py-3 text-right text-xs font-bold text-gray-500 uppercase">Devengos</th>
                        <th class="px-8 py-3 text-right text-xs font-bold text-gray-500 uppercase">Deducciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="px-8 py-4 text-sm text-gray-700 font-medium">Salario Base</td>
                        <td class="px-8 py-4 text-sm text-right text-gray-900"><?= number_format($nomina['devengos_base'], 2) ?> €</td>
                        <td class="px-8 py-4 text-sm text-right"></td>
                    </tr>
                    <tr>
                        <td class="px-8 py-4 text-sm text-gray-700 font-medium">Complementos</td>
                        <td class="px-8 py-4 text-sm text-right text-gray-900"><?= number_format($nomina['devengos_complementos'], 2) ?> €</td>
                        <td class="px-8 py-4 text-sm text-right"></td>
                    </tr>
                    <tr>
                        <td class="px-8 py-4 text-sm text-gray-500 italic">Seguridad Social (Contingencias, etc.)</td>
                        <td class="px-8 py-4 text-sm text-right"></td>
                        <td class="px-8 py-4 text-sm text-right text-red-600">- <?= number_format($nomina['deduccion_seguridad_social_trabajador'], 2) ?> €</td>
                    </tr>
                    <tr>
                        <td class="px-8 py-4 text-sm text-gray-500 italic">Retención IRPF</td>
                        <td class="px-8 py-4 text-sm text-right"></td>
                        <td class="px-8 py-4 text-sm text-right text-red-600">- <?= number_format($nomina['deduccion_irpf'], 2) ?> €</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer Section (Totals) -->
        <div class="p-8 bg-gray-900 text-white">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex gap-12">
                    <div class="text-center">
                        <p class="text-xs text-gray-400 uppercase mb-1">Total Bruto</p>
                        <p class="text-xl font-bold"><?= number_format($nomina['devengos_total_bruto'], 2) ?> €</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-400 uppercase mb-1">Total Deducciones</p>
                        <p class="text-xl font-bold text-red-400"><?= number_format($nomina['deducciones_total'], 2) ?> €</p>
                    </div>
                </div>
                <div class="bg-primary-600 px-8 py-4 rounded-2xl text-center">
                    <p class="text-xs text-primary-100 uppercase mb-1 font-bold">Líquido a Percibir</p>
                    <p class="text-3xl font-black"><?= number_format($nomina['liquido_a_percibir'], 2) ?> €</p>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center">
                <p class="text-[10px] text-gray-500 italic">Este documento es un justificante de pago conforme a la normativa laboral española vigente para 2026.</p>
            </div>
        </div>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>