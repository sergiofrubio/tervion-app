<?php
$pageTitle = "Gestión de Cobro - Verifactu";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?= PROJECT_ROOT ?>/facturas" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Factura <?= $factura['serie'] ?>-<?= str_pad($factura['numero'], 6, '0', STR_PAD_LEFT) ?></h1>
            <p class="mt-1 text-sm text-gray-500">Gestión de estado de pago (Verifactu: los datos fiscales son inalterables).</p>
        </div>
        <div class="ml-auto">
            <a href="<?= PROJECT_ROOT ?>/facturas/pdf?id=<?= $factura['factura_id'] ?>" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-600 hover:bg-blue-100 transition-all">
                <i class="bi bi-printer"></i>
                Imprimir PDF
            </a>
        </div>
    </div>

    <!-- Info Banner Verifactu -->
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex gap-3 items-start">
        <i class="bi bi-info-circle-fill text-blue-500 mt-0.5"></i>
        <div class="text-sm text-blue-800">
            <p class="font-bold">Registro Encadenado (Verifactu)</p>
            <p>Esta factura ya ha sido registrada y encadenada en el sistema. Según la normativa vigente, no se pueden modificar los conceptos ni los importes. Para correcciones, debe emitir una factura rectificativa.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/facturas/edit" method="POST" class="p-8">
            <input type="hidden" name="factura_id" value="<?= $factura['factura_id'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Conceptos Bloqueados -->
                <div class="space-y-4 md:col-span-2 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Resumen de Facturación</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Paciente</p>
                            <p class="text-sm font-semibold"><?= $factura['nombre'] . ' ' . $factura['apellidos'] ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Fecha</p>
                            <p class="text-sm font-semibold"><?= date('d/m/Y', strtotime($factura['fecha_emision'])) ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Base Imponible</p>
                            <p class="text-sm font-semibold"><?= number_format($factura['precio'], 2, ',', '.') ?> €</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Total Factura</p>
                            <p class="text-sm font-bold text-primary-600"><?= number_format($factura['total'], 2, ',', '.') ?> €</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500">Descripción</p>
                        <p class="text-sm italic text-gray-600">"<?= $factura['descripcion'] ?>"</p>
                    </div>
                </div>

                <!-- Estado (Único campo editable) -->
                <div class="space-y-2 md:col-span-2 max-w-sm mx-auto w-full">
                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="bi bi-credit-card text-primary-500"></i> Estado de Pago
                    </label>
                    <select name="estado" class="w-full rounded-2xl border-gray-200 text-lg font-bold focus:border-primary-500 focus:ring-primary-500 transition-all">
                        <option value="Pendiente" <?= $factura['estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="Pagada" <?= $factura['estado'] == 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                    </select>
                </div>
            </div>

            <div class="mt-10 flex justify-end gap-3">
                <a href="<?= PROJECT_ROOT ?>/facturas" class="px-6 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-all">
                    Volver
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-8 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    <i class="bi bi-save"></i>
                    Actualizar Estado
                </button>
            </div>
        </form>
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
