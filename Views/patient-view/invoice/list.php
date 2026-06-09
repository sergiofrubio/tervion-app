<?php
$pageTitle = "Mis Facturas - Velion";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-8 animate-fade-in pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Mis Facturas</h1>
            <p class="text-gray-500 text-lg">Historial de servicios y pagos realizados.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white px-4 py-2 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="bi bi-receipt text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Total Facturas</p>
                    <p class="text-lg font-black text-gray-900"><?= count($facturas) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-indigo-50 border border-indigo-100 rounded-3xl p-6 flex flex-col md:flex-row items-center gap-6">
        <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center flex-shrink-0 shadow-lg shadow-indigo-200">
            <i class="bi bi-info-circle-fill text-xl"></i>
        </div>
        <div class="space-y-1">
            <p class="text-indigo-900 font-bold">Información Verifactu</p>
            <p class="text-indigo-700 text-sm leading-relaxed">
                Todas tus facturas se emiten cumpliendo la normativa **Verifactu**. Puedes verificar la autenticidad de cada factura escaneando el código QR que encontrarás en el PDF descargable.
            </p>
        </div>
    </div>

    <!-- Invoices List -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Nº Factura</th>
                        <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Fecha</th>
                        <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Concepto</th>
                        <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Estado</th>
                        <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Total</th>
                        <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($facturas)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-300">
                                        <i class="bi bi-receipt-cutoff text-3xl"></i>
                                    </div>
                                    <p class="text-gray-400 italic">No tienes facturas disponibles en este momento.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($facturas as $f): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-5">
                                    <span class="text-sm font-bold text-gray-900"><?= $f['serie'] ?>-<?= str_pad($f['numero'], 6, '0', STR_PAD_LEFT) ?></span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-700"><?= date('d/m/Y', strtotime($f['fecha_emision'])) ?></span>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-tighter"><?= date('H:i', strtotime($f['fecha_hora_emision'])) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm text-gray-600"><?= htmlspecialchars($f['descripcion']) ?></span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php if ($f['estado'] === 'Pagada'): ?>
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-bold text-green-700 ring-1 ring-inset ring-green-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                            Pagada
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                            Pendiente
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <span class="text-sm font-black text-gray-900"><?= number_format($f['total'], 2, ',', '.') ?> €</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <a href="<?= PROJECT_ROOT ?>/facturas/pdf?id=<?= $f['factura_id'] ?>" 
                                       target="_blank"
                                       class="inline-flex items-center gap-2 bg-gray-50 text-gray-600 px-4 py-2 rounded-xl font-bold text-xs transition-all hover:bg-primary-600 hover:text-white hover:scale-105 active:scale-95 shadow-sm">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                        Descargar PDF
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Help Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex items-start gap-6">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-question-circle text-2xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="font-bold text-gray-900">¿Tienes dudas sobre una factura?</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Si encuentras algún error o necesitas una factura rectificativa, por favor ponte en contacto con administración.
                </p>
                <a href="mailto:administracion@velion.com" class="text-sm font-bold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1 transition-all hover:gap-2">
                    Contactar con soporte <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex items-start gap-6">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-shield-check text-2xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="font-bold text-gray-900">Pagos Seguros</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Aceptamos pagos con tarjeta, transferencia bancaria y efectivo en clínica. Todos tus datos están protegidos.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
