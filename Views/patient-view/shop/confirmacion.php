<?php
$pageTitle = "¡Compra Confirmada! - Velion";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-2xl mx-auto text-center space-y-10 animate-fade-in pb-16">
    <!-- Animated success checkmark -->
    <div class="relative w-32 h-32 mx-auto flex items-center justify-center bg-green-50 rounded-full border border-green-100 shadow-xl shadow-green-100/50">
        <i class="bi bi-patch-check-fill text-6xl text-green-500 animate-scale-up"></i>
        <!-- Ripple effect -->
        <span class="absolute inset-0 rounded-full bg-green-400/10 animate-ping opacity-75"></span>
    </div>

    <!-- Success Header -->
    <div class="space-y-4">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">¡Muchas Gracias por tu Compra! 🎉</h1>
        <p class="text-gray-500 text-lg max-w-lg mx-auto">
            El pago se ha procesado correctamente. Las sesiones se han añadido de forma inmediata a tu panel de bonos.
        </p>
    </div>

    <!-- Purchase summary detail card -->
    <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-2xl shadow-gray-100/50 text-left relative overflow-hidden">
        <!-- Background shapes -->
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-48 h-48 bg-primary-50 rounded-full blur-2xl"></div>

        <div class="relative z-10 space-y-6">
            <h3 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-4">Detalles de la Transacción</h3>

            <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                <div>
                    <span class="text-gray-400 block font-medium">Bono Adquirido</span>
                    <strong class="text-gray-900 text-base font-bold"><?= htmlspecialchars($bono['nombre']) ?></strong>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">Sesiones Disponibles</span>
                    <strong class="text-gray-900 text-base font-bold"><?= htmlspecialchars($bono['numero_sesiones']) ?> Sesiones</strong>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">Importe Total</span>
                    <strong class="text-primary-600 text-xl font-black"><?= number_format($bono['precio'], 2, ',', '.') ?>€</strong>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">Nº Pedido Redsys</span>
                    <code class="text-gray-800 bg-gray-50 px-2 py-0.5 rounded border border-gray-100 text-xs font-mono font-bold"><?= htmlspecialchars($order ?? 'N/A') ?></code>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">Fecha y Hora</span>
                    <strong class="text-gray-900 font-semibold"><?= date('d/m/Y H:i') ?></strong>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">Estado del Pago</span>
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700 bg-green-50 px-2.5 py-1 rounded-full ring-1 ring-inset ring-green-600/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        Aprobado
                    </span>
                </div>
            </div>

            <!-- Electronic Invoice block (Verifactu) -->
            <?php if (!empty($factura)): ?>
                <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100 space-y-4 mt-4">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                            <i class="bi bi-file-earmark-check-fill text-2xl"></i>
                        </div>
                        <div class="space-y-1 flex-1">
                            <h4 class="font-bold text-gray-900 text-sm">Factura Electrónica Emitida (Sistema Verifactu)</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Tu factura con número <strong class="text-gray-700"><?= htmlspecialchars($factura['serie'] . '-' . $factura['numero']) ?></strong> ha sido generada y firmada digitalmente de acuerdo con la normativa AEAT.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="<?= PROJECT_ROOT ?>/paciente/facturas/pdf?id=<?= $factura['factura_id'] ?>" 
                           target="_blank"
                           class="flex-1 inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition-all hover:scale-[1.01] shadow-lg shadow-blue-100">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                            Descargar Factura PDF
                        </a>
                        <a href="<?= PROJECT_ROOT ?>/paciente/citas" 
                           class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition-all hover:scale-[1.01]">
                            Reservar mi Primera Cita
                            <i class="bi bi-calendar3"></i>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100 flex gap-4 mt-4">
                    <i class="bi bi-exclamation-triangle-fill text-amber-500 text-2xl"></i>
                    <div class="space-y-1">
                        <h4 class="font-bold text-gray-900 text-sm">Facturación en proceso</h4>
                        <p class="text-xs text-gray-500">
                            Estamos generando tu factura electrónica. Podrás descargarla en unos minutos desde tu menú "Mis Facturas".
                        </p>
                        <div class="pt-3">
                            <a href="<?= PROJECT_ROOT ?>/paciente/citas" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition-all">
                                Reservar Cita
                                <i class="bi bi-calendar3"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleUp {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .animate-fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .animate-scale-up {
        animation: scaleUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
