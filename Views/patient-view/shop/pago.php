<?php
$pageTitle = "Confirmar Compra - Velion";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-2xl mx-auto space-y-8 animate-fade-in pb-16">
    <!-- Breadcrumbs / Back button -->
    <div>
        <a href="<?= PROJECT_ROOT ?>/paciente/tienda" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
            <i class="bi bi-arrow-left"></i> Volver a la tienda
        </a>
    </div>

    <!-- Review Order Card -->
    <div class="bg-white rounded-[2rem] border border-gray-100 p-8 md:p-10 shadow-2xl shadow-gray-100/50 overflow-hidden relative">
        <!-- Accent Glow -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-orange-100/40 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 space-y-8">
            <div class="border-b border-gray-100 pb-6">
                <h1 class="text-3xl font-black text-gray-900">Resumen del Pedido</h1>
                <p class="text-gray-500 text-sm mt-1">Revisa los detalles antes de proceder al pago seguro.</p>
            </div>

            <!-- Product details -->
            <div class="flex items-center justify-between p-6 bg-gray-50 rounded-2xl border border-gray-100">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-primary-600 uppercase tracking-widest">Servicio</span>
                    <h3 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($bono['nombre']) ?></h3>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($bono['numero_sesiones']) ?> sesiones completas de fisioterapia</p>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black text-gray-900"><?= number_format($bono['precio'], 2, ',', '.') ?>€</span>
                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">IVA Incluido</p>
                </div>
            </div>

            <!-- Total detail break down -->
            <div class="space-y-4 pt-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Base Imponible (Subtotal)</span>
                    <span><?= number_format($bono['precio'] / 1.21, 2, ',', '.') ?>€</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>I.V.A. (21%)</span>
                    <span><?= number_format($bono['precio'] - ($bono['precio'] / 1.21), 2, ',', '.') ?>€</span>
                </div>
                <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-900">Total a pagar</span>
                    <span class="text-3xl font-black text-primary-600"><?= number_format($bono['precio'], 2, ',', '.') ?>€</span>
                </div>
            </div>

            <!-- Form submission to Redsys procesarPago -->
            <form action="<?= PROJECT_ROOT ?>/paciente/tienda/procesar-pago" method="POST" class="pt-6">
                <input type="hidden" name="bono_id" value="<?= $bono['bono_id'] ?>">
                
                <button type="submit" class="w-full flex items-center justify-center gap-3 bg-gray-900 text-white px-8 py-5 rounded-2xl font-bold text-lg transition-all hover:bg-primary-600 hover:scale-[1.01] active:scale-95 shadow-xl shadow-gray-200">
                    <i class="bi bi-shield-lock-fill"></i>
                    Proceder al Pago Seguro con Redsys
                </button>
            </form>

            <!-- SSL / Security Seal -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-50 text-xs text-gray-400 font-medium">
                <div class="flex items-center gap-2">
                    <i class="bi bi-shield-check text-green-500 text-lg"></i>
                    <span>Pasarela oficial Redsys con encriptación SSL</span>
                </div>
                <div class="flex gap-4">
                    <span>VISA</span>
                    <span>MasterCard</span>
                    <span>Bizum</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
