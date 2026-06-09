<?php
$pageTitle = "Tienda de Bonos - Velion";
include TEMPLATE_DIR . 'header.php';

// Los bonos activos vienen del controlador $bonosActivos
?>

<div class="space-y-10 animate-fade-in pb-16">
    <!-- Header Section -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-500 via-orange-600 to-rose-600 p-10 text-white shadow-2xl shadow-orange-200/50">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="max-w-2xl space-y-4">
                <h1 class="text-4xl md:text-5xl font-black tracking-tight">Tu Salud, a tu Ritmo 🧘‍♂️</h1>
                <p class="text-orange-50 text-lg opacity-90 leading-relaxed">
                    Adquiere nuestros bonos de sesiones y disfruta de una atención personalizada con descuentos exclusivos. 
                    Sin caducidad inmediata y totalmente gestionables desde tu panel.
                </p>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white/20 backdrop-blur-xl rounded-full flex items-center justify-center border border-white/30 animate-pulse">
                    <i class="bi bi-ticket-perforated-fill text-6xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Abstract Shapes -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-amber-400/20 rounded-full blur-3xl"></div>
    </div>

    <!-- Store Section -->
    <div class="space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900">Bonos Disponibles</h2>
                <p class="text-gray-500 text-sm">Selecciona el plan que mejor se adapte a tus necesidades de recuperación.</p>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl border border-gray-100 shadow-sm text-sm font-medium text-gray-600">
                <i class="bi bi-shield-check text-green-500"></i>
                Pago Seguro SSL
            </div>
        </div>

        <?php if (!empty($bonosActivos)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($bonosActivos as $bono): ?>
                    <div class="group relative bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                        <!-- Background Accent -->
                        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-primary-50 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
                        
                        <div class="relative z-10 flex flex-col h-full">
                            <div class="flex justify-between items-start mb-6">
                                <div class="p-4 bg-orange-50 text-orange-600 rounded-2xl group-hover:bg-orange-100 transition-colors">
                                    <i class="bi bi-award text-3xl"></i>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-bold text-primary-700 ring-1 ring-inset ring-primary-600/10 uppercase tracking-wider">
                                    Ahorro Pack
                                </span>
                            </div>

                            <div class="space-y-2 mb-8">
                                <h3 class="text-2xl font-black text-gray-900 group-hover:text-primary-600 transition-colors">
                                    <?= htmlspecialchars($bono['nombre']) ?>
                                </h3>
                                <div class="flex items-center gap-2">
                                    <span class="text-4xl font-black text-gray-900">
                                        <?= number_format($bono['precio'], 2, ',', '.') ?>€
                                    </span>
                                    <span class="text-gray-400 text-sm font-medium">IVA incluido</span>
                                </div>
                            </div>

                            <div class="space-y-4 mb-10 flex-grow">
                                <div class="flex items-center gap-3 text-gray-600">
                                    <div class="w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                                        <i class="bi bi-check2 text-sm font-bold"></i>
                                    </div>
                                    <span class="text-sm font-semibold"><?= $bono['numero_sesiones'] ?> Sesiones completas</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-600">
                                    <div class="w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                                        <i class="bi bi-check2 text-sm font-bold"></i>
                                    </div>
                                    <span class="text-sm font-semibold">Válido para cualquier especialidad</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-600">
                                    <div class="w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                                        <i class="bi bi-check2 text-sm font-bold"></i>
                                    </div>
                                    <span class="text-sm font-semibold">Transferible a familiares</span>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-50 mt-auto">
                                <a href="<?= PROJECT_ROOT ?>/vista-pacientes/tienda/pago?id=<?= $bono['bono_id'] ?? 0 ?>" 
                                   class="w-full inline-flex items-center justify-center gap-2 bg-gray-900 text-white px-8 py-4 rounded-2xl font-bold transition-all hover:bg-primary-600 hover:scale-[1.02] active:scale-95 shadow-lg shadow-gray-200">
                                    Comprar Ahora
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                <p class="text-center mt-4 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                    Solo <?= number_format($bono['precio'] / ($bono['numero_sesiones'] ?: 1), 2) ?>€ por sesión
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-[2rem] p-20 text-center border border-dashed border-gray-200">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="bi bi-bag-x text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No hay bonos disponibles actualmente</h3>
                <p class="text-gray-500 max-w-sm mx-auto">Vuelve pronto para ver nuestras ofertas y packs de sesiones.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- FAQ / Trust Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12">
        <div class="bg-indigo-900 rounded-[2rem] p-10 text-white relative overflow-hidden">
            <div class="relative z-10 space-y-6">
                <h3 class="text-2xl font-bold">¿Cómo funcionan los bonos?</h3>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold">1</div>
                        <p class="text-indigo-100 text-sm">Elige tu bono y completa el pago mediante nuestra pasarela segura.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold">2</div>
                        <p class="text-indigo-100 text-sm">Las sesiones se añadirán automáticamente a tu cuenta personal.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold">3</div>
                        <p class="text-indigo-100 text-sm">Reserva tus citas normalmente y selecciona tu bono como forma de pago.</p>
                    </div>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 opacity-10 -mr-10 -mb-10">
                <i class="bi bi-question-circle-fill text-[200px]"></i>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 p-10 shadow-sm flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Métodos de Pago Aceptados</h3>
            <div class="grid grid-cols-3 gap-6 opacity-60 grayscale hover:grayscale-0 transition-all duration-700">
                <div class="flex items-center justify-center h-16 bg-gray-50 rounded-xl">
                    <i class="bi bi-credit-card text-3xl"></i>
                </div>
                <div class="flex items-center justify-center h-16 bg-gray-50 rounded-xl font-bold text-xl italic text-blue-800">
                    VISA
                </div>
                <div class="flex items-center justify-center h-16 bg-gray-50 rounded-xl font-bold text-xl text-red-600">
                    MasterCard
                </div>
                <div class="flex items-center justify-center h-16 bg-gray-50 rounded-xl font-bold text-xl text-blue-600 italic">
                    Paypal
                </div>
                <div class="flex items-center justify-center h-16 bg-gray-50 rounded-xl font-bold text-xl text-primary-600">
                    Bizum
                </div>
                <div class="flex items-center justify-center h-16 bg-gray-50 rounded-xl font-bold text-xl text-gray-400">
                    GPay
                </div>
            </div>
            <p class="mt-8 text-xs text-gray-400 text-center font-medium italic">
                Todos tus datos están protegidos por encriptación de grado bancario (AES-256).
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
