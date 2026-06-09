<?php
$pageTitle = "Gestión de Facturas (Verifactu)";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Registro de Facturación</h1>
            <p class="mt-1 text-sm text-gray-500">Sistema adaptado a Verifactu con encadenamiento de registros.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="<?= PROJECT_ROOT ?>/facturas/create" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                <i class="bi bi-plus-lg"></i>
                Nueva Factura
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nº Factura</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Paciente</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha / Tipo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <!-- <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Huella (Hash)</th> -->
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (empty($facturas)) : ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 italic">
                                <i class="bi bi-inbox text-4xl block mb-2 opacity-20"></i>
                                No hay facturas registradas.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($facturas as $factura) : ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    <?= $factura['serie'] ?>-<?= str_pad($factura['numero'], 6, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?= $factura['nombre'] . ' ' . $factura['apellidos'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?= date('d/m/Y', strtotime($factura['fecha_emision'])) ?>
                                    <span class="text-xs text-primary-500 font-semibold block"><?= $factura['tipo_factura'] ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    <?= number_format($factura['total'], 2, ',', '.') ?> €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($factura['estado'] === 'Pagada') : ?>
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            Pagada
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                            Pendiente
                                        </span>
                                    <?php     endif; ?>
                                </td>
                                <!-- <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-gray-400">
                                    <span title="<?= $factura['huella'] ?>"><?= substr($factura['huella'], 0, 8) ?>...</span>
                                </td> -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        <a href="<?= PROJECT_ROOT ?>/facturas/pdf?id=<?= $factura['factura_id'] ?>" target="_blank" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Imprimir PDF">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                        <a href="<?= PROJECT_ROOT ?>/facturas/edit?id=<?= $factura['factura_id'] ?>" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all" title="Gestionar Pago">
                                            <i class="bi bi-cash-stack"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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
