<?php
$pageTitle = "Finalizar Compra - Velion";
include TEMPLATE_DIR . 'header.php';

// El ID del bono viene del controlador en $idBono
?>

<div class="max-w-4xl mx-auto space-y-10 animate-fade-in pb-16">
    <!-- Breadcrumbs -->
    <nav class="flex text-sm font-medium text-gray-500 mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="<?= PROJECT_ROOT ?>/vista-pacientes/tienda" class="hover:text-primary-600 transition-colors">Tienda</a></li>
            <li><i class="bi bi-chevron-right text-[10px]"></i></li>
            <li class="text-gray-900 font-bold">Pasarela de Pago</li>
        </ol>
    </nav>

    <form action="<?= PROJECT_ROOT ?>/vista-pacientes/tienda/procesar-pago" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-10" x-data="{ paymentSource: 'new' }">
        <input type="hidden" name="id_bono" value="<?= htmlspecialchars($idBono) ?>">
        <input type="hidden" name="payment_source" :value="paymentSource">

        <!-- Payment Methods -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                    <i class="bi bi-credit-card-2-front text-primary-600"></i>
                    Método de Pago
                </h2>
                
                <div class="space-y-4">
                    <!-- Saved Methods -->
                    <?php if (!empty($metodosPago)): ?>
                        <div class="mb-6">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Tus tarjetas guardadas</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($metodosPago as $metodo): ?>
                                    <label @click="paymentSource = 'saved_<?= $metodo['metodo_id'] ?>'" 
                                           class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition-all"
                                           :class="paymentSource == 'saved_<?= $metodo['metodo_id'] ?>' ? 'border-primary-500 bg-primary-50/30' : 'border-gray-100 hover:border-primary-100'">
                                        <input type="radio" name="selected_method_id" value="<?= $metodo['metodo_id'] ?>" 
                                               :checked="paymentSource == 'saved_<?= $metodo['metodo_id'] ?>'" class="hidden">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400">
                                                <i class="bi bi-credit-card"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm text-gray-900"><?= htmlspecialchars($metodo['proveedor']) ?> •••• <?= $metodo['last4'] ?></p>
                                                <p class="text-[10px] text-gray-500 uppercase">Exp: <?= $metodo['fecha_expiracion'] ?></p>
                                            </div>
                                        </div>
                                        <div x-show="paymentSource == 'saved_<?= $metodo['metodo_id'] ?>'" class="absolute top-2 right-2">
                                            <i class="bi bi-check-circle-fill text-primary-600"></i>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                                
                                <label @click="paymentSource = 'new'" 
                                       class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition-all border-dashed"
                                       :class="paymentSource == 'new' ? 'border-primary-500 bg-primary-50/30' : 'border-gray-200 hover:border-primary-100'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-primary-600">
                                            <i class="bi bi-plus-lg"></i>
                                        </div>
                                        <p class="font-bold text-sm text-gray-900">Nueva tarjeta</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- New Card Fields (Visible only if paymentSource is 'new') -->
                    <div x-show="paymentSource == 'new'" x-transition class="space-y-6 pt-6 border-t border-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">Nombre en la tarjeta</label>
                                <input type="text" name="card_name" placeholder="Ej: JUAN PEREZ" class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-primary-500 outline-none transition-all uppercase text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">Número de tarjeta</label>
                                <input type="text" name="card_number" placeholder="0000 0000 0000 0000" class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-primary-500 outline-none transition-all text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">Caducidad</label>
                                <input type="text" name="expiry" placeholder="MM/AA" class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-primary-500 outline-none transition-all text-center text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">CVV</label>
                                <input type="text" name="cvv" placeholder="123" class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-primary-500 outline-none transition-all text-center text-sm">
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 p-4 bg-primary-50/50 rounded-2xl border border-primary-100">
                            <input type="checkbox" name="save_method" id="save_method" class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            <label for="save_method" class="text-sm font-medium text-gray-700 cursor-pointer">
                                Guardar este método de pago para futuros pagos
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="space-y-6">
            <div class="bg-gray-900 rounded-3xl p-8 text-white shadow-xl">
                <h3 class="text-xl font-bold mb-6">Resumen del pedido</h3>
                <div class="space-y-4 pb-6 border-b border-white/10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold"><?= htmlspecialchars($bono['nombre']) ?></p>
                            <p class="text-xs text-gray-400"><?= $bono['numero_sesiones'] ?> sesiones • ID: #<?= htmlspecialchars($idBono) ?></p>
                        </div>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Subtotal</span>
                        <span><?= number_format($bono['precio'], 2, ',', '.') ?>€</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Impuestos (21%)</span>
                        <span>Incluidos</span>
                    </div>
                </div>
                <div class="pt-6 space-y-6">
                    <div class="flex justify-between items-end">
                        <span class="text-lg font-bold">Total a pagar</span>
                        <span class="text-3xl font-black text-amber-400"><?= number_format($bono['precio'], 2, ',', '.') ?>€</span>
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-500 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-primary-900/20 active:scale-95">
                        CONFIRMAR PAGO
                    </button>
                </div>
            </div>

            <!-- Guarantee -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 text-center">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-shield-lock-fill text-xl"></i>
                </div>
                <p class="text-sm font-bold text-gray-900">Garantía de Satisfacción</p>
                <p class="text-xs text-gray-500 mt-1">Dispones de 14 días para solicitar la devolución si no has consumido ninguna sesión.</p>
            </div>
        </div>
    </form>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
